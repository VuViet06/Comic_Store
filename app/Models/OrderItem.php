<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{

    protected $fillable = [
        'order_id',
        'comic_id',
        'price',
        'quantity',
    ];


    protected $casts = [
        'price' => 'decimal:2',
        'quantity' => 'integer',
    ];


    public function order()
    {
        return $this->belongsTo(Order::class);
    }


    public function comic()
    {
        return $this->belongsTo(Comic::class);
    }

    
    public function getSubtotalAttribute(): float
    {
        return $this->price * $this->quantity;
    }
}
