<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EventController extends Controller
{
    //
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
}
