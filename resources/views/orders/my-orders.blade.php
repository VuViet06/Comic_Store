{{-- Trang đơn hàng của tôi (chỉ logged-in) --}}
@extends('layouts.app')

@section('title', 'Đơn hàng của tôi')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Đơn hàng của tôi</h1>

    {{-- Thống kê --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
        <div class="border rounded-lg p-4 text-center">
            <div class="text-2xl font-bold">{{ $stats['total_orders'] }}</div>
            <div class="text-sm text-gray-600">Tổng đơn</div>
        </div>
        <div class="border rounded-lg p-4 text-center">
            <div class="text-2xl font-bold">{{ $stats['pending'] }}</div>
            <div class="text-sm text-gray-600">Chờ xử lý</div>
        </div>
        <div class="border rounded-lg p-4 text-center">
            <div class="text-2xl font-bold">{{ $stats['shipping'] }}</div>
            <div class="text-sm text-gray-600">Đang giao</div>
        </div>
        <div class="border rounded-lg p-4 text-center">
            <div class="text-2xl font-bold">{{ $stats['completed'] }}</div>
            <div class="text-sm text-gray-600">Hoàn thành</div>
        </div>
        <div class="border rounded-lg p-4 text-center">
            <div class="text-2xl font-bold">{{ number_format($stats['total_spent']) }}</div>
            <div class="text-sm text-gray-600">Tổng chi tiêu</div>
        </div>
    </div>

    {{-- Filter --}}
    <div class="mb-4">
        <a href="{{ route('my-orders.index') }}" 
           class="px-4 py-2 {{ !$currentStatus ? 'bg-blue-600 text-white' : 'bg-gray-200' }} rounded">
            Tất cả
        </a>
        @foreach(\App\Models\Order::getStatuses() as $status => $label)
            <a href="{{ route('my-orders.index', ['status' => $status]) }}" 
               class="px-4 py-2 {{ $currentStatus === $status ? 'bg-blue-600 text-white' : 'bg-gray-200' }} rounded">
                {{ $label }}
            </a>
        @endforeach
    </div>

    {{-- Danh sách đơn hàng --}}
    <div class="space-y-4">
        @forelse($orders as $order)
            <div class="border rounded-lg p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-lg font-bold">Mã đơn: {{ $order->code }}</h3>
                        <p class="text-sm text-gray-600">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div class="text-right">
                        <span class="px-3 py-1 rounded {{ $order->order_status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ \App\Models\Order::getStatuses()[$order->order_status] ?? $order->order_status }}
                        </span>
                        <p class="text-lg font-bold mt-2">{{ number_format($order->total_amount) }} VNĐ</p>
                    </div>
                </div>

                <div class="mb-4">
                    <p class="text-sm"><strong>Sản phẩm:</strong> {{ $order->items->count() }} món</p>
                </div>

                <div class="flex gap-2">
                    <a href="{{ route('my-orders.show', $order->code) }}" class="btn-secondary">
                        Xem chi tiết
                    </a>
                    @if($order->canBeCancelled())
                        <form action="{{ route('my-orders.cancel', $order->code) }}" method="POST" 
                              onsubmit="return confirm('Bạn có chắc muốn hủy đơn hàng này?')">
                            @csrf
                            <button type="submit" class="btn-danger">Hủy đơn</button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center py-12">
                <p class="text-xl text-gray-600">Bạn chưa có đơn hàng nào</p>
                <a href="{{ route('home') }}" class="btn-primary mt-4">Mua sắm ngay</a>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($total > 15)
        <div class="mt-6 flex justify-center">
            <div class="flex gap-2">
                @php
                    $currentPage = request()->get('page', 1);
                    $totalPages = ceil($total / 15);
                @endphp
                
                @if($currentPage > 1)
                    <a href="{{ route('my-orders.index', array_merge(request()->query(), ['page' => $currentPage - 1])) }}" 
                       class="btn-secondary">Trước</a>
                @endif
                
                @for($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++)
                    <a href="{{ route('my-orders.index', array_merge(request()->query(), ['page' => $i])) }}" 
                       class="pagination-link {{ $i == $currentPage ? 'active' : '' }}">
                        {{ $i }}
                    </a>
                @endfor
                
                @if($currentPage < $totalPages)
                    <a href="{{ route('my-orders.index', array_merge(request()->query(), ['page' => $currentPage + 1])) }}" 
                       class="btn-secondary">Sau</a>
                @endif
            </div>
        </div>
    @endif
</div>
@endsection
