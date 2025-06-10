<?php

namespace Database\Seeders;

use App\Constants\PaymentStatus;
use App\Models\Bet;
use App\Models\Event;
use App\Models\EventOption;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DetailedEventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $statuses = ['open', 'closed', 'settled'];

        // Step 1: Create events
        foreach (range(1, 10) as $i) {
            $status = $statuses[array_rand($statuses)];

            $event = Event::create([
                'title' => "Prediction Event {$i}",
                'description' => "This is prediction event number {$i}.",
                'bet_closes_at' => now()->addDays(rand(1, 7)),
                'status' => $status,
            ]);

            $options = ($i % 2 === 0) ? ['Team A', 'Team B'] : ['Yes', 'No'];

            foreach ($options as $label) {
                EventOption::create([
                    'event_id' => $event->id,
                    'label' => $label,
                ]);
            }
        }

        $events = Event::with('options')->get();

        // Step 2: Create bets and transactions for each user
        foreach ($events as $event) {
            $options = $event->options;

            foreach ($users as $user) {
                $chosenOption = $options->random();
                $amount = rand(50, 500);
                $transactionId = 'TXN_' . Str::upper(Str::random(10));
                $orderId = 'ORD_' . Str::upper(Str::random(12));

                // Create Bet
                $bet = Bet::create([
                    'user_id' => $user->id,
                    'event_id' => $event->id,
                    'event_option_id' => $chosenOption->id,
                    'amount' => $amount,
                    'transaction_id' => $transactionId,
                    'transaction_status' => 'success',
                ]);

                // Payment received transaction
                Transaction::create([
                    'user_id' => $user->id,
                    'bet_id' => $bet->id,
                    'order_id' => $orderId,
                    'transaction_id' => $transactionId,
                    'type' => 'payment_received',
                    'amount' => $amount,
                    'reference' => 'PG_REF_' . Str::upper(Str::random(12)),
                    'status' => PaymentStatus::PAID,
                    'remark' => 'Payment received',
                ]);

                // Bet placed transaction
                Transaction::create([
                    'user_id' => $user->id,
                    'bet_id' => $bet->id,
                    'order_id' => 'ORD_' . Str::upper(Str::random(12)),
                    'type' => 'bet_placed',
                    'amount' => $amount,
                    'status' => PaymentStatus::PAID,
                    'remark' => 'Bet placed on ' . $chosenOption->label,
                ]);
            }
        }

        // Step 3: Settle events
        $settledEvents = Event::where('status', 'settled')->with('options')->get();

        foreach ($settledEvents as $event) {
            $options = $event->options;

            if ($options->isEmpty()) continue;

            $winningOption = $options->random();
            $event->update(['winning_option_id' => $winningOption->id]);

            $winningBets = Bet::where('event_id', $event->id)
                ->where('event_option_id', $winningOption->id)
                ->get();

            $totalPool = Bet::where('event_id', $event->id)->sum('amount');
            $totalWinningAmount = $winningBets->sum('amount');

            if ($totalWinningAmount > 0) {
                foreach ($winningBets as $bet) {
                    $share = $bet->amount / $totalWinningAmount;
                    $payout = round($share * $totalPool, 2);
                    $isPaid = rand(0, 1) === 1;

                    $bet->update([
                        'is_winner' => true,
                        'payout_amount' => $payout,
                        'is_paid' => $isPaid,
                    ]);

                    if ($isPaid) {
                        Transaction::create([
                            'user_id' => $bet->user_id,
                            'bet_id' => $bet->id,
                            'order_id' => 'ORD_' . Str::upper(Str::random(12)),
                            'type' => 'payout_sent',
                            'amount' => $payout,
                            'status' => PaymentStatus::PAID,
                            'remark' => 'Winnings payout for Event #' . $event->id,
                        ]);
                    }
                }
            }

            // Mark losing bets
            Bet::where('event_id', $event->id)
                ->where('event_option_id', '!=', $winningOption->id)
                ->update([
                    'is_winner' => false,
                    'payout_amount' => null,
                    'is_paid' => false,
                ]);
        }

        // Step 4: Handle closed events
        $closedEvents = Event::where('status', 'closed')->get();

        foreach ($closedEvents as $event) {
            $event->update(['winning_option_id' => null]);

            Bet::where('event_id', $event->id)->update([
                'is_winner' => null,
                'payout_amount' => null,
                'is_paid' => false,
            ]);
        }
    }
}
