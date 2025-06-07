<?php

namespace App\Repositories;

use App\Interfaces\BetInterface;
use Illuminate\Support\Facades\Auth;

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

    public function makeBet($userId, $betAmount){

    }
}
