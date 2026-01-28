@extends('layouts.admin')

@section('title', 'Nhập hàng')
@section('page-title', 'Nhập hàng: ' . $comic->title)

@section('content')
<div class="max-w-2xl">
    <div class="mb-6">
        <a href="{{ route('admin.inventory.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Quay lại
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex items-center gap-4">
            @if($comic->cover)
                <img src="{{ $comic->cover }}" alt="{{ $comic->title }}" class="w-20 h-28 object-cover rounded">
            @endif
            <div>
                <h3 class="font-bold text-lg">{{ $comic->title }}</h3>
                <p class="text-gray-600">Tồn kho hiện tại: <strong class="{{ $comic->stock == 0 ? 'text-red-600' : 'text-green-600' }}">{{ $comic->stock }}</strong></p>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.inventory.import', $comic->id) }}" method="POST" class="bg-white rounded-lg shadow-md p-6 space-y-6">
        @csrf
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Số lượng nhập <span class="text-red-500">*</span></label>
            <input type="number" name="quantity" value="{{ old('quantity', 1) }}" required min="1"
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('quantity') border-red-500 @enderror">
            @error('quantity')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Ghi chú</label>
            <textarea name="notes" rows="3" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="VD: Nhập từ nhà cung cấp ABC">{{ old('notes') }}</textarea>
        </div>

        <div class="flex items-center justify-end gap-4">
            <a href="{{ route('admin.inventory.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Hủy</a>
            <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Nhập hàng</button>
        </div>
    </form>
</div>
@endsection
