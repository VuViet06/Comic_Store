@extends('layouts.admin')

@section('title', 'Thêm đối tác vận chuyển')
@section('page-title', 'Thêm đối tác vận chuyển mới')

@section('content')
<div class="max-w-2xl">
    <form action="{{ route('admin.shipping.store') }}" method="POST" class="bg-white rounded-lg shadow-md p-6 space-y-6">
        @csrf
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Tên đối tác <span class="text-red-500">*</span></label>
            <input type="text" name="name" value="{{ old('name') }}" required
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror">
            @error('name')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Mã đối tác <span class="text-red-500">*</span></label>
            <input type="text" name="code" value="{{ old('code') }}" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 font-mono uppercase @error('code') border-red-500 @enderror" placeholder="VD: GHTK, GHN, VNPOST">
            @error('code')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Phí vận chuyển mặc định (VNĐ)</label>
            <input type="number" name="shipping_fee" value="{{ old('shipping_fee', 0) }}" min="0"
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Thời gian giao hàng dự kiến</label>
            <input type="text" name="estimated_days" value="{{ old('estimated_days') }}" placeholder="VD: 2-3 ngày"
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Mô tả</label>
            <textarea name="description" rows="3" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('description') }}</textarea>
        </div>

        <div>
            <label class="flex items-center">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600">
                <span class="ml-2 text-sm text-gray-700">Kích hoạt đối tác</span>
            </label>
        </div>

        <div class="flex items-center justify-end gap-4">
            <a href="{{ route('admin.shipping.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Hủy</a>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Thêm đối tác</button>
        </div>
    </form>
</div>
@endsection
