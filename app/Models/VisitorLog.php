<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class VisitorLog extends Model
{
    use HasUuids;
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'ip_address', 'user_agent', 'page_url', 'referer',
        'browser', 'platform', 'device_type', 'country',
        'user_id', 'visited_date',
    ];

    protected $casts = ['visited_date' => 'date'];

    public function user() { return $this->belongsTo(User::class); }
}
