<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Holding extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'stock_name',
        'buy_date',
        'quantity',
        'buy_price',
        'current_price',
        'previous_close'
    ];

    protected $casts = [
        'buy_date' => 'date',
        'quantity' => 'decimal:4',
        'buy_price' => 'decimal:2',
        'current_price' => 'decimal:2',
        'previous_close' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
