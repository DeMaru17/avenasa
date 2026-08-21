<?php

namespace Tests\Feature\Localization;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocalizedSlugResolutionTest extends TestCase
{
    use RefreshDatabase;

    protected Category $category;

    protected Brand $brand;

    protected Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->category = Category::create([
            'name_id' => 'Mikrobiologi',
            'name_en' => 'Microbiology',
            'slug_id' => 'mikrobiologi',
            'slug_en' => 'microbiology',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $this->brand = Brand::create([
            'name' => 'Merck',
            'slug' => 'merck',
            'logo_path' => '',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $this->product = Product::create([
            'category_id' => $this->category->id,
            'brand_id' => $this->brand->id,
            'name_id' => 'Cawan Petri Steril 90mm',
            'name_en' => 'Sterile Petri Dish 90mm',
            'slug_id' => 'cawan-petri-steril-90mm',
            'slug_en' => 'sterile-petri-dish-90mm',
            'summary_id' => 'Cawan petri berkualitas tinggi untuk kultur mikrobiologi.',
            'summary_en' => 'High quality petri dish for microbiological culture.',
            'primary_image_path' => '',
            'is_active' => true,
            'sort_order' => 1,
        ]);
    }

    public function test_indonesian_product_route_resolves_via_slug_id(): void
    {
        $response = $this->get('/id/products/cawan-petri-steril-90mm');

        $response->assertStatus(200);
        $response->assertSee('Cawan Petri Steril 90mm');
        $this->assertSame('id', app()->getLocale());
    }

    public function test_english_product_route_resolves_via_slug_en(): void
    {
        $response = $this->get('/en/products/sterile-petri-dish-90mm');

        $response->assertStatus(200);
        $response->assertSee('Sterile Petri Dish 90mm');
        $this->assertSame('en', app()->getLocale());
    }

    public function test_cross_language_slug_on_id_route_returns_404(): void
    {
        // Accessing English slug on /id prefix must strictly return 404
        $response = $this->get('/id/products/sterile-petri-dish-90mm');

        $response->assertStatus(404);
    }

    public function test_cross_language_slug_on_en_route_returns_404(): void
    {
        // Accessing Indonesian slug on /en prefix must strictly return 404
        $response = $this->get('/en/products/cawan-petri-steril-90mm');

        $response->assertStatus(404);
    }

    public function test_inactive_product_returns_404_on_both_locales(): void
    {
        $inactiveProduct = Product::create([
            'category_id' => $this->category->id,
            'brand_id' => $this->brand->id,
            'name_id' => 'Produk Nonaktif',
            'name_en' => 'Inactive Product',
            'slug_id' => 'produk-nonaktif',
            'slug_en' => 'inactive-product',
            'primary_image_path' => '',
            'is_active' => false,
            'sort_order' => 2,
        ]);

        $this->get('/id/products/produk-nonaktif')->assertStatus(404);
        $this->get('/en/products/inactive-product')->assertStatus(404);
    }
}
