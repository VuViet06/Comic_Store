<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /**
     * Các trạng thái đơn hàng
     */
    const STATUS_PENDING = 'pending';
    const STATUS_SHIPPING = 'shipping';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_RETURNED = 'returned';

    /**
     * Các phương thức thanh toán
     */
    const PAYMENT_COD = 'cod';
    const PAYMENT_BANK_TRANSFER = 'bank_transfer';
    const PAYMENT_MOMO = 'momo';
    const PAYMENT_VNPAY = 'vnpay';

    /**
     * Các trạng thái thanh toán
     */
    const PAYMENT_STATUS_UNPAID = 'unpaid';
    const PAYMENT_STATUS_PENDING = 'pending';
    const PAYMENT_STATUS_PAID = 'paid';
    const PAYMENT_STATUS_FAILED = 'failed';
    const PAYMENT_STATUS_REFUNDED = 'refunded';


    protected $fillable = [
        'user_id',
        'code',
        'subtotal_amount',
        'discount_amount',
        'total_amount',
        'order_status',
        'payment_status',
        'customer_name',
        'customer_phone',
        'shipping_address_line',
        'shipping_ward',
        'shipping_province',
        'shipping_postal_code',
        'payment_method',
        'customer_note',
    ];


    protected $casts = [
        'subtotal_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function vouchers()
    {
        return $this->belongsToMany(Voucher::class, 'order_vouchers')
            ->withPivot('discount_amount')
            ->withTimestamps();
    }

    public function shipments()
    {
        return $this->hasMany(Shipment::class);
    }


    public static function getStatuses(): array
    {
        return [
            self::STATUS_PENDING => 'Chờ xử lý',
            self::STATUS_SHIPPING => 'Đang vận chuyển',
            self::STATUS_COMPLETED => 'Hoàn thành',
            self::STATUS_CANCELLED => 'Đã hủy',
            self::STATUS_RETURNED => 'Hoàn trả',
        ];
    }


    public static function getPaymentMethods(): array
    {
        return [
            self::PAYMENT_COD => 'Thanh toán khi nhận hàng',
            self::PAYMENT_BANK_TRANSFER => 'Chuyển khoản ngân hàng',
            self::PAYMENT_MOMO => 'Ví MoMo',
            self::PAYMENT_VNPAY => 'VNPay',
        ];
    }


    public function scopeStatus($query, string $status)
    {
        return $query->where('order_status', $status);
    }

   
    public function canBeCancelled(): bool
    {
        return in_array($this->order_status, [self::STATUS_PENDING]);
    }
}
