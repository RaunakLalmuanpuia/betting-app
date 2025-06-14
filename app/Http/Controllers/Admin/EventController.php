<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventOption;
use App\Repositories\BetRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EventController extends Controller
{
    //
    private BetRepository $repository;
    public function __construct(BetRepository $betRepository)
    {
        $this->repository = $betRepository;
    }
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 5);
        $search = $request->get('search');
        $page = $request->get('page', 1);

        $query = Event::with(['winningOption', 'options', 'bets']);

        if ($search) {
            $query->where('title', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")
                ->orWhere('status', 'like', "%{$search}%");
        }

        $events = $query->orderBy('created_at', 'desc')->paginate($perPage)->withQueryString();

        return Inertia::render('Backend/Admin/Event/Index', [
            'events' => $events->items(),
            'pagination' => [
                'page' => $events->currentPage(),
                'rowsPerPage' => $events->perPage(),
                'rowsNumber' => $events->total(),
            ],
            'filters' => [
                'search' => $search
            ]
        ]);
    }
    public function show(Event $event)
    {

        $totalAmountAllOptions = $event->bets()->sum('amount');

        $options = $event->options->map(function ($option) use ($event, $totalAmountAllOptions) {
            $optionTotalAmount = $option->bets->sum('amount');
            return [
                'label' => $option->label,
                'total_bets' => $option->bets->count(),
                'total_amount' => $optionTotalAmount,
                'odds' => $optionTotalAmount > 0 ? round($totalAmountAllOptions / $optionTotalAmount, 2) : '-',
                'is_winner' => $event->winning_option_id === $option->id,
            ];
        });

        $stats = [
            'total_bets' => $event->bets()->count(),
            'total_amount' => $event->bets()->sum('amount'),
            'unique_users' => $event->bets()->distinct('user_id')->count(),
            'winners' => $event->bets()->where('is_winner', true)->count(),
            'total_payout' => $event->bets()->where('is_winner', true)->sum('payout_amount'),
        ];
        $winningSummary = null;

        if ($event->winning_option_id) {
            $winningSummary = [
                'label' => optional($event->winningOption)->label,
                'winners' => $event->bets()->where('is_winner', true)->count(),
                'total_payout' => $event->bets()->where('is_winner', true)->sum('payout_amount'),
                'unpaid_winners' => $event->bets()->where('is_winner', true)->where('is_paid', false)->count(),
            ];
        }
        return Inertia::render('Backend/Admin/Event/Show', [
            'event' => $event,
            'options' =>$options,
            'stats' => $stats,
            'winning_summary' => $winningSummary,
        ]);
    }
    public function create(){
        return Inertia::render('Backend/Admin/Event/Create');
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'bet_closes_at' => 'required|date',
            'status' => 'required|in:open,closed,settled',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'options' => 'required|array|size:2',
            'options.*.label' => 'required|string|max:255',
            'options.*.description' => 'nullable|string',
            'options.*.image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('events', 'public');
        }

        $options = [];

        foreach ($request->file('options', []) as $index => $fileArray) {
            $options[$index] = $data['options'][$index];
            if (isset($fileArray['image'])) {
                $options[$index]['image'] = $fileArray['image']->store('event_options', 'public');
            }
        }

        $event = Event::create($data);

        foreach ($options as $option) {
            $event->options()->create($option);
        }

        return redirect()->route('admin.events.index')->with('success', 'Event created successfully.');
    }

    public function edit(Event $event)
    {
        $event->load('options');

        return Inertia::render('Backend/Admin/Event/Edit', [
            'event' => $event
        ]);
    }
    public function update(Request $request, Event $event)
    {
        // Validate incoming request
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'bet_closes_at' => 'required|date',
            'status' => 'required|in:open,closed,settled',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

            'options' => 'required|array|size:2',
            'options.*.id' => 'required|exists:event_options,id',
            'options.*.label' => 'required|string|max:255',
            'options.*.description' => 'nullable|string',
            'options.*.image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Update main event details
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('events', 'public');
        } else {
            unset($data['image']); // prevent overriding with null
        }

        $event->update($data);

        // Update each option
        foreach ($data['options'] as $index => $optionData) {
            $option = EventOption::find($optionData['id']);

            // Handle image upload if present
            if ($request->hasFile("options.{$index}.image")) {
                $optionData['image'] = $request->file("options.{$index}.image")->store('event_options', 'public');
            } else {
                unset($optionData['image']);
            }

            // Update the option
            $option->update($optionData);
        }

        return redirect()->route('admin.events.index')->with('success', 'Event updated successfully.');
    }

    public function destroy(Event $event)
    {
        $event->delete(); // soft delete

        return redirect()->route('admin.events.index')->with('success', 'Event deleted successfully.');
    }

    public function setWinningOption(Request $request, Event $event)
    {
        $request->validate([
            'winning_option_id' => 'required|exists:event_options,id',
        ]);

        $event->winning_option_id = $request->winning_option_id;
        $event->status = 'settled';
        $event->save();

        $this->repository->settleEventBets($event);

        return redirect()->route('admin.events.index')->with('success', 'Winning option set successfully.');

    }
    public function jsonShow(Event $event)
    {
        $event->load(['winningOption', 'options', 'bets']);
        return response()->json($event);
    }

}
