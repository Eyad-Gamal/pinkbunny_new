<!DOCTYPE html>
<html lang="{{ $lang ?? app()->getLocale() }}" dir="{{ $dir ?? (app()->getLocale() === 'ar' ? 'rtl' : 'ltr') }}" class="{{ auth()->check() && auth()->user()->preferred_theme === 'dark' ? 'dark' : '' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Pink Bunny Beauty')</title>
    <meta name="description" content="@yield('meta_description', 'Pink Bunny Beauty — clean, cute cosmetics and skincare.')">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,400;0,600;0,700;0,800;0,900;1,400&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-bunny-bg text-bunny-text antialiased transition-colors duration-300 dark:bg-bunny-dark-bg dark:text-bunny-dark-text" data-theme="{{ auth()->user()->preferred_theme ?? session('theme', 'light') }}">
    <div x-data="{ mobileMenu: false }" class="flex min-h-screen flex-col">
        <x-navbar />
        <main id="swup" class="transition-fade flex-1 pb-20 pt-28">
            @if (session('toast'))
                <x-toast :message="session('toast')" />
            @endif
            @if (session('error'))
                <x-toast :message="session('error')" type="error" />
            @endif
            @yield('content')
        </main>
        <x-footer />
    </div>
</body>
</html>
