<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class OrderTracking extends Model
{
    use HasUuids;
    protected $table = 'order_tracking';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'order_id', 'status', 'description_en', 'description_ar',
        'location', 'updated_by', 'occurred_at',
    ];

    protected $casts = ['occurred_at' => 'datetime'];

    public function order()   { return $this->belongsTo(Order::class); }
    public function updater() { return $this->belongsTo(User::class, 'updated_by'); }
}
