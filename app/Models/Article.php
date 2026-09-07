<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title_id',
        'title_en',
        'slug_id',
        'slug_en',
        'excerpt_id',
        'excerpt_en',
        'content_id',
        'content_en',
        'cover_image_path',
        'type',
        'published_at',
        'is_featured',
        'is_active',
        'sort_order',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::creating(function (Article $article): void {
            if (empty($article->slug_id) && ! empty($article->title_id)) {
                $article->slug_id = static::generateUniqueSlug($article->title_id, 'slug_id');
            }

            if (empty($article->slug_en) && ! empty($article->title_en)) {
                $article->slug_en = static::generateUniqueSlug($article->title_en, 'slug_en');
            }
        });

        static::updating(function (Article $article): void {
            // Slugs are permanent and stable; only generate if column is completely empty.
            if (empty($article->slug_id) && ! empty($article->title_id)) {
                $article->slug_id = static::generateUniqueSlug($article->title_id, 'slug_id', $article->id);
            }

            if (empty($article->slug_en) && ! empty($article->title_en)) {
                $article->slug_en = static::generateUniqueSlug($article->title_en, 'slug_en', $article->id);
            }
        });
    }

    /**
     * Generate a unique slug for the given column with deterministic collision suffixes (-2, -3, ...).
     */
    public static function generateUniqueSlug(string $title, string $column = 'slug_id', ?int $ignoreId = null): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $count = 2;

        while (static::where($column, $slug)->when($ignoreId, fn (Builder $q) => $q->where('id', '!=', $ignoreId))->exists()) {
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
            'published_at' => 'datetime',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /**
     * Get the localized article title.
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
     * Get the localized article slug.
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
     * Get the localized article excerpt.
     */
    public function excerpt(): Attribute
    {
        return Attribute::make(
            get: fn (): ?string => app()->getLocale() === 'en' && ! empty($this->excerpt_en)
                ? $this->excerpt_en
                : $this->excerpt_id,
        );
    }

    /**
     * Get the localized article rich content.
     */
    public function content(): Attribute
    {
        return Attribute::make(
            get: fn (): ?string => app()->getLocale() === 'en' && ! empty($this->content_en)
                ? $this->content_en
                : $this->content_id,
        );
    }

    /**
     * Scope a query to only include published and active articles.
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_active', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    /**
     * Scope a query to only include active articles.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include featured articles.
     */
    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope a query to order articles by published date descending with id tie-breaker.
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderByDesc('published_at')->orderByDesc('id');
    }

    /**
     * Get the products related to this article.
     */
    public function relatedProducts(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'article_product')
            ->withPivot('sort_order')
            ->withTimestamps()
            ->orderByPivot('sort_order', 'asc');
    }
}
