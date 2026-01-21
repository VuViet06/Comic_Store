<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', 'Admin Panel') - {{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        @stack('scripts')
    </head>
    <body class="font-sans antialiased bg-gray-100">
        <div class="min-h-screen flex">
            <!-- Sidebar -->
            <aside class="w-64 bg-gray-800 text-white min-h-screen">
                <div class="p-4">
                    <h1 class="text-xl font-bold mb-6">Admin Panel</h1>
                    <nav class="space-y-2">
                        <a href="{{ route('admin.dashboard') }}" 
                           class="block px-4 py-2 rounded {{ request()->routeIs('admin.dashboard') ? 'bg-gray-700' : 'hover:bg-gray-700' }} transition-colors">
                            Dashboard
                        </a>
                        <a href="{{ route('admin.comics.index') }}" 
                           class="block px-4 py-2 rounded {{ request()->routeIs('admin.comics.*') ? 'bg-gray-700' : 'hover:bg-gray-700' }} transition-colors">
                            Quản lý Truyện
                        </a>
                        <a href="{{ route('admin.orders.index') }}" 
                           class="block px-4 py-2 rounded {{ request()->routeIs('admin.orders.*') ? 'bg-gray-700' : 'hover:bg-gray-700' }} transition-colors">
                            Quản lý Đơn hàng
                        </a>
                        <a href="{{ route('admin.categories.index') }}" 
                           class="block px-4 py-2 rounded {{ request()->routeIs('admin.categories.*') ? 'bg-gray-700' : 'hover:bg-gray-700' }} transition-colors">
                            Danh mục
                        </a>
                        <a href="{{ route('admin.publishers.index') }}" 
                           class="block px-4 py-2 rounded {{ request()->routeIs('admin.publishers.*') ? 'bg-gray-700' : 'hover:bg-gray-700' }} transition-colors">
                            Nhà xuất bản
                        </a>
                        <a href="{{ route('admin.vouchers.index') }}" 
                           class="block px-4 py-2 rounded {{ request()->routeIs('admin.vouchers.*') ? 'bg-gray-700' : 'hover:bg-gray-700' }} transition-colors">
                            Mã giảm giá
                        </a>
                        <a href="{{ route('admin.inventory.index') }}" 
                           class="block px-4 py-2 rounded {{ request()->routeIs('admin.inventory.*') ? 'bg-gray-700' : 'hover:bg-gray-700' }} transition-colors">
                            Tồn kho
                        </a>
                        <a href="{{ route('admin.users.index') }}" 
                           class="block px-4 py-2 rounded {{ request()->routeIs('admin.users.*') ? 'bg-gray-700' : 'hover:bg-gray-700' }} transition-colors">
                            Người dùng
                        </a>
                        <a href="{{ route('admin.shipping.index') }}" 
                           class="block px-4 py-2 rounded {{ request()->routeIs('admin.shipping.*') ? 'bg-gray-700' : 'hover:bg-gray-700' }} transition-colors">
                            Vận chuyển
                        </a>
                    </nav>
                </div>
                
                <div class="absolute bottom-0 w-64 p-4 border-t border-gray-700">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-sm">{{ Auth::user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-sm text-gray-400 hover:text-white transition-colors">
                                Đăng xuất
                            </button>
                        </form>
                    </div>
                    <a href="{{ route('home') }}" class="text-sm text-gray-400 hover:text-white transition-colors">
                        ← Về trang chủ
                    </a>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="flex-1">
                <!-- Top Bar -->
                <header class="bg-white shadow-sm border-b">
                    <div class="px-6 py-4">
                        <h2 class="text-2xl font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h2>
                    </div>
                </header>

                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="mx-6 mt-4">
                        <div class="alert alert-success">{{ session('success') }}</div>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="mx-6 mt-4">
                        <div class="alert alert-error">{{ session('error') }}</div>
                    </div>
                @endif
                
                @if($errors->any())
                    <div class="mx-6 mt-4">
                        <div class="alert alert-error">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <!-- Page Content -->
                <div class="p-6">
                    @yield('content')
                </div>
            </main>
        </div>
    </body>
</html>
