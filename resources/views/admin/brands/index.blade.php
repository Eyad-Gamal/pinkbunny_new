@extends('layouts.admin')
@section('title', 'Brands')
@section('page-title', 'Brands')

@section('content')
    <div class="grid-3-1">
        {{-- Brands List --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title">All Brands ({{ $brands->total() }})</span>
            </div>
            <table class="data-table">
                <thead><tr>
                    <th>Brand</th>
                    <th>Products</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr></thead>
                <tbody>
                    @foreach($brands as $brand)
                        <tr>
                            <td>
                                <div class="table-product">
                                    @if($brand->logo)
                                        <img src="{{ $brand->logo }}" alt="" style="object-fit: contain; background: rgba(255,255,255,0.05); padding: 4px;">
                                    @else
                                        <div class="table-product-placeholder">✨</div>
                                    @endif
                                    <strong class="text-primary">{{ $brand->name }}</strong>
                                </div>
                            </td>
                            <td style="color: var(--text-secondary);">{{ $brand->products_count }}</td>
                            <td><span class="badge {{ $brand->is_active ? 'badge-success' : 'badge-danger' }}">{{ $brand->is_active ? 'Active' : 'Inactive' }}</span></td>
                            <td>
                                <div class="actions-cell">
                                    <a href="#" class="link-action">Edit</a>
                                    <form method="POST" action="{{ route('admin.brands.destroy', $brand->id) }}" class="inline" onsubmit="return confirm('Delete this brand?')">@csrf @method('DELETE')
                                        <button class="link-action danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div style="padding: 16px;">{{ $brands->links() }}</div>
        </div>

        {{-- Add Brand --}}
        <div class="card">
            <div class="card-header"><span class="card-title">Add Brand</span></div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.brands.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Name *</label>
                        <input type="text" name="name" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Description (EN)</label>
                        <textarea name="description_en" class="form-input" rows="2"></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Description (AR)</label>
                        <textarea name="description_ar" class="form-input" rows="2" dir="rtl"></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Logo</label>
                        <input type="file" name="logo" class="form-input" accept="image/*">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Sort Order</label>
                        <input type="number" name="sort_order" value="0" class="form-input">
                    </div>
                    <input type="hidden" name="is_active" value="1">
                    <button type="submit" class="btn btn-primary w-full">Create Brand</button>
                </form>
            </div>
        </div>
    </div>
@endsection
