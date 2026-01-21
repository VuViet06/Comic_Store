{{-- Trang kết quả tra cứu đơn hàng (cho guest) --}}
@extends('layouts.app')

@section('title', 'Tra cứu đơn hàng')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <a href="{{ route('orders.track') }}" class="text-blue-600 hover:underline">← Tra cứu đơn hàng khác</a>
    </div>

    <h1 class="text-3xl font-bold mb-6">Thông tin đơn hàng</h1>

    <div class="max-w-3xl mx-auto">
        <div class="border rounded-lg p-6">
            <div class="mb-6">
                <h2 class="text-xl font-bold mb-4">Thông tin đơn hàng</h2>
                <div class="space-y-2">
                    <p><strong>Mã đơn:</strong> <span class="font-mono">{{ $order->code }}</span></p>
                    <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                    <p><strong>Trạng thái:</strong> 
                        <span class="px-3 py-1 rounded {{ $order->order_status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ \App\Models\Order::getStatuses()[$order->order_status] ?? $order->order_status }}
                        </span>
                    </p>
                    <p><strong>Tổng tiền:</strong> {{ number_format($order->total_amount) }} VNĐ</p>
                </div>
            </div>

            {{-- Danh sách sản phẩm --}}
            <div class="mb-6">
                <h2 class="text-xl font-bold mb-4">Sản phẩm</h2>
                <div class="space-y-2">
                    @foreach($order->items as $item)
                        <div class="flex justify-between border-b pb-2">
                            <span>{{ $item->comic->title }} x{{ $item->quantity }}</span>
                            <span>{{ number_format($item->subtotal) }} VNĐ</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="text-center">
                <a href="{{ route('home') }}" class="btn-primary">Tiếp tục mua sắm</a>
            </div>
        </div>
    </div>
</div>
@endsection
