<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bet;
use App\Models\Event;
use App\Repositories\BetRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BetController extends Controller
{
    //
    private BetRepository $repository;
    public function __construct(BetRepository $betRepository)
    {
        $this->repository = $betRepository;
    }

    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 15);

        $bets = $this->repository->getAllBets($request->only(['search', 'event_id']), $perPage);

        $events = Event::select('id as value', 'title as label')->orderBy('title')->get();

        return Inertia::render('Backend/Admin/Bet/Index', [
            'bets' => $bets,
            'filters' => $request->only('search', 'event_id', 'per_page'),
            'events' => $events,
        ]);
    }
}
