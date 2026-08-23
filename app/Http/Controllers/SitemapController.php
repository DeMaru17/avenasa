<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Generate and serve the dynamic XML sitemap.
     */
    public function index(): Response
    {
        $staticRoutes = [
            'home' => ['id' => url('/id'), 'en' => url('/en')],
            'about' => ['id' => url('/id/about'), 'en' => url('/en/about')],
            'products' => ['id' => url('/id/products'), 'en' => url('/en/products')],
            'partners-clients' => ['id' => url('/id/partners-clients'), 'en' => url('/en/partners-clients')],
            'contact' => ['id' => url('/id/contact'), 'en' => url('/en/contact')],
        ];

        $products = Product::where('is_active', true)
            ->select('id', 'slug_id', 'slug_en', 'updated_at')
            ->get();

        $content = view('sitemap', [
            'staticRoutes' => $staticRoutes,
            'products' => $products,
        ])->render();

        return response($content, 200, [
            'Content-Type' => 'application/xml; charset=utf-8',
        ]);
    }
}
