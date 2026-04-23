<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasUuids;
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'user_id', 'label', 'full_name', 'phone',
        'street', 'city', 'governorate', 'country', 'postal_code', 'is_default',
    ];

    protected $casts = ['is_default' => 'boolean'];

    public function user() { return $this->belongsTo(User::class); }
}
