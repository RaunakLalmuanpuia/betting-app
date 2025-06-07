<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bet extends Model
{
    //
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'event_id',
        'event_option_id',
        'amount',
        'is_winner',
        'payout_amount',
        'is_paid',
    ];

    protected $casts = [
        'is_winner' => 'boolean',
        'is_paid' => 'boolean',
        'amount' => 'decimal:2',
        'payout_amount' => 'decimal:2',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
    public function option()
    {
        return $this->belongsTo(EventOption::class, 'event_option_id');
    }
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
