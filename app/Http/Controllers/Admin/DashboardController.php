<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Comic;
use App\Models\User;
use App\Models\Category;
use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Hiển thị dashboard với thống kê
     */
    public function index()
    {
        // Thống kê đơn hàng
        $orderStats = [
            'total' => Order::count(),
            'pending' => Order::where('order_status', Order::STATUS_PENDING)->count(),
            'shipping' => Order::where('order_status', Order::STATUS_SHIPPING)->count(),
            'completed' => Order::where('order_status', Order::STATUS_COMPLETED)->count(),
            'cancelled' => Order::where('order_status', Order::STATUS_CANCELLED)->count(),
            'today' => Order::whereDate('created_at', today())->count(),
            'this_month' => Order::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
        ];

        // Doanh thu
        $revenueStats = [
            'today' => Order::where('order_status', Order::STATUS_COMPLETED)
                ->whereDate('created_at', today())
                ->sum('total_amount'),
            'this_month' => Order::where('order_status', Order::STATUS_COMPLETED)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('total_amount'),
            'all_time' => Order::where('order_status', Order::STATUS_COMPLETED)
                ->sum('total_amount'),
        ];

        // Thống kê sản phẩm
        $productStats = [
            'total' => Comic::count(),
            'active' => Comic::where('is_active', true)->count(),
            'out_of_stock' => Comic::where('stock', 0)->count(),
            'low_stock' => Comic::where('stock', '>', 0)->where('stock', '<=', 10)->count(),
        ];

        // Thống kê người dùng
        $userStats = [
            'total' => User::count(),
            'customers' => User::where('role', User::ROLE_USER)->count(),
            'admins' => User::where('role', User::ROLE_ADMIN)->count(),
            'new_today' => User::whereDate('created_at', today())->count(),
        ];

        // Đơn hàng gần đây
        $recentOrders = Order::with(['user', 'items.comic'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Sản phẩm bán chạy (top 10)
        $topProducts = DB::table('order_items')
            ->join('comics', 'order_items.comic_id', '=', 'comics.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.order_status', Order::STATUS_COMPLETED)
            ->select('comics.id', 'comics.title', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->groupBy('comics.id', 'comics.title')
            ->orderBy('total_sold', 'desc')
            ->limit(10)
            ->get();

        // Doanh thu theo ngày (7 ngày gần nhất)
        $revenueByDay = Order::where('order_status', Order::STATUS_COMPLETED)
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as revenue'),
                DB::raw('COUNT(*) as orders')
            )
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        return view('admin.dashboard', compact(
            'orderStats',
            'revenueStats',
            'productStats',
            'userStats',
            'recentOrders',
            'topProducts',
            'revenueByDay'
        ));
    }
}
