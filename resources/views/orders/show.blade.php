{{-- Trang chi tiết đơn hàng (chỉ logged-in) --}}
@extends('layouts.app')

@section('title', 'Chi tiết đơn hàng')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <a href="{{ route('my-orders.index') }}" class="text-blue-600 hover:underline">← Quay lại danh sách đơn hàng</a>
    </div>

    <h1 class="text-3xl font-bold mb-6">Chi tiết đơn hàng</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Thông tin đơn hàng --}}
        <div class="lg:col-span-2">
            {{-- Thông tin cơ bản --}}
            <div class="border rounded-lg p-6 mb-6">
                <h2 class="text-xl font-bold mb-4">Thông tin đơn hàng</h2>
                <div class="space-y-2">
                    <p><strong>Mã đơn:</strong> <span class="font-mono">{{ $order->code }}</span></p>
                    <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                    <p><strong>Trạng thái:</strong> 
                        <span class="px-3 py-1 rounded {{ $order->order_status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ \App\Models\Order::getStatuses()[$order->order_status] ?? $order->order_status }}
                        </span>
                    </p>
                    <p><strong>Phương thức thanh toán:</strong> {{ \App\Models\Order::getPaymentMethods()[$order->payment_method] ?? $order->payment_method }}</p>
                    <p><strong>Trạng thái thanh toán:</strong> {{ $order->payment_status }}</p>
                </div>
            </div>

            {{-- Danh sách sản phẩm --}}
            <div class="border rounded-lg p-6 mb-6">
                <h2 class="text-xl font-bold mb-4">Sản phẩm</h2>
                <table class="w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-2">Sản phẩm</th>
                            <th class="text-left py-2">Giá</th>
                            <th class="text-left py-2">Số lượng</th>
                            <th class="text-left py-2">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr class="border-b">
                                <td class="py-4">
                                    <div class="flex items-center gap-4">
                                        {{-- TODO: Ảnh bìa --}}
                                        <div>
                                            <h3 class="font-semibold">{{ $item->comic->title }}</h3>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4">{{ number_format($item->price) }} VNĐ</td>
                                <td class="py-4">{{ $item->quantity }}</td>
                                <td class="py-4">{{ number_format($item->subtotal) }} VNĐ</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Thông tin giao hàng --}}
            <div class="border rounded-lg p-6">
                <h2 class="text-xl font-bold mb-4">Thông tin giao hàng</h2>
                <div class="space-y-2">
                    <p><strong>Người nhận:</strong> {{ $order->customer_name }}</p>
                    <p><strong>Số điện thoại:</strong> {{ $order->customer_phone }}</p>
                    <p><strong>Địa chỉ:</strong> {{ $order->shipping_address_line }}</p>
                    @if($order->shipping_ward)
                        <p><strong>Phường/Xã:</strong> {{ $order->shipping_ward }}</p>
                    @endif
                    @if($order->shipping_province)
                        <p><strong>Tỉnh/Thành phố:</strong> {{ $order->shipping_province }}</p>
                    @endif
                    @if($order->customer_note)
                        <p><strong>Ghi chú:</strong> {{ $order->customer_note }}</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Tổng kết --}}
        <div class="lg:col-span-1">
            <div class="border rounded-lg p-6 sticky top-4">
                <h2 class="text-xl font-bold mb-4">Tổng kết</h2>
                <div class="space-y-2 mb-4">
                    <div class="flex justify-between">
                        <span>Tạm tính:</span>
                        <span>{{ number_format($order->subtotal_amount) }} VNĐ</span>
                    </div>
                    @if($order->discount_amount > 0)
                        <div class="flex justify-between text-red-600">
                            <span>Giảm giá:</span>
                            <span>-{{ number_format($order->discount_amount) }} VNĐ</span>
                        </div>
                    @endif
                    <div class="flex justify-between font-bold text-lg border-t pt-2">
                        <span>Tổng cộng:</span>
                        <span>{{ number_format($order->total_amount) }} VNĐ</span>
                    </div>
                </div>

                @if($order->canBeCancelled())
                    <form action="{{ route('my-orders.cancel', $order->code) }}" method="POST" 
                          onsubmit="return confirm('Bạn có chắc muốn hủy đơn hàng này?')">
                        @csrf
                        <button type="submit" class="btn-danger w-full">Hủy đơn hàng</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
