<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Client;
use App\Models\CompanyProfile;
use App\Models\CoreValue;
use App\Models\HeroBanner;
use App\Models\Management;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Quotation;
use App\Models\User;
use Filament\Panel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DomainFoundationTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_domain_can_be_created_and_queried(): void
    {
        $category = Category::create([
            'name_id' => 'Mikrobiologi',
            'name_en' => 'Microbiology',
            'slug_id' => 'mikrobiologi',
            'slug_en' => 'microbiology',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'slug_id' => 'mikrobiologi',
            'slug_en' => 'microbiology',
        ]);

        $this->assertTrue($category->is_active);
        $this->assertEquals(1, $category->sort_order);

        // Test localized accessors
        app()->setLocale('id');
        $this->assertEquals('Mikrobiologi', $category->name);
        $this->assertEquals('mikrobiologi', $category->slug);

        app()->setLocale('en');
        $this->assertEquals('Microbiology', $category->name);
        $this->assertEquals('microbiology', $category->slug);

        // Test scopes
        $this->assertCount(1, Category::active()->get());
        $this->assertCount(1, Category::ordered()->get());
    }

    public function test_brand_domain_can_be_created_and_queried(): void
    {
        $brand = Brand::create([
            'name' => 'Merck',
            'slug' => 'merck',
            'logo_path' => 'brands/merck.png',
            'website_url' => 'https://www.merckmillipore.com',
            'description_id' => 'Deskripsi Merck ID',
            'description_en' => 'Merck Description EN',
            'is_new_principal' => false,
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('brands', [
            'id' => $brand->id,
            'slug' => 'merck',
        ]);

        $this->assertTrue($brand->is_active);
        $this->assertFalse($brand->is_new_principal);

        app()->setLocale('id');
        $this->assertEquals('Deskripsi Merck ID', $brand->description);

        app()->setLocale('en');
        $this->assertEquals('Merck Description EN', $brand->description);
    }

    public function test_product_and_product_images_relationships_and_casts(): void
    {
        $category = Category::create([
            'name_id' => 'Biologi Molekuler',
            'name_en' => 'Molecular Biology',
            'slug_id' => 'biologi-molekuler',
            'slug_en' => 'molecular-biology',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $brand = Brand::create([
            'name' => 'Neogen',
            'slug' => 'neogen',
            'logo_path' => 'brands/neogen.png',
            'website_url' => 'https://www.neogen.com',
            'is_new_principal' => false,
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $specs = [
            ['key_id' => 'Kapasitas', 'key_en' => 'Capacity', 'value_id' => '96 sumur', 'value_en' => '96 wells'],
            ['key_id' => 'Waktu Uji', 'key_en' => 'Test Time', 'value_id' => '35 menit', 'value_en' => '35 minutes'],
        ];

        $product = Product::create([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'name_id' => 'Sistem PCR Real-Time',
            'name_en' => 'Real-Time PCR System',
            'slug_id' => 'sistem-pcr-real-time',
            'slug_en' => 'real-time-pcr-system',
            'summary_id' => 'Ringkasan singkat produk ID',
            'summary_en' => 'Short product summary EN',
            'description_id' => '<p>Deskripsi lengkap produk ID</p>',
            'description_en' => '<p>Full product description EN</p>',
            'specifications' => $specs,
            'primary_image_path' => 'products/primary/pcr.jpg',
            'brochure_path' => 'brochures/pcr-brochure.pdf',
            'is_featured' => true,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $image1 = ProductImage::create([
            'product_id' => $product->id,
            'image_path' => 'products/gallery/pcr_side.jpg',
            'caption_id' => 'Tampak Samping',
            'caption_en' => 'Side View',
            'sort_order' => 1,
        ]);

        $image2 = ProductImage::create([
            'product_id' => $product->id,
            'image_path' => 'products/gallery/pcr_back.jpg',
            'caption_id' => 'Tampak Belakang',
            'caption_en' => 'Back View',
            'sort_order' => 2,
        ]);

        // Relationships check
        $this->assertEquals($category->id, $product->category->id);
        $this->assertEquals($brand->id, $product->brand->id);
        $this->assertCount(2, $product->images);
        $this->assertCount(2, $product->productImages);
        $this->assertEquals($product->id, $image1->product->id);
        $this->assertCount(1, $category->products);
        $this->assertCount(1, $brand->products);

        // JSON Specifications cast check
        $this->assertIsArray($product->specifications);
        $this->assertEquals('96 sumur', $product->specifications[0]['value_id']);

        // Localized accessors
        app()->setLocale('id');
        $this->assertEquals('Sistem PCR Real-Time', $product->name);
        $this->assertEquals('sistem-pcr-real-time', $product->slug);
        $this->assertEquals('Ringkasan singkat produk ID', $product->summary);
        $this->assertEquals('<p>Deskripsi lengkap produk ID</p>', $product->description);
        $this->assertEquals('Tampak Samping', $image1->caption);

        app()->setLocale('en');
        $this->assertEquals('Real-Time PCR System', $product->name);
        $this->assertEquals('real-time-pcr-system', $product->slug);
        $this->assertEquals('Short product summary EN', $product->summary);
        $this->assertEquals('<p>Full product description EN</p>', $product->description);
        $this->assertEquals('Side View', $image1->caption);

        // Cascade delete check
        $product->delete();
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
        $this->assertDatabaseMissing('product_images', ['id' => $image1->id]);
        $this->assertDatabaseMissing('product_images', ['id' => $image2->id]);
    }

    public function test_quotation_domain_and_relationship(): void
    {
        $category = Category::create([
            'name_id' => 'Imunologi',
            'name_en' => 'Immunology',
            'slug_id' => 'imunologi',
            'slug_en' => 'immunology',
            'is_active' => true,
        ]);

        $brand = Brand::create([
            'name' => 'Era Biology',
            'slug' => 'era-biology',
            'logo_path' => 'brands/era.png',
            'is_active' => true,
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'name_id' => 'Kit ELISA Salmonella',
            'name_en' => 'Salmonella ELISA Kit',
            'slug_id' => 'kit-elisa-salmonella',
            'slug_en' => 'salmonella-elisa-kit',
            'primary_image_path' => 'products/primary/elisa.jpg',
            'is_active' => true,
        ]);

        $quotation = Quotation::create([
            'product_id' => $product->id,
            'name' => 'Dr. Ahmad Prasetyo',
            'email' => 'ahmad@lab.co.id',
            'phone' => '08123456789',
            'company' => 'RS Cipto Mangunkusumo',
            'subject' => 'Permintaan Penawaran Kit ELISA',
            'message' => 'Mohon penawaran harga resmi untuk 5 box Kit ELISA Salmonella.',
            'status' => 'New',
            'locale' => 'id',
            'admin_notes' => null,
        ]);

        $this->assertDatabaseHas('quotations', [
            'id' => $quotation->id,
            'name' => 'Dr. Ahmad Prasetyo',
            'status' => 'New',
        ]);

        $this->assertEquals($product->id, $quotation->product->id);
        $this->assertCount(1, $product->quotations);

        // Test scope
        $this->assertCount(1, Quotation::new()->get());
        $this->assertCount(1, Quotation::status('New')->get());
        $this->assertCount(0, Quotation::status('Closed')->get());

        // Test null on delete
        $product->delete();
        $quotation->refresh();
        $this->assertNull($quotation->product_id);
    }

    public function test_hero_banner_domain(): void
    {
        $banner = HeroBanner::create([
            'title_id' => 'Solusi Distribusi Peralatan Laboratorium',
            'title_en' => 'Trusted Laboratory Equipment Distribution Solutions',
            'subtitle_id' => 'Lebih dari 15 tahun melayani laboratorium di Indonesia.',
            'subtitle_en' => 'Over 15 years serving laboratories in Indonesia.',
            'image_path' => 'hero-banners/banner1.jpg',
            'mobile_image_path' => 'hero-banners/banner1-mobile.jpg',
            'button_text_id' => 'Jelajahi Katalog',
            'button_text_en' => 'Explore Catalog',
            'button_url' => '/products',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('hero_banners', [
            'id' => $banner->id,
            'button_url' => '/products',
        ]);

        app()->setLocale('id');
        $this->assertEquals('Solusi Distribusi Peralatan Laboratorium', $banner->title);
        $this->assertEquals('Jelajahi Katalog', $banner->button_text);

        app()->setLocale('en');
        $this->assertEquals('Trusted Laboratory Equipment Distribution Solutions', $banner->title);
        $this->assertEquals('Explore Catalog', $banner->button_text);
    }

    public function test_company_profile_singleton_domain(): void
    {
        $profile = CompanyProfile::create([
            'tagline_id' => 'Memberdayakan Sains untuk Masa Depan Sejahtera',
            'tagline_en' => 'Empowering Science for a Prosperous Future',
            'about_id' => 'Deskripsi perusahaan ID',
            'about_en' => 'Company description EN',
            'vision_id' => 'Menjadi mitra distribusi ilmiah paling terpercaya',
            'vision_en' => 'To become the most trusted scientific distribution partner',
            'mission_id' => '1. Mendistribusikan produk berkualitas',
            'mission_en' => '1. Distribute quality products',
            'address' => 'Mensana Tower Lt. 15, Cibubur, Bekasi',
            'phone' => '(021) 39722772',
            'whatsapp' => '0822-614-614-00',
            'email' => 'admin@avenasa.co.id',
            'maps_embed_url' => 'https://maps.google.com/embed?...',
        ]);

        $this->assertDatabaseHas('company_profiles', [
            'id' => $profile->id,
            'email' => 'admin@avenasa.co.id',
        ]);

        app()->setLocale('id');
        $this->assertEquals('Memberdayakan Sains untuk Masa Depan Sejahtera', $profile->tagline);
        $this->assertEquals('Menjadi mitra distribusi ilmiah paling terpercaya', $profile->vision);

        app()->setLocale('en');
        $this->assertEquals('Empowering Science for a Prosperous Future', $profile->tagline);
        $this->assertEquals('To become the most trusted scientific distribution partner', $profile->vision);
    }

    public function test_core_value_domain(): void
    {
        $value = CoreValue::create([
            'title_id' => 'Integritas',
            'title_en' => 'Integrity',
            'description_id' => 'Menjaga kejujuran dan transparansi',
            'description_en' => 'Maintaining honesty and transparency',
            'icon_name' => 'shield-check',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('core_values', [
            'id' => $value->id,
            'icon_name' => 'shield-check',
        ]);

        app()->setLocale('id');
        $this->assertEquals('Integritas', $value->title);

        app()->setLocale('en');
        $this->assertEquals('Integrity', $value->title);
    }

    public function test_management_domain(): void
    {
        $founder = Management::create([
            'name' => 'Erik Haryanto',
            'position_id' => 'Komisaris',
            'position_en' => 'Commissioner',
            'bio_id' => 'Riwayat singkat pengalaman Erik',
            'bio_en' => 'Short biography of Erik',
            'photo_path' => 'management/erik.jpg',
            'sort_order' => 1,
            'is_active' => false, // Initial deployment status as inactive per spec
        ]);

        $this->assertDatabaseHas('managements', [
            'id' => $founder->id,
            'name' => 'Erik Haryanto',
            'is_active' => false,
        ]);

        $this->assertCount(0, Management::active()->get());
        $this->assertCount(1, Management::ordered()->get());

        app()->setLocale('id');
        $this->assertEquals('Komisaris', $founder->position);

        app()->setLocale('en');
        $this->assertEquals('Commissioner', $founder->position);
    }

    public function test_client_domain(): void
    {
        $client = Client::create([
            'name' => 'Kalbe Farma',
            'logo_path' => 'clients/kalbe.png',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('clients', [
            'id' => $client->id,
            'name' => 'Kalbe Farma',
        ]);

        $this->assertCount(1, Client::active()->get());
        $this->assertCount(1, Client::ordered()->get());
    }

    public function test_user_filament_integration(): void
    {
        $user = User::factory()->create([
            'name' => 'Admin ANS',
            'email' => 'admin@avenasa.co.id',
        ]);

        $panel = Panel::make()->id('admin');
        $this->assertTrue($user->canAccessPanel($panel));
    }
}
