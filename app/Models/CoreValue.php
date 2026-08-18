<?php

namespace App\Models;

use Database\Factories\CoreValueFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoreValue extends Model
{
    /** @use HasFactory<CoreValueFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title_id',
        'title_en',
        'description_id',
        'description_en',
        'icon_name',
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
     * Get the localized description.
     */
    public function description(): Attribute
    {
        return Attribute::make(
            get: fn (): string => app()->getLocale() === 'en' && ! empty($this->description_en)
                ? $this->description_en
                : $this->description_id,
        );
    }

    /**
     * Scope a query to only include active core values.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to order core values by sort order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc');
    }
}
