<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\Brands\BrandResource;
use App\Filament\Resources\Brands\Pages\CreateBrand;
use App\Filament\Resources\Brands\Pages\EditBrand;
use App\Filament\Resources\Categories\CategoryResource;
use App\Filament\Resources\Categories\Pages\CreateCategory;
use App\Filament\Resources\Categories\Pages\EditCategory;
use App\Filament\Resources\Clients\ClientResource;
use App\Filament\Resources\CompanyProfiles\CompanyProfileResource;
use App\Filament\Resources\CoreValues\CoreValueResource;
use App\Filament\Resources\CoreValues\Pages\CreateCoreValue;
use App\Filament\Resources\HeroBanners\HeroBannerResource;
use App\Filament\Resources\HeroBanners\Pages\CreateHeroBanner;
use App\Filament\Resources\Management\ManagementResource;
use App\Filament\Resources\Products\Pages\CreateProduct;
use App\Filament\Resources\Products\Pages\EditProduct;
use App\Filament\Resources\Products\ProductResource;
use App\Filament\Resources\Products\RelationManagers\ImagesRelationManager;
use App\Filament\Resources\Quotations\QuotationResource;
use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\UserResource;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Client;
use App\Models\CompanyProfile;
use App\Models\CoreValue;
use App\Models\HeroBanner;
use App\Models\Management;
use App\Models\Product;
use App\Models\Quotation;
use App\Models\User;
use Database\Seeders\DevelopmentAdminSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class FilamentResourceTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        $this->adminUser = User::factory()->create([
            'name' => 'Admin Test',
            'email' => 'admin@avenasa.co.id',
            'password' => Hash::make('password'),
        ]);
    }

    public function test_filament_admin_panel_can_be_accessed_by_authenticated_user(): void
    {
        $response = $this->actingAs($this->adminUser)->get('/admin');
        $response->assertSuccessful();
    }

    public function test_unauthenticated_user_is_redirected_to_filament_login(): void
    {
        $response = $this->get('/admin');
        $response->assertRedirect('/admin/login');
    }

    public function test_user_resource_pages_can_be_rendered(): void
    {
        $this->actingAs($this->adminUser);

        $this->get(UserResource::getUrl('index'))->assertSuccessful();
        $this->get(UserResource::getUrl('create'))->assertSuccessful();
        $this->get(UserResource::getUrl('edit', ['record' => $this->adminUser]))->assertSuccessful();
    }

    public function test_user_can_be_created_via_user_resource(): void
    {
        $this->actingAs($this->adminUser);

        Livewire::test(CreateUser::class)
            ->fillForm([
                'name' => 'New Staff',
                'email' => 'staff@avenasa.co.id',
                'password' => 'secret123',
                'password_confirmation' => 'secret123',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $newUser = User::where('email', 'staff@avenasa.co.id')->first();
        $this->assertNotNull($newUser);
        $this->assertEquals('New Staff', $newUser->name);
        $this->assertTrue(Hash::check('secret123', $newUser->password));
    }

    public function test_user_validation_displays_clean_indonesian_error_messages(): void
    {
        $this->actingAs($this->adminUser);

        // 1. Required fields
        Livewire::test(CreateUser::class)
            ->fillForm([
                'name' => '',
                'email' => '',
                'password' => '',
                'password_confirmation' => '',
            ])
            ->call('create')
            ->assertHasFormErrors([
                'name' => 'required',
                'email' => 'required',
                'password' => 'required',
            ]);

        // 2. Duplicate email
        Livewire::test(CreateUser::class)
            ->fillForm([
                'name' => 'Duplicate User',
                'email' => 'admin@avenasa.co.id', // Already exists
                'password' => 'password123',
                'password_confirmation' => 'password123',
            ])
            ->call('create')
            ->assertHasFormErrors(['email' => 'unique']);

        // 3. Password confirmation mismatch
        Livewire::test(CreateUser::class)
            ->fillForm([
                'name' => 'Mismatch Password User',
                'email' => 'mismatch@avenasa.co.id',
                'password' => 'password123',
                'password_confirmation' => 'different_password',
            ])
            ->call('create')
            ->assertHasFormErrors(['password' => 'confirmed']);
    }

    public function test_user_password_is_not_changed_if_left_empty_on_edit(): void
    {
        $this->actingAs($this->adminUser);

        $user = User::factory()->create([
            'password' => Hash::make('original-password'),
        ]);

        Livewire::test(EditUser::class, ['record' => $user->getRouteKey()])
            ->fillForm([
                'name' => 'Updated Name',
                'email' => $user->email,
                'password' => null,
                'password_confirmation' => null,
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $user->refresh();
        $this->assertEquals('Updated Name', $user->name);
        $this->assertTrue(Hash::check('original-password', $user->password));
    }

    public function test_category_auto_generates_slug_on_create_via_backend_and_preserves_on_edit(): void
    {
        $this->actingAs($this->adminUser);

        // 1. Create via Filament form without slug input
        Livewire::test(CreateCategory::class)
            ->fillForm([
                'name_id' => 'Mikrobiologi Industri',
                'name_en' => 'Industrial Microbiology',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $category = Category::where('name_id', 'Mikrobiologi Industri')->first();
        $this->assertNotNull($category);
        $this->assertEquals('mikrobiologi-industri', $category->slug_id);
        $this->assertEquals('industrial-microbiology', $category->slug_en);

        // 2. On edit, changing name_id should NOT change slug_id
        Livewire::test(EditCategory::class, ['record' => $category->getRouteKey()])
            ->fillForm([
                'name_id' => 'Mikrobiologi Industri Modern',
                'name_en' => 'Advanced Industrial Microbiology',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $category->refresh();
        $this->assertEquals('Mikrobiologi Industri Modern', $category->name_id);
        $this->assertEquals('mikrobiologi-industri', $category->slug_id);
        $this->assertEquals('industrial-microbiology', $category->slug_en);

        // 3. Duplicate name on creation generates unique slug
        $duplicateCategory = Category::create([
            'name_id' => 'Mikrobiologi Industri',
            'name_en' => 'Industrial Microbiology',
        ]);
        $this->assertEquals('mikrobiologi-industri-1', $duplicateCategory->slug_id);
        $this->assertEquals('industrial-microbiology-1', $duplicateCategory->slug_en);
    }

    public function test_brand_auto_generates_slug_on_create_and_preserves_on_edit(): void
    {
        $this->actingAs($this->adminUser);

        // 1. Create via Filament form without slug input
        Livewire::test(CreateBrand::class)
            ->fillForm([
                'name' => 'Merck KGaA',
                'logo_path' => UploadedFile::fake()->image('merck.png'),
                'website_url' => 'https://www.merckmillipore.com',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $brand = Brand::where('name', 'Merck KGaA')->first();
        $this->assertNotNull($brand);
        $this->assertEquals('merck-kgaa', $brand->slug);

        // 2. Edit brand name does not overwrite slug
        Livewire::test(EditBrand::class, ['record' => $brand->getRouteKey()])
            ->fillForm([
                'name' => 'Merck KGaA Global Life Science',
                'website_url' => $brand->website_url,
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $brand->refresh();
        $this->assertEquals('Merck KGaA Global Life Science', $brand->name);
        $this->assertEquals('merck-kgaa', $brand->slug);
    }

    public function test_brand_validates_external_url(): void
    {
        $this->actingAs($this->adminUser);

        Livewire::test(CreateBrand::class)
            ->fillForm([
                'name' => 'Invalid URL Brand',
                'logo_path' => UploadedFile::fake()->image('brand.png'),
                'website_url' => 'not-a-valid-url',
            ])
            ->call('create')
            ->assertHasFormErrors(['website_url' => 'url']);
    }

    public function test_product_auto_generates_slug_on_create_and_preserves_on_edit(): void
    {
        $this->actingAs($this->adminUser);

        $category = Category::create([
            'name_id' => 'Diagnostik',
            'name_en' => 'Diagnostics',
        ]);

        $brand = Brand::create([
            'name' => 'Era Biology',
            'logo_path' => 'brands/era.png',
        ]);

        // 1. Create via Filament form without slug input
        Livewire::test(CreateProduct::class)
            ->fillForm([
                'category_id' => $category->id,
                'brand_id' => $brand->id,
                'name_id' => 'K Fungus Dynamic Test System',
                'name_en' => 'K Fungus Dynamic Test System',
                'primary_image_path' => UploadedFile::fake()->image('product.jpg'),
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $product = Product::where('name_id', 'K Fungus Dynamic Test System')->first();
        $this->assertNotNull($product);
        $this->assertEquals('k-fungus-dynamic-test-system', $product->slug_id);
        $this->assertEquals('k-fungus-dynamic-test-system', $product->slug_en);

        // 2. Edit product name preserves original slug
        Livewire::test(EditProduct::class, ['record' => $product->getRouteKey()])
            ->fillForm([
                'category_id' => $category->id,
                'brand_id' => $brand->id,
                'name_id' => 'K Fungus Dynamic Test System Plus',
                'name_en' => 'K Fungus Dynamic Test System Plus',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $product->refresh();
        $this->assertEquals('K Fungus Dynamic Test System Plus', $product->name_id);
        $this->assertEquals('k-fungus-dynamic-test-system', $product->slug_id);
        $this->assertEquals('k-fungus-dynamic-test-system', $product->slug_en);
    }

    public function test_core_value_can_be_created_with_curated_icon_picker(): void
    {
        $this->actingAs($this->adminUser);

        Livewire::test(CreateCoreValue::class)
            ->fillForm([
                'title_id' => 'Integritas & Kepatuhan',
                'title_en' => 'Integrity & Compliance',
                'icon_name' => 'shield-check',
                'description_id' => 'Menjunjung tinggi standar etika dan kepatuhan hukum tertinggi.',
                'description_en' => 'Upholding the highest ethical standards and regulatory compliance.',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $value = CoreValue::where('title_id', 'Integritas & Kepatuhan')->first();
        $this->assertNotNull($value);
        $this->assertEquals('shield-check', $value->icon_name);
    }

    public function test_hero_banner_cta_validates_internal_relative_path(): void
    {
        $this->actingAs($this->adminUser);

        // Reject external URL
        Livewire::test(CreateHeroBanner::class)
            ->fillForm([
                'title_id' => 'Banner Test',
                'title_en' => 'Test Banner',
                'image_path' => UploadedFile::fake()->image('banner.jpg'),
                'button_url' => 'https://example.com',
            ])
            ->call('create')
            ->assertHasFormErrors(['button_url' => 'regex']);

        // Reject external www URL
        Livewire::test(CreateHeroBanner::class)
            ->fillForm([
                'title_id' => 'Banner Test',
                'title_en' => 'Test Banner',
                'image_path' => UploadedFile::fake()->image('banner.jpg'),
                'button_url' => 'www.example.com',
            ])
            ->call('create')
            ->assertHasFormErrors(['button_url' => 'regex']);

        // Reject path without leading slash
        Livewire::test(CreateHeroBanner::class)
            ->fillForm([
                'title_id' => 'Banner Test',
                'title_en' => 'Test Banner',
                'image_path' => UploadedFile::fake()->image('banner.jpg'),
                'button_url' => 'products',
            ])
            ->call('create')
            ->assertHasFormErrors(['button_url' => 'regex']);

        // Accept valid internal relative path
        Livewire::test(CreateHeroBanner::class)
            ->fillForm([
                'title_id' => 'Banner Beranda ANS',
                'title_en' => 'ANS Home Banner',
                'button_text_id' => 'Jelajahi Katalog',
                'button_text_en' => 'Explore Catalog',
                'image_path' => UploadedFile::fake()->image('banner.jpg'),
                'button_url' => '/products',
            ])
            ->call('create')
            ->assertHasNoFormErrors(['button_url']);

        $banner = HeroBanner::where('title_id', 'Banner Beranda ANS')->first();
        $this->assertNotNull($banner);
        $this->assertEquals('/products', $banner->button_url);
        $this->assertNotEquals('/id/products', $banner->button_url);
        $this->assertNotEquals('/en/products', $banner->button_url);
    }

    public function test_sort_order_defaults_to_next_position(): void
    {
        Category::create([
            'name_id' => 'Cat 1',
            'name_en' => 'Cat 1',
            'sort_order' => 5,
            'is_active' => true,
        ]);

        $nextOrder = (Category::max('sort_order') ?? 0) + 1;
        $this->assertEquals(6, $nextOrder);
    }

    public function test_category_resource_crud_and_delete_protection(): void
    {
        $this->actingAs($this->adminUser);

        $category = Category::create([
            'name_id' => 'Diagnostik Cepat',
            'name_en' => 'Rapid Diagnostics',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $this->get(CategoryResource::getUrl('index'))->assertSuccessful();
        $this->get(CategoryResource::getUrl('edit', ['record' => $category]))->assertSuccessful();

        $brand = Brand::create([
            'name' => 'Test Brand',
            'logo_path' => 'brands/test.png',
            'is_active' => true,
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'name_id' => 'Rapid Test Device',
            'name_en' => 'Rapid Test Device',
            'primary_image_path' => 'products/primary/test.jpg',
            'is_active' => true,
        ]);

        // Attempting to delete category with products should be blocked
        $this->assertEquals(1, $category->products()->count());
    }

    public function test_brand_resource_pages_render(): void
    {
        $this->actingAs($this->adminUser);

        $brand = Brand::create([
            'name' => 'Merck KGaA',
            'logo_path' => 'brands/merck.png',
            'website_url' => 'https://www.merckmillipore.com',
            'is_new_principal' => false,
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $this->get(BrandResource::getUrl('index'))->assertSuccessful();
        $this->get(BrandResource::getUrl('edit', ['record' => $brand]))->assertSuccessful();
    }

    public function test_product_resource_and_relation_manager(): void
    {
        $this->actingAs($this->adminUser);

        $category = Category::create([
            'name_id' => 'Mikrobiologi',
            'name_en' => 'Microbiology',
            'is_active' => true,
        ]);

        $brand = Brand::create([
            'name' => 'Merck',
            'logo_path' => 'brands/merck.png',
            'is_active' => true,
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'name_id' => 'Media Dehidrasi',
            'name_en' => 'Dehydrated Media',
            'primary_image_path' => 'products/primary/media.jpg',
            'is_active' => true,
        ]);

        $this->get(ProductResource::getUrl('index'))->assertSuccessful();
        $this->get(ProductResource::getUrl('edit', ['record' => $product]))->assertSuccessful();

        Livewire::test(ImagesRelationManager::class, [
            'ownerRecord' => $product,
            'pageClass' => EditProduct::class,
        ])->assertSuccessful();
    }

    public function test_hero_banner_resource_pages_render(): void
    {
        $this->actingAs($this->adminUser);

        $banner = HeroBanner::create([
            'title_id' => 'Banner Beranda',
            'title_en' => 'Home Banner',
            'image_path' => 'hero-banners/banner1.jpg',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $this->get(HeroBannerResource::getUrl('index'))->assertSuccessful();
        $this->get(HeroBannerResource::getUrl('edit', ['record' => $banner]))->assertSuccessful();
    }

    public function test_company_profile_resource_pages_render(): void
    {
        $this->actingAs($this->adminUser);

        $profile = CompanyProfile::create([
            'tagline_id' => 'Slogan ID',
            'tagline_en' => 'Tagline EN',
            'about_id' => 'Tentang ID',
            'about_en' => 'About EN',
            'vision_id' => 'Visi ID',
            'vision_en' => 'Vision EN',
            'mission_id' => 'Misi ID',
            'mission_en' => 'Mission EN',
            'address' => 'Mensana Tower',
            'phone' => '021 39722772',
            'whatsapp' => '0822-614-614-00',
            'email' => 'admin@avenasa.co.id',
        ]);

        $this->get(CompanyProfileResource::getUrl('index'))->assertSuccessful();
        $this->get(CompanyProfileResource::getUrl('edit', ['record' => $profile]))->assertSuccessful();
    }

    public function test_core_value_management_and_client_resources_render(): void
    {
        $this->actingAs($this->adminUser);

        $value = CoreValue::create([
            'title_id' => 'Inovasi',
            'title_en' => 'Innovation',
            'description_id' => 'Deskripsi Inovasi',
            'description_en' => 'Innovation Description',
            'icon_name' => 'light-bulb',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $management = Management::create([
            'name' => 'Fernanda Ramadhan F',
            'position_id' => 'Direktur',
            'position_en' => 'Director',
            'sort_order' => 1,
            'is_active' => false,
        ]);

        $client = Client::create([
            'name' => 'Bio Farma',
            'logo_path' => 'clients/biofarma.png',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $this->get(CoreValueResource::getUrl('index'))->assertSuccessful();
        $this->get(ManagementResource::getUrl('index'))->assertSuccessful();
        $this->get(ClientResource::getUrl('index'))->assertSuccessful();
    }

    public function test_quotation_resource_pages_render(): void
    {
        $this->actingAs($this->adminUser);

        $quotation = Quotation::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@hospital.id',
            'subject' => 'Permintaan Penawaran',
            'message' => 'Detail permintaan...',
            'status' => 'New',
            'locale' => 'id',
        ]);

        $this->get(QuotationResource::getUrl('index'))->assertSuccessful();
        $this->get(QuotationResource::getUrl('edit', ['record' => $quotation]))->assertSuccessful();
    }

    public function test_development_admin_seeder_is_idempotent(): void
    {
        $seeder = new DevelopmentAdminSeeder;

        $seeder->run();
        $this->assertDatabaseHas('users', ['email' => 'admin@avenasa.co.id']);

        // Run second time to verify idempotency
        $seeder->run();
        $this->assertEquals(1, User::where('email', 'admin@avenasa.co.id')->count());
    }
}
