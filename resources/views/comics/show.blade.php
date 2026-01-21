{{-- Trang chi tiết truyện --}}
@extends('layouts.app')

@section('title', $comic->title)

@section('content')
<div class="container mx-auto px-4 py-8">
    {{-- Breadcrumb --}}
    <nav class="mb-6 text-sm">
        <ol class="flex items-center space-x-2 text-gray-600">
            <li><a href="{{ route('home') }}" class="hover:text-blue-600">Trang chủ</a></li>
            <li>/</li>
            <li><a href="{{ route('home') }}" class="hover:text-blue-600">Truyện</a></li>
            <li>/</li>
            <li class="text-gray-900">{{ $comic->title }}</li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
        {{-- Ảnh bìa --}}
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="aspect-[3/4] bg-gray-100 flex items-center justify-center">
                @if($comic->cover)
                    <img src="{{ $comic->cover }}" alt="{{ $comic->title }}" 
                         class="w-full h-full object-cover">
                @else
                    <svg class="w-32 h-32 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                @endif
            </div>
        </div>

        {{-- Thông tin truyện --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <h1 class="text-3xl font-bold mb-4">{{ $comic->title }}</h1>
            
            <div class="space-y-3 mb-6">
                <div class="flex items-center">
                    <span class="text-gray-600 w-32">Nhà xuất bản:</span>
                    <span class="font-medium">{{ $comic->publisher->name ?? 'N/A' }}</span>
                </div>
                @if($comic->published_year)
                    <div class="flex items-center">
                        <span class="text-gray-600 w-32">Năm xuất bản:</span>
                        <span class="font-medium">{{ $comic->published_year }}</span>
                    </div>
                @endif
                <div class="flex items-center">
                    <span class="text-gray-600 w-32">Phiên bản:</span>
                    <span class="font-medium">{{ $comic->edition_type }}</span>
                </div>
                <div class="flex items-center">
                    <span class="text-gray-600 w-32">Tình trạng:</span>
                    <span class="font-medium">{{ $comic->condition }}</span>
                </div>
                @if($comic->series)
                    <div class="flex items-center">
                        <span class="text-gray-600 w-32">Bộ truyện:</span>
                        <span class="font-medium">{{ $comic->series }} @if($comic->volume)- Tập {{ $comic->volume }}@endif</span>
                    </div>
                @endif
                @if($comic->category)
                    <div class="flex items-center">
                        <span class="text-gray-600 w-32">Danh mục:</span>
                        <span class="badge badge-info">{{ $comic->category->name }}</span>
                    </div>
                @endif
                <div class="flex items-center">
                    <span class="text-gray-600 w-32">Tồn kho:</span>
                    <span class="font-medium {{ $comic->stock > 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ $comic->stock > 0 ? $comic->stock . ' quyển' : 'Hết hàng' }}
                    </span>
                </div>
            </div>

            <div class="border-t border-b py-6 my-6">
                <div class="text-3xl font-bold text-red-600">
                    {{ number_format($comic->price) }} VNĐ
                </div>
            </div>

            {{-- Form thêm vào giỏ hàng --}}
            @if($comic->stock > 0 && $comic->is_active)
                <form id="add-to-cart-form" action="{{ route('cart.add') }}" method="POST" class="mb-6">
                    @csrf
                    <input type="hidden" name="comic_id" value="{{ $comic->id }}">
                    <div class="flex gap-4 items-center">
                        <div>
                            <label for="quantity" class="form-label mb-1">Số lượng</label>
                            <input type="number" name="quantity" id="quantity" value="1" 
                                   min="1" max="{{ $comic->stock }}" 
                                   class="form-input w-24 text-center">
                        </div>
                        <div class="flex-1">
                            <label class="form-label mb-1 opacity-0">Button</label>
                            <button type="submit" id="add-to-cart-btn" class="btn-primary w-full">
                                Thêm vào giỏ hàng
                            </button>
                        </div>
                    </div>
                </form>
            @else
                <div class="alert alert-warning">
                    <p class="font-semibold">Sản phẩm hiện không có sẵn</p>
                </div>
            @endif

            {{-- Mô tả --}}
            @if($comic->description)
                <div class="mt-8 pt-8 border-t">
                    <h2 class="text-xl font-bold mb-4">Mô tả</h2>
                    <div class="prose max-w-none text-gray-700">
                        <p class="whitespace-pre-line">{{ $comic->description }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Truyện liên quan --}}
    @if($relatedComics->count() > 0)
        <div class="mt-12">
            <h2 class="text-2xl font-bold mb-6">Truyện liên quan</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($relatedComics as $related)
                    <div class="card">
                        <a href="{{ route('comics.show', $related->slug) }}" class="block">
                            <div class="aspect-[3/4] bg-gray-200 overflow-hidden">
                                @if($related->cover)
                                    <img src="{{ $related->cover }}" alt="{{ $related->title }}" 
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                @endif
                            </div>
                            <div class="p-4">
                                <h3 class="font-semibold mb-2 line-clamp-2">{{ $related->title }}</h3>
                                <p class="text-lg font-bold text-red-600">{{ number_format($related->price) }} VNĐ</p>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
document.getElementById('add-to-cart-form')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const form = this;
    const button = document.getElementById('add-to-cart-btn');
    const formData = new FormData(form);
    
    try {
        setLoading(button, true);
        
        const response = await fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            showToast(data.message || 'Đã thêm vào giỏ hàng', 'success');
            updateCartCount();
        } else {
            showToast(data.message || 'Có lỗi xảy ra', 'error');
        }
    } catch (error) {
        showToast('Có lỗi xảy ra. Vui lòng thử lại.', 'error');
    } finally {
        setLoading(button, false);
    }
});
</script>
@endpush
@endsection
