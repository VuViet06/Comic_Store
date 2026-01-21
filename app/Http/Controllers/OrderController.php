<?php

namespace App\Http\Controllers;

use App\Services\OrderService;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * Danh sách đơn hàng của tôi (chỉ logged-in)
     */
    public function myOrders(Request $request)
    {
        $userId = Auth::id();
        $status = $request->get('status');
        $page = $request->get('page', 1);

        $result = $this->orderService->getUserOrders(
            $userId,
            $status,
            15,
            ($page - 1) * 15
        );

        $stats = $this->orderService->getUserStats($userId);

        return view('orders.my-orders', [
            'orders' => $result['orders'],
            'total' => $result['total'],
            'stats' => $stats,
            'currentStatus' => $status,
        ]);
    }

    /**
     * Chi tiết đơn hàng (chỉ logged-in)
     */
    public function show($code)
    {
        $userId = Auth::id();
        $order = $this->orderService->getOrderByCode($code);

        if (!$order) {
            return redirect()->route('my-orders.index')
                ->with('error', 'Không tìm thấy đơn hàng.');
        }

        // Kiểm tra quyền
        if ($order->user_id != $userId) {
            abort(403, 'Bạn không có quyền xem đơn hàng này.');
        }

        return view('orders.show', compact('order'));
    }

    /**
     * Tra cứu đơn hàng (cho guest)
     */
    public function track(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'phone' => 'required|string',
        ]);

        try {
            $order = $this->orderService->trackOrder(
                $request->code,
                $request->phone
            );

            return view('orders.track-result', compact('order'));

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Yêu cầu hủy đơn (chỉ logged-in)
     */
    public function requestCancel($code)
    {
        $userId = Auth::id();
        $order = $this->orderService->getOrderByCode($code);

        if (!$order || $order->user_id != $userId) {
            return redirect()->route('my-orders.index')
                ->with('error', 'Không tìm thấy đơn hàng.');
        }

        try {
            $this->orderService->cancelOrder($order->id, $userId);

            return redirect()->route('my-orders.show', $code)
                ->with('success', 'Đã hủy đơn hàng thành công. Tồn kho đã được hoàn trả.');

        } catch (\Exception $e) {
            return back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Yêu cầu hoàn trả (chỉ logged-in)
     */
    public function requestReturn($code)
    {
        $request = request();
        $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        $userId = Auth::id();
        $order = $this->orderService->getOrderByCode($code);

        if (!$order || $order->user_id != $userId) {
            return redirect()->route('my-orders.index')
                ->with('error', 'Không tìm thấy đơn hàng.');
        }

        try {
            $this->orderService->requestReturn(
                $order->id,
                $userId,
                $request->reason
            );

            return redirect()->route('my-orders.show', $code)
                ->with('success', 'Đã gửi yêu cầu hoàn trả. Chúng tôi sẽ xử lý sớm nhất.');

        } catch (\Exception $e) {
            return back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * API Lấy trạng thái đơn hàng (AJAX)
     */
    public function getStatus(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $order = $this->orderService->getOrderByCode($request->code);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy đơn hàng',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'order_status' => $order->order_status,
            'payment_status' => $order->payment_status,
            'status_label' => Order::getStatuses()[$order->order_status] ?? $order->order_status,
        ]);
    }
}
