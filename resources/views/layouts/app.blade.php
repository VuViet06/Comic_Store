 <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name', 'Laravel'))</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @stack('scripts')
    </head>
    <body class="font-sans antialiased text-slate-800">
        <div class="min-h-screen bg-slate-50">
            @include('layouts.header')

            
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main>
                @if(session('success'))
                    <div class="container mx-auto px-4 pt-4">
                        <div class="alert alert-success">{{ session('success') }}</div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="container mx-auto px-4 pt-4">
                        <div class="alert alert-error">{{ session('error') }}</div>
                    </div>
                @endif

                @if($errors->any())
                    <div class="container mx-auto px-4 pt-4">
                        <div class="alert alert-error">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                @yield('content')
            </main>

            @include('layouts.footer')
        </div>
    </body>
</html>
