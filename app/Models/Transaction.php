<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'order_id',
        'transaction_id',
        'user_id',
        'bet_id',
        'type',
        'amount',
        'reference',
        'status',
        'remark',
        'msg',
        'extra',
        'transaction_date',
    ];

    protected $casts = [
        'transaction_date' => 'datetime',
        'amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bet()
    {
        return $this->belongsTo(Bet::class);
    }
}
