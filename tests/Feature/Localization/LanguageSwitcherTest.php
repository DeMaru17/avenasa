<?php

namespace Tests\Feature\Localization;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Services\LocalizationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LanguageSwitcherTest extends TestCase
{
    use RefreshDatabase;

    protected LocalizationService $localization;

    protected function setUp(): void
    {
        parent::setUp();
        $this->localization = app(LocalizationService::class);
    }

    public function test_language_switcher_maps_standard_pages(): void
    {
        $this->get('/id/about');
        $enUrl = $this->localization->getSwitchUrl('en');
        $this->assertStringContainsString('/en/about', $enUrl);

        $this->get('/en/about');
        $idUrl = $this->localization->getSwitchUrl('id');
        $this->assertStringContainsString('/id/about', $idUrl);

        $this->get('/id/partners-clients');
        $enUrl = $this->localization->getSwitchUrl('en');
        $this->assertStringContainsString('/en/partners-clients', $enUrl);
    }

    public function test_language_switcher_maps_product_detail_slugs(): void
    {
        $category = Category::create([
            'name_id' => 'Mikrobiologi',
            'name_en' => 'Microbiology',
            'slug_id' => 'mikrobiologi',
            'slug_en' => 'microbiology',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $brand = Brand::create([
            'name' => 'Merck',
            'slug' => 'merck',
            'logo_path' => '',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'name_id' => 'Cawan Petri Steril 90mm',
            'name_en' => 'Sterile Petri Dish 90mm',
            'slug_id' => 'cawan-petri-steril-90mm',
            'slug_en' => 'sterile-petri-dish-90mm',
            'primary_image_path' => '',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $responseId = $this->get('/id/products/cawan-petri-steril-90mm');
        $responseId->assertStatus(200);
        $responseId->assertSee('/en/products/sterile-petri-dish-90mm');

        $responseEn = $this->get('/en/products/sterile-petri-dish-90mm');
        $responseEn->assertStatus(200);
        $responseEn->assertSee('/id/products/cawan-petri-steril-90mm');
    }

    public function test_language_switcher_maps_filtered_catalog_queries(): void
    {
        Category::create([
            'name_id' => 'Mikrobiologi',
            'name_en' => 'Microbiology',
            'slug_id' => 'mikrobiologi',
            'slug_en' => 'microbiology',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $response = $this->get('/id/products?category=mikrobiologi&brand=merck');
        $response->assertStatus(200);

        $enUrl = $this->localization->getSwitchUrl('en');
        $this->assertStringContainsString('category=microbiology', $enUrl);
        $this->assertStringContainsString('brand=merck', $enUrl);
    }

    public function test_language_switcher_preserves_contact_product_id(): void
    {
        $response = $this->get('/id/contact?product_id=12');
        $response->assertStatus(200);

        $enUrl = $this->localization->getSwitchUrl('en');
        $this->assertStringContainsString('product_id=12', $enUrl);
        $this->assertStringContainsString('/en/contact', $enUrl);
    }
}
