<?php

namespace App\Models;

use Database\Factories\HeroBannerFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeroBanner extends Model
{
    /** @use HasFactory<HeroBannerFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title_id',
        'title_en',
        'subtitle_id',
        'subtitle_en',
        'image_path',
        'mobile_image_path',
        'button_text_id',
        'button_text_en',
        'button_url',
        'sort_order',
        'is_active',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the localized title.
     */
    public function title(): Attribute
    {
        return Attribute::make(
            get: fn (): string => app()->getLocale() === 'en' && ! empty($this->title_en)
                ? $this->title_en
                : $this->title_id,
        );
    }

    /**
     * Get the localized subtitle.
     */
    public function subtitle(): Attribute
    {
        return Attribute::make(
            get: fn (): ?string => app()->getLocale() === 'en' && ! empty($this->subtitle_en)
                ? $this->subtitle_en
                : $this->subtitle_id,
        );
    }

    /**
     * Get the localized button text.
     */
    public function buttonText(): Attribute
    {
        return Attribute::make(
            get: fn (): ?string => app()->getLocale() === 'en' && ! empty($this->button_text_en)
                ? $this->button_text_en
                : $this->button_text_id,
        );
    }

    /**
     * Scope a query to only include active hero banners.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to order hero banners by sort order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('id', 'asc');
    }
}
