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
        'bet_uuid',
        'user_id',
        'event_id',
        'event_option_id',
        'amount',
        'transaction_id',
        'transaction_status',
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
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->bet_uuid = $model->bet_uuid ?? (string) \Illuminate\Support\Str::uuid();
        });
    }

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
        return $this->hasOne(Transaction::class);
    }
}
