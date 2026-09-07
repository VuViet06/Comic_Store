@extends('layouts.admin')

@section('title', 'Sửa truyện')
@section('page-title', 'Sửa truyện: ' . $comic->title)

@section('content')
<div class="max-w-4xl">
    <form action="{{ route('admin.comics.update', $comic->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-lg shadow-md p-6 space-y-6">
            <h3 class="text-lg font-semibold border-b pb-3">Thông tin cơ bản</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tên truyện <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $comic->title) }}" required
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-500 @enderror">
                    @error('title')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>



                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tác giả <span class="text-red-500">*</span></label>
                    <input type="text" name="author" value="{{ old('author', $comic->author) }}" required
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('author') border-red-500 @enderror">
                    @error('author')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Danh mục <span class="text-red-500">*</span></label>
                    <select name="category_id" required
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('category_id') border-red-500 @enderror">
                        <option value="">Chọn danh mục</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $comic->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nhà xuất bản <span class="text-red-500">*</span></label>
                    <select name="publisher_id" required
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('publisher_id') border-red-500 @enderror">
                        <option value="">Chọn NXB</option>
                        @foreach($publishers as $publisher)
                            <option value="{{ $publisher->id }}" {{ old('publisher_id', $comic->publisher_id) == $publisher->id ? 'selected' : '' }}>
                                {{ $publisher->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('publisher_id')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Năm xuất bản</label>
                    <input type="number" name="published_year" value="{{ old('published_year', $comic->published_year) }}" min="1900" max="{{ date('Y') }}"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('published_year') border-red-500 @enderror">
                    @error('published_year')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Loại ấn bản <span class="text-red-500">*</span></label>
                    <select name="edition_type" required
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="regular" {{ old('edition_type', $comic->edition_type) == 'regular' ? 'selected' : '' }}>Thường</option>
                        <option value="special" {{ old('edition_type', $comic->edition_type) == 'special' ? 'selected' : '' }}>Đặc biệt</option>
                        <option value="limited" {{ old('edition_type', $comic->edition_type) == 'limited' ? 'selected' : '' }}>Giới hạn</option>
                        <option value="collectors" {{ old('edition_type', $comic->edition_type) == 'collectors' ? 'selected' : '' }}>Sưu tầm</option>
                    </select>
                </div>



                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Bộ truyện</label>
                    <input type="text" name="series" value="{{ old('series', $comic->series) }}" placeholder="VD: One Piece"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tập số</label>
                    <input type="number" name="volume" value="{{ old('volume', $comic->volume) }}" min="1"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Mô tả</label>
                    <textarea name="description" rows="4"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('description', $comic->description) }}</textarea>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 space-y-6">
            <h3 class="text-lg font-semibold border-b pb-3">Giá & Tồn kho</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Giá bán (VNĐ) <span class="text-red-500">*</span></label>
                    <input type="number" name="price" value="{{ old('price', $comic->price) }}" required min="0"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('price') border-red-500 @enderror">
                    @error('price')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Số lượng tồn kho <span class="text-red-500">*</span></label>
                    <input type="number" name="stock" value="{{ old('stock', $comic->stock) }}" required min="0"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('stock') border-red-500 @enderror">
                    @error('stock')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 space-y-6">
            <h3 class="text-lg font-semibold border-b pb-3">Ảnh bìa</h3>

            @if($comic->cover)
                <div class="mb-4">
                    <p class="text-sm text-gray-600 mb-2">Ảnh hiện tại:</p>
                    <img src="{{ $comic->cover }}" alt="{{ $comic->title }}" class="w-32 h-auto rounded-lg shadow">
                </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Chọn ảnh mới (để trống nếu không đổi)</label>
                <input type="file" name="cover" accept="image/*"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('cover') border-red-500 @enderror">
                <p class="mt-1 text-sm text-gray-500">Chấp nhận: JPG, PNG, GIF. Tối đa 2MB.</p>
                @error('cover')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <label class="flex items-center">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $comic->is_active) ? 'checked' : '' }}
                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                <span class="ml-2 text-sm text-gray-700">Hiển thị trên trang web (đang bán)</span>
            </label>
        </div>

        <div class="flex items-center justify-end gap-4">
            <a href="{{ route('admin.comics.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                Hủy
            </a>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                Cập nhật
            </button>
        </div>
    </form>
</div>
@endsection
