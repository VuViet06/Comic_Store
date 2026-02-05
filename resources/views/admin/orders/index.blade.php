@extends('layouts.admin')

@section('title', 'Quản lý Đơn hàng')
@section('page-title', 'Quản lý Đơn hàng')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="grid grid-cols-1 md:grid-cols-6 gap-4">
            <div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Mã đơn, tên, SĐT..."
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <select name="status" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">Tất cả trạng thái</option>
                    @foreach(\App\Models\Order::getStatuses() as $key => $value)
                        <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <select name="payment_status" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">Thanh toán</option>
                    @foreach(\App\Models\Order::getPaymentStatuses() as $key => $value)
                        <option value="{{ $key }}" {{ request('payment_status') == $key ? 'selected' : '' }}>{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <input type="date" name="date_from" value="{{ request('date_from') }}" placeholder="Từ ngày"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <input type="date" name="date_to" value="{{ request('date_to') }}" placeholder="Đến ngày"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                    Lọc
                </button>
                <a href="{{ route('admin.orders.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mã đơn</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Khách hàng</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tổng tiền</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thanh toán</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày tạo</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($orders as $order)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.orders.show', $order->code) }}" class="text-blue-600 hover:underline font-medium">
                                    {{ $order->code }}
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $order->customer_name }}</div>
                                <div class="text-sm text-gray-500">{{ $order->customer_phone }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                {{ number_format($order->total_amount) }} VNĐ
                            </td>
                            <td class="px-6 py-4">
                                @switch($order->order_status)
                                    @case('pending')
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">Chờ xử lý</span>
                                        @break
                                    @case('shipping')
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">Đang giao</span>
                                        @break
                                    @case('completed')
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Hoàn thành</span>
                                        @break
                                    @case('cancelled')
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">Đã hủy</span>
                                        @break
                                    @default
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">{{ $order->order_status }}</span>
                                @endswitch
                            </td>
                            <td class="px-6 py-4">
                                @switch($order->payment_status)
                                    @case('paid')
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Đã thanh toán</span>
                                        @break
                                    @case('unpaid')
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">Chưa thanh toán</span>
                                        @break
                                    @case('pending')
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">Đang xử lý</span>
                                        @break
                                    @default
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">{{ $order->payment_status }}</span>
                                @endswitch
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $order->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.orders.show', $order->code) }}" class="text-blue-600 hover:text-blue-900">
                                    Xem chi tiết
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                Chưa có đơn hàng nào
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
            <div class="px-6 py-4 border-t">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
