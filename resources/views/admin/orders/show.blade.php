@extends('layouts.admin')

@section('title', 'Chi tiết đơn hàng')
@section('page-title', 'Đơn hàng: ' . $order->code)

@section('content')
<div class="space-y-6">
    <!-- Back & Actions -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Quay lại
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Order Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Status Update Form -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold mb-4">Cập nhật trạng thái</h3>
                <form action="{{ route('admin.orders.update-status', $order->code) }}" method="POST" class="flex flex-wrap gap-4">
                    @csrf
                    <div class="flex-1 min-w-[200px]">
                        <select name="order_status" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                            @foreach(\App\Models\Order::getStatuses() as $key => $value)
                                <option value="{{ $key }}" {{ $order->order_status == $key ? 'selected' : '' }}>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex-1 min-w-[200px]">
                        <select name="payment_status" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                            @foreach(\App\Models\Order::getPaymentStatuses() as $key => $value)
                                <option value="{{ $key }}" {{ $order->payment_status == $key ? 'selected' : '' }}>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Cập nhật
                    </button>
                </form>
            </div>

            <!-- Order Items -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold mb-4">Sản phẩm</h3>
                <div class="space-y-4">
                    @foreach($order->items as $item)
                        <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg">
                            @if($item->comic && $item->comic->cover)
                                <img src="{{ $item->comic->cover }}" alt="{{ $item->comic->title ?? 'Sản phẩm' }}" class="w-16 h-20 object-cover rounded">
                            @else
                                <div class="w-16 h-20 bg-gray-200 rounded flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @endif
                            <div class="flex-1">
                                <p class="font-medium">{{ $item->comic->title ?? 'Sản phẩm đã xóa' }}</p>
                                <p class="text-sm text-gray-500">Đơn giá: {{ number_format($item->price) }} VNĐ</p>
                            </div>
                            <div class="text-center">
                                <p class="text-sm text-gray-500">Số lượng</p>
                                <p class="font-medium">{{ $item->quantity }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-500">Thành tiền</p>
                                <p class="font-medium">{{ number_format($item->price * $item->quantity) }} VNĐ</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Order Summary -->
                <div class="mt-6 pt-6 border-t space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Tạm tính</span>
                        <span>{{ number_format($order->subtotal) }} VNĐ</span>
                    </div>
                    @if($order->discount_amount > 0)
                        <div class="flex justify-between text-sm text-green-600">
                            <span>Giảm giá</span>
                            <span>-{{ number_format($order->discount_amount) }} VNĐ</span>
                        </div>
                    @endif
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Phí vận chuyển</span>
                        <span>{{ number_format($order->shipping_fee) }} VNĐ</span>
                    </div>
                    <div class="flex justify-between font-bold text-lg pt-2 border-t">
                        <span>Tổng cộng</span>
                        <span class="text-green-600">{{ number_format($order->total_amount) }} VNĐ</span>
                    </div>
                </div>
            </div>

            <!-- Shipping Info -->
            @if($order->shipments && $order->shipments->count() > 0)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold mb-4">Thông tin vận chuyển</h3>
                    @foreach($order->shipments as $shipment)
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-500">Đối tác:</span>
                                    <span class="font-medium">{{ $shipment->shippingPartner->name ?? 'N/A' }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Mã vận đơn:</span>
                                    <span class="font-medium">{{ $shipment->tracking_code ?? 'N/A' }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Trạng thái:</span>
                                    <span class="font-medium">{{ $shipment->status }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Customer Info Sidebar -->
        <div class="space-y-6">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold mb-4">Thông tin khách hàng</h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <span class="text-gray-500">Họ tên:</span>
                        <p class="font-medium">{{ $order->customer_name }}</p>
                    </div>
                    <div>
                        <span class="text-gray-500">Điện thoại:</span>
                        <p class="font-medium">{{ $order->customer_phone }}</p>
                    </div>
                    <div>
                        <span class="text-gray-500">Email:</span>
                        <p class="font-medium">{{ $order->customer_email ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <span class="text-gray-500">Địa chỉ:</span>
                        <p class="font-medium">{{ $order->shipping_address }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold mb-4">Thông tin đơn hàng</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Mã đơn:</span>
                        <span class="font-medium">{{ $order->code }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Ngày tạo:</span>
                        <span class="font-medium">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Phương thức TT:</span>
                        <span class="font-medium">{{ $order->payment_method == 'cod' ? 'COD' : 'Chuyển khoản' }}</span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold mb-4">Thao tác</h3>
                <div class="space-y-2">
                    @if($order->order_status !== 'cancelled' && $order->order_status !== 'completed')
                        <form action="{{ route('admin.orders.cancel', $order->code) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn hủy đơn hàng này?')">
                            @csrf
                            <button type="submit" class="w-full px-4 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors">
                                Hủy đơn hàng
                            </button>
                        </form>
                    @endif

                    @if($order->order_status === 'completed')
                        <form action="{{ route('admin.orders.return', $order->code) }}" method="POST" onsubmit="return confirm('Xác nhận xử lý hoàn trả?')">
                            @csrf
                            <button type="submit" class="w-full px-4 py-2 bg-yellow-100 text-yellow-700 rounded-lg hover:bg-yellow-200 transition-colors">
                                Xử lý hoàn trả
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            @if($order->notes)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold mb-4">Ghi chú</h3>
                    <p class="text-gray-600">{{ $order->notes }}</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
