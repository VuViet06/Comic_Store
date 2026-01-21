{{-- Trang checkout --}}
@extends('layouts.app')

@section('title', 'Thanh toán')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Thanh toán</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Form thông tin --}}
        <div class="lg:col-span-2">
            <form action="{{ route('checkout.process') }}" method="POST">
                @csrf

                {{-- Thông tin người nhận --}}
                <div class="border rounded-lg p-6 mb-6">
                    <h2 class="text-xl font-bold mb-4">Thông tin người nhận</h2>
                    
                    {{-- Dropdown chọn địa chỉ đã lưu (chỉ hiển thị cho logged-in user) --}}
                    @auth
                        @php
                            $savedAddresses = auth()->user()->addresses()->orderBy('is_default', 'desc')->get();
                        @endphp
                        @if($savedAddresses->count() > 0)
                            <div class="mb-4 p-4 bg-blue-50 rounded-lg">
                                <label for="saved_address" class="form-label text-blue-800">Chọn địa chỉ đã lưu</label>
                                <select name="saved_address" id="saved_address" class="form-input">
                                    <option value="">-- Nhập địa chỉ mới --</option>
                                    @foreach($savedAddresses as $addr)
                                        <option value="{{ $addr->id }}" 
                                                data-name="{{ $addr->recipient_name }}"
                                                data-phone="{{ $addr->phone }}"
                                                data-address="{{ $addr->address_line }}"
                                                data-ward="{{ $addr->ward }}"
                                                data-province="{{ $addr->province }}"
                                                data-postal="{{ $addr->postal_code }}"
                                                {{ $addr->is_default ? 'selected' : '' }}>
                                            {{ $addr->label ? "[$addr->label] " : '' }}{{ $addr->recipient_name }} - {{ $addr->full_address }}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="text-xs text-blue-600 mt-1">
                                    <a href="{{ route('addresses.create') }}" class="hover:underline">+ Thêm địa chỉ mới</a>
                                </p>
                            </div>
                        @endif
                    @endauth

                    <div class="space-y-4">
                        <div>
                            <label for="customer_name" class="form-label">Họ và tên *</label>
                            <input type="text" name="customer_name" id="customer_name" 
                                   value="{{ old('customer_name', $defaultData['customer_name'] ?? '') }}" 
                                   class="form-input" required>
                            @error('customer_name') <span class="form-error">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="customer_phone" class="form-label">Số điện thoại *</label>
                            <input type="tel" name="customer_phone" id="customer_phone" 
                                   value="{{ old('customer_phone', $defaultData['customer_phone'] ?? '') }}" 
                                   class="form-input" required>
                            @error('customer_phone') <span class="form-error">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="shipping_address_line" class="form-label">Địa chỉ *</label>
                            <input type="text" name="shipping_address_line" id="shipping_address_line" 
                                   value="{{ old('shipping_address_line') }}" 
                                   class="form-input" required>
                            @error('shipping_address_line') <span class="form-error">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="shipping_ward" class="form-label">Phường/Xã</label>
                                <input type="text" name="shipping_ward" id="shipping_ward" 
                                       value="{{ old('shipping_ward') }}" class="form-input">
                            </div>

                            <div>
                                <label for="shipping_province" class="form-label">Tỉnh/Thành phố</label>
                                <input type="text" name="shipping_province" id="shipping_province" 
                                       value="{{ old('shipping_province') }}" class="form-input">
                            </div>
                        </div>

                        <div>
                            <label for="shipping_postal_code" class="form-label">Mã bưu điện</label>
                            <input type="text" name="shipping_postal_code" id="shipping_postal_code" 
                                   value="{{ old('shipping_postal_code') }}" class="form-input">
                        </div>
                    </div>
                </div>

                {{-- Phương thức thanh toán --}}
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-xl font-bold mb-4">Phương thức thanh toán</h2>
                    
                    <div class="space-y-3">
                        <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 transition-colors">
                            <input type="radio" name="payment_method" value="cod" checked class="mr-3 w-4 h-4 text-blue-600">
                            <div>
                                <span class="font-medium">Thanh toán khi nhận hàng (COD)</span>
                                <p class="text-sm text-gray-600">Thanh toán bằng tiền mặt khi nhận hàng</p>
                            </div>
                        </label>
                        <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 transition-colors">
                            <input type="radio" name="payment_method" value="vnpay" class="mr-3 w-4 h-4 text-blue-600">
                            <div>
                                <span class="font-medium">VNPay</span>
                                <p class="text-sm text-gray-600">Thanh toán qua cổng VNPay</p>
                            </div>
                        </label>
                        <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 transition-colors">
                            <input type="radio" name="payment_method" value="momo" class="mr-3 w-4 h-4 text-blue-600">
                            <div>
                                <span class="font-medium">Ví MoMo</span>
                                <p class="text-sm text-gray-600">Thanh toán qua ví điện tử MoMo</p>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Ghi chú --}}
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <label for="customer_note" class="form-label">Ghi chú (tùy chọn)</label>
                    <textarea name="customer_note" id="customer_note" rows="3" 
                              class="form-input" placeholder="Ghi chú cho đơn hàng...">{{ old('customer_note') }}</textarea>
                </div>

                {{-- Mã giảm giá --}}
                <div class="border rounded-lg p-6 mb-6 bg-gray-50">
                    <label for="voucher_code" class="form-label">Mã giảm giá</label>
                    <div class="flex gap-2">
                        <input type="text" name="voucher_code" id="voucher_code" 
                               class="form-input flex-1" placeholder="Nhập mã giảm giá">
                        <button type="button" id="apply-voucher-btn" class="btn-secondary whitespace-nowrap">
                            Áp dụng
                        </button>
                    </div>
                    <div id="voucher-message" class="mt-2 text-sm"></div>
                </div>

                <input type="hidden" name="voucher_id" id="voucher_id">

                <button type="submit" id="checkout-submit-btn" class="btn-primary w-full py-3 text-lg">
                    Đặt hàng
                </button>
            </form>
        </div>

        {{-- Tóm tắt đơn hàng --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-4">
                <h2 class="text-xl font-bold mb-4">Đơn hàng của bạn</h2>
                
                <div class="space-y-2 mb-4">
                    @foreach($cartSummary['items'] as $item)
                        <div class="flex justify-between text-sm">
                            <span>{{ $item['comic']->title }} x{{ $item['quantity'] }}</span>
                            <span>{{ number_format($item['subtotal']) }} VNĐ</span>
                        </div>
                    @endforeach
                </div>

                <div class="border-t pt-4 space-y-2">
                    <div class="flex justify-between">
                        <span>Tạm tính:</span>
                        <span id="subtotal">{{ number_format($cartSummary['subtotal']) }} VNĐ</span>
                    </div>
                    <div class="flex justify-between text-red-600" id="discount-row" style="display: none;">
                        <span>Giảm giá:</span>
                        <span id="discount">0 VNĐ</span>
                    </div>
                    <div class="flex justify-between font-bold text-lg border-t pt-2">
                        <span>Tổng cộng:</span>
                        <span id="total">{{ number_format($cartSummary['subtotal']) }} VNĐ</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Auto-fill form khi chọn địa chỉ đã lưu
document.getElementById('saved_address')?.addEventListener('change', function() {
    const selected = this.options[this.selectedIndex];
    
    if (this.value) {
        document.getElementById('customer_name').value = selected.dataset.name || '';
        document.getElementById('customer_phone').value = selected.dataset.phone || '';
        document.getElementById('shipping_address_line').value = selected.dataset.address || '';
        document.getElementById('shipping_ward').value = selected.dataset.ward || '';
        document.getElementById('shipping_province').value = selected.dataset.province || '';
        document.getElementById('shipping_postal_code').value = selected.dataset.postal || '';
    }
});

// Trigger change on page load if default address is selected
document.addEventListener('DOMContentLoaded', function() {
    const savedAddressSelect = document.getElementById('saved_address');
    if (savedAddressSelect && savedAddressSelect.value) {
        savedAddressSelect.dispatchEvent(new Event('change'));
    }
});

// Apply voucher
document.getElementById('apply-voucher-btn')?.addEventListener('click', async function() {
    const code = document.getElementById('voucher_code').value.trim();
    const messageDiv = document.getElementById('voucher-message');
    const button = this;
    
    if (!code) {
        messageDiv.innerHTML = '<span class="text-red-600">Vui lòng nhập mã giảm giá</span>';
        return;
    }
    
    try {
        setLoading(button, true);
        messageDiv.innerHTML = '';
        
        const response = await fetch('{{ route("checkout.apply-voucher") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ voucher_code: code })
        });
        
        const data = await response.json();
        
        if (data.success) {
            document.getElementById('voucher_id').value = data.voucher_id;
            document.getElementById('discount-row').style.display = 'flex';
            document.getElementById('discount').textContent = new Intl.NumberFormat('vi-VN').format(data.discount) + ' VNĐ';
            document.getElementById('total').textContent = new Intl.NumberFormat('vi-VN').format(data.total) + ' VNĐ';
            messageDiv.innerHTML = '<span class="text-green-600">✓ Áp dụng mã giảm giá thành công</span>';
            showToast('Áp dụng mã giảm giá thành công', 'success');
        } else {
            document.getElementById('voucher_id').value = '';
            document.getElementById('discount-row').style.display = 'none';
            document.getElementById('discount').textContent = '0 VNĐ';
            const subtotal = {{ $cartSummary['subtotal'] }};
            document.getElementById('total').textContent = new Intl.NumberFormat('vi-VN').format(subtotal) + ' VNĐ';
            messageDiv.innerHTML = '<span class="text-red-600">' + (data.message || 'Mã giảm giá không hợp lệ') + '</span>';
            showToast(data.message || 'Mã giảm giá không hợp lệ', 'error');
        }
    } catch (error) {
        messageDiv.innerHTML = '<span class="text-red-600">Có lỗi xảy ra. Vui lòng thử lại.</span>';
        showToast('Có lỗi xảy ra. Vui lòng thử lại.', 'error');
    } finally {
        setLoading(button, false);
    }
});

// Form submission with loading
document.querySelector('form[action="{{ route("checkout.process") }}"]')?.addEventListener('submit', function(e) {
    const button = document.getElementById('checkout-submit-btn');
    setLoading(button, true);
});
</script>
@endpush
@endsection
