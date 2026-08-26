<?php

namespace Tests\Feature\Localization;

use App\Models\Brand;
use App\Models\Category;
use App\Models\CompanyProfile;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoAndAnalyticsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        CompanyProfile::create([
            'tagline_id' => 'Distributor Alat Kesehatan & Laboratorium',
            'tagline_en' => 'Medical & Laboratory Equipment Distributor',
            'about_id' => 'Tentang ANS',
            'about_en' => 'About ANS',
            'vision_id' => 'Menjadi penyedia solusi diagnostik dan medis terkemuka di Indonesia.',
            'vision_en' => 'To be the leading diagnostic and medical solution provider in Indonesia.',
            'mission_id' => 'Menyediakan alat terbaik.',
            'mission_en' => 'Provide the best equipment.',
            'phone' => '021 39722772',
            'whatsapp' => '082261461400',
            'email' => 'info@avenasa.co.id',
            'address' => 'Mensana Tower Lt. 15, Jl. Raya Kranggan RT.002/RW.016, Cibubur, Bekasi 17433',
        ]);
    }

    /**
     * 1. Test localized titles and descriptions on static pages.
     */
    public function test_seo_meta_tags_render_localized_titles_and_descriptions(): void
    {
        // Home ID
        $responseId = $this->get('/id');
        $responseId->assertStatus(200);
        $responseId->assertSee('<title>PT Abhipraya Nawasena Sejahtera - Distributor Alat Kesehatan &amp; Laboratorium</title>', false);
        $responseId->assertSee('<meta name="description" content="Distributor Alat Kesehatan &amp; Laboratorium">', false);

        // Home EN
        $responseEn = $this->get('/en');
        $responseEn->assertStatus(200);
        $responseEn->assertSee('<title>PT Abhipraya Nawasena Sejahtera - Medical &amp; Laboratory Equipment Distributor</title>', false);
        $responseEn->assertSee('<meta name="description" content="Medical &amp; Laboratory Equipment Distributor">', false);

        // About ID & EN
        $aboutId = $this->get('/id/about');
        $aboutId->assertStatus(200);
        $aboutId->assertSee('<title>Tentang ANS | PT Abhipraya Nawasena Sejahtera</title>', false);

        $aboutEn = $this->get('/en/about');
        $aboutEn->assertStatus(200);
        $aboutEn->assertSee('<title>About ANS | PT Abhipraya Nawasena Sejahtera</title>', false);
    }

    /**
     * 2. Test canonical URL and hreflang tags for standard pages.
     */
    public function test_canonical_url_and_hreflang_tags_for_standard_pages(): void
    {
        $response = $this->get('/id/about');
        $response->assertStatus(200);

        $appUrl = url('/');
        $response->assertSee('<link rel="canonical" href="'.$appUrl.'/id/about">', false);
        $response->assertSee('<link rel="alternate" hreflang="id" href="'.$appUrl.'/id/about">', false);
        $response->assertSee('<link rel="alternate" hreflang="en" href="'.$appUrl.'/en/about">', false);
        $response->assertSee('<link rel="alternate" hreflang="x-default" href="'.$appUrl.'/en/about">', false);
    }

    /**
     * 3. Test product detail canonical and reciprocal hreflang.
     */
    public function test_product_detail_canonical_and_reciprocal_hreflang(): void
    {
        $category = Category::create(['name_id' => 'Diagnostik', 'name_en' => 'Diagnostics', 'slug_id' => 'diagnostik', 'slug_en' => 'diagnostics', 'is_active' => true]);
        $brand = Brand::create(['name' => 'Era Biology', 'slug' => 'era-biology', 'logo_path' => 'brands/era.png', 'is_active' => true]);

        $product = Product::create([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'name_id' => 'Alat PCR Real Time',
            'name_en' => 'Real Time PCR System',
            'slug_id' => 'alat-pcr-real-time',
            'slug_en' => 'real-time-pcr-system',
            'primary_image_path' => 'products/pcr.jpg',
            'summary_id' => 'Sistem amplifikasi asam nukleat akurat.',
            'summary_en' => 'Accurate nucleic acid amplification system.',
            'is_active' => true,
        ]);

        $responseId = $this->get('/id/products/alat-pcr-real-time');
        $responseId->assertStatus(200);

        $appUrl = url('/');
        $responseId->assertSee('<link rel="canonical" href="'.$appUrl.'/id/products/alat-pcr-real-time">', false);
        $responseId->assertSee('<link rel="alternate" hreflang="id" href="'.$appUrl.'/id/products/alat-pcr-real-time">', false);
        $responseId->assertSee('<link rel="alternate" hreflang="en" href="'.$appUrl.'/en/products/real-time-pcr-system">', false);
        $responseId->assertSee('<link rel="alternate" hreflang="x-default" href="'.$appUrl.'/en/products/real-time-pcr-system">', false);
    }

    /**
     * 4. Test product detail without English slug omits EN hreflang per AC-05.
     */
    public function test_product_detail_hreflang_without_english_slug_omits_en_tag(): void
    {
        $category = Category::create(['name_id' => 'Reagen', 'name_en' => 'Reagents', 'slug_id' => 'reagen', 'slug_en' => 'reagents', 'is_active' => true]);
        $brand = Brand::create(['name' => 'Merck', 'slug' => 'merck', 'logo_path' => 'brands/merck.png', 'is_active' => true]);

        $product = Product::create([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'name_id' => 'Reagen Khusus',
            'name_en' => 'Special Reagent',
            'slug_id' => 'reagen-khusus',
            'slug_en' => 'temp-slug',
            'primary_image_path' => 'products/reagen.jpg',
            'is_active' => true,
        ]);

        Product::where('id', $product->id)->update(['slug_en' => '']);

        $response = $this->get('/id/products/reagen-khusus');
        $response->assertStatus(200);

        $appUrl = url('/');
        $response->assertSee('<link rel="alternate" hreflang="id" href="'.$appUrl.'/id/products/reagen-khusus">', false);
        $response->assertSee('<link rel="alternate" hreflang="x-default" href="'.$appUrl.'/id/products/reagen-khusus">', false);
        $response->assertDontSee('hreflang="en"', false);
    }

    /**
     * 5. Test catalog filter sets noindex and points canonical to base catalog.
     */
    public function test_catalog_filter_sets_noindex_and_points_canonical_to_base_catalog(): void
    {
        $category = Category::create(['name_id' => 'Mikrobiologi', 'name_en' => 'Microbiology', 'slug_id' => 'mikrobiologi', 'slug_en' => 'microbiology', 'is_active' => true]);

        $response = $this->get('/id/products?category=mikrobiologi');
        $response->assertStatus(200);

        $appUrl = url('/');
        $response->assertSee('<meta name="robots" content="noindex, follow">', false);
        $response->assertSee('<link rel="canonical" href="'.$appUrl.'/id/products">', false);
    }

    /**
     * 6. Test contact with product_id context sets noindex and points canonical to base contact.
     */
    public function test_contact_with_product_id_sets_noindex_and_points_canonical_to_base_contact(): void
    {
        $category = Category::create(['name_id' => 'Lab', 'name_en' => 'Lab', 'slug_id' => 'lab', 'slug_en' => 'lab', 'is_active' => true]);
        $brand = Brand::create(['name' => 'Brand A', 'slug' => 'brand-a', 'logo_path' => 'brands/brand-a.png', 'is_active' => true]);
        $product = Product::create([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'name_id' => 'Item 1',
            'name_en' => 'Item 1 EN',
            'slug_id' => 'item-1',
            'primary_image_path' => 'products/item1.jpg',
            'is_active' => true,
        ]);

        $response = $this->get('/id/contact?product_id='.$product->id);
        $response->assertStatus(200);

        $appUrl = url('/');
        $response->assertSee('<meta name="robots" content="noindex, follow">', false);
        $response->assertSee('<link rel="canonical" href="'.$appUrl.'/id/contact">', false);
    }

    /**
     * 7. Test Open Graph metadata and image fallbacks.
     */
    public function test_open_graph_metadata_and_image_fallbacks(): void
    {
        $response = $this->get('/id/about');
        $response->assertStatus(200);

        $response->assertSee('<meta property="og:site_name" content="PT Abhipraya Nawasena Sejahtera">', false);
        $response->assertSee('<meta property="og:locale" content="id_ID">', false);
        $response->assertSee('<meta property="og:locale:alternate" content="en_US">', false);
    }

    /**
     * 8. Test Organization & WebSite JSON-LD structured data.
     */
    public function test_organization_and_website_json_ld_schema(): void
    {
        $response = $this->get('/id');
        $response->assertStatus(200);

        $response->assertSee('"@type":"Organization"', false);
        $response->assertSee('"name":"PT Abhipraya Nawasena Sejahtera"', false);
        $response->assertSee('"@type":"WebSite"', false);
    }

    /**
     * 9. Test Product JSON-LD contains only official fields and NO fake pricing or ratings.
     */
    public function test_product_json_ld_schema_contains_no_fake_pricing_or_ratings(): void
    {
        $category = Category::create(['name_id' => 'Lab', 'name_en' => 'Lab', 'slug_id' => 'lab', 'slug_en' => 'lab', 'is_active' => true]);
        $brand = Brand::create(['name' => 'Brand B', 'slug' => 'brand-b', 'logo_path' => 'brands/brand-b.png', 'is_active' => true]);
        $product = Product::create([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'name_id' => 'Lab Analyzer',
            'name_en' => 'Lab Analyzer EN',
            'slug_id' => 'lab-analyzer',
            'slug_en' => 'lab-analyzer-en',
            'primary_image_path' => 'products/analyzer.jpg',
            'summary_id' => 'Official lab analyzer instrument.',
            'is_active' => true,
        ]);

        $response = $this->get('/id/products/lab-analyzer');
        $response->assertStatus(200);

        $response->assertSee('"@type":"Product"', false);
        $response->assertSee('"name":"Lab Analyzer"', false);
        $response->assertSee('"@type":"Brand"', false);
        $response->assertSee('"name":"Brand B"', false);

        // Assert strictly NO fake pricing, rating, or SKU
        $response->assertDontSee('"price"', false);
        $response->assertDontSee('"aggregateRating"', false);
        $response->assertDontSee('"review"', false);
        $response->assertDontSee('"sku"', false);
        $response->assertDontSee('"gtin"', false);
    }

    /**
     * 10. Test dynamic sitemap contains all required public localized routes and active products.
     */
    public function test_sitemap_xml_contains_all_required_public_localized_routes_and_active_products(): void
    {
        $category = Category::create(['name_id' => 'Cat', 'name_en' => 'Cat', 'slug_id' => 'cat', 'slug_en' => 'cat', 'is_active' => true]);
        $brand = Brand::create(['name' => 'Brand', 'slug' => 'brand', 'logo_path' => 'brands/brand.png', 'is_active' => true]);
        $product = Product::create([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'name_id' => 'Active Product',
            'name_en' => 'Active Product EN',
            'slug_id' => 'active-prod-id',
            'slug_en' => 'active-prod-en',
            'primary_image_path' => 'products/active.jpg',
            'is_active' => true,
        ]);

        $response = $this->get('/sitemap.xml');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/xml; charset=utf-8');

        $appUrl = url('/');
        // Check static routes
        $response->assertSee('<loc>'.$appUrl.'/id</loc>', false);
        $response->assertSee('<loc>'.$appUrl.'/en</loc>', false);
        $response->assertSee('<loc>'.$appUrl.'/id/about</loc>', false);
        $response->assertSee('<loc>'.$appUrl.'/en/about</loc>', false);
        $response->assertSee('<loc>'.$appUrl.'/id/products</loc>', false);
        $response->assertSee('<loc>'.$appUrl.'/en/products</loc>', false);
        $response->assertSee('<loc>'.$appUrl.'/id/partners-clients</loc>', false);
        $response->assertSee('<loc>'.$appUrl.'/en/partners-clients</loc>', false);
        $response->assertSee('<loc>'.$appUrl.'/id/contact</loc>', false);
        $response->assertSee('<loc>'.$appUrl.'/en/contact</loc>', false);

        // Check active product routes
        $response->assertSee('<loc>'.$appUrl.'/id/products/active-prod-id</loc>', false);
        $response->assertSee('<loc>'.$appUrl.'/en/products/active-prod-en</loc>', false);
    }

    /**
     * 11. Test dynamic sitemap excludes inactive products and administrative routes.
     */
    public function test_sitemap_xml_excludes_inactive_products_and_admin_routes(): void
    {
        $category = Category::create(['name_id' => 'Cat', 'name_en' => 'Cat', 'slug_id' => 'cat', 'slug_en' => 'cat', 'is_active' => true]);
        $brand = Brand::create(['name' => 'Brand', 'slug' => 'brand', 'logo_path' => 'brands/brand.png', 'is_active' => true]);
        Product::create([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'name_id' => 'Inactive Product',
            'name_en' => 'Inactive Product EN',
            'slug_id' => 'inactive-prod',
            'primary_image_path' => 'products/inactive.jpg',
            'is_active' => false,
        ]);

        $response = $this->get('/sitemap.xml');
        $response->assertStatus(200);

        $response->assertDontSee('inactive-prod', false);
        $response->assertDontSee('/admin', false);
        $response->assertDontSee('/filament', false);
    }

    /**
     * 12. Test static robots.txt disallows admin and contains sitemap directive.
     */
    public function test_robots_txt_disallows_admin_and_contains_sitemap_directive(): void
    {
        $robotsContent = file_get_contents(public_path('robots.txt'));

        $this->assertStringContainsString('User-agent: *', $robotsContent);
        $this->assertStringContainsString('Disallow: /admin', $robotsContent);
        $this->assertStringContainsString('Disallow: /filament', $robotsContent);
        $this->assertStringContainsString('Sitemap:', $robotsContent);
    }

    /**
     * 13. Test GA4 script is not initialized when measurement ID is empty.
     */
    public function test_ga4_is_disabled_when_measurement_id_is_empty(): void
    {
        config(['services.google.analytics_id' => null]);

        $response = $this->get('/id');
        $response->assertStatus(200);

        $response->assertDontSee('googletagmanager.com/gtag/js', false);
        $response->assertSee('window.dataLayer = window.dataLayer || [];', false);
    }

    /**
     * 14. Test GA4 script initializes when measurement ID is configured.
     */
    public function test_ga4_renders_correct_measurement_id_when_configured(): void
    {
        config(['services.google.analytics_id' => 'G-TESTMEASURE123']);

        $response = $this->withServerVariables(['APP_ENV' => 'production'])->get('/id');
        $response->assertStatus(200);

        $response->assertSee('googletagmanager.com/gtag/js?id=G-TESTMEASURE123', false);
        $response->assertSee("gtag('config', 'G-TESTMEASURE123')", false);
    }
}
