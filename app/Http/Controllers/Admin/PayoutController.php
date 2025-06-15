<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bet;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\In;
use Inertia\Inertia;

class PayoutController extends Controller
{

    public function index(Request $request)
    {
        // Base query for winning bets
        $baseQuery = Bet::with(['user', 'event', 'option', 'transactions'])
            ->where('is_winner', true);

        // Apply search filter
        if ($search = $request->search) {
            $baseQuery->where(function ($q) use ($search) {
                $q->where('bet_uuid', 'like', "%{$search}%")
                    ->orWhereHas('user', fn($q2) => $q2->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('event', fn($q2) => $q2->where('title', 'like', "%{$search}%"));
            });
        }

        // Clone query for stats (before pagination is applied)
        $statsQuery = clone $baseQuery;

        // Paginated data
        $bets = $baseQuery->orderByDesc('created_at')->paginate(15)->withQueryString();

        $statsRaw = $statsQuery
            ->selectRaw("
            COUNT(*) as total_winning_bets,
            SUM(payout_amount) as total_payout_amount,
            SUM(CASE WHEN is_paid = 1 THEN 1 ELSE 0 END) as total_paid_bets,
            SUM(CASE WHEN is_paid = 0 THEN 1 ELSE 0 END) as total_unpaid_bets,
            SUM(CASE WHEN is_paid = 1 THEN payout_amount ELSE 0 END) as total_paid_amount,
            SUM(CASE WHEN is_paid = 0 THEN payout_amount ELSE 0 END) as total_unpaid_amount
        ")
            ->first();

        // Format stats into an array
        $stats = [
            'total_winning_bets'  => (int) $statsRaw->total_winning_bets,
            'total_payout_amount' => (float) $statsRaw->total_payout_amount,
            'total_paid_bets'     => (int) $statsRaw->total_paid_bets,
            'total_unpaid_bets'   => (int) $statsRaw->total_unpaid_bets,
            'total_paid_amount'   => (float) $statsRaw->total_paid_amount,
            'total_unpaid_amount' => (float) $statsRaw->total_unpaid_amount,
        ];

        return Inertia::render('Backend/Admin/Payout/Index', [
            'bets' => $bets,
            'filters' => $request->only('search'),
            'stats' => $stats,
        ]);
    }
}
