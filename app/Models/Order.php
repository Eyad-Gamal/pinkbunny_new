<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Order extends Model
{
    use HasUuids, SoftDeletes, LogsActivity;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'order_number', 'user_id', 'address_id', 'status',
        'subtotal', 'shipping_fee', 'discount_amount', 'total_amount',
        'coupon_code', 'payment_method', 'payment_status', 'payment_reference',
        'notes', 'admin_notes',
        'shipped_at', 'delivered_at',
        'tracking_number', 'shipping_company',
        'whatsapp_notified', 'whatsapp_confirmed_at',
    ];

    protected $casts = [
        'subtotal'               => 'decimal:2',
        'shipping_fee'           => 'decimal:2',
        'discount_amount'        => 'decimal:2',
        'total_amount'           => 'decimal:2',
        'shipped_at'             => 'datetime',
        'delivered_at'           => 'datetime',
        'whatsapp_notified'      => 'boolean',
        'whatsapp_confirmed_at'  => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['status', 'payment_status', 'tracking_number', 'admin_notes'])
            ->setDescriptionForEvent(fn(string $e) => "Order {$e}: #{$this->order_number}");
    }

    protected static function booted(): void
    {
        static::creating(function (Order $order) {
            if (!$order->order_number) {
                $date  = now()->format('Ymd');
                $count = self::whereDate('created_at', today())->count() + 1;
                $order->order_number = 'PB-' . $date . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    // Relationships
    public function user()     { return $this->belongsTo(User::class); }
    public function address()  { return $this->belongsTo(Address::class); }
    public function items()    { return $this->hasMany(OrderItem::class); }
    public function tracking() { return $this->hasMany(OrderTracking::class)->orderByDesc('occurred_at'); }

    // Status helpers
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending'    => 'Pending',
            'confirmed'  => 'Confirmed',
            'processing' => 'Processing',
            'shipped'    => 'Shipped',
            'delivered'  => 'Delivered',
            'cancelled'  => 'Cancelled',
            'refunded'   => 'Refunded',
            default      => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending'    => 'yellow',
            'confirmed'  => 'blue',
            'processing' => 'purple',
            'shipped'    => 'indigo',
            'delivered'  => 'green',
            'cancelled'  => 'red',
            'refunded'   => 'gray',
            default      => 'gray',
        };
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['pending', 'confirmed']);
    }
}
