<?php

namespace App\Http\Controllers\Player;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EventController extends Controller
{
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
}
