@extends('layouts.admin')
@section('title', 'Categories')
@section('page-title', 'Categories')

@section('content')
    <div class="grid-3-1">
        <div class="card">
            <div class="card-header">
                <span class="card-title">All Categories ({{ $categories->total() }})</span>
                <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm">+ Add</a>
            </div>
            <table class="data-table">
                <thead><tr>
                    <th>Category</th>
                    <th>Products</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr></thead>
                <tbody>
                    @foreach($categories as $cat)
                        <tr>
                            <td>
                                <div class="flex items-center gap-1">
                                    <span style="font-size: 18px;">{{ $cat->icon }}</span>
                                    <div>
                                        <strong class="text-primary">{{ $cat->name_en }}</strong>
                                        <br><span style="font-size: 11px; color: var(--text-muted);">{{ $cat->name_ar }}</span>
                                    </div>
                                </div>
                            </td>
                            <td style="color: var(--text-secondary);">{{ $cat->products_count }}</td>
                            <td><span class="badge {{ $cat->is_active ? 'badge-success' : 'badge-danger' }}">{{ $cat->is_active ? 'Active' : 'Inactive' }}</span></td>
                            <td>
                                <div class="actions-cell">
                                    <a href="{{ route('admin.categories.edit', $cat->id) }}" class="link-action">Edit</a>
                                    <form method="POST" action="{{ route('admin.categories.destroy', $cat->id) }}" class="inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')
                                        <button class="link-action danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div style="padding: 16px;">{{ $categories->links() }}</div>
        </div>

        <div class="card">
            <div class="card-header"><span class="card-title">Quick Add</span></div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.categories.store') }}">
                    @csrf
                    <div class="form-group"><label class="form-label">Name (EN) *</label><input type="text" name="name_en" class="form-input" required></div>
                    <div class="form-group"><label class="form-label">Name (AR) *</label><input type="text" name="name_ar" class="form-input" dir="rtl" required></div>
                    <div class="form-group"><label class="form-label">Icon (emoji)</label><input type="text" name="icon" class="form-input" placeholder="💄"></div>
                    <div class="form-group"><label class="form-label">Sort Order</label><input type="number" name="sort_order" value="0" class="form-input"></div>
                    <input type="hidden" name="is_active" value="1">
                    <button type="submit" class="btn btn-primary w-full">Create</button>
                </form>
            </div>
        </div>
    </div>
@endsection
