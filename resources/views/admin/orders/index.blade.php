@extends('layouts.admin')
@section('title', 'Orders')
@section('page-title', 'Orders')

@section('content')
    {{-- Status Tabs --}}
    <div class="tab-pills">
        <a href="{{ route('admin.orders.index') }}" class="tab-pill {{ !request('status') ? 'active' : '' }}">All ({{ $statusCounts->sum() }})</a>
        @foreach(['pending','confirmed','processing','shipped','delivered','cancelled'] as $s)
            <a href="{{ route('admin.orders.index', ['status' => $s]) }}" class="tab-pill {{ request('status') === $s ? 'active' : '' }}">
                {{ ucfirst($s) }} ({{ $statusCounts[$s] ?? 0 }})
            </a>
        @endforeach
    </div>

    <div class="toolbar">
        <div class="toolbar-left">
            <form method="GET" style="display: flex; gap: 8px; flex-wrap: wrap;">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Order # or customer..." class="form-input toolbar-search">
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-input" style="width: 150px;">
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-input" style="width: 150px;">
                @if(request('status'))<input type="hidden" name="status" value="{{ request('status') }}">@endif
                <button type="submit" class="btn btn-outline btn-sm">Search</button>
            </form>
        </div>
        <a href="{{ route('admin.orders.export', request()->query()) }}" class="btn btn-outline btn-sm">📥 Export Excel</a>
    </div>

    <div class="card">
        <div style="overflow-x: auto;">
            <table class="data-table">
                <thead><tr>
                    <th>Order</th>
                    <th>Customer</th>
                    <th>Items</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Payment</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr></thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td><strong class="text-primary font-mono">{{ $order->order_number }}</strong></td>
                            <td>
                                <strong class="text-primary" style="font-size: 13px;">{{ $order->user->name }}</strong>
                                <br><span style="font-size: 11px; color: var(--text-muted);">{{ $order->user->phone }}</span>
                            </td>
                            <td style="color: var(--text-secondary);">{{ $order->items->count() }}</td>
                            <td><strong class="text-primary">{{ number_format($order->total_amount, 0) }}</strong></td>
                            <td>
                                @php $bc = match($order->status) { 'pending' => 'badge-warning', 'confirmed' => 'badge-info', 'processing' => 'badge-purple', 'shipped' => 'badge-indigo', 'delivered' => 'badge-success', 'cancelled' => 'badge-danger', default => 'badge-gray' }; @endphp
                                <span class="badge {{ $bc }}">{{ ucfirst($order->status) }}</span>
                            </td>
                            <td style="color: var(--text-muted); font-size: 12px;">{{ str_replace('_', ' ', $order->payment_method) }}</td>
                            <td style="color: var(--text-muted); font-size: 12px;">{{ $order->created_at->format('M d, H:i') }}</td>
                            <td><a href="{{ route('admin.orders.show', $order->id) }}" class="link-action">View →</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center" style="padding: 48px; color: var(--text-muted);">No orders found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="pagination">{{ $orders->links() }}</div>
@endsection
