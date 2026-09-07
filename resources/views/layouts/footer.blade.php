{{-- Footer Component --}}
<footer class="bg-primary-950 text-slate-300 mt-20 relative overflow-hidden">
    <!-- Decorative background element -->
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-4xl h-px bg-gradient-to-r from-transparent via-primary-500/50 to-transparent"></div>
    <div class="absolute -top-24 -right-24 w-96 h-96 bg-primary-600/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="container mx-auto px-4 py-16 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 lg:gap-8">
            {{-- About --}}
            <div class="space-y-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-primary-400 to-primary-600 rounded-xl flex items-center justify-center text-white font-black text-xl shadow-lg shadow-primary-500/20">
                        CS
                    </div>
                    <h3 class="text-2xl font-extrabold text-white tracking-tight">Comic Store</h3>
                </div>
                <p class="text-slate-400 text-sm leading-relaxed">
                    Khám phá thế giới truyện tranh bản quyền. Manga, comic Mỹ, manhua và light novel với chất lượng xuất bản tốt nhất.
                </p>
                <div class="flex space-x-4 pt-2">
                    <a href="#" class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-slate-400 hover:bg-primary-500 hover:text-white transition-all duration-300">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-slate-400 hover:bg-primary-500 hover:text-white transition-all duration-300">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-slate-400 hover:bg-primary-500 hover:text-white transition-all duration-300">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/></svg>
                    </a>
                </div>
            </div>

            {{-- Quick Links --}}
            <div>
                <h3 class="text-lg font-bold text-white mb-6">Liên kết nhanh</h3>
                <ul class="space-y-3">
                    <li><a href="{{ route('home') }}" class="text-slate-400 hover:text-primary-400 transition-colors flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-primary-500/50"></span> Trang chủ</a></li>
                    <li><a href="{{ route('cart.index') }}" class="text-slate-400 hover:text-primary-400 transition-colors flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-primary-500/50"></span> Giỏ hàng</a></li>
                    <li><a href="{{ route('orders.track') }}" class="text-slate-400 hover:text-primary-400 transition-colors flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-primary-500/50"></span> Tra cứu đơn hàng</a></li>
                    @auth
                        <li><a href="{{ route('my-orders.index') }}" class="text-slate-400 hover:text-primary-400 transition-colors flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-primary-500/50"></span> Đơn hàng của tôi</a></li>
                        <li><a href="{{ route('profile.edit') }}" class="text-slate-400 hover:text-primary-400 transition-colors flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-primary-500/50"></span> Tài khoản</a></li>
                    @else
                        <li><a href="{{ route('login') }}" class="text-slate-400 hover:text-primary-400 transition-colors flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-primary-500/50"></span> Đăng nhập</a></li>
                        <li><a href="{{ route('register') }}" class="text-slate-400 hover:text-primary-400 transition-colors flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-primary-500/50"></span> Đăng ký</a></li>
                    @endauth
                </ul>
            </div>

            {{-- Policies --}}
            <div>
                <h3 class="text-lg font-bold text-white mb-6">Chính sách</h3>
                <ul class="space-y-3">
                    <li><a href="#" class="text-slate-400 hover:text-primary-400 transition-colors flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-primary-500/50"></span> Chính sách bảo mật</a></li>
                    <li><a href="#" class="text-slate-400 hover:text-primary-400 transition-colors flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-primary-500/50"></span> Điều khoản sử dụng</a></li>
                    <li><a href="#" class="text-slate-400 hover:text-primary-400 transition-colors flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-primary-500/50"></span> Chính sách vận chuyển</a></li>
                    <li><a href="#" class="text-slate-400 hover:text-primary-400 transition-colors flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-primary-500/50"></span> Chính sách đổi trả</a></li>
                    <li><a href="#" class="text-slate-400 hover:text-primary-400 transition-colors flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-primary-500/50"></span> Hướng dẫn mua hàng</a></li>
                </ul>
            </div>

            {{-- Contact --}}
            <div>
                <h3 class="text-lg font-bold text-white mb-6">Liên hệ</h3>
                <ul class="space-y-4">
                    <li class="flex items-start gap-3">
                        <div class="mt-1 w-8 h-8 rounded-full bg-white/5 flex items-center justify-center flex-shrink-0 text-primary-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <span class="text-sm text-slate-400 leading-relaxed">123 Đường ABC, Quận XYZ, TP.HCM</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center flex-shrink-0 text-primary-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        </div>
                        <span class="text-sm text-slate-400">0123 456 789</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center flex-shrink-0 text-primary-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <span class="text-sm text-slate-400">contact@comicstore.vn</span>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Bottom Footer --}}
        <div class="border-t border-slate-800/50 mt-16 pt-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div class="text-sm text-slate-500 font-medium">
                    © {{ date('Y') }} Comic Store. All rights reserved.
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-xs text-slate-500 mr-2">Thanh toán an toàn:</span>
                    <span class="bg-white/10 text-slate-300 px-3 py-1.5 rounded text-xs font-bold tracking-wider">COD</span>
                    <span class="bg-blue-600/20 text-blue-400 border border-blue-600/30 px-3 py-1.5 rounded text-xs font-bold tracking-wider">VNPAY</span>
                    <span class="bg-pink-600/20 text-pink-400 border border-pink-600/30 px-3 py-1.5 rounded text-xs font-bold tracking-wider">MOMO</span>
                </div>
            </div>
        </div>
    </div>
</footer>
