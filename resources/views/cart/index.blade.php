@extends('layouts.app')

@section('title', __('messages.cart.title') . ' — Pink Bunny')

@section('content')
    <section class="page-shell">
        <h1 class="section-title">{{ __('messages.cart.title') }}</h1>

        @if($items->isEmpty())
            <div class="card-surface mt-10 p-14 text-center">
                <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-bunny-accent/50 dark:bg-bunny-dark-card">
                    <svg class="h-8 w-8 text-bunny-muted dark:text-bunny-dark-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" /></svg>
                </div>
                <p class="mt-5 font-serif text-xl font-semibold text-bunny-text dark:text-bunny-dark-text">{{ __('messages.cart.empty') }}</p>
                <p class="mt-2 text-sm text-bunny-muted dark:text-bunny-dark-muted">Discover our curated collection of premium beauty products.</p>
                <a href="{{ route('products.index') }}" class="pill-btn-primary mt-6">{{ __('messages.home.shop_now') }}</a>
            </div>
        @else
            <div class="mt-8 grid gap-8 lg:grid-cols-[1.4fr_0.7fr]">
                {{-- Cart Items --}}
                <div class="space-y-4">
                    @foreach($items as $item)
                        <div class="card-surface grid gap-5 p-5 sm:grid-cols-[100px_1fr]">
                            <div class="img-zoom overflow-hidden rounded-2xl">
                                @php $cartImg = $item->product->images[0] ?? 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=800&q=80'; @endphp
                                <img src="{{ str_starts_with($cartImg, 'http') ? $cartImg : asset('storage/' . $cartImg) }}" alt="{{ $item->product->display_name }}" class="h-24 w-full rounded-2xl object-cover sm:h-full">
                            </div>
                            <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
                                <div class="min-w-0 flex-1">
                                    <p class="text-[11px] font-semibold uppercase tracking-[0.15em] text-bunny-secondary">{{ $item->product->brand->name }}</p>
                                    <h2 class="mt-1 truncate font-serif text-lg font-semibold text-bunny-text dark:text-bunny-dark-text">{{ $item->product->display_name }}</h2>
                                    <p class="mt-1.5 text-sm font-medium text-bunny-muted dark:text-bunny-dark-muted">{{ number_format((float) $item->product->current_price, 2) }} {{ __('messages.currency') }}</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <form action="{{ route('cart.update', $item) }}" method="POST" class="flex items-center gap-2">
                                        @csrf
                                        @method('PUT')
                                        <input type="number" min="0" name="quantity" value="{{ $item->quantity }}" class="field mt-0 w-16 py-2 text-center text-sm">
                                        <button type="submit" class="pill-btn-outline px-3 py-2 text-xs">Update</button>
                                    </form>
                                    <form action="{{ route('cart.remove', $item) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-red-200 text-red-500 transition-all hover:bg-red-50 hover:text-red-600 dark:border-red-500/20 dark:hover:bg-red-500/10">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Summary --}}
                <aside class="card-surface h-fit p-6">
                    <h2 class="font-serif text-2xl font-semibold text-bunny-text dark:text-bunny-dark-text">{{ __('messages.orders.checkout') }}</h2>
                    <div class="mt-6 space-y-3 text-sm">
                        <div class="flex justify-between text-bunny-muted dark:text-bunny-dark-muted"><span>{{ __('messages.cart.subtotal') }}</span><span>{{ number_format((float) $subtotal, 2) }} {{ __('messages.currency') }}</span></div>
                        <div class="flex justify-between text-bunny-muted dark:text-bunny-dark-muted"><span>{{ __('messages.cart.shipping') }}</span><span>{{ number_format((float) $shipping, 2) }} {{ __('messages.currency') }}</span></div>
                        <div class="flex justify-between text-bunny-muted dark:text-bunny-dark-muted"><span>{{ __('messages.cart.discount') }}</span><span class="text-emerald-600 dark:text-emerald-400">-{{ number_format((float) $discount, 2) }} {{ __('messages.currency') }}</span></div>
                        <div class="divider"></div>
                        <div class="flex justify-between pt-2 font-serif text-xl font-bold text-bunny-text dark:text-bunny-dark-text"><span>{{ __('messages.cart.total') }}</span><span>{{ number_format((float) $total, 2) }} {{ __('messages.currency') }}</span></div>
                    </div>
                    <form action="{{ route('coupon.apply') }}" method="POST" class="mt-6 flex gap-2">
                        @csrf
                        <input type="text" name="code" class="field mt-0 flex-1" placeholder="Coupon code">
                        <button type="submit" class="pill-btn-outline px-4 py-3 text-xs">Apply</button>
                    </form>
                    <a href="{{ route('checkout') }}" class="pill-btn-primary mt-5 w-full text-center">{{ __('messages.cart.checkout') }}</a>
                </aside>
            </div>
        @endif
    </section>
@endsection
