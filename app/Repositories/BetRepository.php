<?php

namespace App\Repositories;


use App\Constants\PaymentStatus;
use App\Constants\PaymentType;
use App\Interfaces\BetInterface;

use App\Models\Bet;
use App\Models\Event;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BetRepository implements BetInterface
{
    public function getBetsByUser(){

        $user = Auth::user();

        $bets = $user->bets()
            ->with([
                'event.options',
                'option',
                'transactions',
            ])
            ->orderByDesc('created_at')
            ->get();

        return $bets;
    }
    public function makeBet(User $user, Event $event,$option, $orderId,float $amount): mixed
    {
        return DB::transaction(function () use ($user, $event, $option, $orderId, $amount) {

            // Create the bet
            $bet = Bet::create([
                'user_id' => $user->id,
                'event_id' => $event->id,
                'event_option_id' => $option,
                'amount' => $amount,
                'transaction_status'=> PaymentStatus::ATTEMPTED,
                'is_winner' => false,
                'is_paid' => false,
            ]);

            Transaction::create([
                'order_id' => $orderId,
                'user_id' => $user->id,
                'bet_id' => $bet->id,
                'type' => PaymentType::BET_PLACED,
                'amount' => $amount,
                'status' => PaymentStatus::ATTEMPTED,
                'remark' => 'Bet placed on option ID ' . $option,
                'transaction_date' => now(),
            ]);
            return $bet;
        });
    }
    public function settleEventBets(Event $event): void
    {
        $event->load('bets');

        $allBets = $event->bets;
        $totalPool = $allBets->sum('amount');

        $winningOptionId = $event->winning_option_id;
        $winningBets = $allBets->where('event_option_id', $winningOptionId);
        $totalWinningAmount = $winningBets->sum('amount');

        DB::transaction(function () use ($allBets, $winningOptionId, $totalPool, $totalWinningAmount) {
            foreach ($allBets as $bet) {
                if ($bet->event_option_id == $winningOptionId && $totalWinningAmount > 0) {
                    $share = $bet->amount / $totalWinningAmount;
                    $payout = $totalPool * $share;

                    $bet->is_winner = true;
                    $bet->payout_amount = $payout;
                } else {
                    $bet->is_winner = false;
                    $bet->payout_amount = 0;
                }
                $bet->save();
            }
            return true;
        });
    }


    public function getAllBets(array $filters, int $perPage = 15)
    {
        return Bet::with(['user', 'event', 'option', 'transactions'])
            ->when($filters['search'] ?? null, function ($query, $search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->whereHas('user', fn($q) =>
                    $q->where('name', 'like', "%$search%")
                        ->orWhere('mobile', 'like', "%$search%")
                    )
                        ->orWhere('amount', 'like', "%$search%")
                        ->orWhere('bet_uuid', 'like', "%$search%");
                });
            })
            ->when($filters['event_id'] ?? null, fn($q, $eventId) => $q->where('event_id', $eventId))
            ->orderByDesc('created_at')
            ->paginate($perPage)
            ->withQueryString();
    }
}
