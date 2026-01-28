@extends('layouts.admin')

@section('title', 'Thêm NXB')
@section('page-title', 'Thêm nhà xuất bản mới')

@section('content')
<div class="max-w-2xl">
    <form action="{{ route('admin.publishers.store') }}" method="POST" class="bg-white rounded-lg shadow-md p-6 space-y-6">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Tên NXB <span class="text-red-500">*</span></label>
            <input type="text" name="name" value="{{ old('name') }}" required
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror">
            @error('name')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>



        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Mô tả</label>
            <textarea name="description" rows="3"
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('description') }}</textarea>
        </div>

        <div class="flex items-center justify-end gap-4">
            <a href="{{ route('admin.publishers.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Hủy</a>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Tạo NXB</button>
        </div>
    </form>
</div>
@endsection
