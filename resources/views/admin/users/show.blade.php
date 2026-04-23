@extends('layouts.admin')
@section('title', $user->name)
@section('page-title', $user->name)

@section('content')
    <div class="grid-3-1">
        {{-- Sidebar --}}
        <div>
            {{-- Orders --}}
            <div class="card mb-3">
                <div class="card-header">
                    <span class="card-title">Orders ({{ $user->orders->count() }})</span>
                </div>
                <div class="card-body">
                    @forelse($user->orders as $order)
                        <div class="status-item">
                            <div>
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="link-action" style="font-size: 13px;">{{ $order->order_number }}</a>
                                @php $bc = match($order->status) { 'pending' => 'badge-warning', 'confirmed' => 'badge-info', 'processing' => 'badge-purple', 'shipped' => 'badge-indigo', 'delivered' => 'badge-success', 'cancelled' => 'badge-danger', default => 'badge-gray' }; @endphp
                                <span class="badge {{ $bc }}" style="margin-left: 8px;">{{ ucfirst($order->status) }}</span>
                            </div>
                            <strong class="text-primary">{{ number_format($order->total_amount, 0) }} EGP</strong>
                        </div>
                    @empty
                        <p style="color: var(--text-muted); font-size: 13px;">No orders</p>
                    @endforelse
                </div>
            </div>

            {{-- Addresses --}}
            <div class="card">
                <div class="card-header"><span class="card-title">Addresses ({{ $user->addresses->count() }})</span></div>
                <div class="card-body">
                    @forelse($user->addresses as $addr)
                        <div style="padding: 10px 0; border-bottom: 1px solid var(--border);">
                            <strong class="text-primary" style="font-size: 13px;">{{ $addr->label }} {{ $addr->is_default ? '⭐' : '' }}</strong>
                            <br><span style="font-size: 12px; color: var(--text-muted);">{{ $addr->street }}, {{ $addr->city }}, {{ $addr->governorate }}</span>
                        </div>
                    @empty
                        <p style="color: var(--text-muted); font-size: 13px;">No addresses</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Profile --}}
        <div class="card">
            <div class="card-header"><span class="card-title">Profile</span></div>
            <div class="card-body" style="text-align: center; padding: 32px;">
                <div class="user-avatar" style="width: 64px; height: 64px; font-size: 24px; border-radius: 16px; margin: 0 auto 16px;">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                <h3 class="text-primary" style="font-size: 18px; font-weight: 700;">{{ $user->name }}</h3>
                @if($user->hasRole('admin'))<span class="badge badge-purple">Admin</span>@else<span class="badge badge-info">Customer</span>@endif
            </div>
            <div class="card-body" style="border-top: 1px solid var(--border);">
                <div class="detail-row"><span class="detail-label">Email</span><span class="detail-value">{{ $user->email ?? '—' }}</span></div>
                <div class="detail-row"><span class="detail-label">Phone</span><span class="detail-value">{{ $user->phone ?? '—' }}</span></div>
                <div class="detail-row"><span class="detail-label">Joined</span><span class="detail-value">{{ $user->created_at->format('M d, Y') }}</span></div>
                <div class="detail-row"><span class="detail-label">Last Login</span><span class="detail-value">{{ $user->last_login_at?->format('M d, Y H:i') ?? '—' }}</span></div>
                <div class="detail-row"><span class="detail-label">Points</span><span class="detail-value text-accent">{{ $user->points_balance }}</span></div>
            </div>
        </div>
    </div>
@endsection
