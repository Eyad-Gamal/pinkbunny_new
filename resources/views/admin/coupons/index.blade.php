@extends('layouts.admin')
@section('title', 'Coupons')
@section('page-title', 'Coupons')

@section('content')
    <div class="grid-3-1">
        <div class="card">
            <div class="card-header">
                <span class="card-title">All Coupons ({{ $coupons->total() }})</span>
            </div>
            <table class="data-table">
                <thead><tr>
                    <th>Code</th>
                    <th>Type</th>
                    <th>Value</th>
                    <th>Used</th>
                    <th>Status</th>
                    <th>Expires</th>
                    <th>Actions</th>
                </tr></thead>
                <tbody>
                    @foreach($coupons as $coupon)
                        <tr>
                            <td><strong class="text-accent font-mono" style="font-size: 13px;">{{ $coupon->code }}</strong></td>
                            <td style="color: var(--text-secondary); text-transform: capitalize;">{{ $coupon->type }}</td>
                            <td class="text-primary">{!! $coupon->type === 'percentage' ? $coupon->value . '%' : number_format($coupon->value, 0) . ' EGP' !!}</td>
                            <td style="color: var(--text-secondary);">{{ $coupon->usages_count }}{{ $coupon->max_uses ? '/' . $coupon->max_uses : '' }}</td>
                            <td><span class="badge {{ $coupon->isValid() ? 'badge-success' : 'badge-danger' }}">{{ $coupon->isValid() ? 'Active' : 'Inactive' }}</span></td>
                            <td style="color: var(--text-muted); font-size: 12px;">{{ $coupon->expires_at?->format('M d, Y') ?? '∞' }}</td>
                            <td>
                                <div class="actions-cell">
                                    <a href="{{ route('admin.coupons.usages', $coupon->id) }}" class="link-action">Usages</a>
                                    <form method="POST" action="{{ route('admin.coupons.destroy', $coupon->id) }}" class="inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')
                                        <button class="link-action danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div style="padding: 16px;">{{ $coupons->links() }}</div>
        </div>

        <div class="card">
            <div class="card-header"><span class="card-title">Create Coupon</span></div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.coupons.store') }}">
                    @csrf
                    <div class="form-group"><label class="form-label">Code (blank = auto)</label><input type="text" name="code" class="form-input font-mono" placeholder="AUTO"></div>
                    <div class="form-group"><label class="form-label">Description</label><input type="text" name="description" class="form-input"></div>
                    <div class="form-row">
                        <div class="form-group"><label class="form-label">Type</label><select name="type" class="form-input"><option value="percentage">Percentage</option><option value="fixed">Fixed</option></select></div>
                        <div class="form-group"><label class="form-label">Value *</label><input type="number" name="value" step="0.01" class="form-input" required></div>
                    </div>
                    <div class="form-row">
                        <div class="form-group"><label class="form-label">Min Order</label><input type="number" name="min_order_amount" value="0" class="form-input"></div>
                        <div class="form-group"><label class="form-label">Max Discount</label><input type="number" name="max_discount_amount" class="form-input"></div>
                    </div>
                    <div class="form-row">
                        <div class="form-group"><label class="form-label">Max Uses</label><input type="number" name="max_uses" class="form-input" placeholder="∞"></div>
                        <div class="form-group"><label class="form-label">Per User</label><input type="number" name="max_uses_per_user" value="1" class="form-input" required></div>
                    </div>
                    <div class="form-group"><label class="form-label">Expires At</label><input type="datetime-local" name="expires_at" class="form-input"></div>
                    <input type="hidden" name="is_active" value="1">
                    <button type="submit" class="btn btn-primary w-full">Create Coupon</button>
                </form>
            </div>
        </div>
    </div>
@endsection
