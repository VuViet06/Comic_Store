{{-- Trang tra cứu đơn hàng (cho guest) --}}
@extends('layouts.app')

@section('title', 'Tra cứu đơn hàng')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto">
        <h1 class="text-3xl font-bold mb-6 text-center">Tra cứu đơn hàng</h1>

        <div class="border rounded-lg p-6">
            <p class="text-gray-600 mb-6 text-center">
                Nhập mã đơn hàng và số điện thoại để tra cứu
            </p>

            <form action="{{ route('orders.track') }}" method="GET">
                <div class="space-y-4">
                    <div>
                        <label class="block mb-2">Mã đơn hàng *</label>
                        <input type="text" name="code" value="{{ old('code') }}" 
                               class="w-full border rounded px-3 py-2" 
                               placeholder="VD: ORD-ABC12345" required>
                        @error('code') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block mb-2">Số điện thoại *</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" 
                               class="w-full border rounded px-3 py-2" 
                               placeholder="Số điện thoại đã đặt hàng" required>
                        @error('phone') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    <button type="submit" class="btn-primary w-full">
                        Tra cứu
                    </button>
                </div>
            </form>

            @auth
                <div class="mt-6 text-center">
                    <p class="text-gray-600 mb-2">Bạn đã có tài khoản?</p>
                    <a href="{{ route('my-orders.index') }}" class="text-blue-600 hover:underline">
                        Xem đơn hàng của tôi
                    </a>
                </div>
            @else
                <div class="mt-6 text-center">
                    <p class="text-gray-600 mb-2">Bạn đã có tài khoản?</p>
                    <a href="{{ route('login') }}" class="text-blue-600 hover:underline">
                        Đăng nhập để xem đơn hàng
                    </a>
                </div>
            @endauth
        </div>
    </div>
</div>
@endsection
