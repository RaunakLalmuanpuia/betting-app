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
    public function all()
    {
        return Post::all();
    }

    public function find($id)
    {
        return Post::findOrFail($id);
    }

    public function create(array $data)
    {
        return Post::create($data);
    }

    public function update($id, array $data)
    {
        $post = Post::findOrFail($id);
        $post->update($data);
        return $post;
    }

    public function delete($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();
        return true;
    }

    public function getBetsByUser(){

        $user = Auth::user();

        $bets = $user->bets()
            ->with([
                'event',
                'option',
                'payments',
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
}
