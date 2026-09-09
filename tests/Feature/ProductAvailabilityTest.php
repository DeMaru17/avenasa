<?php

namespace Tests\Feature;

use App\Filament\Resources\Articles\Pages\EditArticle;
use App\Filament\Resources\Articles\RelationManagers\RelatedProductsRelationManager;
use App\Filament\Resources\Products\Pages\ListProducts;
use App\Models\Article;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Filament\Actions\AttachAction;
use Filament\Forms\Components\Select;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class ProductAvailabilityTest extends TestCase
{
    use RefreshDatabase;

    protected Category $category;

    protected Brand $activeBrand;

    protected Brand $inactiveBrand;

    protected User $adminUser;

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

        $this->activeBrand = Brand::create([
            'name' => 'Active Brand',
            'slug' => 'active-brand',
            'logo_path' => 'brands/active.png',
            'is_active' => true,
        ]);

        $this->inactiveBrand = Brand::create([
            'name' => 'Inactive Brand',
            'slug' => 'inactive-brand',
            'logo_path' => 'brands/inactive.png',
            'is_active' => false,
        ]);

        $this->adminUser = User::factory()->create([
            'name' => 'Admin Test',
            'email' => 'admin@avenasa.co.id',
            'password' => Hash::make('password'),
        ]);
    }

    private function createProduct(Brand $brand, bool $isActive, string $name, string $slug): Product
    {
        return Product::create([
            'category_id' => $this->category->id,
            'brand_id' => $brand->id,
            'name_id' => $name,
            'name_en' => $name.' EN',
            'slug_id' => $slug,
            'slug_en' => $slug.'-en',
            'primary_image_path' => 'products/sample.jpg',
            'is_active' => $isActive,
            'is_featured' => true,
            'sort_order' => 1,
        ]);
    }

    /**
     * Test 1-4: Scope available logic (4 quadrants).
     */
    public function test_product_availability_quadrants(): void
    {
        // 1. Active Product + Active Brand -> Available
        $prod1 = $this->createProduct($this->activeBrand, true, 'Product 1', 'prod-1');

        // 2. Inactive Product + Active Brand -> Not Available
        $prod2 = $this->createProduct($this->activeBrand, false, 'Product 2', 'prod-2');

        // 3. Active Product + Inactive Brand -> Not Available
        $prod3 = $this->createProduct($this->inactiveBrand, true, 'Product 3', 'prod-3');

        // 4. Inactive Product + Inactive Brand -> Not Available
        $prod4 = $this->createProduct($this->inactiveBrand, false, 'Product 4', 'prod-4');

        $availableIds = Product::available()->pluck('id')->all();

        $this->assertContains($prod1->id, $availableIds);
        $this->assertNotContains($prod2->id, $availableIds);
        $this->assertNotContains($prod3->id, $availableIds);
        $this->assertNotContains($prod4->id, $availableIds);

        // Product active scope still checks product is_active independently
        $activeIds = Product::active()->pluck('id')->all();
        $this->assertContains($prod1->id, $activeIds);
        $this->assertNotContains($prod2->id, $activeIds);
        $this->assertContains($prod3->id, $activeIds); // product itself is active
        $this->assertNotContains($prod4->id, $activeIds);
    }

    /**
     * Test 5: Public Product listing excludes Product with inactive Brand.
     */
    public function test_public_product_listing_excludes_product_with_inactive_brand(): void
    {
        $this->createProduct($this->activeBrand, true, 'Visible System', 'visible-sys');
        $this->createProduct($this->inactiveBrand, true, 'Hidden Brand System', 'hidden-brand-sys');

        $response = $this->get('/id/products');
        $response->assertStatus(200);
        $response->assertSee('Visible System');
        $response->assertDontSee('Hidden Brand System');
    }

    /**
     * Test 6: Public Product detail returns 404 for Product with inactive Brand.
     */
    public function test_public_product_detail_returns_404_when_brand_is_inactive(): void
    {
        $this->createProduct($this->inactiveBrand, true, 'Hidden Detail System', 'hidden-detail-sys');

        $responseId = $this->get('/id/products/hidden-detail-sys');
        $responseId->assertStatus(404);

        $responseEn = $this->get('/en/products/hidden-detail-sys-en');
        $responseEn->assertStatus(404);
    }

    /**
     * Test 7: Public Product search / filter excludes Product with inactive Brand.
     */
    public function test_public_product_filter_excludes_product_with_inactive_brand(): void
    {
        $this->createProduct($this->activeBrand, true, 'Visible In Cat', 'visible-in-cat');
        $this->createProduct($this->inactiveBrand, true, 'Hidden In Cat', 'hidden-in-cat');

        $response = $this->get('/id/products?category='.$this->category->slug_id);
        $response->assertStatus(200);
        $response->assertSee('Visible In Cat');
        $response->assertDontSee('Hidden In Cat');
    }

    /**
     * Test 8: Featured Products on homepage excludes Product with inactive Brand.
     */
    public function test_featured_product_on_homepage_excludes_inactive_brand(): void
    {
        $this->createProduct($this->activeBrand, true, 'Featured Active', 'feat-active');
        $this->createProduct($this->inactiveBrand, true, 'Featured Inactive Brand', 'feat-inactive-brand');

        $response = $this->get('/id');
        $response->assertStatus(200);
        $response->assertSee('Featured Active');
        $response->assertDontSee('Featured Inactive Brand');
    }

    /**
     * Test 9: Sitemap excludes Product with inactive Brand.
     */
    public function test_sitemap_excludes_product_with_inactive_brand(): void
    {
        $this->createProduct($this->activeBrand, true, 'Sitemap Active', 'sitemap-active');
        $this->createProduct($this->inactiveBrand, true, 'Sitemap Inactive Brand', 'sitemap-inactive-brand');

        $response = $this->get('/sitemap.xml');
        $response->assertStatus(200);
        $response->assertSee('/id/products/sitemap-active');
        $response->assertDontSee('/id/products/sitemap-inactive-brand');
    }

    /**
     * Test 10: Article Related Products on public detail excludes Product with inactive Brand.
     */
    public function test_article_related_products_public_excludes_inactive_brand(): void
    {
        $article = Article::create([
            'title_id' => 'Artikel Uji Ketersediaan',
            'title_en' => 'Availability Test Article',
            'published_at' => now()->subHour(),
            'is_active' => true,
        ]);

        $availableProduct = $this->createProduct($this->activeBrand, true, 'Related Active', 'rel-active');
        $unavailableProduct = $this->createProduct($this->inactiveBrand, true, 'Related Inactive Brand', 'rel-inactive-brand');

        $article->relatedProducts()->attach([
            $availableProduct->id => ['sort_order' => 1],
            $unavailableProduct->id => ['sort_order' => 2],
        ]);

        $response = $this->get("/id/articles/{$article->slug_id}");
        $response->assertStatus(200);
        $response->assertSee('Related Active');
        $response->assertDontSee('Related Inactive Brand');
    }

    /**
     * Test 11: Article Related Product selector in Filament excludes Product with inactive Brand.
     */
    public function test_article_related_product_selector_excludes_inactive_brand(): void
    {
        $this->actingAs($this->adminUser);

        $availableProduct = $this->createProduct($this->activeBrand, true, 'Available For Attach', 'avail-attach');
        $unavailableProduct = $this->createProduct($this->inactiveBrand, true, 'Unavailable For Attach', 'unavail-attach');

        $article = Article::create([
            'title_id' => 'Artikel Form',
            'title_en' => 'Form Article',
            'published_at' => now(),
        ]);

        // In RelationManager AttachAction
        $manager = Livewire::test(RelatedProductsRelationManager::class, [
            'ownerRecord' => $article,
            'pageClass' => EditArticle::class,
        ]);

        $action = $manager->instance()->getTable()->getAction('attach');
        $this->assertInstanceOf(AttachAction::class, $action);

        $recordSelect = $action->getRecordSelect();
        $this->assertInstanceOf(Select::class, $recordSelect);

        $results = $recordSelect->getSearchResults('');
        $this->assertArrayHasKey($availableProduct->id, $results);
        $this->assertArrayNotHasKey($unavailableProduct->id, $results);
    }

    /**
     * Test 12: Existing article_product relationship is NOT automatically deleted when Brand becomes inactive.
     */
    public function test_article_product_relationship_persists_when_brand_becomes_inactive(): void
    {
        $article = Article::create([
            'title_id' => 'Artikel Hubungan Persisten',
            'title_en' => 'Persistent Relation Article',
            'published_at' => now(),
        ]);

        $product = $this->createProduct($this->activeBrand, true, 'Persistent Product', 'persist-prod');
        $article->relatedProducts()->attach($product->id, ['sort_order' => 1]);

        $this->assertDatabaseHas('article_product', [
            'article_id' => $article->id,
            'product_id' => $product->id,
        ]);

        // Deactivate Brand
        $this->activeBrand->update(['is_active' => false]);

        // The relationship MUST remain intact in database!
        $this->assertDatabaseHas('article_product', [
            'article_id' => $article->id,
            'product_id' => $product->id,
        ]);

        // But public display hides it
        $response = $this->get("/id/articles/{$article->slug_id}");
        $response->assertStatus(200);
        $response->assertDontSee('Persistent Product');
    }

    /**
     * Test 13: Reactivation - when Brand becomes active again, Product becomes available again.
     */
    public function test_reactivation_makes_product_available_again(): void
    {
        $article = Article::create([
            'title_id' => 'Artikel Reaktivasi',
            'title_en' => 'Reactivation Article',
            'published_at' => now(),
        ]);

        $product = $this->createProduct($this->inactiveBrand, true, 'Reactivatable Product', 'react-prod');
        $article->relatedProducts()->attach($product->id, ['sort_order' => 1]);

        // Initially inactive brand: public detail 404, article does not show it
        $this->get('/id/products/react-prod')->assertStatus(404);
        $this->get("/id/articles/{$article->slug_id}")->assertDontSee('Reactivatable Product');

        // Now activate brand
        $this->inactiveBrand->update(['is_active' => true]);

        // Now product becomes available again without modifying product record!
        $this->get('/id/products/react-prod')->assertStatus(200)->assertSee('Reactivatable Product');
        $this->get("/id/articles/{$article->slug_id}")->assertSee('Reactivatable Product');
    }

    /**
     * Test 14: Product admin management - Product remains manageable in Admin even when Brand is inactive.
     */
    public function test_product_remains_visible_and_manageable_in_admin_table_when_brand_is_inactive(): void
    {
        $this->actingAs($this->adminUser);

        $productInactiveBrand = $this->createProduct($this->inactiveBrand, true, 'Admin View Inactive Brand', 'admin-inactive-brand');
        $productInactiveSelf = $this->createProduct($this->activeBrand, false, 'Admin View Inactive Self', 'admin-inactive-self');

        Livewire::test(ListProducts::class)
            ->assertCanSeeTableRecords([$productInactiveBrand, $productInactiveSelf])
            ->assertSee('Admin View Inactive Brand')
            ->assertSee('Brand Nonaktif'); // Badge description indicator
    }
}
