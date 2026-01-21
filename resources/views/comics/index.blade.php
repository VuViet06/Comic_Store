{{-- Trang chủ - Comic Store --}}
@extends('layouts.app')

@section('title', 'Comic Store - Thế giới truyện tranh')

@section('content')

{{-- ==================== HERO BANNER ==================== --}}
<section class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500 text-white">
    <div class="container mx-auto px-4 py-16 md:py-20">
        <div class="flex flex-col lg:flex-row items-center justify-between gap-10">
            {{-- Text Content --}}
            <div class="flex-1 text-center lg:text-left">
                <span class="inline-block bg-yellow-400 text-purple-900 text-sm font-bold px-4 py-1 rounded-full mb-4">
                    🎉 Miễn phí ship đơn từ 500k
                </span>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6 leading-tight">
                    Khám phá thế giới 
                    <span class="text-yellow-300 block">Truyện tranh</span>
                </h1>
                <p class="text-lg md:text-xl text-white/90 mb-8 max-w-xl mx-auto lg:mx-0">
                    Hàng ngàn đầu truyện manga, comic Mỹ, manhua, manhwa và light novel chính hãng với giá tốt nhất!
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    <a href="#catalog" class="bg-white text-purple-600 px-8 py-4 rounded-xl font-bold hover:bg-yellow-300 hover:text-purple-700 transition-all duration-300 shadow-lg hover:shadow-xl inline-flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        Mua sắm ngay
                    </a>
                    <a href="{{ route('orders.track') }}" class="border-2 border-white text-white px-8 py-4 rounded-xl font-bold hover:bg-white hover:text-purple-600 transition-all duration-300 inline-flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Tra cứu đơn hàng
                    </a>
                </div>
            </div>

            {{-- Stats Cards --}}
            <div class="grid grid-cols-2 gap-4 lg:gap-6">
                <div class="bg-white/15 backdrop-blur-md rounded-2xl p-6 text-center hover:bg-white/25 transition-colors">
                    <div class="text-4xl font-bold text-yellow-300 mb-1">1000+</div>
                    <div class="text-white/80">Đầu truyện</div>
                </div>
                <div class="bg-white/15 backdrop-blur-md rounded-2xl p-6 text-center hover:bg-white/25 transition-colors">
                    <div class="text-4xl font-bold text-yellow-300 mb-1">24h</div>
                    <div class="text-white/80">Giao hàng</div>
                </div>
                <div class="bg-white/15 backdrop-blur-md rounded-2xl p-6 text-center hover:bg-white/25 transition-colors">
                    <div class="text-4xl font-bold text-yellow-300 mb-1">100%</div>
                    <div class="text-white/80">Chính hãng</div>
                </div>
                <div class="bg-white/15 backdrop-blur-md rounded-2xl p-6 text-center hover:bg-white/25 transition-colors">
                    <div class="text-4xl font-bold text-yellow-300 mb-1">⭐ 4.9</div>
                    <div class="text-white/80">Đánh giá</div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ==================== QUICK CATEGORIES ==================== --}}
<section class="bg-white py-6 shadow-sm">
    <div class="container mx-auto px-4">
        <div class="flex flex-wrap items-center justify-center gap-3">
            <a href="{{ route('home') }}" class="px-5 py-2 rounded-full font-medium transition-all {{ !request('category') ? 'bg-purple-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-purple-100' }}">
                Tất cả
            </a>
            @foreach($categories as $cat)
                <a href="{{ route('home', ['category' => $cat->id]) }}" 
                   class="px-5 py-2 rounded-full font-medium transition-all {{ request('category') == $cat->id ? 'bg-purple-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-purple-100' }}">
                    {{ $cat->name }}
                </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ==================== MAIN CONTENT ==================== --}}
<section id="catalog" class="bg-gray-50 py-10">
    <div class="container mx-auto px-4">
        <div class="flex flex-col lg:flex-row gap-8">
            
            {{-- ========== SIDEBAR FILTER ========== --}}
            <aside class="w-full lg:w-72 flex-shrink-0">
                <div class="bg-white rounded-2xl shadow-md p-6 sticky top-4">
                    <h2 class="text-xl font-bold mb-6 pb-3 border-b flex items-center">
                        <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                        Bộ lọc
                    </h2>
                    
                    <form method="GET" action="{{ route('home') }}" class="space-y-5">
                        {{-- Search --}}
                        <div>
                            <label for="search" class="form-label">Tìm kiếm</label>
                            <div class="relative">
                                <input type="text" name="search" id="search" value="{{ request('search') }}" 
                                       placeholder="Nhập tên truyện..." class="form-input pl-10">
                                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>

                        {{-- Publisher --}}
                        <div>
                            <label for="publisher" class="form-label">Nhà xuất bản</label>
                            <select name="publisher" id="publisher" class="form-input">
                                <option value="">Tất cả NXB</option>
                                @foreach($publishers as $publisher)
                                    <option value="{{ $publisher->id }}" {{ request('publisher') == $publisher->id ? 'selected' : '' }}>
                                        {{ $publisher->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Condition --}}
                        <div>
                            <label for="condition" class="form-label">Tình trạng</label>
                            <select name="condition" id="condition" class="form-input">
                                <option value="">Tất cả</option>
                                <option value="new" {{ request('condition') == 'new' ? 'selected' : '' }}>Mới 100%</option>
                                <option value="like_new" {{ request('condition') == 'like_new' ? 'selected' : '' }}>Như mới</option>
                                <option value="good" {{ request('condition') == 'good' ? 'selected' : '' }}>Tốt</option>
                                <option value="fair" {{ request('condition') == 'fair' ? 'selected' : '' }}>Khá</option>
                            </select>
                        </div>

                        {{-- Edition Type --}}
                        <div>
                            <label for="edition_type" class="form-label">Phiên bản</label>
                            <select name="edition_type" id="edition_type" class="form-input">
                                <option value="">Tất cả</option>
                                <option value="regular" {{ request('edition_type') == 'regular' ? 'selected' : '' }}>Thường</option>
                                <option value="special" {{ request('edition_type') == 'special' ? 'selected' : '' }}>Đặc biệt</option>
                                <option value="limited" {{ request('edition_type') == 'limited' ? 'selected' : '' }}>Giới hạn</option>
                                <option value="collectors" {{ request('edition_type') == 'collectors' ? 'selected' : '' }}>Sưu tầm</option>
                            </select>
                        </div>

                        {{-- In Stock --}}
                        <div class="flex items-center bg-gray-50 p-3 rounded-lg">
                            <input type="checkbox" name="in_stock" id="in_stock" value="1" 
                                   {{ request('in_stock') ? 'checked' : '' }} class="rounded text-purple-600 focus:ring-purple-500">
                            <label for="in_stock" class="ml-2 text-sm text-gray-700">Chỉ hiện còn hàng</label>
                        </div>

                        {{-- Buttons --}}
                        <div class="flex flex-col gap-3 pt-2">
                            <button type="submit" class="btn-primary w-full py-3">
                                <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                Áp dụng bộ lọc
                            </button>
                            <a href="{{ route('home') }}" class="btn-secondary w-full text-center py-3">
                                Xóa bộ lọc
                            </a>
                        </div>
                    </form>
                </div>
            </aside>

            {{-- ========== MAIN GRID ========== --}}
            <main class="flex-1">
                {{-- Sort Bar --}}
                <div class="bg-white rounded-2xl shadow-sm p-4 mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex items-center gap-2">
                        <span class="text-gray-600">Tìm thấy</span>
                        <span class="font-bold text-purple-600">{{ $comics->total() }}</span>
                        <span class="text-gray-600">truyện</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-sm text-gray-500">Sắp xếp:</span>
                        <select id="sort-select" class="form-input py-2 min-w-[160px]" onchange="updateSort(this.value)">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Mới nhất</option>
                            <option value="best_seller" {{ request('sort') == 'best_seller' ? 'selected' : '' }}>Bán chạy</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá thấp → cao</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá cao → thấp</option>
                            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Tên A-Z</option>
                        </select>
                    </div>
                </div>

                {{-- Products Grid --}}
                @if($comics->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                        @foreach($comics as $comic)
                            <div class="bg-white rounded-2xl shadow-sm overflow-hidden group hover:shadow-xl transition-all duration-300">
                                {{-- Image --}}
                                <div class="aspect-[3/4] bg-gray-100 overflow-hidden relative">
                                    @if($comic->cover)
                                        <img src="{{ $comic->cover }}" alt="{{ $comic->title }}" 
                                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-300">
                                            <svg class="w-20 h-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                            </svg>
                                        </div>
                                    @endif
                                    
                                    {{-- Badges --}}
                                    <div class="absolute top-3 left-3 flex flex-col gap-2">
                                        @if($comic->stock <= 0)
                                            <span class="bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-full">Hết hàng</span>
                                        @elseif($comic->stock <= 5)
                                            <span class="bg-orange-500 text-white text-xs font-bold px-3 py-1 rounded-full">Sắp hết</span>
                                        @endif
                                        @if($comic->condition == 'new')
                                            <span class="bg-green-500 text-white text-xs font-bold px-3 py-1 rounded-full">Mới</span>
                                        @endif
                                    </div>

                                    {{-- Quick View Button --}}
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                        <a href="{{ route('comics.show', $comic->slug) }}" class="bg-white text-purple-600 px-6 py-3 rounded-xl font-bold hover:bg-purple-600 hover:text-white transition-colors">
                                            Xem chi tiết
                                        </a>
                                    </div>
                                </div>
                                
                                {{-- Content --}}
                                <div class="p-5">
                                    @if($comic->category)
                                        <span class="text-xs text-purple-600 font-medium">{{ $comic->category->name }}</span>
                                    @endif
                                    <h3 class="font-bold text-lg mt-1 mb-2 line-clamp-2 min-h-[3.5rem] group-hover:text-purple-600 transition-colors">
                                        {{ $comic->title }}
                                    </h3>
                                    <p class="text-sm text-gray-500 mb-3">{{ $comic->publisher->name ?? 'N/A' }}</p>
                                    
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <span class="text-2xl font-bold text-red-500">{{ number_format($comic->price) }}</span>
                                            <span class="text-sm text-gray-500">VNĐ</span>
                                        </div>
                                        @if($comic->stock > 0)
                                            <span class="text-sm text-green-600 font-medium">Còn {{ $comic->stock }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-10">
                        {{ $comics->links() }}
                    </div>
                @else
                    {{-- Empty State --}}
                    <div class="text-center py-16 bg-white rounded-2xl shadow-sm">
                        <svg class="w-24 h-24 text-gray-300 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="text-2xl font-bold text-gray-700 mb-2">Không tìm thấy truyện nào</h3>
                        <p class="text-gray-500 mb-6">Thử thay đổi bộ lọc hoặc từ khóa tìm kiếm</p>
                        <a href="{{ route('home') }}" class="btn-primary inline-flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Xem tất cả truyện
                        </a>
                    </div>
                @endif
            </main>
        </div>
    </div>
</section>

{{-- ==================== FEATURES ==================== --}}
<section class="bg-white py-12">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="flex items-center p-6 bg-gradient-to-r from-purple-50 to-pink-50 rounded-2xl">
                <div class="bg-purple-100 p-4 rounded-xl mr-4">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                    </svg>
                </div>
                <div>
                    <h4 class="font-bold text-gray-800">Miễn phí ship</h4>
                    <p class="text-sm text-gray-500">Đơn từ 500.000đ</p>
                </div>
            </div>
            <div class="flex items-center p-6 bg-gradient-to-r from-blue-50 to-cyan-50 rounded-2xl">
                <div class="bg-blue-100 p-4 rounded-xl mr-4">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
                <div>
                    <h4 class="font-bold text-gray-800">100% Chính hãng</h4>
                    <p class="text-sm text-gray-500">Cam kết sách thật</p>
                </div>
            </div>
            <div class="flex items-center p-6 bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl">
                <div class="bg-green-100 p-4 rounded-xl mr-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                </div>
                <div>
                    <h4 class="font-bold text-gray-800">Đổi trả 7 ngày</h4>
                    <p class="text-sm text-gray-500">Nếu sản phẩm lỗi</p>
                </div>
            </div>
            <div class="flex items-center p-6 bg-gradient-to-r from-orange-50 to-amber-50 rounded-2xl">
                <div class="bg-orange-100 p-4 rounded-xl mr-4">
                    <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <div>
                    <h4 class="font-bold text-gray-800">Hỗ trợ 24/7</h4>
                    <p class="text-sm text-gray-500">Hotline: 0123.456.789</p>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
function updateSort(value) {
    const url = new URL(window.location.href);
    url.searchParams.set('sort', value);
    window.location.href = url.toString();
}
</script>
@endpush

@endsection
