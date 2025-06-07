<?php

namespace Database\Seeders;

use App\Models\Bet;
use App\Models\Event;
use App\Models\EventOption;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DetailedEventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $users = User::all();

        // 1. Create 10 events with varying statuses
        $statuses = ['open', 'closed', 'settled'];

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

        // 2. Create bets for every user on every event
        foreach ($events as $event) {
            $options = $event->options;

            foreach ($users as $user) {
                $chosenOption = $options->random();
                $amount = rand(50, 500);

                $bet = Bet::create([
                    'user_id' => $user->id,
                    'event_id' => $event->id,
                    'event_option_id' => $chosenOption->id,
                    'amount' => $amount,
                ]);


                Transaction::create([
                    'user_id' => $user->id,
                    'bet_id' => $bet->id,
                    'type' => 'payment_received',
                    'amount' => $amount,
                    'reference' => 'PG_REF_' . Str::random(12),
                    'remarks' => 'Payment received',
                ]);

                Transaction::create([
                    'user_id' => $user->id,
                    'bet_id' => $bet->id,
                    'type' => 'bet_placed',
                    'amount' => $amount,
                    'remarks' => 'Bet placed on ' . $chosenOption->label,
                ]);
            }
        }

        // 3. Settle events with status = 'settled'
        $settledEvents = Event::where('status', 'settled')->with('options')->get();

        foreach ($settledEvents as $event) {
            $options = $event->options;

            if ($options->isEmpty()) {
                continue;
            }

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

                    // Randomly decide if the payout is pending
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
                            'type' => 'payout_sent',
                            'amount' => $payout,
                            'remarks' => 'Winnings payout for Event #' . $event->id,
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

        // 4. Handle closed events (no result declared yet)
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
