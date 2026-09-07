<nav x-data="{ open: false }" class="sticky top-0 z-50 glass border-b-0">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <div class="flex items-center">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                        <div class="w-10 h-10 bg-gradient-to-br from-primary-500 to-primary-700 rounded-xl flex items-center justify-center text-white font-black text-xl shadow-lg shadow-primary-500/30 group-hover:scale-105 transition-transform">
                            CS
                        </div>
                        <span class="font-extrabold text-2xl tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-slate-800 to-slate-600">
                            Comic Store
                        </span>
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-12 sm:flex">
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')" class="font-semibold text-slate-600 hover:text-primary-600 transition-colors py-8 border-b-2 border-transparent hover:border-primary-500 {{ request()->routeIs('home') ? 'text-primary-600 border-primary-500' : '' }}">
                        {{ __('Trang chủ') }}
                    </x-nav-link>

                    @auth
                        <x-nav-link :href="route('my-orders.index')" :active="request()->routeIs('my-orders.*')" class="font-semibold text-slate-600 hover:text-primary-600 transition-colors py-8 border-b-2 border-transparent hover:border-primary-500 {{ request()->routeIs('my-orders.*') ? 'text-primary-600 border-primary-500' : '' }}">
                            {{ __('Đơn hàng') }}
                        </x-nav-link>
                    @endauth
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-6">
                <!-- Cart Icon -->
                <a href="{{ route('cart.index') }}" class="relative group p-2 hover:bg-primary-50 rounded-full transition-colors" title="Giỏ hàng">
                    <svg class="h-6 w-6 text-slate-600 group-hover:text-primary-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    <span id="cart-count" class="absolute 0 top-0 right-0 transform translate-x-1 -translate-y-1 bg-rose-500 text-white text-[10px] font-bold rounded-full h-5 w-5 flex items-center justify-center shadow-md border-2 border-white" style="display: none;">0</span>
                </a>

                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-semibold rounded-xl text-slate-600 bg-white/50 hover:bg-white hover:shadow-sm focus:outline-none transition ease-in-out duration-150">
                                <div class="w-8 h-8 bg-gradient-to-tr from-primary-100 to-primary-200 rounded-full flex items-center justify-center mr-2 ring-2 ring-white">
                                    <span class="text-primary-700 font-bold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                </div>
                                <span class="hidden sm:inline">{{ Auth::user()->name }}</span>
                                <svg class="w-4 h-4 ml-1 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="px-4 py-3 text-sm text-slate-700 border-b border-slate-100 bg-slate-50 rounded-t-md">
                                <div class="font-medium truncate">{{ Auth::user()->email }}</div>
                            </div>
                            <x-dropdown-link :href="route('profile.edit')" class="font-medium hover:text-primary-600 hover:bg-primary-50">
                                {{ __('Tài khoản') }}
                            </x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault(); this.closest('form').submit();"
                                        class="font-medium text-rose-600 hover:text-rose-700 hover:bg-rose-50">
                                    {{ __('Đăng xuất') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <div class="ms-4 flex items-center space-x-3">
                        <a href="{{ route('login') }}" class="font-semibold text-slate-600 hover:text-primary-600 transition-colors px-3 py-2">Đăng nhập</a>
                        <a href="{{ route('register') }}" class="btn-primary">Đăng ký</a>
                    </div>
                @endauth
            </div>

            <!-- Mobile menu button -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-xl text-slate-400 hover:text-slate-500 hover:bg-slate-100 focus:outline-none focus:bg-slate-100 focus:text-slate-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div x-show="open" x-cloak class="hidden sm:hidden border-t border-slate-100 bg-white">
        <div class="pt-2 pb-3 space-y-1 px-2">
            <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')" class="rounded-xl font-medium">
                {{ __('Trang chủ') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('cart.index')" class="rounded-xl font-medium">
                {{ __('Giỏ hàng') }}
            </x-responsive-nav-link>
            @auth
                <x-responsive-nav-link :href="route('my-orders.index')" :active="request()->routeIs('my-orders.*')" class="rounded-xl font-medium">
                    {{ __('Đơn hàng') }}
                </x-responsive-nav-link>
            @endauth
        </div>

        @auth
            <div class="pt-4 pb-3 border-t border-slate-100 bg-slate-50">
                <div class="px-4 flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center">
                        <span class="text-primary-700 font-bold text-lg">{{ substr(Auth::user()->name, 0, 1) }}</span>
                    </div>
                    <div>
                        <div class="font-bold text-base text-slate-800">{{ Auth::user()->name }}</div>
                        <div class="font-medium text-sm text-slate-500">{{ Auth::user()->email }}</div>
                    </div>
                </div>

                <div class="mt-3 space-y-1 px-2">
                    <x-responsive-nav-link :href="route('profile.edit')" class="rounded-xl font-medium">
                        {{ __('Tài khoản') }}
                    </x-responsive-nav-link>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();"
                                class="rounded-xl font-medium text-rose-600">
                            {{ __('Đăng xuất') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        @else
            <div class="pt-4 pb-4 border-t border-slate-100 bg-slate-50">
                <div class="px-4 space-y-3">
                    <a href="{{ route('login') }}" class="block w-full text-center px-4 py-2 text-slate-600 font-semibold bg-white border border-slate-200 rounded-xl hover:bg-slate-50">Đăng nhập</a>
                    <a href="{{ route('register') }}" class="block w-full text-center btn-primary">Đăng ký</a>
                </div>
            </div>
        @endauth
    </div>
</nav>
