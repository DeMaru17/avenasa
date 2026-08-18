<?php

namespace App\Models;

use Database\Factories\CategoryFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    /** @use HasFactory<CategoryFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name_id',
        'name_en',
        'slug_id',
        'slug_en',
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
     * Get the category name based on the current application locale.
     */
    public function name(): Attribute
    {
        return Attribute::make(
            get: fn (): string => app()->getLocale() === 'en' && ! empty($this->name_en)
                ? $this->name_en
                : $this->name_id,
        );
    }

    /**
     * Get the localized slug based on the current application locale.
     */
    public function slug(): Attribute
    {
        return Attribute::make(
            get: fn (): string => app()->getLocale() === 'en' && ! empty($this->slug_en)
                ? $this->slug_en
                : $this->slug_id,
        );
    }

    /**
     * Get the products for the category.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    /**
     * Scope a query to only include active categories.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to order by sort order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc');
    }
}
