<?php

namespace Tests\Feature\Localization;

use App\Models\Brand;
use App\Models\Category;
use App\Models\CompanyProfile;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PublicProductDetailTest extends TestCase
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
            'name_id' => 'Sistem PCR Real-Time',
            'name_en' => 'Real-Time PCR System',
            'slug_id' => 'sistem-pcr-real-time',
            'slug_en' => 'real-time-pcr-system',
            'summary_id' => 'Instrumen PCR resolusi tinggi.',
            'summary_en' => 'High-resolution PCR instrument.',
            'description_id' => 'Deskripsi lengkap alat PCR canggih.',
            'description_en' => 'Comprehensive description of advanced PCR.',
            'specifications' => [
                [
                    'key_id' => 'Kapasitas',
                    'key_en' => 'Capacity',
                    'value_id' => '96 sumur',
                    'value_en' => '96 wells',
                ],
                [
                    'key_id' => 'Waktu Deteksi',
                    'key_en' => 'Detection Time',
                    'value_id' => '< 35 menit',
                    'value_en' => '< 35 minutes',
                ],
            ],
            'primary_image_path' => 'products/sample-pcr.jpg',
            'brochure_path' => null,
            'is_active' => true,
            'is_featured' => true,
            'sort_order' => 1,
        ], $overrides));
    }

    public function test_product_detail_resolves_correctly_for_id_and_en(): void
    {
        $category = $this->createCategory();
        $brand = $this->createBrand();
        $product = $this->createProduct($category, $brand);

        // Indonesian route
        $idResponse = $this->get('/id/products/sistem-pcr-real-time');
        $idResponse->assertStatus(200);
        $idResponse->assertViewIs('pages.products.show');
        $idResponse->assertSee('Sistem PCR Real-Time');
        $idResponse->assertSee('Instrumen PCR resolusi tinggi.');
        $idResponse->assertSee('Mikrobiologi');
        $idResponse->assertSee('Merck');

        // English route
        $enResponse = $this->get('/en/products/real-time-pcr-system');
        $enResponse->assertStatus(200);
        $enResponse->assertViewIs('pages.products.show');
        $enResponse->assertSee('Real-Time PCR System');
        $enResponse->assertSee('High-resolution PCR instrument.');
        $enResponse->assertSee('Microbiology');
        $enResponse->assertSee('Merck');
    }

    public function test_strict_slug_resolution_rejects_cross_language_slug_with_404(): void
    {
        $category = $this->createCategory();
        $brand = $this->createBrand();
        $this->createProduct($category, $brand);

        // English slug on ID prefix -> 404
        $crossId = $this->get('/id/products/real-time-pcr-system');
        $crossId->assertStatus(404);

        // Indonesian slug on EN prefix -> 404
        $crossEn = $this->get('/en/products/sistem-pcr-real-time');
        $crossEn->assertStatus(404);
    }

    public function test_inactive_product_returns_404(): void
    {
        $category = $this->createCategory();
        $brand = $this->createBrand();
        $this->createProduct($category, $brand, ['is_active' => false]);

        $idResponse = $this->get('/id/products/sistem-pcr-real-time');
        $idResponse->assertStatus(404);

        $enResponse = $this->get('/en/products/real-time-pcr-system');
        $enResponse->assertStatus(404);
    }

    public function test_gallery_renders_primary_image_and_supporting_images(): void
    {
        $category = $this->createCategory();
        $brand = $this->createBrand();
        $product = $this->createProduct($category, $brand, ['primary_image_path' => 'products/main.jpg']);

        ProductImage::create([
            'product_id' => $product->id,
            'image_path' => 'products/gallery-1.jpg',
            'caption_id' => 'Tampak Samping',
            'caption_en' => 'Side View',
            'sort_order' => 1,
        ]);

        $response = $this->get('/id/products/sistem-pcr-real-time');
        $response->assertStatus(200);
        $response->assertSee('storage/products/main.jpg', false);
        $response->assertSee('storage/products/gallery-1.jpg', false);
        $response->assertSee('fetchpriority="high"', false);
    }

    public function test_fallback_image_rendered_when_no_images_available(): void
    {
        $category = $this->createCategory();
        $brand = $this->createBrand();
        $this->createProduct($category, $brand, ['primary_image_path' => '']);

        $response = $this->get('/id/products/sistem-pcr-real-time');
        $response->assertStatus(200);
        $response->assertSee('PT ANS');
        $response->assertSee('Foto produk belum tersedia');
    }

    public function test_specifications_table_renders_localized_data_and_escapes_html(): void
    {
        $category = $this->createCategory();
        $brand = $this->createBrand();
        $this->createProduct($category, $brand, [
            'specifications' => [
                [
                    'key_id' => 'Rentang Suhu',
                    'key_en' => 'Temperature Range',
                    'value_id' => '37°C - 99°C <script>alert("xss")</script>',
                    'value_en' => '37°C - 99°C <script>alert("xss")</script>',
                ],
            ],
        ]);

        // ID test
        $idResponse = $this->get('/id/products/sistem-pcr-real-time');
        $idResponse->assertStatus(200);
        $idResponse->assertSee('Spesifikasi Teknis');
        $idResponse->assertSee('Rentang Suhu');
        $idResponse->assertSee('37°C - 99°C &lt;script&gt;alert(&quot;xss&quot;)&lt;/script&gt;', false);
        $idResponse->assertDontSee('<script>alert("xss")</script>', false);

        // EN test
        $enResponse = $this->get('/en/products/real-time-pcr-system');
        $enResponse->assertStatus(200);
        $enResponse->assertSee('Technical Specifications');
        $enResponse->assertSee('Temperature Range');
    }

    public function test_empty_specifications_hides_specification_section(): void
    {
        $category = $this->createCategory();
        $brand = $this->createBrand();
        $this->createProduct($category, $brand, ['specifications' => null]);

        $response = $this->get('/id/products/sistem-pcr-real-time');
        $response->assertStatus(200);
        $response->assertDontSee('Spesifikasi Teknis');
    }

    public function test_brochure_download_route_and_missing_file_handling(): void
    {
        Storage::fake('public');

        $category = $this->createCategory();
        $brand = $this->createBrand();
        $product = $this->createProduct($category, $brand, [
            'brochure_path' => 'brochures/pcr-manual.pdf',
        ]);

        // Case 1: Physical file exists -> successful download
        Storage::disk('public')->put('brochures/pcr-manual.pdf', 'PDF-CONTENT');

        $downloadResponse = $this->get('/id/products/sistem-pcr-real-time/brochure');
        $downloadResponse->assertStatus(200);
        $downloadResponse->assertHeader('content-disposition');

        // Case 2: File missing on disk -> 404
        Storage::disk('public')->delete('brochures/pcr-manual.pdf');

        $missingResponse = $this->get('/id/products/sistem-pcr-real-time/brochure');
        $missingResponse->assertStatus(404);
    }

    public function test_brochure_cta_hidden_when_brochure_unavailable(): void
    {
        $category = $this->createCategory();
        $brand = $this->createBrand();
        $this->createProduct($category, $brand, ['brochure_path' => null]);

        $response = $this->get('/id/products/sistem-pcr-real-time');
        $response->assertStatus(200);
        $response->assertDontSee('Unduh Brosur Produk (PDF)');
    }

    public function test_quotation_cta_navigates_to_contact_with_numeric_product_id(): void
    {
        $category = $this->createCategory();
        $brand = $this->createBrand();
        $product = $this->createProduct($category, $brand);

        $idResponse = $this->get('/id/products/sistem-pcr-real-time');
        $idResponse->assertStatus(200);
        $idResponse->assertSee('/id/contact?product_id='.$product->id, false);
        $idResponse->assertSee('Minta Penawaran Harga');

        $enResponse = $this->get('/en/products/real-time-pcr-system');
        $enResponse->assertStatus(200);
        $enResponse->assertSee('/en/contact?product_id='.$product->id, false);
        $enResponse->assertSee('Request a Quotation');
    }

    public function test_description_strips_html_tags_and_renders_clean_text(): void
    {
        $category = $this->createCategory();
        $brand = $this->createBrand();
        $this->createProduct($category, $brand, [
            'description_id' => '<p>Deskripsi alat dengan <strong>format html</strong>.</p>',
            'description_en' => '<p>Tool description with <strong>html format</strong>.</p>',
        ]);

        $idResponse = $this->get('/id/products/sistem-pcr-real-time');
        $idResponse->assertStatus(200);
        $idResponse->assertSee('Deskripsi alat dengan format html.');
        $idResponse->assertDontSee('&lt;p&gt;', false);
        $idResponse->assertDontSee('&lt;/p&gt;', false);
        $idResponse->assertDontSee('<p>Deskripsi', false);
        $idResponse->assertDontSee('<strong>format html</strong>', false);
    }

    public function test_mobile_sticky_cta_includes_whatsapp_button_when_available(): void
    {
        CompanyProfile::create([
            'tagline_id' => 'Empowering Science',
            'tagline_en' => 'Empowering Science',
            'about_id' => 'Tentang Kami',
            'about_en' => 'About Us',
            'vision_id' => 'Visi',
            'vision_en' => 'Vision',
            'mission_id' => 'Misi 1',
            'mission_en' => 'Mission 1',
            'whatsapp' => '082261461400',
            'email' => 'admin@avenasa.co.id',
            'phone' => '02139722772',
            'address' => 'Mensana Tower',
        ]);

        $category = $this->createCategory();
        $brand = $this->createBrand();
        $product = $this->createProduct($category, $brand);

        $response = $this->get('/id/products/sistem-pcr-real-time');
        $response->assertStatus(200);
        $response->assertSee('md:hidden fixed bottom-0', false);
        $response->assertSee('https://wa.me/6282261461400', false);
        $response->assertSee('Minta Penawaran');
    }

    public function test_language_switcher_maps_paired_product_slugs(): void
    {
        $category = $this->createCategory();
        $brand = $this->createBrand();
        $this->createProduct($category, $brand);

        // When visiting Indonesian product detail, switcher to EN should point to English slug
        $idResponse = $this->get('/id/products/sistem-pcr-real-time');
        $idResponse->assertStatus(200);
        $idResponse->assertSee('/en/products/real-time-pcr-system', false);

        // When visiting English product detail, switcher to ID should point to Indonesian slug
        $enResponse = $this->get('/en/products/real-time-pcr-system');
        $enResponse->assertStatus(200);
        $enResponse->assertSee('/id/products/sistem-pcr-real-time', false);
    }

    public function test_breadcrumb_renders_localized_hierarchy_and_category_link(): void
    {
        $category = $this->createCategory([
            'name_id' => 'Biologi Molekuler',
            'name_en' => 'Molecular Biology',
            'slug_id' => 'biologi-molekuler',
            'slug_en' => 'molecular-biology',
        ]);
        $brand = $this->createBrand();
        $this->createProduct($category, $brand);

        $idResponse = $this->get('/id/products/sistem-pcr-real-time');
        $idResponse->assertStatus(200);
        $idResponse->assertSee('Beranda');
        $idResponse->assertSee('Produk');
        $idResponse->assertSee('Biologi Molekuler');
        $idResponse->assertSee('/id/products?category=biologi-molekuler', false);

        $enResponse = $this->get('/en/products/real-time-pcr-system');
        $enResponse->assertStatus(200);
        $enResponse->assertSee('Home');
        $enResponse->assertSee('Products');
        $enResponse->assertSee('Molecular Biology');
        $enResponse->assertSee('/en/products?category=molecular-biology', false);
    }
}
