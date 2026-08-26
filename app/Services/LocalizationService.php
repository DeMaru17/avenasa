<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class LocalizationService
{
    /**
     * Whitelist of supported locales.
     *
     * @var list<string>
     */
    public const SUPPORTED_LOCALES = ['id', 'en'];

    /**
     * Get the current active locale.
     */
    public function getCurrentLocale(): string
    {
        return app()->getLocale();
    }

    /**
     * Get the alternate/opposite locale.
     */
    public function getAlternateLocale(?string $currentLocale = null): string
    {
        $current = $currentLocale ?? $this->getCurrentLocale();

        return $current === 'id' ? 'en' : 'id';
    }

    /**
     * Generate the contextual URL for switching to a target locale.
     */
    public function getSwitchUrl(string $targetLocale, ?Request $request = null): string
    {
        $request = $request ?? request();
        $currentRoute = Route::current();

        if (! $currentRoute) {
            return url($targetLocale);
        }

        $routeName = $currentRoute->getName();
        $currentLocale = $this->getCurrentLocale();

        // 1. Product Detail (products.show & products.brochure)
        if ($routeName === 'products.show' || $routeName === 'products.brochure') {
            $slug = $currentRoute->parameter('slug');
            $currentColumn = $currentLocale === 'en' ? 'slug_en' : 'slug_id';
            $targetColumn = $targetLocale === 'en' ? 'slug_en' : 'slug_id';

            $product = Product::where($currentColumn, $slug)->first();

            if ($product && ! empty($product->{$targetColumn})) {
                return route($routeName, [
                    'locale' => $targetLocale,
                    'slug' => $product->{$targetColumn},
                ]);
            }

            // Defensive Fallback (per SPEC-05 Section 6.2)
            return route('products.index', ['locale' => $targetLocale]);
        }

        // 2. Product Catalog (products.index) with potential category filter
        if ($routeName === 'products.index') {
            $queryParams = $request->query();

            if (isset($queryParams['category']) && is_string($queryParams['category'])) {
                $categorySlug = $queryParams['category'];
                $currentCatColumn = $currentLocale === 'en' ? 'slug_en' : 'slug_id';
                $targetCatColumn = $targetLocale === 'en' ? 'slug_en' : 'slug_id';

                $category = Category::where($currentCatColumn, $categorySlug)->first();

                if ($category && ! empty($category->{$targetCatColumn})) {
                    $queryParams['category'] = $category->{$targetCatColumn};
                }
            }

            $baseUrl = route('products.index', ['locale' => $targetLocale]);

            return count($queryParams) > 0 ? $baseUrl.'?'.http_build_query($queryParams) : $baseUrl;
        }

        // 3. Contact page with potential product_id query param
        if ($routeName === 'contact') {
            $queryParams = $request->query();
            $baseUrl = route('contact', ['locale' => $targetLocale]);

            return count($queryParams) > 0 ? $baseUrl.'?'.http_build_query($queryParams) : $baseUrl;
        }

        // 4. Standard Named Routes (home, about, partners-clients, etc.)
        if ($routeName) {
            $parameters = $currentRoute->parameters();
            $parameters['locale'] = $targetLocale;
            $queryParams = $request->query();

            $baseUrl = route($routeName, $parameters);

            return count($queryParams) > 0 ? $baseUrl.'?'.http_build_query($queryParams) : $baseUrl;
        }

        return url($targetLocale);
    }

    /**
     * Get the canonical URL for the specified locale (or current locale).
     */
    public function getCanonicalUrl(?string $locale = null, ?Request $request = null): string
    {
        $request = $request ?? request();
        $targetLocale = $locale ?? $this->getCurrentLocale();

        if ($targetLocale === $this->getCurrentLocale()) {
            $currentRoute = Route::current();
            if ($currentRoute && $currentRoute->getName()) {
                return route($currentRoute->getName(), array_merge($currentRoute->parameters(), ['locale' => $targetLocale]));
            }

            return url($request->getPathInfo());
        }

        return $this->getSwitchUrl($targetLocale, $request);
    }

    /**
     * Get hreflang alternate URLs for the current page.
     *
     * @return array<string, string>
     */
    public function getHreflangUrls(?Request $request = null): array
    {
        $request = $request ?? request();
        $currentRoute = $request->route();
        $routeName = $currentRoute?->getName();

        if ($routeName === 'products.show' || $routeName === 'products.brochure') {
            $slug = $currentRoute->parameter('slug');
            $currentLocale = $this->getCurrentLocale();
            $currentColumn = $currentLocale === 'en' ? 'slug_en' : 'slug_id';

            $product = Product::where($currentColumn, $slug)->first();

            $idUrl = (! empty($product?->slug_id)) ? route('products.show', ['locale' => 'id', 'slug' => $product->slug_id]) : null;
            $enUrl = (! empty($product?->slug_en)) ? route('products.show', ['locale' => 'en', 'slug' => $product->slug_en]) : null;
            $xDefault = $enUrl ?? $idUrl ?? url('/en');

            $hreflangs = [
                'x-default' => $xDefault,
            ];

            if ($idUrl) {
                $hreflangs['id'] = $idUrl;
            }
            if ($enUrl) {
                $hreflangs['en'] = $enUrl;
            }

            return $hreflangs;
        }

        $idUrl = $this->getCanonicalUrl('id', $request);
        $enUrl = $this->getCanonicalUrl('en', $request);

        return [
            'id' => $idUrl,
            'en' => $enUrl,
            'x-default' => $enUrl, // Default EN version
        ];
    }

    /**
     * Localize an internal path/URL with the active or given locale prefix.
     */
    public function localizeUrl(?string $url = null, ?string $locale = null): string
    {
        if (empty($url)) {
            return '';
        }

        // If absolute external URL (e.g. http:// or https:// or //), return as-is
        if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://') || str_starts_with($url, '//')) {
            return $url;
        }

        $targetLocale = $locale ?? $this->getCurrentLocale();
        $cleanPath = ltrim($url, '/');

        // Check if path already starts with a supported locale prefix
        foreach (self::SUPPORTED_LOCALES as $supportedLocale) {
            if ($cleanPath === $supportedLocale || str_starts_with($cleanPath, $supportedLocale.'/')) {
                return url($cleanPath);
            }
        }

        return url($targetLocale.($cleanPath !== '' ? '/'.$cleanPath : ''));
    }
}
