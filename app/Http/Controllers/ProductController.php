<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display the product catalog listing page.
     */
    public function index(string $locale, Request $request): View
    {
        return view('pages.products.index');
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
