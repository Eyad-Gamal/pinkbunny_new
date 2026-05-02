@extends('layouts.app')

@section('title', $product->display_name . ' — Pink Bunny')

@section('content')
    @php
        $images = $product->images ?: ['https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=1200&q=80'];
    @endphp

    <section class="page-shell">
        <div class="grid gap-10 lg:grid-cols-[1fr_0.95fr]">
            {{-- Image Gallery --}}
            <div class="card-surface overflow-hidden p-4" x-data="{ activeImage: '{{ str_starts_with($images[0], 'http') ? $images[0] : asset('storage/' . $images[0]) }}' }">
                <div class="img-zoom overflow-hidden rounded-2xl">
                    <img :src="activeImage" alt="{{ $product->display_name }}" class="h-[440px] w-full rounded-2xl object-cover">
                </div>
                @if(count($images) > 1)
                    <div class="mt-4 grid grid-cols-4 gap-3">
                        @foreach($images as $image)
                            <button type="button" @click="activeImage = '{{ str_starts_with($image, 'http') ? $image : asset('storage/' . $image) }}'" class="overflow-hidden rounded-xl border-2 transition-all duration-200" :class="activeImage === '{{ str_starts_with($image, 'http') ? $image : asset('storage/' . $image) }}' ? 'border-bunny-primary shadow-soft' : 'border-bunny-border dark:border-bunny-dark-border opacity-60 hover:opacity-100'">
                                <img src="{{ str_starts_with($image, 'http') ? $image : asset('storage/' . $image) }}" alt="Thumbnail" class="h-20 w-full object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Product Info --}}
            <div class="space-y-6">
                <div class="card-surface p-8">
                    <a href="{{ route('brands.show', $product->brand) }}" class="text-xs font-semibold uppercase tracking-[0.2em] text-bunny-secondary transition-colors hover:text-bunny-primary">{{ $product->brand->name }}</a>
                    <h1 class="mt-3 font-sans text-3xl font-extrabold text-bunny-text sm:text-4xl dark:text-bunny-dark-text">{{ $product->display_name }}</h1>

                    {{-- Rating --}}
                    <div class="mt-4 flex items-center gap-2">
                        <div class="flex items-center gap-0.5">
                            @for($i = 1; $i <= 5; $i++)
                                <span class="text-sm {{ $i <= round($product->average_rating) ? 'star-gold' : 'star-empty' }}">★</span>
                            @endfor
                        </div>
                        <span class="text-sm text-bunny-muted dark:text-bunny-dark-muted">({{ $product->total_reviews }} reviews)</span>
                    </div>

                    {{-- Price --}}
                    <div class="mt-5 flex items-baseline gap-3">
                        <span class="font-sans text-3xl font-extrabold text-bunny-text dark:text-bunny-dark-text">{{ number_format((float) $product->current_price, 2) }} {{ __('messages.currency') }}</span>
                        @if($product->sale_price)
                            <span class="text-lg text-bunny-muted line-through dark:text-bunny-dark-muted">{{ number_format((float) $product->price, 2) }}</span>
                        @endif
                    </div>

                    {{-- Flash Sale Timer --}}
                    @if($product->is_flash_sale && $product->flash_sale_ends_at && $product->flash_sale_ends_at->isFuture())
                        <div class="mt-6 rounded-2xl bg-gradient-to-r from-bunny-primary to-bunny-primary-dark p-5">
                            <p class="mb-3 text-xs font-semibold uppercase tracking-[0.2em] text-white/70">{{ __('messages.home.flash_sale') }}</p>
                            <x-flash-sale-timer :ends-at="$product->flash_sale_ends_at" />
                        </div>
                    @endif

                    {{-- Actions --}}
                    <div class="mt-8 flex flex-wrap gap-3">
                        @auth
                            <form action="{{ route('cart.add') }}" method="POST" class="flex flex-wrap items-center gap-3">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="number" min="1" max="{{ max($product->stock_quantity, 1) }}" name="quantity" value="1" class="field mt-0 w-20 py-2.5 text-center">
                                <button type="submit" class="pill-btn-primary px-8 py-3" @disabled($product->stock_quantity <= 0)>
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" /></svg>
                                    {{ __('messages.products.add_to_cart') }}
                                </button>
                            </form>
                            <form action="{{ route('wishlist.toggle') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <button type="submit" class="pill-btn-outline px-5 py-3">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" /></svg>
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="pill-btn-primary px-8 py-3">{{ __('messages.nav.login') }} to purchase</a>
                        @endauth
                    </div>
                </div>

                {{-- Details Tabs --}}
                <div class="card-surface p-8" x-data="{ tab: 'description' }">
                    <div class="flex gap-1 border-b border-bunny-border dark:border-bunny-dark-border">
                        <button @click="tab = 'description'" :class="tab === 'description' ? 'border-bunny-primary text-bunny-primary' : 'border-transparent text-bunny-muted dark:text-bunny-dark-muted'" class="border-b-2 px-4 py-3 text-sm font-semibold transition-colors">Description</button>
                        <button @click="tab = 'ingredients'" :class="tab === 'ingredients' ? 'border-bunny-primary text-bunny-primary' : 'border-transparent text-bunny-muted dark:text-bunny-dark-muted'" class="border-b-2 px-4 py-3 text-sm font-semibold transition-colors">Ingredients</button>
                        <button @click="tab = 'howto'" :class="tab === 'howto' ? 'border-bunny-primary text-bunny-primary' : 'border-transparent text-bunny-muted dark:text-bunny-dark-muted'" class="border-b-2 px-4 py-3 text-sm font-semibold transition-colors">How to use</button>
                    </div>
                    <div class="mt-5 text-sm leading-relaxed text-bunny-muted dark:text-bunny-dark-muted">
                        <div x-show="tab === 'description'">{{ $product->display_description }}</div>
                        <div x-show="tab === 'ingredients'" x-cloak>{{ $product->ingredients ?: 'No ingredients listed yet.' }}</div>
                        <div x-show="tab === 'howto'" x-cloak>{{ $product->display_how_to_use ?: 'Apply as directed on product packaging.' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Related Products --}}
    <section class="page-shell mt-16 reveal">
        <h2 class="section-title">Customers also bought</h2>
        <div class="mt-8 grid gap-6 md:grid-cols-2 xl:grid-cols-3 animate-stagger">
            @foreach($related as $item)
                <x-product-card :product="$item" />
            @endforeach
        </div>
    </section>

    {{-- Reviews --}}
    <section class="page-shell mt-16 reveal">
        <div class="card-surface p-8">
            <div class="grid gap-10 lg:grid-cols-[0.35fr_1fr]">
                {{-- Rating Summary --}}
                <div>
                    <h2 class="section-title">Reviews</h2>
                    <p class="mt-4 font-sans text-5xl font-extrabold text-bunny-primary">{{ number_format((float) $product->average_rating, 1) }}</p>
                    <div class="mt-2 flex items-center gap-1">
                        @for($i = 1; $i <= 5; $i++)
                            <span class="{{ $i <= round($product->average_rating) ? 'star-gold' : 'star-empty' }}">★</span>
                        @endfor
                        <span class="ms-2 text-sm text-bunny-muted dark:text-bunny-dark-muted">({{ $product->total_reviews }})</span>
                    </div>
                    <div class="mt-6 space-y-2.5">
                        @foreach(array_reverse($ratings->all(), true) as $rating => $count)
                            <div class="flex items-center gap-3 text-sm">
                                <span class="w-5 text-end text-bunny-muted dark:text-bunny-dark-muted">{{ $rating }}</span>
                                <span class="star-gold text-xs">★</span>
                                <div class="h-2 flex-1 overflow-hidden rounded-full bg-bunny-border dark:bg-bunny-dark-border">
                                    <div class="h-2 rounded-full bg-bunny-secondary transition-all duration-500" style="width: {{ $product->total_reviews ? ($count / max($product->total_reviews, 1)) * 100 : 0 }}%"></div>
                                </div>
                                <span class="w-6 text-xs text-bunny-muted dark:text-bunny-dark-muted">{{ $count }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Reviews List --}}
                <div>
                    @auth
                        <form action="{{ route('reviews.store') }}" method="POST" class="mb-8 rounded-2xl border border-bunny-border p-5 dark:border-bunny-dark-border">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <h3 class="font-semibold text-bunny-text dark:text-bunny-dark-text">Write a review</h3>
                            <div class="mt-4 grid gap-4 md:grid-cols-[140px_1fr]">
                                <select name="rating" class="field mt-0 text-sm">
                                    @for($i = 5; $i >= 1; $i--)
                                        <option value="{{ $i }}">{{ $i }} ★</option>
                                    @endfor
                                </select>
                                <textarea name="comment" rows="3" class="field mt-0" placeholder="Share your experience..."></textarea>
                            </div>
                            <button type="submit" class="pill-btn-primary mt-4 px-6 py-2.5 text-sm">Submit review</button>
                        </form>
                    @endauth

                    <div class="space-y-4">
                        @forelse($product->reviews as $review)
                            <div class="rounded-2xl border border-bunny-border p-5 dark:border-bunny-dark-border">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex items-center gap-3">
                                        <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-bunny-primary/10 text-xs font-semibold text-bunny-primary">{{ strtoupper(substr($review->user->name, 0, 1)) }}</span>
                                        <div>
                                            <p class="text-sm font-semibold text-bunny-text dark:text-bunny-dark-text">{{ $review->user->name }}</p>
                                            <p class="text-xs text-bunny-muted dark:text-bunny-dark-muted">{{ $review->created_at->format('d M Y') }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-0.5">
                                        @for($i = 1; $i <= 5; $i++)
                                            <span class="text-xs {{ $i <= $review->rating ? 'star-gold' : 'star-empty' }}">★</span>
                                        @endfor
                                    </div>
                                </div>
                                <p class="mt-3 text-sm leading-relaxed text-bunny-muted dark:text-bunny-dark-muted">{{ $review->comment ?: $review->review_text }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-bunny-muted dark:text-bunny-dark-muted">No reviews yet. Be the first to share your experience!</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
