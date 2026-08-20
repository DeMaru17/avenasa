<?php

namespace Tests\Feature\Seeder;

use App\Models\Brand;
use App\Models\Category;
use App\Models\CompanyProfile;
use App\Models\CoreValue;
use App\Models\Management;
use App\Models\Product;
use Database\Seeders\BrandSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\ProductSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductSeederTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(CategorySeeder::class);
        $this->seed(BrandSeeder::class);
    }

    public function test_product_seeder_creates_exactly_fifty_verified_products(): void
    {
        $this->seed(ProductSeeder::class);

        $this->assertEquals(50, Product::count());
        $this->assertEquals(50, Product::active()->count());
    }

    public function test_seeded_products_have_valid_auto_generated_slugs_and_identities(): void
    {
        $this->seed(ProductSeeder::class);

        foreach (Product::all() as $product) {
            $this->assertNotEmpty($product->name_id);
            $this->assertNotEmpty($product->name_en);
            $this->assertEquals($product->name_en, $product->name_id, "Product '{$product->name_en}' must adhere to official manufacturer naming policy.");
            $this->assertNotEmpty($product->slug_id, "slug_id for product '{$product->name_en}' should be auto-generated.");
            $this->assertNotEmpty($product->slug_en, "slug_en for product '{$product->name_en}' should be auto-generated.");
            $this->assertEquals($product->slug_en, $product->slug_id, "slug_id and slug_en should match for product '{$product->name_en}'.");
            $this->assertNotNull($product->category_id);
            $this->assertNotNull($product->brand_id);
            $this->assertEmpty($product->primary_image_path, 'Initial primary_image_path must be empty/pending.');
            $this->assertNull($product->brochure_path, 'Initial brochure_path must be null.');
        }
    }

    public function test_seeded_products_have_valid_brand_and_category_relationships(): void
    {
        $this->seed(ProductSeeder::class);

        $product = Product::where('name_en', 'Photometer MD Series')->first();
        $this->assertNotNull($product);
        $this->assertInstanceOf(Brand::class, $product->brand);
        $this->assertEquals('Lovibond', $product->brand->name);
        $this->assertInstanceOf(Category::class, $product->category);
        $this->assertEquals('Water Testing & Colour Measurement', $product->category->name_en);

        $elisa = Product::where('name_en', 'SENSISpec Allergen Detection ELISA Kits')->first();
        $this->assertNotNull($elisa);
        $this->assertEquals('Gold Standard Diagnostics', $elisa->brand->name);
        $this->assertEquals('Food Safety & Allergen Diagnostics', $elisa->category->name_en);

        $bioreactor = Product::where('name_en', 'HABITAT Research Benchtop Bioreactor')->first();
        $this->assertNotNull($bioreactor);
        $this->assertEquals('IKA', $bioreactor->brand->name);
        $this->assertEquals('Laboratory Equipment & Instruments', $bioreactor->category->name_en);
    }

    public function test_seeded_products_have_valid_bilingual_specifications_json(): void
    {
        $this->seed(ProductSeeder::class);

        $product = Product::where('name_en', 'Photometer MD Series')->first();
        $this->assertNotNull($product);
        $this->assertIsArray($product->specifications);
        $this->assertNotEmpty($product->specifications);

        foreach ($product->specifications as $spec) {
            $this->assertArrayHasKey('key_id', $spec);
            $this->assertArrayHasKey('key_en', $spec);
            $this->assertArrayHasKey('value_id', $spec);
            $this->assertArrayHasKey('value_en', $spec);
            $this->assertNotEmpty($spec['key_id']);
            $this->assertNotEmpty($spec['key_en']);
            $this->assertNotEmpty($spec['value_id']);
            $this->assertNotEmpty($spec['value_en']);
        }
    }

    public function test_product_seeder_is_idempotent(): void
    {
        $this->seed(ProductSeeder::class);
        $this->assertEquals(50, Product::count());

        // Re-run seeder
        $this->seed(ProductSeeder::class);
        $this->assertEquals(50, Product::count());
    }

    public function test_database_seeder_runs_entire_suite_including_products_cleanly(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertEquals(1, CompanyProfile::count());
        $this->assertEquals(6, CoreValue::count());
        $this->assertEquals(3, Management::count());
        $this->assertEquals(7, Category::count());
        $this->assertEquals(18, Brand::count());
        $this->assertEquals(50, Product::count());
    }
}
