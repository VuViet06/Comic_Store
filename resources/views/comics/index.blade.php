@extends('layouts.app')

@section('title', 'Comic Store - Thế giới truyện tranh')

@section('content')

    {{-- Hero Section --}}
    <section class="relative overflow-hidden bg-slate-900 pt-20 pb-24 lg:pt-32 lg:pb-36">
        <!-- Background Elements -->
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[1000px] h-[500px] bg-primary-600/30 rounded-full blur-[120px] pointer-events-none"></div>
        <div class="absolute bottom-0 right-0 w-[600px] h-[600px] bg-accent/20 rounded-full blur-[100px] pointer-events-none translate-x-1/3 translate-y-1/3"></div>
        <div class="absolute top-1/4 left-10 w-24 h-24 bg-rose-500/20 rounded-full blur-2xl pointer-events-none"></div>
        
        <div class="container mx-auto px-4 relative z-10">
            <div class="flex flex-col lg:flex-row items-center gap-16 lg:gap-8">
                <!-- Text Content -->
                <div class="flex-1 text-center lg:text-left max-w-2xl mx-auto lg:mx-0">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/5 border border-white/10 text-primary-300 font-medium text-sm mb-8 backdrop-blur-sm">
                        <span class="w-2 h-2 rounded-full bg-accent animate-pulse"></span>
                        Cập nhật hàng ngàn đầu truyện mới
                    </div>
                    <h1 class="text-5xl md:text-6xl lg:text-7xl font-extrabold text-white mb-6 leading-[1.1] tracking-tight">
                        Thế giới <br />
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-accent to-rose-400">Truyện tranh</span> <br />
                        trong tầm tay
                    </h1>
                    <p class="text-lg md:text-xl text-slate-300 mb-10 max-w-xl mx-auto lg:mx-0 leading-relaxed">
                        Manga, Manhua, Manhwa và Light Novel chính hãng. Khám phá các bộ sưu tập độc quyền chỉ có tại Comic Store.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="#catalog" class="px-8 py-4 rounded-xl font-bold bg-white text-slate-900 hover:bg-accent hover:text-white transition-all duration-300 shadow-[0_0_20px_rgba(255,255,255,0.3)] hover:shadow-[0_0_30px_rgba(251,191,36,0.5)] hover:-translate-y-1 inline-flex items-center justify-center gap-2 group">
                            <svg class="w-5 h-5 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            Mua sắm ngay
                        </a>
                        <a href="{{ route('orders.track') }}" class="px-8 py-4 rounded-xl font-bold border-2 border-white/20 text-white hover:bg-white/10 transition-all duration-300 inline-flex items-center justify-center gap-2 backdrop-blur-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            Tra cứu đơn hàng
                        </a>
                    </div>
                </div>

                <!-- Floating Stats Cards -->
                <div class="flex-1 w-full max-w-lg mx-auto relative h-[400px]">
                    <div class="absolute top-0 right-0 w-48 bg-white/10 backdrop-blur-xl border border-white/20 rounded-3xl p-6 text-center transform rotate-6 hover:rotate-0 transition-all duration-500 hover:-translate-y-2 shadow-2xl z-20">
                        <div class="text-4xl font-black text-accent mb-1 drop-shadow-md">1000+</div>
                        <div class="text-slate-200 font-medium">Đầu truyện</div>
                    </div>
                    <div class="absolute bottom-10 left-0 w-48 bg-white/10 backdrop-blur-xl border border-white/20 rounded-3xl p-6 text-center transform -rotate-6 hover:rotate-0 transition-all duration-500 hover:-translate-y-2 shadow-2xl z-20">
                        <div class="text-4xl font-black text-emerald-400 mb-1 drop-shadow-md">24h</div>
                        <div class="text-slate-200 font-medium">Giao hàng tốc hành</div>
                    </div>
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-56 bg-gradient-to-br from-primary-600/80 to-primary-800/80 backdrop-blur-xl border border-white/20 rounded-3xl p-8 text-center shadow-glow z-30 transform hover:scale-105 transition-all duration-500">
                        <div class="flex justify-center mb-3">
                            <svg class="w-10 h-10 text-accent" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        </div>
                        <div class="text-5xl font-black text-white mb-2 drop-shadow-md">4.9</div>
                        <div class="text-primary-100 font-medium">Đánh giá trung bình</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Categories Nav --}}
    <section class="bg-white border-b border-slate-100 sticky top-16 z-30 shadow-sm">
        <div class="max-w-[1400px] mx-auto px-4">
            <div id="category-nav" class="flex flex-nowrap overflow-x-auto py-3 hide-scrollbar gap-2 items-center md:justify-center">
                <button type="button" data-category=""
                    class="category-btn flex-shrink-0 px-4 py-1.5 rounded-full text-sm font-semibold transition-all duration-200 {{ !request('category') ? 'bg-primary-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-primary-50 hover:text-primary-600' }}">
                    Tất cả
                </button>
                @foreach ($categories as $cat)
                    <button type="button" data-category="{{ $cat->id }}"
                        class="category-btn flex-shrink-0 px-4 py-1.5 rounded-full text-sm font-semibold transition-all duration-200 {{ request('category') == $cat->id ? 'bg-primary-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-primary-50 hover:text-primary-600' }}">
                        {{ $cat->name }}
                    </button>
                @endforeach
            </div>
        </div>
    </section>

    <style>
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>

    {{-- Catalog Section --}}
    <section id="catalog" class="py-8" x-data="{ filterOpen: false }">
        <div class="max-w-[1400px] mx-auto px-4">

            {{-- Toolbar: Filter toggle + Search + Sort --}}
            <div class="flex flex-wrap items-center gap-3 mb-6">
                {{-- Filter Toggle Button --}}
                <button @click="filterOpen = !filterOpen"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 border"
                    :class="filterOpen ? 'bg-primary-600 text-white border-primary-600 shadow-md shadow-primary-500/20' : 'bg-white text-slate-600 border-slate-200 hover:border-primary-300 hover:text-primary-600'">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                    <span>Bộ lọc</span>
                    @if(request('publisher') || request('price') || request('edition_type') || request('in_stock'))
                        <span class="w-2 h-2 rounded-full bg-accent animate-pulse"></span>
                    @endif
                </button>

                {{-- Search --}}
                <form id="search-form" method="GET" action="{{ route('home') }}" class="flex-1 min-w-[200px] max-w-md">
                    @foreach (['publisher', 'edition_type', 'in_stock', 'category', 'sort', 'price'] as $param)
                        @if (request($param))
                            <input type="hidden" name="{{ $param }}" value="{{ request($param) }}">
                        @endif
                    @endforeach
                    <div class="relative">
                        <input type="text" name="search" id="search" value="{{ request('search') }}"
                            placeholder="Tìm truyện, tác giả..."
                            class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-primary-500/40 focus:border-primary-500 transition-all placeholder:text-slate-400">
                        <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </form>

                {{-- Sort --}}
                <div class="hidden sm:flex items-center gap-2 ml-auto">
                    <select id="sort-select" class="py-2.5 pl-4 pr-9 bg-white border border-slate-200 rounded-xl text-sm font-medium text-slate-600 focus:outline-none focus:ring-2 focus:ring-primary-500/40 focus:border-primary-500 cursor-pointer appearance-none" onchange="updateSort(this.value)" style="background-image: url('data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 20 20%22 fill=%22%2394a3b8%22><path fill-rule=%22evenodd%22 d=%22M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z%22 clip-rule=%22evenodd%22/></svg>'); background-position: right 0.75rem center; background-repeat: no-repeat; background-size: 1rem;">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Mới nhất</option>
                        <option value="best_seller" {{ request('sort') == 'best_seller' ? 'selected' : '' }}>Bán chạy</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá thấp → cao</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá cao → thấp</option>
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Tên A-Z</option>
                    </select>
                </div>
            </div>

            <div class="flex gap-6 relative items-start">
                {{-- Filter Sidebar — Slide Panel --}}
                <aside
                    x-show="filterOpen"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="-translate-x-4 opacity-0"
                    x-transition:enter-end="translate-x-0 opacity-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="translate-x-0 opacity-100"
                    x-transition:leave-end="-translate-x-4 opacity-0"
                    x-cloak
                    class="w-60 flex-shrink-0 relative z-20">
                    <div class="bg-white rounded-2xl border border-slate-100 p-5 sticky top-32 shadow-sm">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-sm font-bold text-slate-800 flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                                </svg>
                                Bộ lọc
                            </h2>
                            <button @click="filterOpen = false" class="p-1 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>

                        <form id="filters-form" method="GET" action="{{ route('home') }}" class="space-y-4">
                            <input type="hidden" name="category" id="hidden-category" value="{{ request('category') }}">

                            {{-- Nhà xuất bản --}}
                            <div x-data="{ open: true }">
                                <button type="button" @click="open = !open" class="flex items-center justify-between w-full text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-2 hover:text-slate-600 transition-colors">
                                    <span>Nhà xuất bản</span>
                                    <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                <div x-show="open" x-collapse class="space-y-0.5 max-h-40 overflow-y-auto pr-1">
                                    @foreach ($publishers as $publisher)
                                        <label class="flex items-center gap-2 py-1.5 px-2 rounded-lg text-[13px] cursor-pointer hover:bg-slate-50 transition-colors">
                                            <input type="radio" name="publisher" value="{{ $publisher->id }}"
                                                class="w-3.5 h-3.5 text-primary-600 border-slate-300 focus:ring-primary-500 focus:ring-offset-0 filter-radio"
                                                {{ request('publisher') == $publisher->id ? 'checked' : '' }}>
                                            <span class="text-slate-600 truncate">{{ $publisher->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Giá --}}
                            <div x-data="{ open: true }">
                                <button type="button" @click="open = !open" class="flex items-center justify-between w-full text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-2 hover:text-slate-600 transition-colors">
                                    <span>Khoảng giá</span>
                                    <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                <div x-show="open" x-collapse class="space-y-0.5">
                                    @php
                                        $priceRanges = [
                                            '0-150000' => 'Dưới 150K',
                                            '150000-300000' => '150K – 300K',
                                            '300000-500000' => '300K – 500K',
                                            '500000-700000' => '500K – 700K',
                                            '700000+' => 'Trên 700K'
                                        ];
                                    @endphp
                                    @foreach($priceRanges as $val => $label)
                                    <label class="flex items-center gap-2 py-1.5 px-2 rounded-lg text-[13px] cursor-pointer hover:bg-slate-50 transition-colors">
                                        <input type="radio" name="price" value="{{ $val }}"
                                            class="w-3.5 h-3.5 text-primary-600 border-slate-300 focus:ring-primary-500 focus:ring-offset-0 filter-radio"
                                            {{ request('price') === $val ? 'checked' : '' }}>
                                        <span class="text-slate-600">{{ $label }}</span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Phiên bản --}}
                            <div x-data="{ open: true }">
                                <button type="button" @click="open = !open" class="flex items-center justify-between w-full text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-2 hover:text-slate-600 transition-colors">
                                    <span>Phiên bản</span>
                                    <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                <div x-show="open" x-collapse class="space-y-0.5">
                                    @php
                                        $editions = [
                                            'regular' => 'Thường',
                                            'special' => 'Đặc biệt',
                                            'limited' => 'Giới hạn',
                                            'collectors' => 'Sưu tầm'
                                        ];
                                    @endphp
                                    @foreach($editions as $val => $label)
                                    <label class="flex items-center gap-2 py-1.5 px-2 rounded-lg text-[13px] cursor-pointer hover:bg-slate-50 transition-colors">
                                        <input type="radio" name="edition_type" value="{{ $val }}"
                                            class="w-3.5 h-3.5 text-primary-600 border-slate-300 focus:ring-primary-500 focus:ring-offset-0 filter-radio"
                                            {{ request('edition_type') == $val ? 'checked' : '' }}>
                                        <span class="text-slate-600">{{ $label }}</span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>

                            <div class="pt-3 border-t border-slate-100">
                                <label class="flex items-center gap-2 p-2 bg-primary-50/60 rounded-lg cursor-pointer hover:bg-primary-50 transition-colors">
                                    <input type="checkbox" name="in_stock" value="1"
                                        {{ request('in_stock') ? 'checked' : '' }}
                                        class="w-4 h-4 text-primary-600 border-primary-300 rounded focus:ring-primary-500 focus:ring-offset-0 filter-auto-submit">
                                    <span class="text-[13px] font-semibold text-primary-700">Chỉ hiện còn hàng</span>
                                </label>
                            </div>
                        </form>
                    </div>
                </aside>

                {{-- Product Grid --}}
                <main class="flex-1 min-w-0 transition-all duration-300" :class="filterOpen ? '' : 'w-full'">
                    <div id="comics-list">
                        @include('comics._list')
                    </div>
                </main>
            </div>
        </div>
    </section>

    @push('scripts')
        <script>
            function buildUrlWithParams(baseUrl, params) {
                const url = new URL(baseUrl, window.location.origin);
                if (params instanceof FormData) {
                    params.forEach((value, key) => {
                        url.searchParams.append(key, value);
                    });
                } else {
                    Object.keys(params).forEach(key => {
                        if (params[key] !== null && params[key] !== undefined && params[key] !== '') {
                            url.searchParams.set(key, params[key]);
                        } else {
                            url.searchParams.delete(key);
                        }
                    });
                }
                return url;
            }

            async function loadComics(url) {
                const listContainer = document.getElementById('comics-list');
                if (!listContainer) return;

                listContainer.classList.add('opacity-50', 'pointer-events-none');

                try {
                    const response = await fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'text/html',
                        },
                    });

                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }

                    const html = await response.text();
                    listContainer.innerHTML = html;
                    history.pushState({}, '', url);
                } catch (e) {
                    console.error(e);
                    window.location.href = url;
                } finally {
                    listContainer.classList.remove('opacity-50', 'pointer-events-none');
                }
            }

            function updateSort(value) {
                const currentUrl = new URL(window.location.href);
                currentUrl.searchParams.set('sort', value);
                loadComics(currentUrl.toString());
            }

            document.addEventListener('DOMContentLoaded', function() {
                const filterForm = document.getElementById('filters-form');
                const searchForm = document.getElementById('search-form');

                // Category Nav: AJAX
                const categoryBtns = document.querySelectorAll('.category-btn');
                categoryBtns.forEach(btn => {
                    btn.addEventListener('click', function() {
                        // Update active state
                        categoryBtns.forEach(b => {
                            b.classList.remove('bg-primary-600', 'text-white', 'shadow-sm');
                            b.classList.add('bg-slate-100', 'text-slate-600', 'hover:bg-primary-50', 'hover:text-primary-600');
                        });
                        this.classList.remove('bg-slate-100', 'text-slate-600', 'hover:bg-primary-50', 'hover:text-primary-600');
                        this.classList.add('bg-primary-600', 'text-white', 'shadow-sm');

                        // Set hidden input value
                        const hiddenInput = document.getElementById('hidden-category');
                        if (hiddenInput && filterForm) {
                            hiddenInput.value = this.getAttribute('data-category');
                            const url = buildUrlWithParams(filterForm.action, new FormData(filterForm));
                            loadComics(url.toString());
                        }
                    });
                });

                // Bộ lọc: auto apply bằng AJAX
                if (filterForm) {
                    // Checkbox auto submit
                    filterForm.querySelectorAll('.filter-auto-submit').forEach(function(input) {
                        input.addEventListener('change', function() {
                            const url = buildUrlWithParams(filterForm.action, new FormData(filterForm));
                            loadComics(url.toString());
                        });
                    });

                    // Radio có thể bấm lần 2 để bỏ chọn + auto apply
                    const radios = filterForm.querySelectorAll('.filter-radio');
                    const lastCheckedByName = {};

                    radios.forEach(function(radio) {
                        if (radio.checked) {
                            lastCheckedByName[radio.name] = radio;
                        }

                        radio.addEventListener('click', function() {
                            const name = radio.name;
                            if (lastCheckedByName[name] === radio) {
                                // Bấm lại cùng radio -> bỏ chọn
                                radio.checked = false;
                                lastCheckedByName[name] = null;
                            } else {
                                // Chọn radio khác trong cùng nhóm
                                lastCheckedByName[name] = radio;
                            }

                            const url = buildUrlWithParams(filterForm.action, new FormData(filterForm));
                            loadComics(url.toString());
                        });
                    });
                }

                // Tìm kiếm: submit bằng AJAX
                if (searchForm) {
                    searchForm.addEventListener('submit', function(e) {
                        e.preventDefault();
                        const url = buildUrlWithParams(searchForm.action, new FormData(searchForm));
                        loadComics(url.toString());
                    });
                }

                // Phân trang: dùng delegation trên comics-list
                const listContainer = document.getElementById('comics-list');
                if (listContainer) {
                    listContainer.addEventListener('click', function(e) {
                        const target = e.target.closest('a');
                        if (!target) return;
                        if (!target.closest('.pagination') && !target.href.includes('page=')) return;

                        e.preventDefault();
                        loadComics(target.href);
                    });
                }

                // Cho phép back/forward của trình duyệt
                window.addEventListener('popstate', function() {
                    const url = window.location.href;
                    loadComics(url);
                });
            });
        </script>
    @endpush

@endsection
