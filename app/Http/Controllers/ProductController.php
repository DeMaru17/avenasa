<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display the product catalog listing page with dual-filtering, AND logic, and pagination.
     */
    public function index(string $locale, Request $request): View
    {
        $categorySlug = $request->query('category');
        $brandSlug = $request->query('brand');

        $selectedCategory = null;
        $selectedBrand = null;

        // Base query: only products where product, category, and brand are ALL active
        $query = Product::query()
            ->where('is_active', true)
            ->whereHas('category', fn ($q) => $q->where('is_active', true))
            ->whereHas('brand', fn ($q) => $q->where('is_active', true))
            ->with(['category', 'brand']);

        // 1. Category Filter (Localized slug matching)
        if (! empty($categorySlug) && is_string($categorySlug)) {
            $catColumn = $locale === 'en' ? 'slug_en' : 'slug_id';
            $selectedCategory = Category::active()->where($catColumn, $categorySlug)->first();

            if ($selectedCategory) {
                $query->where('category_id', $selectedCategory->id);
            } else {
                // Invalid category slug: graceful zero results
                $query->whereRaw('1 = 0');
            }
        }

        // 2. Brand Filter (Universal slug matching)
        if (! empty($brandSlug) && is_string($brandSlug)) {
            $selectedBrand = Brand::active()->where('slug', $brandSlug)->first();

            if ($selectedBrand) {
                $query->where('brand_id', $selectedBrand->id);
            } else {
                // Invalid brand slug: graceful zero results
                $query->whereRaw('1 = 0');
            }
        }

        // Default Ordering: sort_order ASC, created_at DESC
        $products = $query
            ->orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(12)
            ->withQueryString();

        // Load active options for Sidebar & Mobile Drawer
        $categories = Category::active()->ordered()->get();
        $brands = Brand::active()->ordered()->get();

        $activeFilterCount = ($selectedCategory ? 1 : 0) + ($selectedBrand ? 1 : 0);

        return view('pages.products.index', [
            'products' => $products,
            'categories' => $categories,
            'brands' => $brands,
            'selectedCategory' => $selectedCategory,
            'selectedBrand' => $selectedBrand,
            'selectedCategorySlug' => $categorySlug,
            'selectedBrandSlug' => $brandSlug,
            'activeFilterCount' => $activeFilterCount,
        ]);
    }

    /**
     * Display a specific product detail page using strict localized slug matching.
     */
    public function show(string $locale, string $slug): View
    {
        $column = $locale === 'en' ? 'slug_en' : 'slug_id';

        $product = Product::query()
            ->where('is_active', true)
            ->where($column, $slug)
            ->with(['category', 'brand', 'images'])
            ->firstOrFail();

        return view('pages.products.show', compact('product'));
    }
}
