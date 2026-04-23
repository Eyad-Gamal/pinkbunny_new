
@extends('layouts.app')

@section('title', 'Profile — Pink Bunny')

@section('content')
<section class="page-shell">
    <div class="grid gap-8 lg:grid-cols-[300px_1fr]">
        {{-- Sidebar --}}
        <div class="card-surface p-8 text-center lg:text-start">
            <div class="mb-6">
                <img src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=D4577B&color=ffffff&size=128&font-size=0.4' }}" alt="{{ auth()->user()->name }}" class="mx-auto h-28 w-28 rounded-3xl object-cover shadow-elevated lg:mx-0">
            </div>
            <h1 class="font-serif text-2xl font-bold text-bunny-text dark:text-bunny-dark-text">{{ auth()->user()->name }}</h1>
            <p class="mt-1 text-sm text-bunny-muted dark:text-bunny-dark-muted">{{ auth()->user()->email }}</p>
            <span class="badge-primary mt-3 inline-flex">{{ ucfirst(auth()->user()->role) }}</span>

            <div class="divider my-6"></div>

            <nav class="space-y-1 text-start">
                <a href="{{ route('orders.index') }}" class="flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-medium text-bunny-muted transition-colors hover:bg-bunny-accent/40 hover:text-bunny-text dark:text-bunny-dark-muted dark:hover:bg-bunny-dark-card dark:hover:text-bunny-dark-text">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" /></svg>
                    Orders
                </a>
                <a href="{{ route('wishlist.index') }}" class="flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-medium text-bunny-muted transition-colors hover:bg-bunny-accent/40 hover:text-bunny-text dark:text-bunny-dark-muted dark:hover:bg-bunny-dark-card dark:hover:text-bunny-dark-text">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" /></svg>
                    Wishlist
                </a>
                <a href="{{ route('settings.index') }}" class="flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-medium text-bunny-muted transition-colors hover:bg-bunny-accent/40 hover:text-bunny-text dark:text-bunny-dark-muted dark:hover:bg-bunny-dark-card dark:hover:text-bunny-dark-text">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                    Settings
                </a>
                <div class="my-2 border-t border-bunny-border dark:border-bunny-dark-border"></div>
                <form method="POST" action="{{ route('logout') }}" data-no-swup>
                    @csrf
                    <button type="submit" class="flex w-full items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-medium text-red-500 transition-colors hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-500/10">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" /></svg>
                        Logout
                    </button>
                </form>
            </nav>
        </div>

        {{-- Main Content --}}
        <div class="space-y-8">
            {{-- Recent Orders --}}
            <div class="card-surface p-6 lg:p-8">
                <div class="flex items-center justify-between">
                    <h2 class="font-serif text-xl font-semibold text-bunny-text dark:text-bunny-dark-text">Recent Orders</h2>
                    <a href="{{ route('orders.index') }}" class="text-xs font-semibold text-bunny-primary hover:text-bunny-primary-dark">View all →</a>
                </div>
                @php $orders = auth()->user()->orders()->latest()->take(5)->get(); @endphp
                @if($orders->count())
                    <div class="mt-5 space-y-3">
                        @foreach($orders as $order)
                            <a href="{{ route('orders.show', $order) }}" class="flex items-center justify-between rounded-2xl border border-bunny-border p-4 transition-all hover:border-bunny-primary hover:shadow-soft dark:border-bunny-dark-border dark:hover:border-bunny-primary">
                                <div class="flex items-center gap-4">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-bunny-primary/10 text-xs font-bold text-bunny-primary dark:bg-bunny-primary/20">#{{ $order->id }}</div>
                                    <div>
                                        <p class="text-sm font-semibold text-bunny-text dark:text-bunny-dark-text">{{ number_format($order->total_amount, 2) }} {{ __('messages.currency') }}</p>
                                        <p class="text-xs text-bunny-muted dark:text-bunny-dark-muted">{{ $order->created_at->format('M d, Y') }} · {{ $order->items->count() }} items</p>
                                    </div>
                                </div>
                                <span class="badge-{{ $order->status === 'delivered' ? 'success' : ($order->status === 'cancelled' ? 'danger' : 'primary') }} text-xs capitalize">{{ $order->status }}</span>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="mt-6 rounded-2xl border-2 border-dashed border-bunny-border p-10 text-center dark:border-bunny-dark-border">
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-bunny-accent/50 dark:bg-bunny-dark-card">
                            <svg class="h-6 w-6 text-bunny-muted dark:text-bunny-dark-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" /></svg>
                        </div>
                        <p class="mt-4 font-serif text-lg font-semibold">No orders yet</p>
                        <p class="mt-1 text-sm text-bunny-muted dark:text-bunny-dark-muted">Start shopping to see your orders here</p>
                        <a href="{{ route('products.index') }}" class="pill-btn-primary mt-5">Start Shopping</a>
                    </div>
                @endif
            </div>

            {{-- Stats --}}
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="card-surface p-6 text-center">
                    <p class="font-serif text-3xl font-bold text-bunny-primary">{{ auth()->user()->orders->count() }}</p>
                    <p class="mt-1 text-xs font-medium text-bunny-muted dark:text-bunny-dark-muted">Total Orders</p>
                </div>
                <div class="card-surface p-6 text-center">
                    <p class="font-serif text-3xl font-bold text-bunny-secondary">{{ number_format(auth()->user()->orders->sum('total_amount'), 2) }}</p>
                    <p class="mt-1 text-xs font-medium text-bunny-muted dark:text-bunny-dark-muted">Total Spent</p>
                </div>
                <div class="card-surface p-6 text-center">
                    <p class="font-serif text-3xl font-bold text-bunny-primary">{{ auth()->user()->wishlist->count() }}</p>
                    <p class="mt-1 text-xs font-medium text-bunny-muted dark:text-bunny-dark-muted">Wishlist Items</p>
                </div>
                <div class="card-surface p-6 text-center">
                    <p class="font-serif text-3xl font-bold text-bunny-secondary">{{ auth()->user()->reviews->count() ?? 0 }}</p>
                    <p class="mt-1 text-xs font-medium text-bunny-muted dark:text-bunny-dark-muted">Reviews</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
