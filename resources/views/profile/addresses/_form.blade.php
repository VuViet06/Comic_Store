{{-- Form địa chỉ giao hàng (dùng chung cho create/edit) --}}
@php
    $address = $address ?? null;
@endphp

<div class="space-y-4">
    {{-- Label --}}
    <div>
        <label for="label" class="form-label">Nhãn địa chỉ</label>
        <input type="text" name="label" id="label" 
               value="{{ old('label', $address?->label) }}" 
               class="form-input" 
               placeholder="VD: Nhà, Văn phòng, ...">
        @error('label') <span class="form-error">{{ $message }}</span> @enderror
    </div>

    {{-- Recipient Name --}}
    <div>
        <label for="recipient_name" class="form-label">Tên người nhận *</label>
        <input type="text" name="recipient_name" id="recipient_name" 
               value="{{ old('recipient_name', $address?->recipient_name) }}" 
               class="form-input" required>
        @error('recipient_name') <span class="form-error">{{ $message }}</span> @enderror
    </div>

    {{-- Phone --}}
    <div>
        <label for="phone" class="form-label">Số điện thoại *</label>
        <input type="tel" name="phone" id="phone" 
               value="{{ old('phone', $address?->phone) }}" 
               class="form-input" required>
        @error('phone') <span class="form-error">{{ $message }}</span> @enderror
    </div>

    {{-- Address Line --}}
    <div>
        <label for="address_line" class="form-label">Địa chỉ chi tiết *</label>
        <input type="text" name="address_line" id="address_line" 
               value="{{ old('address_line', $address?->address_line) }}" 
               class="form-input" 
               placeholder="Số nhà, tên đường, ..."
               required>
        @error('address_line') <span class="form-error">{{ $message }}</span> @enderror
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        {{-- Ward --}}
        <div>
            <label for="ward" class="form-label">Phường/Xã</label>
            <input type="text" name="ward" id="ward" 
                   value="{{ old('ward', $address?->ward) }}" 
                   class="form-input">
            @error('ward') <span class="form-error">{{ $message }}</span> @enderror
        </div>

        {{-- District --}}
        <div>
            <label for="district" class="form-label">Quận/Huyện</label>
            <input type="text" name="district" id="district" 
                   value="{{ old('district', $address?->district) }}" 
                   class="form-input">
            @error('district') <span class="form-error">{{ $message }}</span> @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        {{-- Province --}}
        <div>
            <label for="province" class="form-label">Tỉnh/Thành phố</label>
            <input type="text" name="province" id="province" 
                   value="{{ old('province', $address?->province) }}" 
                   class="form-input">
            @error('province') <span class="form-error">{{ $message }}</span> @enderror
        </div>

        {{-- Postal Code --}}
        <div>
            <label for="postal_code" class="form-label">Mã bưu điện</label>
            <input type="text" name="postal_code" id="postal_code" 
                   value="{{ old('postal_code', $address?->postal_code) }}" 
                   class="form-input">
            @error('postal_code') <span class="form-error">{{ $message }}</span> @enderror
        </div>
    </div>

    {{-- Is Default (chỉ hiển thị khi tạo mới hoặc chưa phải mặc định) --}}
    @if(!$address || !$address->is_default)
        <div class="flex items-center">
            <input type="checkbox" name="is_default" id="is_default" value="1" 
                   {{ old('is_default') ? 'checked' : '' }}
                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
            <label for="is_default" class="ml-2 text-sm text-gray-700">
                Đặt làm địa chỉ mặc định
            </label>
        </div>
    @endif
</div>
