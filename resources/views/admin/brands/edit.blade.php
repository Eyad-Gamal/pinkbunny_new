@extends('layouts.admin')
@section('title', 'Edit Brand')
@section('page-title', 'Edit Brand: ' . $brand->name)

@section('content')
    <div class="card" style="max-width: 600px;">
        <div class="card-header">
            <span class="card-title">Edit Brand Details</span>
            <a href="{{ route('admin.brands.index') }}" class="btn btn-secondary">Back to Brands</a>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.brands.update', $brand->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label class="form-label">Name *</label>
                    <input type="text" name="name" class="form-input" value="{{ old('name', $brand->name) }}" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Description (EN)</label>
                    <textarea name="description_en" class="form-input" rows="3">{{ old('description_en', $brand->description_en) }}</textarea>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Description (AR)</label>
                    <textarea name="description_ar" class="form-input" rows="3" dir="rtl">{{ old('description_ar', $brand->description_ar) }}</textarea>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Logo</label>
                    @if($brand->logo)
                        <div style="margin-bottom: 10px;">
                            <img src="{{ asset('storage/' . $brand->logo) }}" alt="Current Logo" style="height: 60px; object-fit: contain; background: #f0f0f0; padding: 4px; border-radius: 4px;">
                        </div>
                    @endif
                    <input type="file" name="logo" class="form-input" accept="image/*">
                    <small style="color: var(--text-secondary);">Leave blank to keep current logo.</small>
                </div>

                <div class="form-group">
                    <label class="form-label">Banner</label>
                    @if($brand->banner)
                        <div style="margin-bottom: 10px;">
                            <img src="{{ asset('storage/' . $brand->banner) }}" alt="Current Banner" style="height: 60px; object-fit: contain; background: #f0f0f0; padding: 4px; border-radius: 4px;">
                        </div>
                    @endif
                    <input type="file" name="banner" class="form-input" accept="image/*">
                    <small style="color: var(--text-secondary);">Leave blank to keep current banner.</small>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Sort Order</label>
                    <input type="number" name="sort_order" class="form-input" value="{{ old('sort_order', $brand->sort_order) }}">
                </div>
                
                <div class="form-group" style="display: flex; align-items: center; gap: 8px;">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $brand->is_active) ? 'checked' : '' }}>
                    <label for="is_active" class="form-label" style="margin-bottom: 0;">Active</label>
                </div>
                
                <div style="margin-top: 24px;">
                    <button type="submit" class="btn btn-primary w-full">Update Brand</button>
                </div>
            </form>
        </div>
    </div>
@endsection
