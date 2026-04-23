<!DOCTYPE html>
<html lang="{{ $lang ?? app()->getLocale() }}" dir="{{ $dir ?? (app()->getLocale() === 'ar' ? 'rtl' : 'ltr') }}" class="{{ auth()->check() && auth()->user()->preferred_theme === 'dark' ? 'dark' : '' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Pink Bunny Beauty') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,400;0,600;0,700;0,800;0,900;1,400&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-bunny-bg text-bunny-text antialiased transition-colors dark:bg-bunny-dark-bg dark:text-bunny-dark-text">
    <div class="flex min-h-screen flex-col items-center justify-center px-4 py-12 sm:px-6 lg:px-8">
        <div class="mb-10 text-center">
            <a href="{{ route('home') }}" class="group inline-flex flex-col items-center gap-3">
                <img src="{{ asset('images/bunny_mascot.png') }}" alt="Pink Bunny Logo" class="h-20 w-20 rounded-full bg-white object-contain shadow-soft transition-transform duration-300 group-hover:scale-110 group-hover:rotate-6">
                <span class="font-sans text-3xl font-extrabold text-bunny-text dark:text-bunny-dark-text tracking-tight">Pink Bunny</span>
            </a>
        </div>
        <div class="glass-card w-full max-w-md p-8 sm:p-10">
            {{ $slot }}
        </div>
        <p class="mt-8 text-center text-xs text-bunny-muted dark:text-bunny-dark-muted">
            © {{ date('Y') }} Pink Bunny Beauty — Premium cosmetics for the modern woman.
        </p>
    </div>
</body>
</html>
