<?php

namespace App\Interfaces;

use App\Models\Event;
use App\Models\User;

interface BetInterface
{
    //
    public function all();
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);

    public function getBetsByUser();

    public function makeBet(User $user, Event $event,$option,$orderId,float $amount):mixed;

    public function settleEventBets(Event $event): void;
}
