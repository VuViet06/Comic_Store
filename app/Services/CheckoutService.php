<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Comic;
use App\Models\Voucher;
use App\Services\InventoryService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutService
{
    protected InventoryService $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    /**
     * Validate giỏ hàng trước khi checkout
     */
    public function validateCart($cartItems)
    {
        $errors = [];

        foreach ($cartItems as $item) {
            $comic = Comic::find($item['comic_id']);
            
            if (!$comic) {
                $errors[] = "Truyện không tồn tại.";
                continue;
            }

            if (!$comic->is_active) {
                $errors[] = "Truyện '{$comic->title}' đã ngừng bán.";
            }

            if ($comic->stock < $item['quantity']) {
                $errors[] = "Truyện '{$comic->title}' chỉ còn {$comic->stock} quyển.";
            }
        }

        if (!empty($errors)) {
            throw new \Exception(implode(' ', $errors));
        }

        return true;
    }

    /**
     * Tính tổng tiền (subtotal, discount, total)
     */
    public function calculateTotals($cartItems, $voucherCode = null, $userId = null)
    {
        $subtotal = 0;

        foreach ($cartItems as $item) {
            $comic = Comic::find($item['comic_id']);
            $subtotal += $comic->price * $item['quantity'];
        }

        $discount = 0;
        $voucherId = null;

        if ($voucherCode) {
            $voucherResult = $this->applyVoucher($voucherCode, $subtotal, $userId);
            $discount = $voucherResult['discount'];
            $voucherId = $voucherResult['voucher_id'];
        }

        $total = max(0, $subtotal - $discount);

        return [
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total' => $total,
            'voucher_id' => $voucherId,
        ];
    }

    /**
     * Áp dụng voucher
     */
    public function applyVoucher($voucherCode, $subtotal, $userId = null)
    {
        $voucher = Voucher::where('code', $voucherCode)
            ->where('is_active', true)
            ->first();

        if (!$voucher) {
            throw new \Exception("Mã giảm giá không hợp lệ.");
        }

        // Kiểm tra thời gian
        $now = now();
        if ($voucher->starts_at && $now->lt($voucher->starts_at)) {
            throw new \Exception("Mã giảm giá chưa có hiệu lực.");
        }
        if ($voucher->ends_at && $now->gt($voucher->ends_at)) {
            throw new \Exception("Mã giảm giá đã hết hạn.");
        }

        // Kiểm tra số lần sử dụng
        if ($voucher->usage_limit && $voucher->used_count >= $voucher->usage_limit) {
            throw new \Exception("Mã giảm giá đã hết lượt sử dụng.");
        }

        // Kiểm tra giá trị đơn tối thiểu
        if ($voucher->min_order_amount && $subtotal < $voucher->min_order_amount) {
            throw new \Exception("Đơn hàng tối thiểu " . number_format($voucher->min_order_amount) . " VNĐ để sử dụng mã này.");
        }

        // Tính giảm giá
        $discount = 0;
        if ($voucher->type === 'percent') {
            $discount = ($subtotal * $voucher->value) / 100;
            if ($voucher->max_discount) {
                $discount = min($discount, $voucher->max_discount);
            }
        } else { // fixed
            $discount = min($voucher->value, $subtotal);
        }

        return [
            'voucher_id' => $voucher->id,
            'discount' => $discount,
            'voucher' => $voucher,
        ];
    }

    /**
     * Tạo đơn hàng
     */
    public function createOrder($cartItems, $customerData, $voucherId = null, $userId = null)
    {
        return DB::transaction(function () use ($cartItems, $customerData, $voucherId, $userId) {
            // Validate cart
            $this->validateCart($cartItems);

            // Tính tổng tiền
            $totals = $this->calculateTotals($cartItems, null, $userId);
            if ($voucherId) {
                $voucher = Voucher::find($voucherId);
                if ($voucher) {
                    $voucherResult = $this->applyVoucher($voucher->code, $totals['subtotal'], $userId);
                    $totals['discount'] = $voucherResult['discount'];
                }
            }

            // Tạo mã đơn
            $orderCode = $this->generateOrderCode();

            // Tạo order
            $order = Order::create([
                'user_id' => $userId,
                'code' => $orderCode,
                'subtotal_amount' => $totals['subtotal'],
                'discount_amount' => $totals['discount'],
                'total_amount' => $totals['total'],
                'order_status' => Order::STATUS_PENDING,
                'payment_status' => Order::PAYMENT_STATUS_UNPAID,
                'customer_name' => $customerData['customer_name'],
                'customer_phone' => $customerData['customer_phone'],
                'shipping_address_line' => $customerData['shipping_address_line'],
                'shipping_ward' => $customerData['shipping_ward'] ?? null,
                'shipping_province' => $customerData['shipping_province'] ?? null,
                'shipping_postal_code' => $customerData['shipping_postal_code'] ?? null,
                'payment_method' => $customerData['payment_method'] ?? Order::PAYMENT_COD,
                'customer_note' => $customerData['customer_note'] ?? null,
            ]);

            // Tạo order items và trừ kho
            foreach ($cartItems as $item) {
                $comic = Comic::find($item['comic_id']);

                OrderItem::create([
                    'order_id' => $order->id,
                    'comic_id' => $comic->id,
                    'price' => $comic->price,
                    'quantity' => $item['quantity'],
                ]);

                // Trừ tồn kho
                $this->inventoryService->reduceStock($comic->id, $item['quantity'], $order->id, $userId);
            }

            // Gắn voucher nếu có
            if ($voucherId) {
                $voucher = Voucher::find($voucherId);
                if ($voucher) {
                    $order->vouchers()->attach($voucher->id, [
                        'discount_amount' => $totals['discount'],
                    ]);
                    $voucher->increment('used_count');
                }
            }

            return $order->load(['items.comic', 'vouchers']);
        });
    }

    /**
     * Tạo mã đơn hàng duy nhất
     */
    protected function generateOrderCode()
    {
        do {
            $code = 'ORD-' . strtoupper(Str::random(8));
        } while (Order::where('code', $code)->exists());

        return $code;
    }

    /**
     * Lấy đơn hàng theo mã
     */
    public function getOrderByCode($code)
    {
        return Order::with(['items.comic', 'vouchers', 'user'])
            ->where('code', $code)
            ->first();
    }

    /**
     * Lấy chi tiết đơn hàng
     */
    public function getOrderDetail($orderId)
    {
        return Order::with(['items.comic', 'vouchers', 'user'])
            ->findOrFail($orderId);
    }
}
