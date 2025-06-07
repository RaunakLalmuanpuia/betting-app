<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventOption extends Model
{
    //
    use HasFactory;

    protected $fillable = ['event_id', 'label'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function bets()
    {
        return $this->hasMany(Bet::class);
    }
}
