<?php

namespace App\Models;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'category_id',
        'brand_id',
        'name_id',
        'name_en',
        'slug_id',
        'slug_en',
        'summary_id',
        'summary_en',
        'description_id',
        'description_en',
        'specifications',
        'primary_image_path',
        'brochure_path',
        'is_featured',
        'is_active',
        'sort_order',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::creating(function (Product $product) {
            if (empty($product->slug_id) && ! empty($product->name_id)) {
                $product->slug_id = static::generateUniqueSlug($product->name_id, 'slug_id');
            }

            if (empty($product->slug_en) && ! empty($product->name_en)) {
                $product->slug_en = static::generateUniqueSlug($product->name_en, 'slug_en');
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
            'category_id' => 'integer',
            'brand_id' => 'integer',
            'specifications' => 'array',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /**
     * Get the localized product name.
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
     * Get the localized product slug.
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
     * Get the localized product summary.
     */
    public function summary(): Attribute
    {
        return Attribute::make(
            get: fn (): ?string => app()->getLocale() === 'en' && ! empty($this->summary_en)
                ? $this->summary_en
                : $this->summary_id,
        );
    }

    /**
     * Get the localized product full description.
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
     * Get the category that owns the product.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Get the brand that owns the product.
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    /**
     * Get the gallery images for the product.
     */
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class, 'product_id')->orderBy('sort_order', 'asc');
    }

    /**
     * Alias for images() relationship.
     */
    public function productImages(): HasMany
    {
        return $this->hasMany(ProductImage::class, 'product_id')->orderBy('sort_order', 'asc');
    }

    /**
     * Get the quotation inquiries associated with the product.
     */
    public function quotations(): HasMany
    {
        return $this->hasMany(Quotation::class, 'product_id');
    }

    /**
     * Scope a query to only include active products.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include featured products.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope a query to order products by sort order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc');
    }
}
