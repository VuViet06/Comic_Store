<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    protected $fillable = [
        'order_id',
        'shipping_partner_id',
        'service_name',
        'tracking_code',
        'status',
        'raw_response',
        'booked_at',
        'delivered_at',
    ];

    protected $casts = [
        'raw_response' => 'array',
        'booked_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function shippingPartner()
    {
        return $this->belongsTo(ShippingPartner::class);
    }
}

