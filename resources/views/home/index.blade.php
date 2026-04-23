@extends('layouts.app')

@section('title', 'Pink Bunny | Shop Cosmetics')

@section('content')
    {{-- Hero Slider --}}
    <section class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
        <x-hero-slider :slides="$slides" />
    </section>

    {{-- Categories --}}
    <section class="page-shell mt-20">
        <div class="mb-8 text-center">
            <h2 class="section-title">Shop by Category</h2>
        </div>
        <div class="flex flex-wrap justify-center gap-6 sm:gap-10 animate-stagger">
            @foreach($categories as $category)
                <a href="{{ route('products.index', ['categories[]' => $category->id]) }}" class="group flex flex-col items-center gap-4">
                    <div class="flex h-24 w-24 sm:h-32 sm:w-32 items-center justify-center rounded-full bg-bunny-primary-light text-4xl shadow-soft transition-transform duration-300 group-hover:-translate-y-2 group-hover:scale-105 group-hover:shadow-elevated dark:bg-bunny-dark-card border-4 border-white">
                        <span>{{ $category->icon ?: '✨' }}</span>
                    </div>
                    <p class="font-extrabold text-bunny-text dark:text-bunny-dark-text text-lg">{{ $category->display_name }}</p>
                </a>
            @endforeach
        </div>
    </section>

    {{-- Best Sellers --}}
    <section class="page-shell mt-24">
        <div class="mb-8 flex items-end justify-between">
            <div>
                <h2 class="section-title">Best Sellers</h2>
                <p class="section-subtitle">Loved by our bunnies</p>
            </div>
            <a href="{{ route('products.index') }}" class="font-bold text-bunny-text hover:text-bunny-primary">View All →</a>
        </div>
        <div class="rounded-[3rem] bg-bunny-accent/40 p-6 sm:p-10 dark:bg-bunny-dark-surface/50">
            <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-4 animate-stagger">
                @forelse($featuredProducts as $product)
                    <x-product-card :product="$product" />
                @empty
                    <div class="card-surface col-span-full p-10 text-center font-semibold text-bunny-muted dark:text-bunny-dark-muted">No featured products yet.</div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- Newsletter --}}
    <section class="page-shell mt-24">
        <div class="grid gap-8 rounded-[3rem] bg-bunny-secondary-light p-10 shadow-soft lg:grid-cols-[1fr_auto] lg:items-center lg:p-14 dark:bg-bunny-dark-card">
            <div>
                <h2 class="section-title">Join the Bunny Club! 🐰</h2>
                <p class="mt-3 max-w-md text-lg font-medium text-bunny-muted dark:text-bunny-dark-muted">Sign up for exclusive deals, new arrivals, and beauty tips.</p>
            </div>
            <form action="{{ route('newsletter.subscribe') }}" method="POST" class="flex w-full flex-col gap-3 sm:flex-row sm:items-start max-w-md">
                @csrf
                <input type="email" name="contact" class="field mt-0 h-14 w-full rounded-full px-6" placeholder="Your email address" required>
                <button type="submit" class="pill-btn-primary h-14 px-8">Subscribe</button>
            </form>
        </div>
    </section>
@endsection
