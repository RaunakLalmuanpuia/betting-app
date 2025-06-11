<?php

namespace App\Http\Controllers\Player;

use App\Http\Controllers\Controller;
use App\Repositories\BetRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BetController extends Controller
{
    private BetRepository $repository;
    public function __construct(BetRepository $betRepository)
    {
        $this->repository = $betRepository;
    }
    public function index(){
        $bets =  $this->repository->getBetsByUser();
        return Inertia::render('Backend/Player/Bet/Index', [
            'bets' => $bets,
        ]);
    }
}
