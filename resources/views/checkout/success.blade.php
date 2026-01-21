{{-- Trang đặt hàng thành công --}}
@extends('layouts.app')

@section('title', 'Đặt hàng thành công')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto text-center">
        <div class="mb-6">
            <svg class="w-16 h-16 text-green-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>

        <h1 class="text-3xl font-bold mb-4">Đặt hàng thành công!</h1>
        <p class="text-xl text-gray-600 mb-6">Cảm ơn bạn đã mua sắm tại cửa hàng của chúng tôi</p>

        <div class="border rounded-lg p-6 mb-6 text-left">
            <h2 class="text-xl font-bold mb-4">Thông tin đơn hàng</h2>
            <div class="space-y-2">
                <p><strong>Mã đơn hàng:</strong> <span class="font-mono">{{ $order->code }}</span></p>
                <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                <p><strong>Trạng thái:</strong> {{ \App\Models\Order::getStatuses()[$order->order_status] ?? $order->order_status }}</p>
                <p><strong>Tổng tiền:</strong> {{ number_format($order->total_amount) }} VNĐ</p>
            </div>
        </div>

        <div class="space-x-4">
            @auth
                <a href="{{ route('my-orders.show', $order->code) }}" class="btn-primary">
                    Xem chi tiết đơn hàng
                </a>
            @else
                <a href="{{ route('orders.track') }}" class="btn-primary">
                    Tra cứu đơn hàng
                </a>
            @endauth
            <a href="{{ route('home') }}" class="btn-secondary">
                Tiếp tục mua sắm
            </a>
        </div>
    </div>
</div>
@endsection
