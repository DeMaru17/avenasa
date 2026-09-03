<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BrandWebsiteUrlTest extends TestCase
{
    use RefreshDatabase;

    public function test_partners_clients_page_displays_dual_action_when_brand_has_products_and_website_url(): void
    {
        $brand = Brand::create([
            'name' => 'Lovibond Test',
            'slug' => 'lovibond-test',
            'logo_path' => 'brands/test.png',
            'website_url' => 'https://www.lovibond.com',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $category = Category::create([
            'name_id' => 'Instrumen',
            'name_en' => 'Instruments',
            'slug_id' => 'instrumen',
            'slug_en' => 'instruments',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        Product::create([
            'brand_id' => $brand->id,
            'category_id' => $category->id,
            'name_id' => 'Lovibond Comparator 2000',
            'name_en' => 'Lovibond Comparator 2000 EN',
            'slug_id' => 'lovibond-comparator-2000',
            'slug_en' => 'lovibond-comparator-2000-en',
            'primary_image_path' => 'products/primary/test.jpg',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $responseId = $this->get('/id/partners-clients');
        $responseId->assertStatus(200);
        $responseId->assertSee('Lihat Produk');
        $responseId->assertSee('Website Resmi');
        $responseId->assertSee('https://www.lovibond.com', false);
        $responseId->assertSee('target="_blank"', false);
        $responseId->assertSee('rel="noopener noreferrer"', false);

        $responseEn = $this->get('/en/partners-clients');
        $responseEn->assertStatus(200);
        $responseEn->assertSee('View Products');
        $responseEn->assertSee('Official Site');
        $responseEn->assertSee('https://www.lovibond.com', false);
    }

    public function test_partners_clients_page_displays_primary_website_cta_when_brand_has_website_url_without_products(): void
    {
        Brand::create([
            'name' => 'Standalone Brand',
            'slug' => 'standalone-brand',
            'logo_path' => 'brands/test2.png',
            'website_url' => 'https://www.standalone.com',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $responseId = $this->get('/id/partners-clients');
        $responseId->assertStatus(200);
        $responseId->assertDontSee('Lihat Produk');
        $responseId->assertSee('Kunjungi Website Resmi');
        $responseId->assertSee('https://www.standalone.com', false);

        $responseEn = $this->get('/en/partners-clients');
        $responseEn->assertStatus(200);
        $responseEn->assertDontSee('View Products');
        $responseEn->assertSee('Visit Official Website');
        $responseEn->assertSee('https://www.standalone.com', false);
    }

    public function test_product_detail_page_displays_official_site_link_and_enriches_json_ld(): void
    {
        $brand = Brand::create([
            'name' => 'IKA Global',
            'slug' => 'ika-global',
            'logo_path' => 'brands/test3.png',
            'website_url' => 'https://www.ika.com',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $category = Category::create([
            'name_id' => 'Laboratorium',
            'name_en' => 'Laboratory',
            'slug_id' => 'laboratorium',
            'slug_en' => 'laboratory',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $product = Product::create([
            'brand_id' => $brand->id,
            'category_id' => $category->id,
            'name_id' => 'IKA Magnetic Stirrer RCT',
            'name_en' => 'IKA Magnetic Stirrer RCT EN',
            'slug_id' => 'ika-magnetic-stirrer-rct',
            'slug_en' => 'ika-magnetic-stirrer-rct-en',
            'primary_image_path' => 'products/primary/test.jpg',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $response = $this->get('/id/products/'.$product->slug_id);
        $response->assertStatus(200);
        $response->assertSee('Situs Resmi');
        $response->assertSee('https://www.ika.com', false);
        $response->assertSee('target="_blank"', false);
        $response->assertSee('rel="noopener noreferrer"', false);

        // JSON-LD Assertion (JSON_UNESCAPED_SLASHES)
        $response->assertSee('"url":"https://www.ika.com"', false);

        $responseEn = $this->get('/en/products/'.$product->slug_en);
        $responseEn->assertStatus(200);
        $responseEn->assertSee('Official Site');
        $responseEn->assertSee('https://www.ika.com', false);
    }
}
