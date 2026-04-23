@extends('layouts.app')

@section('title', __('messages.orders.title') . ' — Pink Bunny')

@section('content')
    <section class="page-shell">
        <h1 class="section-title">{{ __('messages.orders.title') }}</h1>
        <p class="section-subtitle">Track and manage your orders</p>

        <div class="mt-8 space-y-4">
            @forelse($orders as $order)
                <a href="{{ route('orders.show', $order) }}" class="card-surface-hover flex flex-col gap-4 p-6 md:flex-row md:items-center md:justify-between">
                    <div class="flex items-center gap-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-bunny-primary/10 dark:bg-bunny-primary/20">
                            <svg class="h-5 w-5 text-bunny-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" /></svg>
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.15em] text-bunny-secondary">{{ $order->order_number }}</p>
                            <h2 class="mt-1 font-serif text-xl font-semibold text-bunny-text dark:text-bunny-dark-text">{{ number_format((float) $order->total_amount, 2) }} {{ __('messages.currency') }}</h2>
                            <p class="mt-1 text-xs text-bunny-muted dark:text-bunny-dark-muted">{{ $order->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                    <span class="badge-{{ $order->status === 'delivered' ? 'success' : ($order->status === 'cancelled' ? 'danger' : 'primary') }} capitalize">{{ $order->status }}</span>
                </a>
            @empty
                <div class="card-surface p-14 text-center">
                    <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-bunny-accent/50 dark:bg-bunny-dark-card">
                        <svg class="h-8 w-8 text-bunny-muted dark:text-bunny-dark-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" /></svg>
                    </div>
                    <p class="mt-5 font-serif text-xl font-semibold">No orders yet</p>
                    <p class="mt-2 text-sm text-bunny-muted dark:text-bunny-dark-muted">Start shopping to see your orders here.</p>
                    <a href="{{ route('products.index') }}" class="pill-btn-primary mt-6">Browse products</a>
                </div>
            @endforelse
        </div>
        <div class="mt-8">{{ $orders->links() }}</div>
    </section>
@endsection
