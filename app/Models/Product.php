<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Product extends Model
{
    use HasUuids, SoftDeletes, LogsActivity;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'name_en', 'name_ar', 'slug',
        'description_en', 'description_ar',
        'price', 'sale_price', 'stock_quantity', 'low_stock_threshold',
        'brand_id', 'category_id', 'images',
        'ingredients_en', 'ingredients_ar',
        'how_to_use_en', 'how_to_use_ar',
        'is_featured', 'is_flash_sale', 'flash_sale_ends_at',
        'is_active', 'average_rating', 'total_reviews', 'total_sold',
    ];

    protected $casts = [
        'images'             => 'array',
        'is_featured'        => 'boolean',
        'is_flash_sale'      => 'boolean',
        'is_active'          => 'boolean',
        'flash_sale_ends_at' => 'datetime',
        'price'              => 'decimal:2',
        'sale_price'         => 'decimal:2',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->setDescriptionForEvent(
            fn(string $eventName) => "Product {$eventName}: {$this->name_en}"
        );
    }

    // Relationships
    public function brand()         { return $this->belongsTo(Brand::class); }
    public function category()      { return $this->belongsTo(Category::class); }
    public function reviews()       { return $this->hasMany(Review::class); }
    public function cartItems()     { return $this->hasMany(CartItem::class); }
    public function wishlistItems() { return $this->hasMany(WishlistItem::class); }
    public function orderItems()    { return $this->hasMany(OrderItem::class); }

    // Computed attributes
    public function getCurrentPriceAttribute(): float
    {
        return (float) ($this->sale_price ?? $this->price);
    }

    public function getDisplayNameAttribute(): string
    {
        return app()->getLocale() === 'ar'
            ? ($this->name_ar ?: $this->name_en)
            : ($this->name_en ?: $this->name_ar);
    }

    public function getDisplayDescriptionAttribute(): ?string
    {
        return app()->getLocale() === 'ar'
            ? ($this->description_ar ?: $this->description_en)
            : ($this->description_en ?: $this->description_ar);
    }

    public function getDisplayHowToUseAttribute(): ?string
    {
        return app()->getLocale() === 'ar'
            ? ($this->how_to_use_ar ?: $this->how_to_use_en)
            : ($this->how_to_use_en ?: $this->how_to_use_ar);
    }

    public function getDiscountPercentAttribute(): int
    {
        if (!$this->sale_price || (float) $this->price <= 0) return 0;
        return (int) round((((float) $this->price - (float) $this->sale_price) / (float) $this->price) * 100);
    }

    public function getIsInFlashSaleAttribute(): bool
    {
        return $this->is_flash_sale && $this->flash_sale_ends_at && $this->flash_sale_ends_at->isFuture();
    }

    public function getIsLowStockAttribute(): bool
    {
        return $this->stock_quantity > 0 && $this->stock_quantity <= $this->low_stock_threshold;
    }

    public function getFirstImageAttribute(): ?string
    {
        return $this->images[0] ?? null;
    }

    // Scopes
    public function scopeActive($query)   { return $query->where('is_active', true); }
    public function scopeFeatured($query)  { return $query->where('is_featured', true)->where('is_active', true); }
    public function scopeInStock($query)   { return $query->where('stock_quantity', '>', 0); }

    public function scopeFlashSale($query)
    {
        return $query->where('is_flash_sale', true)
                     ->where('flash_sale_ends_at', '>', now())
                     ->where('is_active', true);
    }

    public function scopeFilter($query, array $filters)
    {
        return $query
            ->when($filters['search'] ?? null, fn($q, $s) =>
                $q->where(fn($q) => $q->where('name_en', 'like', "%{$s}%")->orWhere('name_ar', 'like', "%{$s}%"))
            )
            ->when($filters['categories'] ?? null, fn($q, $cats) => $q->whereIn('category_id', $cats))
            ->when($filters['brands'] ?? null, fn($q, $brands) => $q->whereIn('brand_id', $brands))
            ->when($filters['min_price'] ?? null, fn($q, $min) => $q->where('price', '>=', $min))
            ->when($filters['max_price'] ?? null, fn($q, $max) => $q->where('price', '<=', $max))
            ->when($filters['min_rating'] ?? null, fn($q, $r) => $q->where('average_rating', '>=', $r))
            ->when($filters['on_sale'] ?? false, fn($q) => $q->whereNotNull('sale_price'))
            ->when($filters['in_stock'] ?? false, fn($q) => $q->where('stock_quantity', '>', 0));
    }

    public function recalculateRating(): void
    {
        $avg   = $this->reviews()->where('is_approved', true)->avg('rating');
        $count = $this->reviews()->where('is_approved', true)->count();
        $this->update(['average_rating' => round($avg ?? 0, 2), 'total_reviews' => $count]);
    }

    public function getRouteKeyName(): string { return 'slug'; }
}
