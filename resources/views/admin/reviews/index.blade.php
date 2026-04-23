@extends('layouts.admin')
@section('title', 'Reviews')
@section('page-title', 'Reviews')

@section('content')
    <div class="tab-pills">
        <a href="{{ route('admin.reviews.index') }}" class="tab-pill {{ !request()->has('approved') ? 'active' : '' }}">All</a>
        <a href="{{ route('admin.reviews.index', ['approved' => '0']) }}" class="tab-pill {{ request('approved') === '0' ? 'active' : '' }}">Pending</a>
        <a href="{{ route('admin.reviews.index', ['approved' => '1']) }}" class="tab-pill {{ request('approved') === '1' ? 'active' : '' }}">Approved</a>
    </div>

    <div class="card">
        <div style="overflow-x: auto;">
            <table class="data-table">
                <thead><tr>
                    <th>Product</th>
                    <th>Customer</th>
                    <th>Rating</th>
                    <th>Comment</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr></thead>
                <tbody>
                    @forelse($reviews as $review)
                        <tr>
                            <td><strong class="text-primary">{{ $review->product->name_en }}</strong></td>
                            <td style="color: var(--text-secondary);">{{ $review->user->name }}</td>
                            <td>
                                <span class="stars">{{ str_repeat('★', $review->rating) }}</span><span class="stars-empty">{{ str_repeat('★', 5 - $review->rating) }}</span>
                            </td>
                            <td><span class="truncate" style="display: block; color: var(--text-muted); font-size: 12px;">{{ $review->comment ?? '—' }}</span></td>
                            <td><span class="badge {{ $review->is_approved ? 'badge-success' : 'badge-warning' }}">{{ $review->is_approved ? 'Approved' : 'Pending' }}</span></td>
                            <td>
                                <div class="actions-cell">
                                    @unless($review->is_approved)
                                        <form method="POST" action="{{ route('admin.reviews.approve', $review->id) }}" class="inline">@csrf
                                            <button class="link-action" style="color: var(--success);">Approve</button>
                                        </form>
                                    @endunless
                                    <form method="POST" action="{{ route('admin.reviews.destroy', $review->id) }}" class="inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')
                                        <button class="link-action danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center" style="padding: 48px; color: var(--text-muted);">No reviews found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="pagination">{{ $reviews->links() }}</div>
@endsection
