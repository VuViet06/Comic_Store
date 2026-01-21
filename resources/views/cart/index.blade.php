{{-- Trang giỏ hàng --}}
@extends('layouts.app')

@section('title', 'Giỏ hàng')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Giỏ hàng của bạn</h1>

    @if($is_empty)
        <div class="text-center py-16 bg-white rounded-lg shadow-md">
            <svg class="w-24 h-24 text-gray-400 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <p class="text-xl text-gray-600 mb-4">Giỏ hàng của bạn đang trống</p>
            <a href="{{ route('home') }}" class="btn-primary inline-block">Tiếp tục mua sắm</a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Danh sách items --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="text-left py-4 px-4 font-semibold text-gray-700">Sản phẩm</th>
                                    <th class="text-left py-4 px-4 font-semibold text-gray-700 hidden md:table-cell">Giá</th>
                                    <th class="text-left py-4 px-4 font-semibold text-gray-700">Số lượng</th>
                                    <th class="text-left py-4 px-4 font-semibold text-gray-700">Thành tiền</th>
                                    <th class="text-left py-4 px-4 font-semibold text-gray-700"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $item)
                                    <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
                                        <td class="py-4 px-4">
                                            <div class="flex items-center gap-4">
                                                <div class="w-20 h-28 bg-gray-200 rounded overflow-hidden flex-shrink-0">
                                                    @if($item['comic']->cover)
                                                        <img src="{{ $item['comic']->cover }}" alt="{{ $item['comic']->title }}" 
                                                             class="w-full h-full object-cover">
                                                    @endif
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <h3 class="font-semibold mb-1">{{ $item['comic']->title }}</h3>
                                                    <p class="text-sm text-gray-600">{{ $item['comic']->publisher->name ?? 'N/A' }}</p>
                                                    <p class="text-sm text-gray-500 md:hidden mt-1">{{ number_format($item['price']) }} VNĐ</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-4 px-4 hidden md:table-cell">
                                            <span class="font-medium">{{ number_format($item['price']) }} VNĐ</span>
                                        </td>
                                        <td class="py-4 px-4">
                                            <form action="{{ route('cart.update') }}" method="POST" class="cart-update-form">
                                                @csrf
                                                <input type="hidden" name="comic_id" value="{{ $item['comic_id'] }}">
                                                <input type="number" name="quantity" value="{{ $item['quantity'] }}" 
                                                       min="1" max="{{ $item['comic']->stock }}" 
                                                       class="form-input w-20 text-center cart-quantity">
                                            </form>
                                        </td>
                                        <td class="py-4 px-4">
                                            <span class="font-bold item-subtotal">{{ number_format($item['subtotal']) }} VNĐ</span>
                                        </td>
                                        <td class="py-4 px-4">
                                            <form action="{{ route('cart.remove') }}" method="POST" class="cart-remove-form inline">
                                                @csrf
                                                <input type="hidden" name="comic_id" value="{{ $item['comic_id'] }}">
                                                <button type="submit" class="text-red-600 hover:text-red-800 transition-colors">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="p-4 border-t bg-gray-50">
                        <form action="{{ route('cart.clear') }}" method="POST" 
                              onsubmit="return confirm('Bạn có chắc muốn xóa toàn bộ giỏ hàng?')">
                            @csrf
                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium transition-colors">
                                Xóa toàn bộ giỏ hàng
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Tổng kết --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-4">
                    <h2 class="text-xl font-bold mb-4">Tổng kết</h2>
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between text-gray-600">
                            <span>Tạm tính:</span>
                            <span id="cart-subtotal">{{ number_format($subtotal) }} VNĐ</span>
                        </div>
                        <div class="flex justify-between font-bold text-lg border-t pt-3">
                            <span>Tổng cộng:</span>
                            <span id="cart-total" class="text-red-600">{{ number_format($subtotal) }} VNĐ</span>
                        </div>
                    </div>
                    <a href="{{ route('checkout.show') }}" class="btn-primary w-full text-center block mb-4">
                        Thanh toán
                    </a>
                    <a href="{{ route('home') }}" class="btn-outline w-full text-center block">
                        Tiếp tục mua sắm
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
// Update quantity
document.querySelectorAll('.cart-quantity').forEach(input => {
    input.addEventListener('change', async function() {
        const form = this.closest('form');
        const formData = new FormData(form);
        const row = form.closest('tr');
        const subtotalCell = row.querySelector('.item-subtotal');
        
        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                subtotalCell.textContent = new Intl.NumberFormat('vi-VN').format(data.item.subtotal) + ' VNĐ';
                document.getElementById('cart-subtotal').textContent = new Intl.NumberFormat('vi-VN').format(data.subtotal) + ' VNĐ';
                document.getElementById('cart-total').textContent = new Intl.NumberFormat('vi-VN').format(data.subtotal) + ' VNĐ';
                updateCartCount();
                showToast('Đã cập nhật giỏ hàng', 'success');
            } else {
                showToast(data.message || 'Có lỗi xảy ra', 'error');
                location.reload(); // Reload to sync with server
            }
        } catch (error) {
            showToast('Có lỗi xảy ra. Vui lòng thử lại.', 'error');
            location.reload();
        }
    });
});

// Remove item
document.querySelectorAll('.cart-remove-form').forEach(form => {
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        if (!confirm('Bạn có chắc muốn xóa sản phẩm này khỏi giỏ hàng?')) {
            return;
        }
        
        const formData = new FormData(this);
        
        try {
            const response = await fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.closest('tr').remove();
                document.getElementById('cart-subtotal').textContent = new Intl.NumberFormat('vi-VN').format(data.subtotal) + ' VNĐ';
                document.getElementById('cart-total').textContent = new Intl.NumberFormat('vi-VN').format(data.subtotal) + ' VNĐ';
                updateCartCount();
                showToast('Đã xóa khỏi giỏ hàng', 'success');
                
                // Check if cart is empty
                if (data.cart_count === 0) {
                    location.reload();
                }
            } else {
                showToast(data.message || 'Có lỗi xảy ra', 'error');
            }
        } catch (error) {
            showToast('Có lỗi xảy ra. Vui lòng thử lại.', 'error');
        }
    });
});
</script>
@endpush
@endsection
