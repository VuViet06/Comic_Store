{{-- Hero Banner Component --}}
<div class="bg-gradient-to-r from-blue-600 via-purple-600 to-pink-500 text-white">
    <div class="container mx-auto px-4 py-12 md:py-16">
        <div class="flex flex-col md:flex-row items-center justify-between gap-8">
            {{-- Text Content --}}
            <div class="flex-1 text-center md:text-left">
                <h1 class="text-3xl md:text-5xl font-bold mb-4 leading-tight">
                    Khám phá thế giới 
                    <span class="text-yellow-300">Truyện tranh</span>
                </h1>
                <p class="text-lg md:text-xl text-white/90 mb-6 max-w-xl">
                    Hàng ngàn đầu truyện manga, comic, manhua chính hãng. 
                    Giao hàng nhanh, giá tốt nhất!
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center md:justify-start">
                    <a href="#catalog" class="bg-white text-purple-600 px-6 py-3 rounded-lg font-semibold hover:bg-yellow-300 hover:text-purple-700 transition-colors inline-flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        Khám phá ngay
                    </a>
                    <a href="{{ route('orders.track') }}" class="border-2 border-white text-white px-6 py-3 rounded-lg font-semibold hover:bg-white hover:text-purple-600 transition-colors inline-flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Tra cứu đơn hàng
                    </a>
                </div>
            </div>

            {{-- Stats/Features --}}
            <div class="flex-shrink-0 grid grid-cols-2 gap-4 text-center">
                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4">
                    <div class="text-3xl font-bold text-yellow-300">1000+</div>
                    <div class="text-sm text-white/80">Đầu truyện</div>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4">
                    <div class="text-3xl font-bold text-yellow-300">24h</div>
                    <div class="text-sm text-white/80">Giao hàng</div>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4">
                    <div class="text-3xl font-bold text-yellow-300">100%</div>
                    <div class="text-sm text-white/80">Chính hãng</div>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4">
                    <div class="text-3xl font-bold text-yellow-300">Free</div>
                    <div class="text-sm text-white/80">Ship 500k</div>
                </div>
            </div>
        </div>
    </div>
</div>
