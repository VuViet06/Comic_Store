<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlashSale extends Model
{
    protected $fillable = [
        'name',
        'description',
        'starts_at',
        'ends_at',
        'is_active',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function comics()
    {
        return $this->belongsToMany(Comic::class, 'flash_sale_comic')
            ->withPivot('discount_type', 'discount_value')
            ->withTimestamps();
    }
}

