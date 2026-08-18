<?php

namespace App\Models;

use Database\Factories\ManagementFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Management extends Model
{
    /** @use HasFactory<ManagementFactory> */
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'managements';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'position_id',
        'position_en',
        'bio_id',
        'bio_en',
        'photo_path',
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
     * Get the localized position.
     */
    public function position(): Attribute
    {
        return Attribute::make(
            get: fn (): string => app()->getLocale() === 'en' && ! empty($this->position_en)
                ? $this->position_en
                : $this->position_id,
        );
    }

    /**
     * Get the localized bio.
     */
    public function bio(): Attribute
    {
        return Attribute::make(
            get: fn (): ?string => app()->getLocale() === 'en' && ! empty($this->bio_en)
                ? $this->bio_en
                : $this->bio_id,
        );
    }

    /**
     * Scope a query to only include active management records.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to order management records by sort order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc');
    }
}
