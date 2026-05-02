@extends('layouts.admin')
@section('title', 'Order #' . $order->order_number)
@section('page-title', 'Order #' . $order->order_number)

@section('content')
    <div style="margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
        <a href="{{ route('admin.orders.index') }}" class="btn btn-ghost" style="padding-left: 0;">← Back to Orders</a>
        <div style="display: flex; gap: 8px;">
            <a href="{{ route('admin.orders.invoice', $order->id) }}" target="_blank" class="btn btn-outline btn-sm">🖨️ Print Invoice</a>
        </div>
    </div>

    <div class="grid-3-1">
        {{-- Left Column --}}
        <div>
            {{-- Order Summary Header --}}
            <div class="card mb-3">
                <div class="card-body" style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 16px;">
                    <div>
                        <span class="font-mono" style="font-size: 12px; color: var(--accent);">{{ $order->order_number }}</span>
                        <h2 style="font-size: 22px; font-weight: 800; color: var(--text-primary); margin: 6px 0 4px;">{{ $order->user->name }}</h2>
                        <span style="font-size: 12px; color: var(--text-muted);">{{ $order->created_at->format('F j, Y \a\t g:i A') }}</span>
                    </div>
                    <div style="text-align: right;">
                        @php $bc = match($order->status) { 'pending' => 'badge-warning', 'confirmed' => 'badge-info', 'processing' => 'badge-purple', 'shipped' => 'badge-indigo', 'delivered' => 'badge-success', 'cancelled' => 'badge-danger', 'refunded' => 'badge-gray', default => 'badge-gray' }; @endphp
                        <span class="badge {{ $bc }}" style="font-size: 12px; padding: 5px 14px;">{{ ucfirst($order->status) }}</span>
                        <div style="margin-top: 8px; font-size: 24px; font-weight: 800; color: var(--accent);">{{ number_format($order->total_amount, 2) }} EGP</div>
                    </div>
                </div>
            </div>

            {{-- Items --}}
            <div class="card mb-3">
                <div class="card-header">
                    <span class="card-title">Items ({{ $order->items->count() }})</span>
                </div>
                <div class="card-body" style="padding: 0;">
                    @foreach($order->items as $item)
                        <div style="display: flex; align-items: center; gap: 16px; padding: 16px 22px; border-bottom: 1px solid var(--border); {{ $loop->last ? 'border-bottom: none;' : '' }}">
                            {{-- Product Image --}}
                            @if($item->product_image)
                                <img src="{{ str_starts_with($item->product_image, 'http') ? $item->product_image : asset('storage/' . $item->product_image) }}" alt="{{ $item->product_name_en }}" style="width: 60px; height: 60px; border-radius: 12px; object-fit: cover; flex-shrink: 0; background: var(--bg-elevated);">
                            @else
                                <div style="width: 60px; height: 60px; border-radius: 12px; background: var(--bg-elevated); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                    <span style="font-size: 20px; color: var(--text-muted);">📦</span>
                                </div>
                            @endif

                            {{-- Product Info --}}
                            <div style="flex: 1; min-width: 0;">
                                <strong style="font-size: 14px; color: var(--text-primary); display: block;">{{ $item->product_name_en }}</strong>
                                @if($item->product_name_ar)
                                    <span style="font-size: 12px; color: var(--text-muted); display: block;">{{ $item->product_name_ar }}</span>
                                @endif
                                <span style="font-size: 12px; color: var(--text-secondary); margin-top: 4px; display: block;">
                                    {{ number_format($item->unit_price, 2) }} EGP × {{ $item->quantity }}
                                </span>
                            </div>

                            {{-- Subtotal --}}
                            <div style="text-align: right; flex-shrink: 0;">
                                <strong style="font-size: 15px; color: var(--text-primary);">{{ number_format($item->subtotal ?: $item->unit_price * $item->quantity, 2) }} EGP</strong>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Totals --}}
                <div class="card-body" style="border-top: 1px solid var(--border); background: var(--bg-elevated);">
                    <div class="detail-row"><span class="detail-label">Subtotal</span><span class="detail-value">{{ number_format($order->subtotal, 2) }} EGP</span></div>
                    <div class="detail-row"><span class="detail-label">Shipping</span><span class="detail-value">{{ number_format($order->shipping_fee, 2) }} EGP</span></div>
                    @if($order->discount_amount > 0)
                        <div class="detail-row"><span class="detail-label">Discount</span><span class="detail-value text-success">−{{ number_format($order->discount_amount, 2) }} EGP</span></div>
                    @endif
                    @if($order->coupon_code)
                        <div class="detail-row"><span class="detail-label">Coupon</span><span class="detail-value text-accent font-mono">{{ $order->coupon_code }}</span></div>
                    @endif
                    <div style="border-top: 1px dashed var(--border-strong); margin-top: 10px; padding-top: 14px; display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 16px; font-weight: 700; color: var(--text-primary);">Total</span>
                        <span style="font-size: 22px; font-weight: 800; color: var(--accent);">{{ number_format($order->total_amount, 2) }} EGP</span>
                    </div>
                </div>
            </div>

            {{-- Tracking --}}
            <div class="card">
                <div class="card-header"><span class="card-title">Order Tracking</span></div>
                <div class="card-body">
                    @forelse($order->tracking as $track)
                        <div class="timeline-item">
                            <div class="timeline-dot"></div>
                            <div>
                                <div class="timeline-text">{{ $track->description_en }}</div>
                                <div class="timeline-meta">{{ $track->occurred_at->format('M d, Y H:i') }}@if($track->updater) · {{ $track->updater->name }}@endif</div>
                            </div>
                        </div>
                    @empty
                        <p style="color: var(--text-muted); font-size: 13px;">No tracking events</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Right Column --}}
        <div>
            {{-- Customer --}}
            <div class="card mb-3">
                <div class="card-header"><span class="card-title">Customer</span></div>
                <div class="card-body">
                    <div class="flex items-center gap-1" style="margin-bottom: 16px;">
                        @if($order->user->avatar)
                            <img src="{{ str_starts_with($order->user->avatar, 'http') ? $order->user->avatar : asset('storage/' . $order->user->avatar) }}" class="user-avatar" style="width: 40px; height: 40px; object-fit: cover;">
                        @else
                            <div class="user-avatar" style="width: 40px; height: 40px; font-size: 14px;">{{ substr($order->user->name, 0, 1) }}</div>
                        @endif
                        <div>
                            <strong class="text-primary" style="font-size: 14px;">{{ $order->user->name }}</strong>
                            <br><span style="font-size: 12px; color: var(--text-muted);">{{ $order->user->email }}</span>
                        </div>
                    </div>
                    <div class="detail-row"><span class="detail-label">Phone</span><span class="detail-value">{{ $order->user->phone ?? '—' }}</span></div>
                    <div class="detail-row"><span class="detail-label">Orders</span><span class="detail-value">{{ $order->user->orders()->count() }}</span></div>
                </div>
            </div>

            {{-- Shipping Address --}}
            @if($order->address)
            <div class="card mb-3">
                <div class="card-header"><span class="card-title">Shipping Address</span></div>
                <div class="card-body" style="font-size: 13px; line-height: 2;">
                    <strong class="text-primary">{{ $order->address->full_name }}</strong><br>
                    <span style="color: var(--accent);">📞 {{ $order->address->phone }}</span><br>
                    <span style="color: var(--text-secondary);">{{ $order->address->street }}</span><br>
                    <span style="color: var(--text-secondary);">{{ $order->address->city }}, {{ $order->address->governorate }}</span><br>
                    <span style="color: var(--text-muted);">{{ $order->address->country }}</span>
                </div>
            </div>
            @endif

            {{-- Update Status --}}
            <div class="card mb-3">
                <div class="card-header"><span class="card-title">Update Status</span></div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.orders.update-status', $order->id) }}">
                        @csrf @method('PUT')
                        <div class="form-group">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-input">
                                @foreach(['pending','confirmed','processing','shipped','delivered','cancelled','refunded'] as $s)
                                    <option value="{{ $s }}" {{ $order->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tracking Number</label>
                            <input type="text" name="tracking_number" value="{{ $order->tracking_number }}" class="form-input" placeholder="e.g. EG1234567890">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Shipping Company</label>
                            <input type="text" name="shipping_company" value="{{ $order->shipping_company }}" class="form-input">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Admin Notes</label>
                            <textarea name="admin_notes" class="form-input" rows="2">{{ $order->admin_notes }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-full">Update Order</button>
                    </form>
                </div>
            </div>

            {{-- Payment Info --}}
            <div class="card">
                <div class="card-header"><span class="card-title">Payment Info</span></div>
                <div class="card-body">
                    <div class="detail-row"><span class="detail-label">Method</span><span class="detail-value">{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</span></div>
                    <div class="detail-row"><span class="detail-label">Status</span><span class="detail-value">{{ ucfirst($order->payment_status) }}</span></div>
                    @if($order->tracking_number)
                        <div class="detail-row"><span class="detail-label">Tracking #</span><span class="detail-value font-mono text-accent">{{ $order->tracking_number }}</span></div>
                    @endif
                    @if($order->shipping_company)
                        <div class="detail-row"><span class="detail-label">Carrier</span><span class="detail-value">{{ $order->shipping_company }}</span></div>
                    @endif
                    @if($order->notes)
                        <div style="margin-top: 14px; padding: 12px; background: var(--bg-elevated); border-radius: 10px;">
                            <span class="form-label">Customer Notes</span>
                            <p style="font-size: 13px; color: var(--text-secondary); margin-top: 4px;">{{ $order->notes }}</p>
                        </div>
                    @endif
                    @if($order->admin_notes)
                        <div style="margin-top: 10px; padding: 12px; background: rgba(244,114,182,0.05); border-radius: 10px; border: 1px solid rgba(244,114,182,0.1);">
                            <span class="form-label" style="color: var(--accent);">Admin Notes</span>
                            <p style="font-size: 13px; color: var(--text-secondary); margin-top: 4px;">{{ $order->admin_notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
