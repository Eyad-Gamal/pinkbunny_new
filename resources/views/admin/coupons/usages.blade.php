@extends('layouts.admin')
@section('title', 'Coupon Usages — ' . $coupon->code)
@section('page-title', 'Usages: ' . $coupon->code)

@section('content')
    <div class="card" style="max-width: 800px;">
        <div class="card-header">
            <span class="card-title"><span class="text-accent font-mono">{{ $coupon->code }}</span> — Usage History</span>
            <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline btn-sm">← Back</a>
        </div>
        <table class="data-table">
            <thead><tr>
                <th>User</th>
                <th>Order</th>
                <th>Discount</th>
                <th>Date</th>
            </tr></thead>
            <tbody>
                @forelse($usages as $u)
                    <tr>
                        <td><strong class="text-primary">{{ $u->user->name }}</strong></td>
                        <td><a href="{{ route('admin.orders.show', $u->order_id) }}" class="link-action">{{ $u->order?->order_number }}</a></td>
                        <td class="text-success">{{ number_format($u->discount_applied, 0) }} EGP</td>
                        <td style="color: var(--text-muted); font-size: 12px;">{{ $u->created_at->format('M d, Y H:i') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center" style="padding: 48px; color: var(--text-muted);">No usages yet</td></tr>
                @endforelse
            </tbody>
        </table>
        <div style="padding: 16px;">{{ $usages->links() }}</div>
    </div>
@endsection
