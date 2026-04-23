@extends('layouts.admin')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    {{-- Primary Stats --}}
    <div class="stats-grid">
        <div class="stat-card pink">
            <div class="stat-icon pink">💰</div>
            <div class="stat-label">Revenue Today</div>
            <div class="stat-value">{{ number_format($stats['revenue_today'] ?? 0, 0) }}</div>
            <div class="stat-sub green">This month: {{ number_format($stats['revenue_month'] ?? 0, 0) }} EGP</div>
        </div>
        <div class="stat-card blue">
            <div class="stat-icon blue">📦</div>
            <div class="stat-label">Orders Today</div>
            <div class="stat-value">{{ $stats['orders_today'] ?? 0 }}</div>
            <div class="stat-sub yellow">{{ $stats['pending_orders'] ?? 0 }} pending</div>
        </div>
        <div class="stat-card green">
            <div class="stat-icon green">👁️</div>
            <div class="stat-label">Visitors Today</div>
            <div class="stat-value">{{ $stats['visitors_today'] ?? 0 }}</div>
            <div class="stat-sub blue">{{ $stats['pageviews_today'] ?? 0 }} page views</div>
        </div>
        <div class="stat-card purple">
            <div class="stat-icon purple">👥</div>
            <div class="stat-label">Total Users</div>
            <div class="stat-value">{{ $stats['total_users'] ?? 0 }}</div>
            <div class="stat-sub purple">{{ $stats['new_users_today'] ?? 0 }} new today</div>
        </div>
    </div>

    {{-- Secondary Stats --}}
    <div class="stats-grid">
        <div class="stat-card green">
            <div class="stat-label">Total Revenue</div>
            <div class="stat-value text-success">{{ number_format($stats['revenue_total'] ?? 0, 0) }}</div>
            <div class="stat-sub" style="color: var(--text-muted)">EGP lifetime</div>
        </div>
        <div class="stat-card blue">
            <div class="stat-label">Active Products</div>
            <div class="stat-value">{{ $stats['total_products'] ?? 0 }}</div>
            <div class="stat-sub blue">in catalogue</div>
        </div>
        <div class="stat-card yellow">
            <div class="stat-label">Low Stock</div>
            <div class="stat-value text-warning">{{ $stats['low_stock_products'] ?? 0 }}</div>
            <div class="stat-sub yellow">need restock</div>
        </div>
        <div class="stat-card red">
            <div class="stat-label">Out of Stock</div>
            <div class="stat-value text-danger">{{ $stats['out_of_stock'] ?? 0 }}</div>
            <div class="stat-sub" style="color: var(--danger)">unavailable</div>
        </div>
    </div>

    {{-- Two Column Row --}}
    <div class="grid-2 mb-3">
        {{-- Orders by Status --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title">Orders by Status</span>
            </div>
            <div class="card-body">
                @forelse($stats['orders_by_status'] ?? [] as $status => $count)
                    @php
                        $badgeClass = match($status) {
                            'pending'    => 'badge-warning',
                            'confirmed'  => 'badge-info',
                            'processing' => 'badge-purple',
                            'shipped'    => 'badge-indigo',
                            'delivered'  => 'badge-success',
                            'cancelled'  => 'badge-danger',
                            'refunded'   => 'badge-gray',
                            default      => 'badge-gray',
                        };
                    @endphp
                    <div class="status-item">
                        <span class="badge {{ $badgeClass }}">{{ ucfirst($status) }}</span>
                        <span class="status-count">{{ $count }}</span>
                    </div>
                @empty
                    <p style="color: var(--text-muted); font-size: 13px;">No orders yet</p>
                @endforelse
            </div>
        </div>

        {{-- Device Breakdown --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title">Device Breakdown</span>
                <span style="font-size: 11px; color: var(--text-muted);">Last 30 days</span>
            </div>
            <div class="card-body">
                @forelse($stats['device_breakdown'] ?? [] as $device => $count)
                    <div class="status-item">
                        <span style="font-size: 13px; color: var(--text-secondary); text-transform: capitalize;">
                            @if($device === 'desktop') 🖥️
                            @elseif($device === 'mobile') 📱
                            @elseif($device === 'tablet') 📋
                            @else ❓
                            @endif
                            {{ $device ?: 'Unknown' }}
                        </span>
                        <span class="status-count">{{ number_format($count) }}</span>
                    </div>
                @empty
                    <p style="color: var(--text-muted); font-size: 13px;">No visitor data yet</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Top Products --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">Top Selling Products</span>
            <a href="{{ route('admin.products.index') }}" class="link-action">View all →</a>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th class="text-right">Total Sold</th>
                </tr>
            </thead>
            <tbody>
                @forelse($stats['top_products'] ?? [] as $product)
                    <tr>
                        <td><strong style="color: var(--text-primary);">{{ $product['name_en'] }}</strong></td>
                        <td style="color: var(--text-secondary);">{{ number_format($product['price'], 0) }} EGP</td>
                        <td class="text-right"><strong class="text-accent">{{ $product['total_sold'] }}</strong></td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="text-center" style="color: var(--text-muted); padding: 32px;">No sales data yet</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
