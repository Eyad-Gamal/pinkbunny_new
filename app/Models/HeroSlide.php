<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class HeroSlide extends Model
{
    use HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'title_en', 'title_ar', 'subtitle_en', 'subtitle_ar',
        'badge_text', 'button_text', 'button_link',
        'bg_color_from', 'bg_color_to', 'text_color',
        'image_1', 'image_2', 'image_3',
        'is_active', 'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('created_at');
    }

    public function getDisplayTitleAttribute(): string
    {
        return app()->getLocale() === 'ar'
            ? ($this->title_ar ?: $this->title_en)
            : $this->title_en;
    }

    public function getDisplaySubtitleAttribute(): ?string
    {
        return app()->getLocale() === 'ar'
            ? ($this->subtitle_ar ?: $this->subtitle_en)
            : $this->subtitle_en;
    }
}
