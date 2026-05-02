@php $p = $product ?? null; @endphp

@if($errors->any())
    <div class="form-error">
        <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
@endif

<div class="form-row mb-2">
    <div class="form-group">
        <label class="form-label">Name (English) *</label>
        <input type="text" name="name_en" value="{{ old('name_en', $p?->name_en) }}" class="form-input" required>
    </div>
    <div class="form-group">
        <label class="form-label">Name (Arabic) *</label>
        <input type="text" name="name_ar" value="{{ old('name_ar', $p?->name_ar) }}" class="form-input" dir="rtl" required>
    </div>
</div>

<div class="form-row mb-2">
    <div class="form-group">
        <label class="form-label">Description (English)</label>
        <textarea name="description_en" class="form-input" rows="4">{{ old('description_en', $p?->description_en) }}</textarea>
    </div>
    <div class="form-group">
        <label class="form-label">Description (Arabic)</label>
        <textarea name="description_ar" class="form-input" rows="4" dir="rtl">{{ old('description_ar', $p?->description_ar) }}</textarea>
    </div>
</div>

<div class="form-row mb-2">
    <div class="form-group">
        <label class="form-label">Price (EGP) *</label>
        <input type="number" name="price" value="{{ old('price', $p?->price) }}" step="0.01" class="form-input" required>
    </div>
    <div class="form-group">
        <label class="form-label">Sale Price</label>
        <input type="number" name="sale_price" value="{{ old('sale_price', $p?->sale_price) }}" step="0.01" class="form-input">
    </div>
</div>

<div class="form-row mb-2">
    <div class="form-group">
        <label class="form-label">Stock Quantity *</label>
        <input type="number" name="stock_quantity" value="{{ old('stock_quantity', $p?->stock_quantity ?? 0) }}" class="form-input" required>
    </div>
    <div class="form-group">
        <label class="form-label">Low Stock Threshold</label>
        <input type="number" name="low_stock_threshold" value="{{ old('low_stock_threshold', $p?->low_stock_threshold ?? 5) }}" class="form-input" required>
    </div>
</div>

<div class="form-row mb-2">
    <div class="form-group">
        <label class="form-label">Brand *</label>
        <select name="brand_id" class="form-input" required>
            <option value="">Select brand</option>
            @foreach($brands as $brand)
                <option value="{{ $brand->id }}" {{ old('brand_id', $p?->brand_id) == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label class="form-label">Category *</label>
        <select name="category_id" class="form-input" required>
            <option value="">Select category</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ old('category_id', $p?->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name_en }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="form-group mb-2">
    <label class="form-label">Images {{ $p ? '' : '*' }}</label>
    <input type="file" name="{{ $p ? 'new_images' : 'images' }}[]" class="form-input" multiple accept="image/*">
    @if($p?->images)
        <div style="display: flex; gap: 8px; margin-top: 10px; flex-wrap: wrap;">
            @foreach($p->images as $img)
                <div style="position: relative;">
                    <img src="{{ str_starts_with($img, 'http') ? $img : asset('storage/' . $img) }}" style="width: 56px; height: 56px; border-radius: 8px; object-fit: cover;">
                    <label style="position: absolute; inset: 0; background: rgba(0,0,0,0.6); display: flex; align-items: center; justify-content: center; border-radius: 8px; opacity: 0; cursor: pointer; transition: opacity 0.15s;" onmouseover="this.style.opacity=1" onmouseout="this.style.opacity=0">
                        <input type="checkbox" name="remove_images[]" value="{{ $img }}" style="display: none;">
                        <span style="color: var(--danger); font-size: 14px; font-weight: bold;">✕</span>
                    </label>
                </div>
            @endforeach
        </div>
    @endif
</div>

<div class="form-row mb-2">
    <div class="form-group">
        <label class="form-label">Ingredients (EN)</label>
        <textarea name="ingredients_en" class="form-input" rows="3">{{ old('ingredients_en', $p?->ingredients_en) }}</textarea>
    </div>
    <div class="form-group">
        <label class="form-label">How to Use (EN)</label>
        <textarea name="how_to_use_en" class="form-input" rows="3">{{ old('how_to_use_en', $p?->how_to_use_en) }}</textarea>
    </div>
</div>

<div class="flex gap-2 flex-wrap mt-2" style="margin-bottom: 8px;">
    <label class="form-check">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $p?->is_active ?? true) ? 'checked' : '' }}>
        Active
    </label>
    <label class="form-check">
        <input type="hidden" name="is_featured" value="0">
        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $p?->is_featured) ? 'checked' : '' }}>
        Featured
    </label>
    <label class="form-check">
        <input type="hidden" name="is_flash_sale" value="0">
        <input type="checkbox" name="is_flash_sale" value="1" {{ old('is_flash_sale', $p?->is_flash_sale) ? 'checked' : '' }}>
        Flash Sale
    </label>
    <div style="display: flex; align-items: center; gap: 8px;">
        <label class="form-label" style="margin: 0;">Ends:</label>
        <input type="datetime-local" name="flash_sale_ends_at" value="{{ old('flash_sale_ends_at', $p?->flash_sale_ends_at?->format('Y-m-d\TH:i')) }}" class="form-input" style="width: 220px;">
    </div>
</div>
