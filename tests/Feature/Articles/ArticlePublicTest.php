<?php

namespace Tests\Feature\Articles;

use App\Models\Article;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Services\LocalizationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticlePublicTest extends TestCase
{
    use RefreshDatabase;

    protected Category $category;

    protected Brand $brand;

    protected function setUp(): void
    {
        parent::setUp();

        $this->category = Category::create([
            'name_id' => 'Diagnostik',
            'name_en' => 'Diagnostics',
            'slug_id' => 'diagnostik',
            'slug_en' => 'diagnostics',
            'is_active' => true,
        ]);

        $this->brand = Brand::create([
            'name' => 'Brand ANS',
            'slug' => 'brand-ans',
            'logo_path' => 'brands/brand-ans.png',
            'is_active' => true,
        ]);
    }

    public function test_article_listing_page_accessible_and_filters_published_only(): void
    {
        $published = Article::create([
            'title_id' => 'Artikel Publik',
            'title_en' => 'Public Article',
            'excerpt_id' => 'Cuplikan artikel publik',
            'excerpt_en' => 'Public article excerpt',
            'published_at' => now()->subHour(),
            'is_active' => true,
        ]);

        $draft = Article::create([
            'title_id' => 'Artikel Rahasia Draft',
            'title_en' => 'Secret Draft Article',
            'published_at' => null,
            'is_active' => true,
        ]);

        // Indonesian Listing
        $responseId = $this->get('/id/articles');
        $responseId->assertStatus(200);
        $responseId->assertSee('Artikel Publik');
        $responseId->assertDontSee('Artikel Rahasia Draft');

        // English Listing
        $responseEn = $this->get('/en/articles');
        $responseEn->assertStatus(200);
        $responseEn->assertSee('Public Article');
        $responseEn->assertDontSee('Secret Draft Article');
    }

    public function test_strict_locale_slug_isolation_and_404_on_cross_locale_slug(): void
    {
        $article = Article::create([
            'title_id' => 'Kertas pH GVS Panduan Lengkap',
            'title_en' => 'pH Paper GVS Complete Guide',
            'published_at' => now()->subHour(),
            'is_active' => true,
        ]);

        $slugId = $article->slug_id;
        $slugEn = $article->slug_en;

        // 1. Correct locale + correct slug => 200 OK
        $this->get("/id/articles/{$slugId}")->assertStatus(200);
        $this->get("/en/articles/{$slugEn}")->assertStatus(200);

        // 2. Cross-locale mismatched slugs => strictly 404 (no fallback)
        $this->get("/en/articles/{$slugId}")->assertStatus(404);
        $this->get("/id/articles/{$slugEn}")->assertStatus(404);

        // 3. Invalid random slug => 404
        $this->get('/id/articles/non-existent-slug')->assertStatus(404);
        $this->get('/en/articles/non-existent-slug')->assertStatus(404);
    }

    public function test_unpublished_and_inactive_articles_return_404_on_public_detail(): void
    {
        $draft = Article::create([
            'title_id' => 'Artikel Draf Internal',
            'title_en' => 'Internal Draft Article',
            'published_at' => null,
            'is_active' => true,
        ]);

        $future = Article::create([
            'title_id' => 'Artikel Terjadwal',
            'title_en' => 'Scheduled Article',
            'published_at' => now()->addWeek(),
            'is_active' => true,
        ]);

        $inactive = Article::create([
            'title_id' => 'Artikel Tidak Aktif',
            'title_en' => 'Inactive Article',
            'published_at' => now()->subDay(),
            'is_active' => false,
        ]);

        $this->get("/id/articles/{$draft->slug_id}")->assertStatus(404);
        $this->get("/en/articles/{$draft->slug_en}")->assertStatus(404);

        $this->get("/id/articles/{$future->slug_id}")->assertStatus(404);
        $this->get("/en/articles/{$future->slug_en}")->assertStatus(404);

        $this->get("/id/articles/{$inactive->slug_id}")->assertStatus(404);
        $this->get("/en/articles/{$inactive->slug_en}")->assertStatus(404);
    }

    public function test_related_products_display_active_products_only_and_preserve_locale(): void
    {
        $article = Article::create([
            'title_id' => 'Artikel Dengan Produk Terkait',
            'title_en' => 'Article With Related Products',
            'content_id' => '<p>Ulasan produk laboratorium ANS</p>',
            'content_en' => '<p>Review of ANS laboratory products</p>',
            'published_at' => now()->subHour(),
            'is_active' => true,
        ]);

        $activeProduct = Product::create([
            'category_id' => $this->category->id,
            'brand_id' => $this->brand->id,
            'name_id' => 'Alat PCR Aktif',
            'name_en' => 'Active PCR System',
            'slug_id' => 'alat-pcr-aktif',
            'slug_en' => 'active-pcr-system',
            'primary_image_path' => 'products/primary/pcr.png',
            'is_active' => true,
        ]);

        $inactiveProduct = Product::create([
            'category_id' => $this->category->id,
            'brand_id' => $this->brand->id,
            'name_id' => 'Alat PCR Nonaktif',
            'name_en' => 'Inactive PCR System',
            'slug_id' => 'alat-pcr-nonaktif',
            'slug_en' => 'inactive-pcr-system',
            'primary_image_path' => 'products/primary/inactive.png',
            'is_active' => false,
        ]);

        $article->relatedProducts()->attach([
            $activeProduct->id => ['sort_order' => 1],
            $inactiveProduct->id => ['sort_order' => 2],
        ]);

        $responseId = $this->get("/id/articles/{$article->slug_id}");
        $responseId->assertStatus(200);
        $responseId->assertSee('Alat PCR Aktif');
        $responseId->assertDontSee('Alat PCR Nonaktif');
        $responseId->assertSee('/id/products/alat-pcr-aktif');

        $responseEn = $this->get("/en/articles/{$article->slug_en}");
        $responseEn->assertStatus(200);
        $responseEn->assertSee('Active PCR System');
        $responseEn->assertDontSee('Inactive PCR System');
        $responseEn->assertSee('/en/products/active-pcr-system');
    }

    public function test_homepage_latest_articles_renders_when_published_and_hides_when_empty(): void
    {
        // 1. Initially when no articles exist, home page does not render latest articles section
        $emptyHomeResponse = $this->get('/id');
        $emptyHomeResponse->assertStatus(200);
        $emptyHomeResponse->assertDontSee('id="latest-articles-title"', false);

        // 2. Create 4 published articles
        for ($i = 1; $i <= 4; $i++) {
            Article::create([
                'title_id' => "Artikel Beranda Ke-{$i}",
                'title_en' => "Homepage Article #{$i}",
                'published_at' => now()->subMinutes(10 * $i),
                'is_active' => true,
            ]);
        }

        $homeResponse = $this->get('/id');
        $homeResponse->assertStatus(200);
        $homeResponse->assertSee('id="latest-articles-title"', false);

        // Only 3 latest should be shown
        $homeResponse->assertSee('Artikel Beranda Ke-1');
        $homeResponse->assertSee('Artikel Beranda Ke-2');
        $homeResponse->assertSee('Artikel Beranda Ke-3');
        $homeResponse->assertDontSee('Artikel Beranda Ke-4');
    }

    public function test_language_switcher_resolves_partner_slug_for_same_article(): void
    {
        $article = Article::create([
            'title_id' => 'Inovasi Biologi Molekuler',
            'title_en' => 'Molecular Biology Innovation',
            'published_at' => now()->subHour(),
            'is_active' => true,
        ]);

        $localizationService = app(LocalizationService::class);

        // Simulate visiting English page and switching to Indonesian
        $this->get("/en/articles/{$article->slug_en}");
        $switchUrlToId = $localizationService->getSwitchUrl('id');
        $this->assertEquals(url("/id/articles/{$article->slug_id}"), $switchUrlToId);

        // Simulate visiting Indonesian page and switching to English
        $this->get("/id/articles/{$article->slug_id}");
        $switchUrlToEn = $localizationService->getSwitchUrl('en');
        $this->assertEquals(url("/en/articles/{$article->slug_en}"), $switchUrlToEn);
    }

    public function test_sitemap_includes_published_articles_and_excludes_drafts(): void
    {
        $published = Article::create([
            'title_id' => 'Artikel Masuk Sitemap',
            'title_en' => 'Sitemap Article',
            'published_at' => now()->subHour(),
            'is_active' => true,
        ]);

        $draft = Article::create([
            'title_id' => 'Draft Luar Sitemap',
            'title_en' => 'Draft Outside Sitemap',
            'published_at' => null,
            'is_active' => true,
        ]);

        $response = $this->get('/sitemap.xml');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/xml; charset=utf-8');

        $response->assertSee(url("/id/articles/{$published->slug_id}"));
        $response->assertSee(url("/en/articles/{$published->slug_en}"));
        $response->assertDontSee($draft->slug_id);
    }
}
