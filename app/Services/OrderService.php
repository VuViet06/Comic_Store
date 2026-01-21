<?php

namespace App\Services;

use App\Models\Order;
use App\Services\InventoryService;
use Illuminate\Support\Facades\DB;

class OrderService
{
    protected InventoryService $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    /**
     * Lấy danh sách đơn hàng của user
     */
    public function getUserOrders($userId, $status = null, $limit = 15, $offset = 0)
    {
        $query = Order::where('user_id', $userId)
            ->with(['items.comic'])
            ->orderBy('created_at', 'desc');

        if ($status) {
            $query->where('order_status', $status);
        }

        return [
            'orders' => $query->skip($offset)->take($limit)->get(),
            'total' => $query->count(),
        ];
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
     * Lấy chi tiết đơn hàng (có kiểm tra quyền)
     */
    public function getOrderDetail($orderId, $userId = null)
    {
        $query = Order::with(['items.comic', 'vouchers', 'user'])
            ->findOrFail($orderId);

        // Kiểm tra quyền nếu có userId
        if ($userId && $query->user_id != $userId) {
            throw new \Exception("Bạn không có quyền xem đơn hàng này.");
        }

        return $query;
    }

    /**
     * Hủy đơn hàng (chỉ khi pending)
     */
    public function cancelOrder($orderId, $userId = null)
    {
        return DB::transaction(function () use ($orderId, $userId) {
            $order = Order::with('items')->findOrFail($orderId);

            // Kiểm tra quyền
            if ($userId && $order->user_id != $userId) {
                throw new \Exception("Bạn không có quyền hủy đơn hàng này.");
            }

            // Chỉ cho phép hủy khi pending
            if ($order->order_status !== Order::STATUS_PENDING) {
                throw new \Exception("Chỉ có thể hủy đơn hàng khi đang chờ xử lý.");
            }

            // Hoàn trả tồn kho
            foreach ($order->items as $item) {
                $this->inventoryService->restoreStock(
                    $item->comic_id,
                    $item->quantity,
                    $order->id,
                    $userId
                );
            }

            // Cập nhật trạng thái
            $order->order_status = Order::STATUS_CANCELLED;
            $order->save();

            return $order;
        });
    }

    /**
     * Yêu cầu hoàn trả (chỉ khi completed)
     */
    public function requestReturn($orderId, $userId = null, $reason = null)
    {
        $order = Order::findOrFail($orderId);

        // Kiểm tra quyền
        if ($userId && $order->user_id != $userId) {
            throw new \Exception("Bạn không có quyền yêu cầu hoàn trả đơn hàng này.");
        }

        // Chỉ cho phép hoàn trả khi đã completed
        if ($order->order_status !== Order::STATUS_COMPLETED) {
            throw new \Exception("Chỉ có thể yêu cầu hoàn trả đơn hàng đã hoàn thành.");
        }

        // Cập nhật trạng thái (admin sẽ xử lý sau)
        $order->order_status = Order::STATUS_RETURNED;
        $order->customer_note = ($order->customer_note ? $order->customer_note . "\n" : '') . 
            "Yêu cầu hoàn trả: " . ($reason ?? 'Không có lý do');
        $order->save();

        return $order;
    }

    /**
     * Thống kê đơn hàng của user
     */
    public function getUserStats($userId)
    {
        $orders = Order::where('user_id', $userId)->get();

        return [
            'total_orders' => $orders->count(),
            'pending' => $orders->where('order_status', Order::STATUS_PENDING)->count(),
            'shipping' => $orders->where('order_status', Order::STATUS_SHIPPING)->count(),
            'completed' => $orders->where('order_status', Order::STATUS_COMPLETED)->count(),
            'cancelled' => $orders->where('order_status', Order::STATUS_CANCELLED)->count(),
            'total_spent' => $orders->where('order_status', Order::STATUS_COMPLETED)->sum('total_amount'),
        ];
    }

    /**
     * Tra cứu đơn hàng (cho guest) - cần mã đơn + số điện thoại
     */
    public function trackOrder($code, $phone)
    {
        $order = Order::with(['items.comic'])
            ->where('code', $code)
            ->where('customer_phone', $phone)
            ->first();

        if (!$order) {
            throw new \Exception("Không tìm thấy đơn hàng. Vui lòng kiểm tra lại mã đơn và số điện thoại.");
        }

        return $order;
    }

    /**
     * Lấy đơn hàng gần đây (cho dashboard)
     */
    public function getRecentOrders($limit = 10)
    {
        return Order::with(['items.comic', 'user'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }
}
