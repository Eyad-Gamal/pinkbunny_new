@extends('layouts.app')

@section('title', __('messages.products.title') . ' — Pink Bunny')

@section('content')
    {{-- Header --}}
    <section class="page-shell">
        <div class="overflow-hidden rounded-[2.5rem] bg-gradient-to-r from-bunny-secondary to-bunny-primary px-8 py-12 shadow-soft">
            <h1 class="font-sans text-4xl font-extrabold text-bunny-text sm:text-5xl">{{ __('messages.products.title') }}</h1>
            <p class="mt-3 max-w-xl text-base text-bunny-text/80">{{ __('messages.products.subtitle') }}</p>
            <form method="GET" class="mt-6">
                <div class="relative max-w-xl">
                    <svg class="absolute start-4 top-1/2 h-4 w-4 -translate-y-1/2 text-bunny-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" /></svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products..." class="w-full rounded-2xl border border-white/30 bg-white/95 py-3 pe-4 ps-11 text-sm text-bunny-text shadow-soft outline-none transition focus:ring-2 focus:ring-white/50 dark:bg-bunny-dark-surface/95 dark:text-bunny-dark-text">
                </div>
            </form>
        </div>
    </section>

    {{-- Content --}}
    <section class="page-shell mt-10 grid gap-8 lg:grid-cols-[280px_minmax(0,1fr)]">
        {{-- Sidebar Filters --}}
        <aside class="card-surface h-fit p-6">
            <div class="flex items-center justify-between">
                <h2 class="font-sans text-xl font-extrabold text-bunny-text dark:text-bunny-dark-text">{{ __('messages.products.filters') }}</h2>
                <a href="{{ route('products.index') }}" class="text-xs font-semibold text-bunny-primary transition-colors hover:text-bunny-primary-dark">{{ __('messages.products.clear') }}</a>
            </div>
            <form method="GET" class="mt-6 space-y-6">
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif

                {{-- Categories --}}
                <div>
                    <h3 class="text-sm font-semibold text-bunny-text dark:text-bunny-dark-text">Categories</h3>
                    <div class="mt-3 space-y-2.5">
                        @foreach($categories as $category)
                            <label class="flex cursor-pointer items-center gap-3 text-sm text-bunny-muted transition-colors hover:text-bunny-text dark:text-bunny-dark-muted dark:hover:text-bunny-dark-text">
                                <input type="checkbox" name="categories[]" value="{{ $category->id }}" class="rounded border-bunny-border text-bunny-primary focus:ring-bunny-primary/20 dark:border-bunny-dark-border" @checked(in_array($category->id, (array) request('categories', [])))>
                                <span>{{ $category->display_name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="divider"></div>

                {{-- Brands --}}
                <div>
                    <h3 class="text-sm font-semibold text-bunny-text dark:text-bunny-dark-text">Brands</h3>
                    <div class="mt-3 space-y-2.5">
                        @foreach($brands as $brand)
                            <label class="flex cursor-pointer items-center gap-3 text-sm text-bunny-muted transition-colors hover:text-bunny-text dark:text-bunny-dark-muted dark:hover:text-bunny-dark-text">
                                <input type="checkbox" name="brands[]" value="{{ $brand->id }}" class="rounded border-bunny-border text-bunny-primary focus:ring-bunny-primary/20 dark:border-bunny-dark-border" @checked(in_array($brand->id, (array) request('brands', [])))>
                                <span>{{ $brand->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="divider"></div>

                {{-- Price --}}
                <div>
                    <h3 class="text-sm font-semibold text-bunny-text dark:text-bunny-dark-text">Price range</h3>
                    <div class="mt-3 grid grid-cols-2 gap-3">
                        <input type="number" name="min_price" value="{{ request('min_price') }}" class="field mt-0 py-2 text-center text-xs" placeholder="Min">
                        <input type="number" name="max_price" value="{{ request('max_price') }}" class="field mt-0 py-2 text-center text-xs" placeholder="Max">
                    </div>
                </div>

                <div class="divider"></div>

                {{-- Toggles --}}
                <div class="space-y-3">
                    <label class="flex cursor-pointer items-center gap-3 text-sm text-bunny-muted dark:text-bunny-dark-muted">
                        <input type="checkbox" name="on_sale" value="1" class="rounded border-bunny-border text-bunny-primary focus:ring-bunny-primary/20 dark:border-bunny-dark-border" @checked(request()->boolean('on_sale'))>
                        On sale only
                    </label>
                    <label class="flex cursor-pointer items-center gap-3 text-sm text-bunny-muted dark:text-bunny-dark-muted">
                        <input type="checkbox" name="in_stock" value="1" class="rounded border-bunny-border text-bunny-primary focus:ring-bunny-primary/20 dark:border-bunny-dark-border" @checked(request()->boolean('in_stock'))>
                        In stock only
                    </label>
                </div>

                <button type="submit" class="pill-btn-primary w-full text-sm">Apply filters</button>
            </form>
        </aside>

        {{-- Products Grid --}}
        <div>
            {{-- Sort bar --}}
            <div class="mb-6 flex flex-col gap-4 rounded-2xl border border-bunny-border bg-white/80 p-4 backdrop-blur-sm dark:border-bunny-dark-border dark:bg-bunny-dark-surface/80 md:flex-row md:items-center md:justify-between">
                <p class="text-sm text-bunny-muted dark:text-bunny-dark-muted">
                    {{ __('messages.products.results', ['from' => $products->firstItem() ?: 0, 'to' => $products->lastItem() ?: 0, 'total' => $products->total()]) }}
                </p>
                <form method="GET" class="flex items-center gap-2">
                    @foreach(request()->except('sort', 'page') as $key => $value)
                        @if(is_array($value))
                            @foreach($value as $item)
                                <input type="hidden" name="{{ $key }}[]" value="{{ $item }}">
                            @endforeach
                        @else
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endif
                    @endforeach
                    <select name="sort" class="field mt-0 py-2 text-xs" onchange="this.form.submit()">
                        <option value="">Popular</option>
                        <option value="newest" @selected(request('sort') === 'newest')>Newest</option>
                        <option value="price_asc" @selected(request('sort') === 'price_asc')>Price low–high</option>
                        <option value="price_desc" @selected(request('sort') === 'price_desc')>Price high–low</option>
                        <option value="rating" @selected(request('sort') === 'rating')>Top rated</option>
                    </select>
                </form>
            </div>

            {{-- Grid --}}
            <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3 animate-stagger">
                @forelse($products as $product)
                    <x-product-card :product="$product" />
                @empty
                    <div class="card-surface col-span-full p-12 text-center">
                        <p class="text-bunny-muted dark:text-bunny-dark-muted">{{ __('messages.products.empty') }}</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-8">{{ $products->links() }}</div>
        </div>
    </section>
@endsection
