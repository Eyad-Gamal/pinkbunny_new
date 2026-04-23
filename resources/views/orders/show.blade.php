@extends('layouts.app')

@section('title', $order->order_number . ' — Pink Bunny')

@section('content')
    <section class="page-shell">
        {{-- Back link --}}
        <a href="{{ route('orders.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-bunny-muted transition-colors hover:text-bunny-primary dark:text-bunny-dark-muted dark:hover:text-bunny-primary">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" /></svg>
            Back to orders
        </a>

        <div class="mt-6 grid gap-8 lg:grid-cols-[1fr_380px]">
            {{-- Left Column --}}
            <div class="space-y-6">
                {{-- Order Header --}}
                <div class="card-surface p-8">
                    <div class="flex flex-col gap-5 sm:flex-row sm:items-start sm:justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-bunny-secondary">{{ $order->order_number }}</p>
                            <h1 class="mt-3 font-serif text-3xl font-bold text-bunny-text dark:text-bunny-dark-text">Order Details</h1>
                            <p class="mt-2 text-sm text-bunny-muted dark:text-bunny-dark-muted">
                                Placed on {{ $order->created_at->format('F j, Y \a\t g:i A') }}
                            </p>
                        </div>
                        <div class="flex flex-col items-start gap-2 sm:items-end">
                            <span class="badge-{{ $order->status === 'delivered' ? 'success' : ($order->status === 'cancelled' || $order->status === 'refunded' ? 'danger' : 'primary') }} capitalize">{{ $order->status_label }}</span>
                            <span class="text-xs text-bunny-muted dark:text-bunny-dark-muted">{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</span>
                        </div>
                    </div>
                </div>

                {{-- Order Tracker --}}
                <div class="card-surface p-8">
                    <h2 class="font-serif text-xl font-semibold text-bunny-text dark:text-bunny-dark-text">Order Tracker</h2>
                    <p class="mt-1 text-sm text-bunny-muted dark:text-bunny-dark-muted">Follow your order every step of the way</p>

                    @php
                        $allStatuses = ['pending', 'confirmed', 'processing', 'shipped', 'delivered'];
                        $cancelledStatuses = ['cancelled', 'refunded'];
                        $isCancelled = in_array($order->status, $cancelledStatuses);
                        $currentIndex = array_search($order->status, $allStatuses);
                        if ($currentIndex === false) $currentIndex = -1;

                        $statusIcons = [
                            'pending'    => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />',
                            'confirmed'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />',
                            'processing' => '<path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-2.25-1.313M21 7.5v2.25m0-2.25l-2.25 1.313M3 7.5l2.25-1.313M3 7.5l2.25 1.313M3 7.5v2.25m9 3l2.25-1.313M12 12.75l-2.25-1.313M12 12.75V15m0 6.75l2.25-1.313M12 21.75V19.5m0 2.25l-2.25-1.313m0-16.875L12 2.25l2.25 1.313M21 14.25v2.25l-2.25 1.313m-13.5 0L3 16.5v-2.25" />',
                            'shipped'    => '<path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />',
                            'delivered'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M21 11.25v8.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5v-8.25M12 4.875A2.625 2.625 0 1 0 9.375 7.5H12m0-2.625V7.5m0-2.625A2.625 2.625 0 1 1 14.625 7.5H12m0 0V21m-8.625-9.75h18c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125h-18c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />',
                            'cancelled'  => '<path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />',
                            'refunded'   => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />',
                        ];

                        $statusLabels = [
                            'pending'    => 'Order Placed',
                            'confirmed'  => 'Confirmed',
                            'processing' => 'Processing',
                            'shipped'    => 'Shipped',
                            'delivered'  => 'Delivered',
                        ];

                        // Build tracking lookup
                        $trackingLookup = $order->tracking->keyBy('status');
                    @endphp

                    <div class="mt-8">
                        @if($isCancelled)
                            {{-- Cancelled / Refunded state --}}
                            <div class="flex items-center gap-4 rounded-2xl border-2 border-red-200 bg-red-50 p-6 dark:border-red-500/20 dark:bg-red-500/10">
                                <div class="flex h-14 w-14 flex-shrink-0 items-center justify-center rounded-full bg-red-100 dark:bg-red-500/20">
                                    <svg class="h-7 w-7 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">{!! $statusIcons[$order->status] !!}</svg>
                                </div>
                                <div>
                                    <p class="font-serif text-lg font-bold text-red-700 dark:text-red-400 capitalize">{{ $order->status_label }}</p>
                                    @if($trackingLookup->has($order->status))
                                        <p class="mt-1 text-sm text-red-600/70 dark:text-red-400/70">{{ $trackingLookup->get($order->status)->occurred_at->format('M j, Y \a\t g:i A') }}</p>
                                    @endif
                                </div>
                            </div>
                        @else
                            {{-- Active timeline --}}
                            <div class="relative">
                                @foreach($allStatuses as $i => $status)
                                    @php
                                        $isCompleted = $i <= $currentIndex;
                                        $isCurrent = $i === $currentIndex;
                                        $isLast = $i === count($allStatuses) - 1;
                                        $tracking = $trackingLookup->get($status);
                                    @endphp
                                    <div class="relative flex gap-5 {{ !$isLast ? 'pb-10' : '' }}">
                                        {{-- Line --}}
                                        @if(!$isLast)
                                            <div class="absolute left-[27px] top-[56px] h-[calc(100%-56px)] w-0.5 {{ $isCompleted && $i < $currentIndex ? 'bg-bunny-primary' : 'bg-bunny-border dark:bg-bunny-dark-border' }}"></div>
                                        @endif

                                        {{-- Icon --}}
                                        <div class="relative z-10 flex h-14 w-14 flex-shrink-0 items-center justify-center rounded-full border-2 transition-all duration-500
                                            {{ $isCompleted
                                                ? ($isCurrent
                                                    ? 'border-bunny-primary bg-bunny-primary shadow-lg shadow-bunny-primary/30'
                                                    : 'border-bunny-primary bg-bunny-primary/10 dark:bg-bunny-primary/20')
                                                : 'border-bunny-border bg-white dark:border-bunny-dark-border dark:bg-bunny-dark-card' }}">
                                            @if($isCurrent)
                                                <div class="absolute inset-0 animate-ping rounded-full bg-bunny-primary/20"></div>
                                            @endif
                                            <svg class="relative h-6 w-6 {{ $isCompleted ? ($isCurrent ? 'text-white' : 'text-bunny-primary') : 'text-bunny-muted dark:text-bunny-dark-muted' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">{!! $statusIcons[$status] !!}</svg>
                                        </div>

                                        {{-- Content --}}
                                        <div class="flex-1 pt-2">
                                            <div class="flex items-center gap-3">
                                                <h3 class="font-serif text-lg font-bold {{ $isCompleted ? 'text-bunny-text dark:text-bunny-dark-text' : 'text-bunny-muted dark:text-bunny-dark-muted' }}">{{ $statusLabels[$status] }}</h3>
                                                @if($isCurrent)
                                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-bunny-primary/10 px-3 py-1 text-[10px] font-extrabold uppercase tracking-wider text-bunny-primary">
                                                        <span class="h-1.5 w-1.5 rounded-full bg-bunny-primary animate-pulse"></span>
                                                        Current
                                                    </span>
                                                @endif
                                            </div>
                                            @if($tracking)
                                                <p class="mt-1 text-sm text-bunny-muted dark:text-bunny-dark-muted">
                                                    {{ app()->getLocale() === 'ar' ? $tracking->description_ar : $tracking->description_en }}
                                                </p>
                                                <p class="mt-0.5 text-xs text-bunny-muted/60 dark:text-bunny-dark-muted/60">{{ $tracking->occurred_at->format('M j, Y \a\t g:i A') }}</p>
                                            @elseif(!$isCompleted)
                                                <p class="mt-1 text-sm text-bunny-muted/50 dark:text-bunny-dark-muted/50 italic">Awaiting...</p>
                                            @endif

                                            {{-- Extra shipping info --}}
                                            @if($status === 'shipped' && $isCompleted && $order->tracking_number)
                                                <div class="mt-3 inline-flex items-center gap-2 rounded-xl bg-bunny-accent/60 px-4 py-2 text-xs font-bold dark:bg-bunny-dark-card">
                                                    <svg class="h-4 w-4 text-bunny-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" /></svg>
                                                    <span class="text-bunny-muted dark:text-bunny-dark-muted">Tracking:</span>
                                                    <span class="text-bunny-text dark:text-bunny-dark-text">{{ $order->tracking_number }}</span>
                                                    @if($order->shipping_company)
                                                        <span class="text-bunny-muted dark:text-bunny-dark-muted">· {{ $order->shipping_company }}</span>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Order Items --}}
                <div class="card-surface p-8">
                    <h2 class="font-serif text-xl font-semibold text-bunny-text dark:text-bunny-dark-text">Items ({{ $order->items->count() }})</h2>
                    <div class="mt-5 space-y-3">
                        @foreach($order->items as $item)
                            <div class="flex items-center gap-4 rounded-2xl border border-bunny-border p-4 transition-all hover:border-bunny-primary/30 dark:border-bunny-dark-border dark:hover:border-bunny-primary/30">
                                @if($item->product_image)
                                    <div class="img-zoom h-16 w-16 flex-shrink-0 overflow-hidden rounded-xl">
                                        <img src="{{ $item->product_image }}" alt="{{ $item->product_name_en }}" class="h-full w-full object-cover">
                                    </div>
                                @else
                                    <div class="flex h-16 w-16 flex-shrink-0 items-center justify-center rounded-xl bg-bunny-accent/50 dark:bg-bunny-dark-card">
                                        <svg class="h-6 w-6 text-bunny-muted dark:text-bunny-dark-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0 0 22.5 18.75V5.25A2.25 2.25 0 0 0 20.25 3H3.75A2.25 2.25 0 0 0 1.5 5.25v13.5A2.25 2.25 0 0 0 3.75 21Z" /></svg>
                                    </div>
                                @endif
                                <div class="flex-1 min-w-0">
                                    <p class="truncate font-semibold text-bunny-text dark:text-bunny-dark-text">{{ $item->product_name_en ?: $item->product?->display_name }}</p>
                                    <p class="mt-0.5 text-sm text-bunny-muted dark:text-bunny-dark-muted">{{ number_format((float) $item->unit_price, 2) }} {{ __('messages.currency') }} × {{ $item->quantity }}</p>
                                </div>
                                <span class="flex-shrink-0 font-serif text-lg font-bold text-bunny-text dark:text-bunny-dark-text">{{ number_format((float) ($item->subtotal ?: $item->unit_price * $item->quantity), 2) }} {{ __('messages.currency') }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Right Column — Summary --}}
            <div class="space-y-6">
                {{-- Price Summary --}}
                <div class="card-surface p-8">
                    <h2 class="font-serif text-xl font-semibold text-bunny-text dark:text-bunny-dark-text">Summary</h2>
                    <div class="mt-6 space-y-3 text-sm">
                        <div class="flex justify-between text-bunny-muted dark:text-bunny-dark-muted"><span>{{ __('messages.cart.subtotal') }}</span><span>{{ number_format((float) $order->subtotal, 2) }} {{ __('messages.currency') }}</span></div>
                        <div class="flex justify-between text-bunny-muted dark:text-bunny-dark-muted"><span>{{ __('messages.cart.shipping') }}</span><span>{{ number_format((float) $order->shipping_fee, 2) }} {{ __('messages.currency') }}</span></div>
                        @if($order->discount_amount > 0)
                            <div class="flex justify-between text-bunny-muted dark:text-bunny-dark-muted"><span>{{ __('messages.cart.discount') }}</span><span class="text-emerald-600 dark:text-emerald-400">-{{ number_format((float) $order->discount_amount, 2) }} {{ __('messages.currency') }}</span></div>
                        @endif
                        @if($order->coupon_code)
                            <div class="flex justify-between text-bunny-muted dark:text-bunny-dark-muted"><span>Coupon</span><span class="font-mono text-xs font-bold text-bunny-primary">{{ $order->coupon_code }}</span></div>
                        @endif
                        <div class="divider"></div>
                        <div class="flex justify-between pt-1 font-serif text-xl font-bold text-bunny-text dark:text-bunny-dark-text"><span>{{ __('messages.cart.total') }}</span><span>{{ number_format((float) $order->total_amount, 2) }} {{ __('messages.currency') }}</span></div>
                    </div>
                </div>

                {{-- Shipping Address --}}
                @if($order->address)
                    <div class="card-surface p-8">
                        <h2 class="font-serif text-xl font-semibold text-bunny-text dark:text-bunny-dark-text">Shipping Address</h2>
                        <div class="mt-4 space-y-1.5 text-sm text-bunny-muted dark:text-bunny-dark-muted">
                            <p class="font-semibold text-bunny-text dark:text-bunny-dark-text">{{ $order->address->full_name }}</p>
                            <p>{{ $order->address->phone }}</p>
                            <p>{{ $order->address->street }}</p>
                            <p>{{ $order->address->city }}, {{ $order->address->governorate }}</p>
                            <p>{{ $order->address->country }}</p>
                        </div>
                    </div>
                @endif

                {{-- Order Notes --}}
                @if($order->notes)
                    <div class="card-surface p-8">
                        <h2 class="font-serif text-xl font-semibold text-bunny-text dark:text-bunny-dark-text">Notes</h2>
                        <p class="mt-3 text-sm text-bunny-muted dark:text-bunny-dark-muted">{{ $order->notes }}</p>
                    </div>
                @endif

                {{-- Actions --}}
                <div class="card-surface p-8">
                    <div class="grid gap-3">
                        <a href="{{ route('orders.index') }}" class="pill-btn-outline w-full text-center text-sm">All Orders</a>
                        <a href="{{ route('products.index') }}" class="pill-btn-primary w-full text-center text-sm">Continue Shopping</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
