<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryTransaction extends Model
{
    protected $fillable = [
        'comic_id',
        'type',
        'quantity_change',
        'order_id',
        'user_id',
        'note',
    ];

    public function comic()
    {
        return $this->belongsTo(Comic::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

