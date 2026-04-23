<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Coupon extends Model
{
    use HasUuids, LogsActivity;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'code', 'description', 'type', 'value',
        'min_order_amount', 'max_discount_amount',
        'is_active', 'starts_at', 'expires_at',
        'max_uses', 'max_uses_per_user', 'used_count',
    ];

    protected $casts = [
        'is_active'           => 'boolean',
        'starts_at'           => 'datetime',
        'expires_at'          => 'datetime',
        'value'               => 'decimal:2',
        'min_order_amount'    => 'decimal:2',
        'max_discount_amount' => 'decimal:2',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->setDescriptionForEvent(
            fn(string $e) => "Coupon {$e}: {$this->code}"
        );
    }

    public function usages() { return $this->hasMany(CouponUsage::class); }

    public function isValid(): bool
    {
        if (!$this->is_active) return false;
        if ($this->starts_at && $this->starts_at->isFuture()) return false;
        if ($this->expires_at && $this->expires_at->isPast()) return false;
        if ($this->max_uses && $this->used_count >= $this->max_uses) return false;
        return true;
    }

    public function calculateDiscount(float $subtotal): float
    {
        if ($this->type === 'percentage') {
            $discount = ($subtotal * $this->value) / 100;
            if ($this->max_discount_amount) {
                $discount = min($discount, (float) $this->max_discount_amount);
            }
            return $discount;
        }
        return min((float) $this->value, $subtotal);
    }

    public function hasUserExceededLimit(string $userId): bool
    {
        return $this->usages()->where('user_id', $userId)->count() >= $this->max_uses_per_user;
    }
}
