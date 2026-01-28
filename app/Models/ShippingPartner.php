<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingPartner extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'code',
        'api_base_url',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function shipments()
    {
        return $this->hasMany(Shipment::class);
    }
}

