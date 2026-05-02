@extends('layouts.admin')
@section('title', 'Products')
@section('page-title', 'Products')

@section('content')
    <div class="toolbar">
        <div class="toolbar-left">
            <form method="GET" style="display: flex; gap: 8px; flex-wrap: wrap;">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products..." class="form-input toolbar-search">
                <select name="status" class="form-input toolbar-filter">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="low_stock" {{ request('status') === 'low_stock' ? 'selected' : '' }}>Low Stock</option>
                    <option value="out_of_stock" {{ request('status') === 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                </select>
                <button type="submit" class="btn btn-outline btn-sm">Filter</button>
            </form>
        </div>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">+ Add Product</a>
    </div>

    <div class="card">
        <div style="overflow-x: auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Brand</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Sold</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr class="{{ $product->trashed() ? 'opacity-50' : '' }}">
                            <td>
                                <div class="table-product">
                                    @if($product->first_image)
                                        <img src="{{ str_starts_with($product->first_image, 'http') ? $product->first_image : asset('storage/' . $product->first_image) }}" alt="">
                                    @else
                                        <div class="table-product-placeholder">💄</div>
                                    @endif
                                    <div class="table-product-info">
                                        <strong>{{ $product->name_en }}</strong>
                                        <span>{{ $product->category?->name_en }}</span>
                                    </div>
                                </div>
                            </td>
                            <td style="color: var(--text-secondary);">{{ $product->brand?->name }}</td>
                            <td>
                                <span class="text-primary">{{ number_format($product->price, 0) }}</span>
                                @if($product->sale_price)
                                    <span class="price-sale">{{ number_format($product->sale_price, 0) }}</span>
                                @endif
                            </td>
                            <td>
                                @if($product->stock_quantity == 0)
                                    <span class="badge badge-danger">Out</span>
                                @elseif($product->is_low_stock)
                                    <span class="badge badge-warning">{{ $product->stock_quantity }}</span>
                                @else
                                    <span style="color: var(--text-secondary);">{{ $product->stock_quantity }}</span>
                                @endif
                            </td>
                            <td style="color: var(--text-secondary);">{{ $product->total_sold }}</td>
                            <td>
                                @if($product->trashed())
                                    <span class="badge badge-gray">Deleted</span>
                                @elseif($product->is_active)
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-danger">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="actions-cell">
                                    @if($product->trashed())
                                        <form method="POST" action="{{ route('admin.products.restore', $product->id) }}" class="inline">@csrf
                                            <button class="link-action">Restore</button>
                                        </form>
                                    @else
                                        <a href="{{ route('admin.products.edit', $product->id) }}" class="link-action">Edit</a>
                                        <form method="POST" action="{{ route('admin.products.destroy', $product->id) }}" class="inline" onsubmit="return confirm('Delete this product?')">@csrf @method('DELETE')
                                            <button class="link-action danger">Delete</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center" style="padding: 48px; color: var(--text-muted);">No products found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="pagination">{{ $products->links() }}</div>
@endsection
