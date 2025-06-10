<?php

namespace App\Http\Controllers\Player;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Repositories\BetRepository;
use App\Traits\CanPay;
use App\Util\SequenceGenerator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class EventController extends Controller
{
     use CanPay;

    private BetRepository $repository;
    public function __construct(BetRepository $betRepository)
    {
        $this->repository = $betRepository;
    }
    public function index(Request $request)
    {
        $perPage = 3;

        $type = $request->get('type', 'Open');

        $query = Event::with(['options.bets', 'winningOption']);

        if ($type !== 'All') {
            $query->where('status', strtolower($type));
        }

        $events = $query->latest()->paginate($perPage)->withQueryString();

        $data = $events->getCollection()->transform(function ($event) {
            $options = $event->options->map(function ($option) {
                $totalBets = $option->bets->count();
                $totalAmount = $option->bets->sum('amount');

                // odds = (total amount of all options / this option's total amount)
                $totalPool = $option->event->bets->sum('amount');
                $odds = $totalAmount > 0 ? round($totalPool / $totalAmount, 2) : null;

                return [
                    'id' => $option->id,
                    'label' => $option->label,
                    'total_bets' => $totalBets,
                    'total_amount' => $totalAmount,
                    'odds' => $odds,
                ];
            });

            return [
                'id' => $event->id,
                'event_name' => $event->title,
                'text' => $event->description,
                'type' => ucfirst($event->status),
                'start_time' => optional($event->created_at)->format('Y-m-d H:i'),
                'bet_closes_at' => optional($event->bet_closes_at)->format('Y-m-d H:i'),
                'img' => $event->image,
                'result' => $event->status === 'settled' && $event->winningOption ? [
                    'label' => $event->winningOption->label,
                    'payout' => optional($options->firstWhere('id', $event->winning_option_id))['odds'],
                ] : null,
                'options' => $options,
            ];
        });

        return Inertia::render('Backend/Player/Event/Index', [
            'events' => $data,
            'pagination' => [
                'current_page' => $events->currentPage(),
                'last_page' => $events->lastPage(),
            ],
            'filters' => [
                'type' => $type,
            ]
        ]);
    }


    public function show(Event $event)
    {
        $event->load(['options.bets']);

        $options = $event->options->map(function ($option) {
            $totalAmount = $option->bets->sum('amount');
            $totalBets = $option->bets->count();

            return [
                'id' => $option->id,
                'label' => $option->label,
                'description' => $option->description,
                'image' => $option->image,
                'total_amount' => $totalAmount,
                'total_bets' => $totalBets,
            ];
        });

        $totalEventAmount = $options->sum('total_amount');

        $options = $options->map(function ($option) use ($totalEventAmount) {
            $odds = $option['total_amount'] > 0
                ? round($totalEventAmount / $option['total_amount'], 2)
                : 0;
            return [...$option, 'odds' => $odds];
        });

        return Inertia::render('Backend/Player/Event/Show', [
            'event' => [
                'id' => $event->id,
                'title' => $event->title,
                'description' => $event->description,
                'image' => $event->image,
                'bet_closes_at' => $event->bet_closes_at,
                'status' => $event->status,
                'options' => $options,
            ]
        ]);
    }

    public function placeBet(Event $event, Request $request){

//        dd($request->all());
        $validatedData=$this->validate($request, [
            'agree'=>'bool:true',
            'event_option_id'=>'required',
            'amount'=>'required|numeric|min:10',

        ]);


        $user = $request->user();

        $orderId=  SequenceGenerator::generateOrderId();

        $amount=$request->amount;

        $token=$this->initiatePayment(
            $request->user(),
            $orderId,
            $amount,
            route('callback.place-bet')
        );

        $option=$request->event_option_id;

        $bet=$this->repository->makeBet($user,$event,$option,$orderId,$amount);

        abort_if(blank($bet),500,'Something went wrong');

        return response()->json([
            'order_id'=>$orderId,
            'token'=>$token,
            'amount' => $amount,
        ]);
    }
}
