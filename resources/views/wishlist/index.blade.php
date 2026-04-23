@extends('layouts.app')

@section('title', __('messages.wishlist.title') . ' — Pink Bunny')

@section('content')
    <section class="page-shell">
        <h1 class="section-title">{{ __('messages.wishlist.title') }}</h1>
        <p class="section-subtitle">Your saved beauty picks</p>
        <div class="mt-8 grid gap-6 sm:grid-cols-2 xl:grid-cols-4 animate-stagger">
            @forelse($items as $item)
                <x-product-card :product="$item->product" />
            @empty
                <div class="card-surface col-span-full p-14 text-center">
                    <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-bunny-accent/50 dark:bg-bunny-dark-card">
                        <svg class="h-8 w-8 text-bunny-muted dark:text-bunny-dark-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" /></svg>
                    </div>
                    <p class="mt-5 font-serif text-xl font-semibold text-bunny-text dark:text-bunny-dark-text">{{ __('messages.wishlist.empty') }}</p>
                    <p class="mt-2 text-sm text-bunny-muted dark:text-bunny-dark-muted">Save your favorite products here for later.</p>
                    <a href="{{ route('products.index') }}" class="pill-btn-primary mt-6">Browse products</a>
                </div>
            @endforelse
        </div>
    </section>
@endsection
