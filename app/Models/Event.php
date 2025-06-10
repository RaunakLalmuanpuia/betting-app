<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    //
    use HasFactory, SoftDeletes;

    protected $fillable = ['title', 'description', 'image', 'bet_closes_at', 'status', 'winning_option_id'];

    public function options()
    {
        return $this->hasMany(EventOption::class);
    }

    public function winningOption()
    {
        return $this->belongsTo(EventOption::class, 'winning_option_id');
    }

    public function bets()
    {
        return $this->hasMany(Bet::class);
    }
}
