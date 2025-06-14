<?php

namespace App\Interfaces;

use App\Models\Event;
use App\Models\User;

interface BetInterface
{

    public function getBetsByUser();

    public function makeBet(User $user, Event $event,$option,$orderId,float $amount):mixed;

    public function settleEventBets(Event $event): void;

    public function getAllBets(array $filters, int $perPage = 15);
}
