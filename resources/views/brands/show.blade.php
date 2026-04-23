@extends('layouts.app')

@section('title', $brand->name . ' — Pink Bunny')

@section('content')
    {{-- Brand Hero --}}
    <section class="page-shell">
        <div class="relative overflow-hidden rounded-[2.5rem] shadow-elevated">
            <img src="{{ $brand->banner ?: 'https://images.unsplash.com/photo-1515377905703-c4788e51af15?auto=format&fit=crop&w=1600&q=80' }}" alt="{{ $brand->name }}" class="h-72 w-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>
            <div class="absolute bottom-0 start-0 end-0 p-8">
                <div class="flex items-end gap-5">
                    <div class="flex h-20 w-20 items-center justify-center rounded-2xl border-2 border-white/40 bg-white shadow-elevated backdrop-blur-sm">
                        <img src="{{ $brand->logo ?: 'https://dummyimage.com/120x120/ffffff/D4577B&text=' . urlencode($brand->name) }}" alt="{{ $brand->name }}" class="max-h-12 max-w-12 object-contain">
                    </div>
                    <div>
                        <h1 class="font-serif text-3xl font-bold text-white sm:text-4xl">{{ $brand->name }}</h1>
                        <p class="mt-2 max-w-2xl text-sm text-white/80">{{ $brand->display_description ?: 'A trusted partner in your beauty routine.' }}</p>
                        <p class="mt-2 text-xs font-semibold uppercase tracking-[0.2em] text-white/60">{{ __('messages.brands.products_count', ['count' => $brand->products_count]) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Content --}}
    <section class="page-shell mt-10 grid gap-8 lg:grid-cols-[260px_minmax(0,1fr)]">
        {{-- Filters --}}
        <aside class="card-surface h-fit p-6">
            <h2 class="font-serif text-lg font-semibold">{{ __('messages.products.filters') }}</h2>
            <form method="GET" class="mt-5 space-y-5">
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
                <label class="flex cursor-pointer items-center gap-3 text-sm text-bunny-muted dark:text-bunny-dark-muted">
                    <input type="checkbox" name="on_sale" value="1" class="rounded border-bunny-border text-bunny-primary focus:ring-bunny-primary/20 dark:border-bunny-dark-border" @checked(request()->boolean('on_sale'))>
                    On sale only
                </label>
                <label class="flex cursor-pointer items-center gap-3 text-sm text-bunny-muted dark:text-bunny-dark-muted">
                    <input type="checkbox" name="in_stock" value="1" class="rounded border-bunny-border text-bunny-primary focus:ring-bunny-primary/20 dark:border-bunny-dark-border" @checked(request()->boolean('in_stock'))>
                    In stock only
                </label>
                <button type="submit" class="pill-btn-primary w-full text-sm">Apply</button>
            </form>
        </aside>

        {{-- Products --}}
        <div>
            <div class="mb-6 flex justify-end">
                <form method="GET">
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
            <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3 animate-stagger">
                @forelse($products as $product)
                    <x-product-card :product="$product" />
                @empty
                    <div class="card-surface col-span-full p-12 text-center text-bunny-muted dark:text-bunny-dark-muted">{{ __('messages.products.empty') }}</div>
                @endforelse
            </div>
            <div class="mt-8">{{ $products->links() }}</div>
        </div>
    </section>
@endsection
