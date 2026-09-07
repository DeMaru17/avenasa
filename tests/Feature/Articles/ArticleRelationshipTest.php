<?php

namespace Tests\Feature\Articles;

use App\Models\Article;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ArticleRelationshipTest extends TestCase
{
    use RefreshDatabase;

    protected Category $category;

    protected Brand $brand;

    protected function setUp(): void
    {
        parent::setUp();

        $this->category = Category::create([
            'name_id' => 'Kategori Uji',
            'name_en' => 'Test Category',
            'slug_id' => 'kategori-uji',
            'slug_en' => 'test-category',
            'is_active' => true,
        ]);

        $this->brand = Brand::create([
            'name' => 'Brand Uji',
            'slug' => 'brand-uji',
            'logo_path' => 'brands/brand-uji.png',
            'is_active' => true,
        ]);
    }

    public function test_article_and_product_many_to_many_relationship(): void
    {
        $article = Article::create([
            'title_id' => 'Artikel Uji Relasi',
            'title_en' => 'Relation Test Article',
            'published_at' => now()->subHour(),
        ]);

        $product = Product::create([
            'category_id' => $this->category->id,
            'brand_id' => $this->brand->id,
            'name_id' => 'Produk A',
            'name_en' => 'Product A',
            'primary_image_path' => 'products/primary/test.png',
            'is_active' => true,
            'sort_order' => 10,
        ]);

        $article->relatedProducts()->attach($product->id, ['sort_order' => 1]);

        $this->assertCount(1, $article->relatedProducts);
        $this->assertEquals('Produk A', $article->relatedProducts->first()->name_id);
        $this->assertEquals(1, $article->relatedProducts->first()->pivot->sort_order);

        // Test inverse relationship
        $this->assertCount(1, $product->articles);
        $this->assertEquals('Artikel Uji Relasi', $product->articles->first()->title_id);
    }

    public function test_reordering_related_products_only_modifies_pivot_sort_order_and_never_touches_products_table(): void
    {
        $article = Article::create([
            'title_id' => 'Panduan Instrumen Lab',
            'title_en' => 'Lab Instrument Guide',
            'published_at' => now()->subHour(),
        ]);

        $productA = Product::create([
            'category_id' => $this->category->id,
            'brand_id' => $this->brand->id,
            'name_id' => 'Produk Alpha',
            'name_en' => 'Product Alpha',
            'primary_image_path' => 'products/primary/alpha.png',
            'is_active' => true,
            'sort_order' => 100, // Global catalog order
        ]);

        $productB = Product::create([
            'category_id' => $this->category->id,
            'brand_id' => $this->brand->id,
            'name_id' => 'Produk Beta',
            'name_en' => 'Product Beta',
            'primary_image_path' => 'products/primary/beta.png',
            'is_active' => true,
            'sort_order' => 200, // Global catalog order
        ]);

        $productC = Product::create([
            'category_id' => $this->category->id,
            'brand_id' => $this->brand->id,
            'name_id' => 'Produk Gamma',
            'name_en' => 'Product Gamma',
            'primary_image_path' => 'products/primary/gamma.png',
            'is_active' => true,
            'sort_order' => 300, // Global catalog order
        ]);

        // Initial attachment: A=1, B=2, C=3
        $article->relatedProducts()->attach([
            $productA->id => ['sort_order' => 1],
            $productB->id => ['sort_order' => 2],
            $productC->id => ['sort_order' => 3],
        ]);

        // Execute explicit reorder: C=1, A=2, B=3
        $newOrder = [$productC->id, $productA->id, $productB->id];
        foreach ($newOrder as $index => $productId) {
            DB::table('article_product')
                ->where('article_id', $article->id)
                ->where('product_id', $productId)
                ->update(['sort_order' => $index + 1]);
        }

        // Verify pivot table reflects the new order
        $this->assertEquals(1, DB::table('article_product')->where('article_id', $article->id)->where('product_id', $productC->id)->value('sort_order'));
        $this->assertEquals(2, DB::table('article_product')->where('article_id', $article->id)->where('product_id', $productA->id)->value('sort_order'));
        $this->assertEquals(3, DB::table('article_product')->where('article_id', $article->id)->where('product_id', $productB->id)->value('sort_order'));

        // Refresh and check ordered collection
        $article->refresh();
        $orderedProducts = $article->relatedProducts()->get();
        $this->assertEquals('Produk Gamma', $orderedProducts[0]->name_id);
        $this->assertEquals('Produk Alpha', $orderedProducts[1]->name_id);
        $this->assertEquals('Produk Beta', $orderedProducts[2]->name_id);

        // CRITICAL CHECK: Ensure products.sort_order on the products table has NOT been touched!
        $this->assertEquals(100, $productA->fresh()->sort_order);
        $this->assertEquals(200, $productB->fresh()->sort_order);
        $this->assertEquals(300, $productC->fresh()->sort_order);
    }

    public function test_duplicate_product_attachment_is_prevented_by_unique_constraint(): void
    {
        $article = Article::create([
            'title_id' => 'Artikel Duplikasi',
            'title_en' => 'Duplicate Article',
            'published_at' => now()->subHour(),
        ]);

        $product = Product::create([
            'category_id' => $this->category->id,
            'brand_id' => $this->brand->id,
            'name_id' => 'Produk Tunggal',
            'name_en' => 'Single Product',
            'primary_image_path' => 'products/primary/test.png',
            'is_active' => true,
        ]);

        $article->relatedProducts()->attach($product->id, ['sort_order' => 1]);

        $this->expectException(QueryException::class);
        $article->relatedProducts()->attach($product->id, ['sort_order' => 2]);
    }
}
