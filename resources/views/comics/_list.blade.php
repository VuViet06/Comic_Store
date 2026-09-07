@if ($comics->count() > 0)
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3 md:gap-4">

        @foreach ($comics as $comic)
            <a href="{{ route('comics.show', $comic->slug) }}" class="group block">
                <div class="bg-white rounded-xl border border-slate-100 overflow-hidden transition-all duration-300 hover:shadow-xl hover:shadow-primary-500/10 hover:-translate-y-1 hover:border-primary-200 h-full flex flex-col relative">

                    {{-- Cover --}}
                    <div class="aspect-[2/3] overflow-hidden relative">
                        @if ($comic->cover)
                            <img src="{{ $comic->cover }}" alt="{{ $comic->title }}"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 ease-out"
                                loading="lazy">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-primary-100 via-violet-50 to-primary-50 flex flex-col items-center justify-center">
                                <div class="w-12 h-12 rounded-xl bg-white/70 shadow-sm flex items-center justify-center mb-2 backdrop-blur-sm">
                                    <svg class="w-6 h-6 text-primary-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                </div>
                                <span class="text-[10px] font-semibold text-primary-300 tracking-wide uppercase">No cover</span>
                            </div>
                        @endif

                        {{-- Badge --}}
                        @if ($comic->stock <= 0)
                            <div class="absolute top-2 left-2 z-10">
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-rose-500/90 text-white backdrop-blur-sm shadow">Hết hàng</span>
                            </div>
                        @elseif($comic->stock <= 5)
                            <div class="absolute top-2 left-2 z-10">
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-amber-500/90 text-white backdrop-blur-sm shadow">Sắp hết</span>
                            </div>
                        @endif

                        {{-- Quick hover overlay --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-slate-900/10 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-300 flex items-end justify-center pb-4">
                            <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-bold bg-white/95 text-primary-700 shadow-lg backdrop-blur-sm translate-y-2 group-hover:translate-y-0 transition-all duration-300 scale-95 group-hover:scale-100">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                Chi tiết
                            </span>
                        </div>
                    </div>

                    {{-- Info --}}
                    <div class="p-3 flex flex-col flex-1">
                        @if ($comic->category)
                            <span class="text-[10px] font-bold text-primary-500 uppercase tracking-wider mb-1 block truncate">{{ $comic->category->name }}</span>
                        @endif

                        <h3 class="font-semibold text-slate-800 text-sm leading-tight mb-auto line-clamp-2 group-hover:text-primary-600 transition-colors duration-200 min-h-[2.5rem]">
                            {{ $comic->title }}
                        </h3>

                        <div class="flex items-center justify-between mt-2 pt-2 border-t border-slate-50">
                            <div class="flex items-baseline gap-px">
                                <span class="text-[15px] font-extrabold text-rose-500 tabular-nums">{{ number_format($comic->price) }}</span>
                                <span class="text-[10px] font-bold text-rose-400">đ</span>
                            </div>
                            @if ($comic->stock > 0 && $comic->stock <= 10)
                                <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded">{{ $comic->stock }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </a>
        @endforeach
    </div>

    <div class="mt-10 flex justify-center">
        {{ $comics->links() }}
    </div>
@else
    <div class="text-center py-20 bg-white rounded-2xl border border-slate-100">
        <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mb-4 mx-auto">
            <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <h3 class="text-lg font-bold text-slate-800 mb-1.5">Không tìm thấy kết quả</h3>
        <p class="text-slate-500 mb-5 text-sm max-w-xs mx-auto">Thử thay đổi bộ lọc hoặc từ khóa tìm kiếm.</p>
        <a href="{{ route('home') }}" class="btn-primary inline-flex items-center gap-1.5 text-sm py-2 px-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
            Xem tất cả
        </a>
    </div>
@endif
