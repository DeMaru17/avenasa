<?php

namespace Tests\Feature\Localization;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicProductCatalogTest extends TestCase
{
    use RefreshDatabase;

    private function createCategory(array $overrides = []): Category
    {
        return Category::create(array_merge([
            'name_id' => 'Mikrobiologi',
            'name_en' => 'Microbiology',
            'slug_id' => 'mikrobiologi',
            'slug_en' => 'microbiology',
            'sort_order' => 1,
            'is_active' => true,
        ], $overrides));
    }

    private function createBrand(array $overrides = []): Brand
    {
        return Brand::create(array_merge([
            'name' => 'Merck',
            'slug' => 'merck',
            'logo_path' => 'brands/merck.png',
            'description_id' => 'Prinsipal reagen kimia dan laboratorium.',
            'description_en' => 'Chemical and laboratory reagent principal.',
            'sort_order' => 1,
            'is_active' => true,
        ], $overrides));
    }

    private function createProduct(Category $category, Brand $brand, array $overrides = []): Product
    {
        return Product::create(array_merge([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'name_id' => 'Media Kultur Agar',
            'name_en' => 'Culture Media Agar',
            'slug_id' => 'media-kultur-agar',
            'slug_en' => 'culture-media-agar',
            'summary_id' => 'Media kultur mikrobiologi berkualitas tinggi.',
            'summary_en' => 'High-quality microbiology culture media.',
            'primary_image_path' => 'products/sample.jpg',
            'is_active' => true,
            'is_featured' => false,
            'sort_order' => 1,
        ], $overrides));
    }

    public function test_catalog_index_returns_successful_response_for_both_locales(): void
    {
        $category = $this->createCategory();
        $brand = $this->createBrand();
        $this->createProduct($category, $brand);

        $idResponse = $this->get('/id/products');
        $idResponse->assertStatus(200);
        $idResponse->assertViewIs('pages.products.index');
        $idResponse->assertSee('Katalog Produk');
        $idResponse->assertSee('Media Kultur Agar');

        $enResponse = $this->get('/en/products');
        $enResponse->assertStatus(200);
        $enResponse->assertViewIs('pages.products.index');
        $enResponse->assertSee('Product Catalog');
        $enResponse->assertSee('Culture Media Agar');
    }

    public function test_public_visibility_respects_product_category_and_brand_active_state(): void
    {
        $activeCat = $this->createCategory(['slug_id' => 'cat-active', 'slug_en' => 'cat-active-en']);
        $inactiveCat = $this->createCategory(['slug_id' => 'cat-inactive', 'slug_en' => 'cat-inactive-en', 'is_active' => false]);

        $activeBrand = $this->createBrand(['slug' => 'brand-active']);
        $inactiveBrand = $this->createBrand(['slug' => 'brand-inactive', 'is_active' => false]);

        // 1. All Active -> Visible
        $visibleProduct = $this->createProduct($activeCat, $activeBrand, [
            'name_id' => 'Visible Product',
            'name_en' => 'Visible Product EN',
            'slug_id' => 'visible-product',
            'slug_en' => 'visible-product-en',
            'is_active' => true,
        ]);

        // 2. Inactive Product -> Hidden
        $inactiveProduct = $this->createProduct($activeCat, $activeBrand, [
            'name_id' => 'Inactive Product',
            'name_en' => 'Inactive Product EN',
            'slug_id' => 'inactive-product',
            'slug_en' => 'inactive-product-en',
            'is_active' => false,
        ]);

        // 3. Inactive Category -> Hidden
        $inactiveCatProduct = $this->createProduct($inactiveCat, $activeBrand, [
            'name_id' => 'Inactive Category Product',
            'name_en' => 'Inactive Category Product EN',
            'slug_id' => 'inactive-cat-product',
            'slug_en' => 'inactive-cat-product-en',
            'is_active' => true,
        ]);

        // 4. Inactive Brand -> Hidden
        $inactiveBrandProduct = $this->createProduct($activeCat, $inactiveBrand, [
            'name_id' => 'Inactive Brand Product',
            'name_en' => 'Inactive Brand Product EN',
            'slug_id' => 'inactive-brand-product',
            'slug_en' => 'inactive-brand-product-en',
            'is_active' => true,
        ]);

        $response = $this->get('/id/products');
        $response->assertStatus(200);
        $response->assertSee('Visible Product');
        $response->assertDontSee('Inactive Product');
        $response->assertDontSee('Inactive Category Product');
        $response->assertDontSee('Inactive Brand Product');
    }

    public function test_category_filter_works_with_localized_slug(): void
    {
        $catMicro = $this->createCategory([
            'name_id' => 'Mikrobiologi',
            'name_en' => 'Microbiology',
            'slug_id' => 'mikrobiologi',
            'slug_en' => 'microbiology',
        ]);
        $catMolecular = $this->createCategory([
            'name_id' => 'Biologi Molekuler',
            'name_en' => 'Molecular Biology',
            'slug_id' => 'biologi-molekuler',
            'slug_en' => 'molecular-biology',
        ]);
        $brand = $this->createBrand();

        $productMicro = $this->createProduct($catMicro, $brand, [
            'name_id' => 'Produk Mikrobiologi',
            'name_en' => 'Microbiology Product',
            'slug_id' => 'produk-mikro',
            'slug_en' => 'micro-product',
        ]);

        $productMolecular = $this->createProduct($catMolecular, $brand, [
            'name_id' => 'Produk Molekuler',
            'name_en' => 'Molecular Product',
            'slug_id' => 'produk-molekuler',
            'slug_en' => 'molecular-product',
        ]);

        // Test Indonesian filter
        $idResponse = $this->get('/id/products?category=mikrobiologi');
        $idResponse->assertStatus(200);
        $idResponse->assertSee('Produk Mikrobiologi');
        $idResponse->assertDontSee('Produk Molekuler');

        // Test English filter
        $enResponse = $this->get('/en/products?category=microbiology');
        $enResponse->assertStatus(200);
        $enResponse->assertSee('Microbiology Product');
        $enResponse->assertDontSee('Molecular Product');
    }

    public function test_brand_filter_works_with_universal_slug(): void
    {
        $category = $this->createCategory();
        $brandMerck = $this->createBrand(['name' => 'Merck', 'slug' => 'merck']);
        $brandNeogen = $this->createBrand(['name' => 'Neogen', 'slug' => 'neogen']);

        $productMerck = $this->createProduct($category, $brandMerck, [
            'name_id' => 'Produk Merck',
            'name_en' => 'Merck Product',
            'slug_id' => 'produk-merck',
            'slug_en' => 'product-merck',
        ]);
        $productNeogen = $this->createProduct($category, $brandNeogen, [
            'name_id' => 'Produk Neogen',
            'name_en' => 'Neogen Product',
            'slug_id' => 'produk-neogen',
            'slug_en' => 'product-neogen',
        ]);

        $response = $this->get('/id/products?brand=merck');
        $response->assertStatus(200);
        $response->assertSee('Produk Merck');
        $response->assertDontSee('Produk Neogen');
    }

    public function test_combined_category_and_brand_filter_uses_and_logic(): void
    {
        $catMicro = $this->createCategory(['slug_id' => 'mikrobiologi', 'slug_en' => 'microbiology']);
        $catMolecular = $this->createCategory(['slug_id' => 'molekuler', 'slug_en' => 'molecular']);

        $brandMerck = $this->createBrand(['name' => 'Merck', 'slug' => 'merck']);
        $brandNeogen = $this->createBrand(['name' => 'Neogen', 'slug' => 'neogen']);

        // Match BOTH: Micro + Merck
        $matchBoth = $this->createProduct($catMicro, $brandMerck, [
            'name_id' => 'Match Both Product',
            'name_en' => 'Match Both Product EN',
            'slug_id' => 'match-both',
            'slug_en' => 'match-both-en',
        ]);

        // Match Category only: Micro + Neogen
        $matchCatOnly = $this->createProduct($catMicro, $brandNeogen, [
            'name_id' => 'Match Category Only',
            'name_en' => 'Match Category Only EN',
            'slug_id' => 'match-cat-only',
            'slug_en' => 'match-cat-only-en',
        ]);

        // Match Brand only: Molecular + Merck
        $matchBrandOnly = $this->createProduct($catMolecular, $brandMerck, [
            'name_id' => 'Match Brand Only',
            'name_en' => 'Match Brand Only EN',
            'slug_id' => 'match-brand-only',
            'slug_en' => 'match-brand-only-en',
        ]);

        $response = $this->get('/id/products?category=mikrobiologi&brand=merck');
        $response->assertStatus(200);
        $response->assertSee('Match Both Product');
        $response->assertDontSee('Match Category Only');
        $response->assertDontSee('Match Brand Only');
    }

    public function test_pagination_is_12_products_per_page_and_preserves_query_string(): void
    {
        $category = $this->createCategory(['slug_id' => 'mikrobiologi', 'slug_en' => 'microbiology']);
        $brand = $this->createBrand(['slug' => 'merck']);

        for ($i = 1; $i <= 15; $i++) {
            $this->createProduct($category, $brand, [
                'name_id' => sprintf('Product %02d', $i),
                'name_en' => sprintf('Product %02d EN', $i),
                'slug_id' => sprintf('product-%02d', $i),
                'slug_en' => sprintf('product-en-%02d', $i),
                'sort_order' => $i,
            ]);
        }

        // Page 1 with filter
        $page1Response = $this->get('/id/products?category=mikrobiologi&brand=merck');
        $page1Response->assertStatus(200);
        $page1Response->assertSee('Product 01');
        $page1Response->assertSee('Product 12');
        $page1Response->assertDontSee('Product 13');

        // Page 2 with filter preserved
        $page2Response = $this->get('/id/products?category=mikrobiologi&brand=merck&page=2');
        $page2Response->assertStatus(200);
        $page2Response->assertSee('Product 13');
        $page2Response->assertSee('Product 15');
        $page2Response->assertDontSee('Product 01');
    }

    public function test_invalid_filter_slugs_render_graceful_empty_state_without_exception(): void
    {
        $category = $this->createCategory();
        $brand = $this->createBrand();
        $this->createProduct($category, $brand);

        $invalidCatResponse = $this->get('/id/products?category=invalid-category-slug');
        $invalidCatResponse->assertStatus(200);
        $invalidCatResponse->assertSee('Tidak Ada Produk yang Sesuai');
        $invalidCatResponse->assertSee('Reset Semua Filter');

        $invalidBrandResponse = $this->get('/en/products?brand=invalid-brand-slug');
        $invalidBrandResponse->assertStatus(200);
        $invalidBrandResponse->assertSee('No Products Found');
        $invalidBrandResponse->assertSee('Reset All Filters');
    }

    public function test_seo_robots_tag_is_noindex_follow_on_filtered_urls(): void
    {
        $category = $this->createCategory(['slug_id' => 'mikrobiologi', 'slug_en' => 'microbiology']);
        $brand = $this->createBrand();
        $this->createProduct($category, $brand);

        // Unfiltered -> no noindex tag in head
        $unfilteredResponse = $this->get('/id/products');
        $unfilteredResponse->assertStatus(200);
        $unfilteredResponse->assertDontSee('<meta name="robots" content="noindex, follow">', false);

        // Filtered -> contains noindex, follow
        $filteredResponse = $this->get('/id/products?category=mikrobiologi');
        $filteredResponse->assertStatus(200);
        $filteredResponse->assertSee('<meta name="robots" content="noindex, follow">', false);
    }

    public function test_language_switcher_preserves_filter_and_maps_category_slug(): void
    {
        $category = $this->createCategory([
            'slug_id' => 'mikrobiologi',
            'slug_en' => 'microbiology',
        ]);
        $brand = $this->createBrand(['slug' => 'merck']);
        $this->createProduct($category, $brand);

        $idResponse = $this->get('/id/products?category=mikrobiologi&brand=merck');
        $idResponse->assertStatus(200);
        $idResponse->assertSee('/en/products?category=microbiology&amp;brand=merck', false);

        $enResponse = $this->get('/en/products?category=microbiology&brand=merck');
        $enResponse->assertStatus(200);
        $enResponse->assertSee('/id/products?category=mikrobiologi&amp;brand=merck', false);
    }

    public function test_brand_filter_renders_realtime_search_and_bounded_scrollable_markup(): void
    {
        $category = $this->createCategory();
        $brand = $this->createBrand(['name' => 'Lovibond', 'slug' => 'lovibond']);
        $this->createProduct($category, $brand);

        // Indonesian catalog
        $idResponse = $this->get('/id/products');
        $idResponse->assertStatus(200);
        $idResponse->assertSee('placeholder="Cari brand..."', false);
        $idResponse->assertSee('max-h-60 overflow-y-auto', false);
        $idResponse->assertSee('Brand tidak ditemukan.');
        $idResponse->assertSee('Lovibond');

        // English catalog
        $enResponse = $this->get('/en/products');
        $enResponse->assertStatus(200);
        $enResponse->assertSee('placeholder="Search brands..."', false);
        $enResponse->assertSee('max-h-60 overflow-y-auto', false);
        $enResponse->assertSee('No brands found.');
        $enResponse->assertSee('Lovibond');
    }
}
