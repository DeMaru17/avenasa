<?php

namespace App\Models;

use Database\Factories\CategoryFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

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
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::creating(function (Category $category) {
            if (empty($category->slug_id) && ! empty($category->name_id)) {
                $category->slug_id = static::generateUniqueSlug($category->name_id, 'slug_id');
            }

            if (empty($category->slug_en) && ! empty($category->name_en)) {
                $category->slug_en = static::generateUniqueSlug($category->name_en, 'slug_en');
            }
        });
    }

    /**
     * Generate a unique slug for the given column.
     */
    public static function generateUniqueSlug(string $name, string $column = 'slug_id', ?int $ignoreId = null): string
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $count = 1;

        while (static::where($column, $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }

        return $slug;
    }

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
