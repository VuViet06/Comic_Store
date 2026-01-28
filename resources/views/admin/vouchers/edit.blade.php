@extends('layouts.admin')

@section('title', 'Sửa mã giảm giá')
@section('page-title', 'Sửa mã: ' . $voucher->code)

@section('content')
<div class="max-w-2xl">
    <form action="{{ route('admin.vouchers.update', $voucher->id) }}" method="POST" class="bg-white rounded-lg shadow-md p-6 space-y-6">
        @csrf
        @method('PUT')
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Mã voucher <span class="text-red-500">*</span></label>
            <input type="text" name="code" value="{{ old('code', $voucher->code) }}" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 font-mono uppercase @error('code') border-red-500 @enderror">
            @error('code')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Loại giảm giá <span class="text-red-500">*</span></label>
                <select name="type" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="percent" {{ old('type', $voucher->type) == 'percent' ? 'selected' : '' }}>Phần trăm (%)</option>
                    <option value="fixed" {{ old('type', $voucher->type) == 'fixed' ? 'selected' : '' }}>Số tiền cố định</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Giá trị <span class="text-red-500">*</span></label>
                <input type="number" name="value" value="{{ old('value', $voucher->value) }}" required min="0" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Đơn tối thiểu (VNĐ)</label>
                <input type="number" name="min_order_amount" value="{{ old('min_order_amount', $voucher->min_order_amount) }}" min="0" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Giảm tối đa (VNĐ)</label>
                <input type="number" name="max_discount" value="{{ old('max_discount', $voucher->max_discount) }}" min="0" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Giới hạn sử dụng</label>
                <input type="number" name="usage_limit" value="{{ old('usage_limit', $voucher->usage_limit) }}" min="1" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Hạn sử dụng</label>
                <input type="date" name="expires_at" value="{{ old('expires_at', $voucher->expires_at ? \Carbon\Carbon::parse($voucher->expires_at)->format('Y-m-d') : '') }}" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>
        </div>

        <div>
            <label class="flex items-center">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $voucher->is_active) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600">
                <span class="ml-2 text-sm text-gray-700">Kích hoạt mã giảm giá</span>
            </label>
        </div>

        <div class="flex items-center justify-end gap-4">
            <a href="{{ route('admin.vouchers.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Hủy</a>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Cập nhật</button>
        </div>
    </form>
</div>
@endsection
