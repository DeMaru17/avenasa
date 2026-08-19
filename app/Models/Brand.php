<?php

namespace App\Models;

use Database\Factories\BrandFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Brand extends Model
{
    /** @use HasFactory<BrandFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'logo_path',
        'website_url',
        'description_id',
        'description_en',
        'is_new_principal',
        'sort_order',
        'is_active',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::creating(function (Brand $brand) {
            if (empty($brand->slug) && ! empty($brand->name)) {
                $brand->slug = static::generateUniqueSlug($brand->name, 'slug');
            }
        });
    }

    /**
     * Generate a unique slug for the given column.
     */
    public static function generateUniqueSlug(string $name, string $column = 'slug', ?int $ignoreId = null): string
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
            'is_new_principal' => 'boolean',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the brand description based on current locale with fallback.
     */
    public function description(): Attribute
    {
        return Attribute::make(
            get: fn (): ?string => app()->getLocale() === 'en' && ! empty($this->description_en)
                ? $this->description_en
                : $this->description_id,
        );
    }

    /**
     * Get the products for the brand.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'brand_id');
    }

    /**
     * Scope a query to only include active brands.
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
