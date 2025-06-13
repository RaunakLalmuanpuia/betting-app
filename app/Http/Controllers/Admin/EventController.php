<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EventController extends Controller
{
    //
    public function index(){
        $events = Event::with(['winningOption', 'options', 'bets'])->latest()->get();

        return Inertia::render('Backend/Admin/Event/Index', [
            'events' => $events
        ]);
    }
}
