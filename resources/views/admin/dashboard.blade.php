@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-6">
    {{-- Thống kê tổng quan --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        {{-- Đơn hàng --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Tổng đơn hàng</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $orderStats['total'] }}</p>
                </div>
                <div class="bg-blue-100 rounded-full p-3">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex gap-4 text-sm">
                <span class="text-gray-600">Hôm nay: <strong>{{ $orderStats['today'] }}</strong></span>
                <span class="text-gray-600">Tháng này: <strong>{{ $orderStats['this_month'] }}</strong></span>
            </div>
        </div>

        {{-- Doanh thu --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Doanh thu tháng này</p>
                    <p class="text-3xl font-bold text-green-600 mt-2">{{ number_format($revenueStats['this_month']) }} VNĐ</p>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex gap-4 text-sm">
                <span class="text-gray-600">Hôm nay: <strong>{{ number_format($revenueStats['today']) }} VNĐ</strong></span>
            </div>
        </div>

        {{-- Sản phẩm --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Tổng sản phẩm</p>
                    <p class="text-3xl font-bold text-purple-600 mt-2">{{ $productStats['total'] }}</p>
                </div>
                <div class="bg-purple-100 rounded-full p-3">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex gap-4 text-sm">
                <span class="text-gray-600">Đang bán: <strong>{{ $productStats['active'] }}</strong></span>
                <span class="text-red-600">Hết hàng: <strong>{{ $productStats['out_of_stock'] }}</strong></span>
            </div>
        </div>

        {{-- Người dùng --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Tổng người dùng</p>
                    <p class="text-3xl font-bold text-orange-600 mt-2">{{ $userStats['total'] }}</p>
                </div>
                <div class="bg-orange-100 rounded-full p-3">
                    <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex gap-4 text-sm">
                <span class="text-gray-600">Khách hàng: <strong>{{ $userStats['customers'] }}</strong></span>
            </div>
        </div>
    </div>

    {{-- Chi tiết thống kê --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Trạng thái đơn hàng --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold mb-4">Trạng thái đơn hàng</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Chờ xử lý</span>
                    <span class="font-bold text-yellow-600">{{ $orderStats['pending'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Đang giao</span>
                    <span class="font-bold text-blue-600">{{ $orderStats['shipping'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Hoàn thành</span>
                    <span class="font-bold text-green-600">{{ $orderStats['completed'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Đã hủy</span>
                    <span class="font-bold text-red-600">{{ $orderStats['cancelled'] }}</span>
                </div>
            </div>
        </div>

        {{-- Doanh thu --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-bold mb-4">Doanh thu</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Hôm nay</span>
                    <span class="font-bold text-green-600">{{ number_format($revenueStats['today']) }} VNĐ</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Tháng này</span>
                    <span class="font-bold text-green-600">{{ number_format($revenueStats['this_month']) }} VNĐ</span>
                </div>
                <div class="flex items-center justify-between border-t pt-3">
                    <span class="text-gray-800 font-semibold">Tổng cộng</span>
                    <span class="font-bold text-green-700 text-lg">{{ number_format($revenueStats['all_time']) }} VNĐ</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Đơn hàng gần đây và Sản phẩm bán chạy --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Đơn hàng gần đây --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold">Đơn hàng gần đây</h3>
                <a href="{{ route('admin.orders.index') }}" class="text-blue-600 hover:underline text-sm">Xem tất cả</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left">Mã đơn</th>
                            <th class="px-4 py-2 text-left">Khách hàng</th>
                            <th class="px-4 py-2 text-left">Tổng tiền</th>
                            <th class="px-4 py-2 text-left">Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $order)
                            <tr class="border-b">
                                <td class="px-4 py-2">
                                    <a href="{{ route('admin.orders.show', $order->code) }}" class="text-blue-600 hover:underline">
                                        {{ $order->code }}
                                    </a>
                                </td>
                                <td class="px-4 py-2">{{ $order->customer_name }}</td>
                                <td class="px-4 py-2">{{ number_format($order->total_amount) }} VNĐ</td>
                                <td class="px-4 py-2">
                                    <span class="badge badge-{{ $order->order_status === 'completed' ? 'success' : ($order->order_status === 'cancelled' ? 'danger' : 'warning') }}">
                                        {{ \App\Models\Order::getStatuses()[$order->order_status] ?? $order->order_status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-4 text-center text-gray-500">Chưa có đơn hàng</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Sản phẩm bán chạy --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold">Sản phẩm bán chạy</h3>
                <a href="{{ route('admin.comics.index') }}" class="text-blue-600 hover:underline text-sm">Xem tất cả</a>
            </div>
            <div class="space-y-3">
                @forelse($topProducts as $product)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                        <div class="flex-1">
                            <p class="font-medium">{{ $product->title }}</p>
                            <p class="text-sm text-gray-600">Đã bán: {{ $product->total_sold }} quyển</p>
                        </div>
                        <a href="{{ route('admin.comics.show', $product->id) }}" class="text-blue-600 hover:underline text-sm">
                            Xem
                        </a>
                    </div>
                @empty
                    <p class="text-center text-gray-500 py-4">Chưa có dữ liệu</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
