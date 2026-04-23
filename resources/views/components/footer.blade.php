<footer class="border-t-2 border-bunny-secondary bg-bunny-secondary/40 px-4 pb-8 pt-16 dark:border-bunny-dark-border dark:bg-bunny-dark-surface/50">
    <div class="mx-auto max-w-7xl">
        {{-- Top section --}}
        <div class="grid gap-12 md:grid-cols-2 lg:grid-cols-4">
            {{-- Brand --}}
            <div class="lg:col-span-1">
                <a href="{{ route('home') }}" class="group inline-flex items-center gap-3">
                    <img src="{{ asset('images/bunny_mascot.png') }}" alt="Pink Bunny Logo" class="h-12 w-12 transition-transform duration-300 group-hover:scale-110 group-hover:rotate-12">
                    <span class="font-sans text-2xl font-extrabold tracking-tight text-bunny-text dark:text-bunny-dark-text">Pink Bunny</span>
                </a>
                <p class="mt-4 max-w-xs text-sm leading-relaxed text-bunny-muted dark:text-bunny-dark-muted">{{ __('messages.footer.tagline') }}</p>
                {{-- Social icons --}}
                <div class="mt-6 flex items-center gap-3">
                    <a href="#" class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-bunny-border text-bunny-muted transition-all duration-200 hover:border-bunny-primary hover:text-bunny-primary dark:border-bunny-dark-border dark:text-bunny-dark-muted dark:hover:border-bunny-primary dark:hover:text-bunny-primary" aria-label="Instagram">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                    </a>
                    <a href="#" class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-bunny-border text-bunny-muted transition-all duration-200 hover:border-bunny-primary hover:text-bunny-primary dark:border-bunny-dark-border dark:text-bunny-dark-muted dark:hover:border-bunny-primary dark:hover:text-bunny-primary" aria-label="TikTok">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.11v-3.5a6.37 6.37 0 00-.79-.05A6.34 6.34 0 003.15 15.2a6.34 6.34 0 0010.86 4.48v-7.15a8.16 8.16 0 005.58 2.17v-3.44a4.85 4.85 0 01-3.77-1.6v-2.97z"/></svg>
                    </a>
                    <a href="#" class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-bunny-border text-bunny-muted transition-all duration-200 hover:border-bunny-primary hover:text-bunny-primary dark:border-bunny-dark-border dark:text-bunny-dark-muted dark:hover:border-bunny-primary dark:hover:text-bunny-primary" aria-label="Facebook">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                </div>
            </div>

            {{-- Shop --}}
            <div>
                <h4 class="text-sm font-semibold uppercase tracking-wider text-bunny-text dark:text-bunny-dark-text">{{ __('messages.footer.shop') }}</h4>
                <div class="mt-4 space-y-3">
                    <a href="{{ route('products.index') }}" class="block text-sm text-bunny-muted transition-colors duration-200 hover:text-bunny-primary dark:text-bunny-dark-muted dark:hover:text-bunny-primary">{{ __('messages.nav.products') }}</a>
                    <a href="{{ route('brands.index') }}" class="block text-sm text-bunny-muted transition-colors duration-200 hover:text-bunny-primary dark:text-bunny-dark-muted dark:hover:text-bunny-primary">{{ __('messages.nav.brands') }}</a>
                </div>
            </div>

            {{-- Company --}}
            <div>
                <h4 class="text-sm font-semibold uppercase tracking-wider text-bunny-text dark:text-bunny-dark-text">{{ __('messages.footer.company') }}</h4>
                <div class="mt-4 space-y-3">
                    <a href="{{ route('about') }}" class="block text-sm text-bunny-muted transition-colors duration-200 hover:text-bunny-primary dark:text-bunny-dark-muted dark:hover:text-bunny-primary">{{ __('messages.nav.about') }}</a>
                    <a href="{{ route('settings.index') }}" class="block text-sm text-bunny-muted transition-colors duration-200 hover:text-bunny-primary dark:text-bunny-dark-muted dark:hover:text-bunny-primary">{{ __('messages.nav.settings') }}</a>
                </div>
            </div>

            {{-- Contact --}}
            <div>
                <h4 class="text-sm font-semibold uppercase tracking-wider text-bunny-text dark:text-bunny-dark-text">{{ __('messages.footer.connect') }}</h4>
                <div class="mt-4 space-y-3 text-sm text-bunny-muted dark:text-bunny-dark-muted">
                    <p class="flex items-center gap-2">
                        <svg class="h-4 w-4 text-bunny-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" /></svg>
                        hello@pinkbunnybeauty.com
                    </p>
                    <p class="flex items-center gap-2">
                        <svg class="h-4 w-4 text-bunny-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" /></svg>
                        +20 100 555 0199
                    </p>
                </div>
            </div>
        </div>

        {{-- Bottom bar --}}
        <div class="mt-14 border-t border-bunny-border pt-6 text-center text-xs text-bunny-muted dark:border-bunny-dark-border dark:text-bunny-dark-muted">
            © {{ date('Y') }} Pink Bunny Beauty. All rights reserved.
        </div>
    </div>
</footer>
