@props(['product'])

@php
    $image = $product->images[0] ?? 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=800&q=80';
    $discount = ($product->sale_price && (float) $product->price > 0)
        ? round(((float) $product->price - (float) $product->sale_price) / (float) $product->price * 100)
        : null;
@endphp

<article class="group flex h-full flex-col overflow-hidden rounded-3xl border border-bunny-border bg-white transition-all duration-300 hover:-translate-y-1 hover:shadow-elevated dark:border-bunny-dark-border dark:bg-bunny-dark-surface">
    {{-- Image --}}
    <div class="img-zoom relative aspect-[4/5]">
        <img src="{{ str_starts_with($image, 'http') ? $image : asset('storage/' . $image) }}" alt="{{ $product->display_name }}" class="h-full w-full object-cover" loading="lazy">

        {{-- Overlays --}}
        @if($discount && $product->is_flash_sale)
            <span class="absolute start-3 top-3 badge bg-red-500 text-xs text-white">-{{ $discount }}%</span>
        @endif

        @if($product->stock_quantity <= 0)
            <span class="absolute inset-0 flex items-center justify-center bg-bunny-text/40 text-sm font-semibold uppercase tracking-wider text-white backdrop-blur-sm">{{ __('messages.products.out_of_stock') }}</span>
        @endif

        {{-- Wishlist Button --}}
        @auth
            <form action="{{ route('wishlist.toggle') }}" method="POST" class="absolute {{ app()->getLocale() === 'ar' ? 'start-3' : 'end-3' }} top-3 opacity-0 transition-opacity duration-300 group-hover:opacity-100">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <button type="submit" class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-white/90 text-bunny-muted shadow-soft backdrop-blur-sm transition-all duration-200 hover:bg-white hover:text-bunny-primary hover:shadow-elevated dark:bg-bunny-dark-surface/90 dark:text-bunny-dark-muted dark:hover:text-bunny-primary">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" /></svg>
                </button>
            </form>
        @endauth

        {{-- Quick-view overlay --}}
        <div class="pointer-events-none absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-black/20 to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100"></div>
    </div>

    {{-- Content --}}
    <div class="flex flex-1 flex-col p-5">
        <a href="{{ route('brands.show', $product->brand) }}" class="text-[11px] font-bold uppercase tracking-[0.15em] text-bunny-muted dark:text-bunny-dark-muted">{{ $product->brand->name }}</a>
        <a href="{{ route('products.show', $product->slug) }}" class="mt-1.5 line-clamp-2 font-sans text-xl font-extrabold leading-snug text-bunny-text transition-colors duration-200 hover:text-bunny-primary dark:text-bunny-dark-text dark:hover:text-bunny-primary">{{ $product->display_name }}</a>

        {{-- Rating --}}
        <div class="mt-2 flex items-center gap-1">
            @for($i = 1; $i <= 5; $i++)
                <span class="text-xs {{ $i <= round($product->average_rating) ? 'star-gold' : 'star-empty' }}">★</span>
            @endfor
            <span class="ms-1.5 text-xs text-bunny-muted dark:text-bunny-dark-muted">({{ $product->total_reviews }})</span>
        </div>

        {{-- Price --}}
        <div class="mt-3 flex items-baseline gap-2">
            <span class="text-lg font-bold text-bunny-text dark:text-bunny-dark-text">{{ number_format((float) $product->current_price, 2) }} {{ __('messages.currency') }}</span>
            @if($product->sale_price)
                <span class="text-sm text-bunny-muted line-through dark:text-bunny-dark-muted">{{ number_format((float) $product->price, 2) }}</span>
            @endif
        </div>

        {{-- Actions --}}
        <div class="mt-4 flex flex-1 items-end gap-2">
            <a href="{{ route('products.show', $product->slug) }}" class="pill-btn-outline flex-1 px-3 py-2 text-xs">{{ __('messages.products.details') }}</a>
            @auth
                <form action="{{ route('cart.add') }}" method="POST" class="flex-1">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="pill-btn-primary w-full px-3 py-2 text-xs" @disabled($product->stock_quantity <= 0)>
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                        {{ __('messages.products.add_to_cart') }}
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="pill-btn-primary flex-1 px-3 py-2 text-center text-xs">{{ __('messages.nav.login') }}</a>
            @endauth
        </div>
    </div>
</article>
