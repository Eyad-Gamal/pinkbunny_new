@php
    $cartCount = auth()->check() ? auth()->user()->cartItems()->sum('quantity') : 0;
    $wishlistCount = auth()->check() ? auth()->user()->wishlistItems()->count() : 0;
@endphp

<header class="fixed inset-x-0 top-0 z-50 px-4 py-4 sm:px-6">
    <nav id="main-navbar" class="mx-auto flex max-w-7xl items-center justify-between rounded-full border-2 border-transparent bg-white px-6 py-3 shadow-soft transition-all duration-300 dark:bg-bunny-dark-surface">
        {{-- Logo --}}
        <a href="{{ route('home') }}" class="group inline-flex items-center gap-3">
            <img src="{{ asset('images/bunny_mascot.png') }}" alt="Pink Bunny Logo" class="h-10 w-10 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-12">
            <span class="hidden font-sans text-xl font-extrabold tracking-tight text-bunny-text dark:text-bunny-dark-text sm:inline">Pink Bunny</span>
        </a>

        {{-- Desktop Navigation --}}
        <div class="hidden items-center gap-1 lg:flex">
            <a href="{{ route('home') }}" class="rounded-full px-4 py-2 text-sm font-medium text-bunny-text transition-colors duration-200 hover:bg-bunny-accent/40 hover:text-bunny-primary dark:text-bunny-dark-text dark:hover:bg-bunny-dark-card">{{ __('messages.nav.home') }}</a>
            <a href="{{ route('products.index') }}" class="rounded-full px-4 py-2 text-sm font-medium text-bunny-text transition-colors duration-200 hover:bg-bunny-accent/40 hover:text-bunny-primary dark:text-bunny-dark-text dark:hover:bg-bunny-dark-card">{{ __('messages.nav.products') }}</a>
            <a href="{{ route('brands.index') }}" class="rounded-full px-4 py-2 text-sm font-medium text-bunny-text transition-colors duration-200 hover:bg-bunny-accent/40 hover:text-bunny-primary dark:text-bunny-dark-text dark:hover:bg-bunny-dark-card">{{ __('messages.nav.brands') }}</a>
            <a href="{{ route('about') }}" class="rounded-full px-4 py-2 text-sm font-medium text-bunny-text transition-colors duration-200 hover:bg-bunny-accent/40 hover:text-bunny-primary dark:text-bunny-dark-text dark:hover:bg-bunny-dark-card">{{ __('messages.nav.about') }}</a>
        </div>

        {{-- Desktop Actions --}}
        <div class="hidden items-center gap-2 lg:flex">
            {{-- Wishlist --}}
            <a href="{{ route('wishlist.index') }}" class="relative inline-flex h-10 w-10 items-center justify-center rounded-full text-bunny-muted transition-colors duration-200 hover:bg-bunny-accent/40 hover:text-bunny-primary dark:text-bunny-dark-muted dark:hover:bg-bunny-dark-card dark:hover:text-bunny-primary" aria-label="Wishlist">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" /></svg>
                @if($wishlistCount)
                    <span class="absolute -end-0.5 -top-0.5 inline-flex h-4 min-w-4 items-center justify-center rounded-full bg-bunny-primary px-1 text-[10px] font-bold text-white">{{ $wishlistCount }}</span>
                @endif
            </a>

            {{-- Cart --}}
            <a href="{{ route('cart.index') }}" class="relative inline-flex h-10 w-10 items-center justify-center rounded-full text-bunny-muted transition-colors duration-200 hover:bg-bunny-accent/40 hover:text-bunny-primary dark:text-bunny-dark-muted dark:hover:bg-bunny-dark-card dark:hover:text-bunny-primary" aria-label="Cart">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" /></svg>
                @if($cartCount)
                    <span class="absolute -end-0.5 -top-0.5 inline-flex h-4 min-w-4 items-center justify-center rounded-full bg-bunny-primary px-1 text-[10px] font-bold text-white">{{ $cartCount }}</span>
                @endif
            </a>

            {{-- Divider --}}
            <div class="mx-1 h-6 w-px bg-bunny-border dark:bg-bunny-dark-border"></div>

            {{-- User --}}
            @auth
                <div x-data="{ open: false }" @click.outside="open = false" class="relative">
                    <button @click="open = !open" class="inline-flex items-center gap-2.5 rounded-full border border-bunny-border px-3 py-1.5 text-sm font-medium transition-all duration-200 hover:border-bunny-primary hover:shadow-soft dark:border-bunny-dark-border dark:hover:border-bunny-primary">
                        @if(auth()->user()->avatar)
                            <img src="{{ str_starts_with(auth()->user()->avatar, 'http') ? auth()->user()->avatar : asset('storage/' . auth()->user()->avatar) }}" class="inline-flex h-7 w-7 rounded-full object-cover shadow-soft" alt="Avatar">
                        @else
                            <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-bunny-primary/10 text-xs font-semibold text-bunny-primary">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </span>
                        @endif
                        <span class="max-w-[100px] truncate">{{ auth()->user()->name }}</span>
                        <svg class="h-3.5 w-3.5 text-bunny-muted transition-transform duration-200 dark:text-bunny-dark-muted" :class="open && 'rotate-180'" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" /></svg>
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95 -translate-y-1" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" x-cloak class="absolute right-0 z-50 mt-3 w-56 rounded-2xl border border-bunny-border/60 bg-white p-2 shadow-elevated dark:border-bunny-dark-border/60 dark:bg-bunny-dark-surface">
                        <a href="{{ route('profile.index') }}" class="flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-medium text-bunny-text transition-colors hover:bg-bunny-accent/40 dark:text-bunny-dark-text dark:hover:bg-bunny-dark-card">
                            <svg class="h-4 w-4 text-bunny-muted dark:text-bunny-dark-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" /></svg>
                            Profile
                        </a>
                        <a href="{{ route('orders.index') }}" class="flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-medium text-bunny-text transition-colors hover:bg-bunny-accent/40 dark:text-bunny-dark-text dark:hover:bg-bunny-dark-card">
                            <svg class="h-4 w-4 text-bunny-muted dark:text-bunny-dark-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" /></svg>
                            Orders
                        </a>
                        <a href="{{ route('settings.index') }}" class="flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-medium text-bunny-text transition-colors hover:bg-bunny-accent/40 dark:text-bunny-dark-text dark:hover:bg-bunny-dark-card">
                            <svg class="h-4 w-4 text-bunny-muted dark:text-bunny-dark-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                            Settings
                        </a>
                        <div class="my-1 border-t border-bunny-border dark:border-bunny-dark-border"></div>
                        <form method="POST" action="{{ route('logout') }}" data-no-swup>
                            @csrf
                            <button type="submit" class="flex w-full items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-medium text-red-500 transition-colors hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-500/10">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" /></svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="pill-btn-primary px-5 py-2 text-xs">
                    {{ __('messages.nav.login') }}
                </a>
            @endauth
        </div>

        {{-- Mobile Menu Button --}}
        <button @click="mobileMenu = !mobileMenu" class="inline-flex h-10 w-10 items-center justify-center rounded-full text-bunny-muted transition-colors hover:bg-bunny-accent/40 hover:text-bunny-primary lg:hidden dark:text-bunny-dark-muted dark:hover:bg-bunny-dark-card">
            <span class="sr-only">Menu</span>
            <svg x-show="!mobileMenu" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9h16.5m-16.5 6.75h16.5" /></svg>
            <svg x-show="mobileMenu" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
        </button>
    </nav>

    {{-- Mobile Menu --}}
    <div x-show="mobileMenu" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2" x-cloak class="mx-auto mt-3 max-w-7xl rounded-3xl border border-bunny-border/60 bg-white/95 p-6 shadow-elevated backdrop-blur-xl dark:border-bunny-dark-border/60 dark:bg-bunny-dark-surface/95 lg:hidden">
        <div class="flex flex-col gap-1">
            <a href="{{ route('home') }}" class="rounded-2xl px-4 py-3 text-sm font-medium transition-colors hover:bg-bunny-accent/40 dark:hover:bg-bunny-dark-card">{{ __('messages.nav.home') }}</a>
            <a href="{{ route('products.index') }}" class="rounded-2xl px-4 py-3 text-sm font-medium transition-colors hover:bg-bunny-accent/40 dark:hover:bg-bunny-dark-card">{{ __('messages.nav.products') }}</a>
            <a href="{{ route('brands.index') }}" class="rounded-2xl px-4 py-3 text-sm font-medium transition-colors hover:bg-bunny-accent/40 dark:hover:bg-bunny-dark-card">{{ __('messages.nav.brands') }}</a>
            <a href="{{ route('about') }}" class="rounded-2xl px-4 py-3 text-sm font-medium transition-colors hover:bg-bunny-accent/40 dark:hover:bg-bunny-dark-card">{{ __('messages.nav.about') }}</a>
            <div class="divider my-2"></div>
            <a href="{{ route('wishlist.index') }}" class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium transition-colors hover:bg-bunny-accent/40 dark:hover:bg-bunny-dark-card">
                <svg class="h-4 w-4 text-bunny-muted dark:text-bunny-dark-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" /></svg>
                {{ __('messages.nav.wishlist') }} @if($wishlistCount)<span class="badge-primary">{{ $wishlistCount }}</span>@endif
            </a>
            <a href="{{ route('cart.index') }}" class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium transition-colors hover:bg-bunny-accent/40 dark:hover:bg-bunny-dark-card">
                <svg class="h-4 w-4 text-bunny-muted dark:text-bunny-dark-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" /></svg>
                {{ __('messages.nav.cart') }} @if($cartCount)<span class="badge-primary">{{ $cartCount }}</span>@endif
            </a>
            @auth
                <div class="divider my-2"></div>
                <a href="{{ route('profile.index') }}" class="rounded-2xl px-4 py-3 text-sm font-medium transition-colors hover:bg-bunny-accent/40 dark:hover:bg-bunny-dark-card">{{ __('messages.nav.profile') }}</a>
                <a href="{{ route('orders.index') }}" class="rounded-2xl px-4 py-3 text-sm font-medium transition-colors hover:bg-bunny-accent/40 dark:hover:bg-bunny-dark-card">Orders</a>
                <a href="{{ route('settings.index') }}" class="rounded-2xl px-4 py-3 text-sm font-medium transition-colors hover:bg-bunny-accent/40 dark:hover:bg-bunny-dark-card">{{ __('messages.nav.settings') }}</a>
                <div class="divider my-2"></div>
                <form method="POST" action="{{ route('logout') }}" data-no-swup>
                    @csrf
                    <button type="submit" class="flex w-full items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium text-red-500 transition-colors hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-500/10">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" /></svg>
                        Logout
                    </button>
                </form>
            @else
                <div class="divider my-2"></div>
                <a href="{{ route('login') }}" class="pill-btn-primary mt-1 w-full text-center">{{ __('messages.nav.login') }}</a>
            @endauth
        </div>
    </div>
</header>
