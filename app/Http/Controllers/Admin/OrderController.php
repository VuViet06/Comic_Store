<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderService;
use App\Services\InventoryService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected OrderService $orderService;
    protected InventoryService $inventoryService;

    public function __construct(
        OrderService $orderService,
        InventoryService $inventoryService
    ) {
        $this->orderService = $orderService;
        $this->inventoryService = $inventoryService;
    }

    /**
     * Danh sách đơn hàng
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items.comic']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }

        // Filter by payment status
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        // Date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Sort
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'total_asc':
                $query->orderBy('total_amount', 'asc');
                break;
            case 'total_desc':
                $query->orderBy('total_amount', 'desc');
                break;
            case 'latest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $orders = $query->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Chi tiết đơn hàng
     */
    public function show($code)
    {
        $order = Order::with(['user', 'items.comic', 'vouchers', 'shipments.shippingPartner'])
            ->where('code', $code)
            ->firstOrFail();

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Cập nhật trạng thái đơn hàng
     */
    public function updateStatus(Request $request, $code)
    {
        $order = Order::where('code', $code)->firstOrFail();

        $request->validate([
            'order_status' => 'required|in:' . implode(',', [
                Order::STATUS_PENDING,
                Order::STATUS_SHIPPING,
                Order::STATUS_COMPLETED,
                Order::STATUS_CANCELLED,
            ]),
            'payment_status' => 'nullable|in:' . implode(',', [
                Order::PAYMENT_STATUS_UNPAID,
                Order::PAYMENT_STATUS_PENDING,
                Order::PAYMENT_STATUS_PAID,
                Order::PAYMENT_STATUS_FAILED,
                Order::PAYMENT_STATUS_REFUNDED,
            ]),
        ]);

        $oldStatus = $order->order_status;
        $newStatus = $request->order_status;

        $order->order_status = $newStatus;
        
        if ($request->filled('payment_status')) {
            $order->payment_status = $request->payment_status;
        }

        $order->save();

        // Nếu hủy đơn, hoàn trả tồn kho
        if ($oldStatus !== Order::STATUS_CANCELLED && $newStatus === Order::STATUS_CANCELLED) {
            foreach ($order->items as $item) {
                $this->inventoryService->restoreStock(
                    $item->comic_id,
                    $item->quantity,
                    $order->id,
                    auth()->id()
                );
            }
        }

        return redirect()->route('admin.orders.show', $code)
            ->with('success', 'Đã cập nhật trạng thái đơn hàng thành công.');
    }

    /**
     * Xử lý hủy đơn hàng
     */
    public function cancel($code)
    {
        $order = Order::where('code', $code)->firstOrFail();

        if ($order->order_status === Order::STATUS_CANCELLED) {
            return redirect()->route('admin.orders.show', $code)
                ->with('error', 'Đơn hàng đã bị hủy.');
        }

        if ($order->order_status === Order::STATUS_COMPLETED) {
            return redirect()->route('admin.orders.show', $code)
                ->with('error', 'Không thể hủy đơn hàng đã hoàn thành.');
        }

        try {
            $this->orderService->cancelOrder($order->id, auth()->id());

            return redirect()->route('admin.orders.show', $code)
                ->with('success', 'Đã hủy đơn hàng và hoàn trả tồn kho.');
        } catch (\Exception $e) {
            return redirect()->route('admin.orders.show', $code)
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Xử lý hoàn trả
     */
    public function processReturn(Request $request, $code)
    {
        $order = Order::where('code', $code)->firstOrFail();

        if ($order->order_status !== Order::STATUS_COMPLETED) {
            return redirect()->route('admin.orders.show', $code)
                ->with('error', 'Chỉ có thể hoàn trả đơn hàng đã hoàn thành.');
        }

        $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        try {
            $this->orderService->requestReturn(
                $order->id,
                auth()->id(),
                $request->reason
            );

            // Hoàn trả tồn kho
            foreach ($order->items as $item) {
                $this->inventoryService->restoreStock(
                    $item->comic_id,
                    $item->quantity,
                    $order->id,
                    auth()->id()
                );
            }

            return redirect()->route('admin.orders.show', $code)
                ->with('success', 'Đã xử lý hoàn trả đơn hàng.');
        } catch (\Exception $e) {
            return redirect()->route('admin.orders.show', $code)
                ->with('error', $e->getMessage());
        }
    }
}
