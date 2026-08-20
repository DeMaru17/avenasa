<?php

namespace Tests\Feature\Seeder;

use App\Models\Brand;
use App\Models\Category;
use Database\Seeders\BrandSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaxonomyAndPrincipalSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_seeder_creates_exactly_seven_active_ordered_categories_with_auto_slugs(): void
    {
        $this->seed(CategorySeeder::class);

        $this->assertEquals(7, Category::count());

        $expectedCategories = [
            1 => ['name_id' => 'Pengujian Kualitas Air & Warna', 'name_en' => 'Water Testing & Colour Measurement'],
            2 => ['name_id' => 'Keamanan Pangan & Uji Alergen', 'name_en' => 'Food Safety & Allergen Diagnostics'],
            3 => ['name_id' => 'Mikrobiologi & Media Kultur', 'name_en' => 'Microbiology & Culture Media'],
            4 => ['name_id' => 'Pengujian Endotoksin & Pirogen', 'name_en' => 'Endotoxin & Pyrogen Testing'],
            5 => ['name_id' => 'Pemantauan Sterilisasi', 'name_en' => 'Sterilization Monitoring'],
            6 => ['name_id' => 'Peralatan & Instrumen Laboratorium', 'name_en' => 'Laboratory Equipment & Instruments'],
            7 => ['name_id' => 'Bahan Kimia, Solven & Solusi Lingkungan', 'name_en' => 'Chemicals, Solvents & Environmental Solutions'],
        ];

        foreach ($expectedCategories as $sortOrder => $data) {
            $category = Category::where('sort_order', $sortOrder)->first();
            $this->assertNotNull($category, "Category with sort_order {$sortOrder} should exist.");
            $this->assertEquals($data['name_id'], $category->name_id);
            $this->assertEquals($data['name_en'], $category->name_en);
            $this->assertNotEmpty($category->slug_id, "slug_id for category {$sortOrder} should be auto-generated.");
            $this->assertNotEmpty($category->slug_en, "slug_en for category {$sortOrder} should be auto-generated.");
            $this->assertTrue($category->is_active);
        }

        // Test idempotency: re-running should not create duplicates
        $this->seed(CategorySeeder::class);
        $this->assertEquals(7, Category::count());
    }

    public function test_brand_seeder_creates_exactly_eighteen_brands_with_correct_principal_classification(): void
    {
        $this->seed(BrandSeeder::class);

        $this->assertEquals(18, Brand::count());

        // Check ERA Biology is marked as new principal
        $eraBiology = Brand::where('name', 'ERA Biology')->first();
        $this->assertNotNull($eraBiology);
        $this->assertTrue($eraBiology->is_new_principal, 'ERA Biology must have is_new_principal = true.');
        $this->assertTrue($eraBiology->is_active);
        $this->assertEmpty($eraBiology->logo_path);
        $this->assertEquals('era-biology', $eraBiology->slug);

        // Check that other brands have is_new_principal = false
        $otherBrandsCount = Brand::where('is_new_principal', false)->count();
        $this->assertEquals(17, $otherBrandsCount);

        // All 18 brands must be active and have auto-generated slugs
        foreach (Brand::all() as $brand) {
            $this->assertTrue($brand->is_active);
            $this->assertNotEmpty($brand->slug);
            $this->assertEmpty($brand->logo_path);
        }

        // Test idempotency: re-running should not create duplicates
        $this->seed(BrandSeeder::class);
        $this->assertEquals(18, Brand::count());
    }

    public function test_database_seeder_runs_all_seeders_cleanly_including_taxonomy_and_brands(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertEquals(7, Category::count());
        $this->assertEquals(18, Brand::count());
        $this->assertEquals(1, Brand::where('is_new_principal', true)->count());
    }
}
