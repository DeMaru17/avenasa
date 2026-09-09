<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\CompanyProfile;
use Illuminate\View\View;

class ArticleController extends Controller
{
    /**
     * Display a listing of published articles.
     */
    public function index(string $locale): View
    {
        $articles = Article::published()
            ->ordered()
            ->paginate(12);

        $companyProfile = CompanyProfile::first();

        return view('pages.articles.index', compact('articles', 'companyProfile'));
    }

    /**
     * Display the specified published article with strict locale slug resolution.
     */
    public function show(string $locale, string $slug): View
    {
        // Strict locale isolation: 'id' strictly matches 'slug_id', 'en' strictly matches 'slug_en'.
        // No cross-locale fallback; mismatched slugs intentionally abort with 404.
        $slugColumn = $locale === 'en' ? 'slug_en' : 'slug_id';

        $article = Article::published()
            ->where($slugColumn, $slug)
            ->firstOrFail();

        // Eager load only available related products (product active AND brand active), preserving the pivot sort order
        $article->load([
            'relatedProducts' => function ($query): void {
                $query->available()
                    ->with(['category', 'brand'])
                    ->orderBy('article_product.sort_order', 'asc');
            },
        ]);

        $companyProfile = CompanyProfile::first();

        return view('pages.articles.show', compact('article', 'companyProfile'));
    }
}
