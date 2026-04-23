<!DOCTYPE html>
<html lang="{{ $lang ?? app()->getLocale() }}" dir="{{ $dir ?? (app()->getLocale() === 'ar' ? 'rtl' : 'ltr') }}" class="{{ auth()->check() && auth()->user()->preferred_theme === 'dark' ? 'dark' : '' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Pink Bunny Beauty') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-bunny-bg text-bunny-text antialiased dark:bg-bunny-dark-bg dark:text-bunny-dark-text">
    <div class="flex min-h-screen flex-col items-center justify-center px-4 py-12 sm:px-6 lg:px-8">
        {{-- Logo --}}
        <div class="mb-10 text-center">
            <a href="{{ route('home') }}" class="group inline-flex flex-col items-center gap-3">
                <span class="inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-bunny-primary text-lg font-bold text-white shadow-glow transition-transform duration-300 group-hover:scale-105">PB</span>
                <span class="font-serif text-2xl font-semibold text-bunny-text dark:text-bunny-dark-text">Pink Bunny</span>
            </a>
        </div>

        {{-- Auth Card --}}
        <div class="glass-card w-full max-w-md p-8 sm:p-10">
            {{ $slot }}
        </div>

        {{-- Decorative bottom text --}}
        <p class="mt-8 text-center text-xs text-bunny-muted dark:text-bunny-dark-muted">
            © {{ date('Y') }} Pink Bunny Beauty — Premium cosmetics for the modern woman.
        </p>
    </div>
</body>
</html>
