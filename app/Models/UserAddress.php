<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    protected $fillable = [
        'user_id',
        'label',
        'recipient_name',
        'phone',
        'address_line',
        'ward',
        'district',
        'province',
        'postal_code',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    /**
     * Địa chỉ thuộc về user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Đặt địa chỉ này làm mặc định
     */
    public function setAsDefault()
    {
        // Bỏ mặc định của các địa chỉ khác
        self::where('user_id', $this->user_id)
            ->where('id', '!=', $this->id)
            ->update(['is_default' => false]);

        // Đặt địa chỉ này làm mặc định
        $this->update(['is_default' => true]);
    }

    /**
     * Format địa chỉ đầy đủ
     */
    public function getFullAddressAttribute(): string
    {
        $parts = array_filter([
            $this->address_line,
            $this->ward,
            $this->district,
            $this->province,
        ]);

        return implode(', ', $parts);
    }

    /**
     * Scope: lấy địa chỉ mặc định
     */
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }
}
