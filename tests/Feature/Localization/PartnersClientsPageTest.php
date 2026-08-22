<?php

namespace Tests\Feature\Localization;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Client;
use App\Models\CompanyProfile;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PartnersClientsPageTest extends TestCase
{
    use RefreshDatabase;

    private function createCompanyProfile(): CompanyProfile
    {
        return CompanyProfile::create([
            'tagline_id' => 'Empowering Science for a Prosperous Future',
            'tagline_en' => 'Empowering Science for a Prosperous Future',
            'about_id' => 'ANS adalah distributor resmi.',
            'about_en' => 'ANS is an authorized distributor.',
            'vision_id' => 'Visi',
            'vision_en' => 'Vision',
            'mission_id' => 'Misi',
            'mission_en' => 'Mission',
            'address' => 'Mensana Tower Lt. 15, Cibubur',
            'phone' => '021 39722772',
            'whatsapp' => '0822-614-614-00',
            'email' => 'admin@avenasa.co.id',
        ]);
    }

    private function createCategory(): Category
    {
        return Category::create([
            'name_id' => 'Kimia Analisis',
            'name_en' => 'Analytical Chemistry',
            'slug_id' => 'kimia-analisis',
            'slug_en' => 'analytical-chemistry',
            'description_id' => 'Kategori analisis',
            'description_en' => 'Analysis category',
            'is_active' => true,
            'sort_order' => 1,
        ]);
    }

    public function test_partners_clients_page_returns_successful_response_for_both_locales(): void
    {
        $this->createCompanyProfile();

        $idResponse = $this->get('/id/partners-clients');
        $idResponse->assertStatus(200);
        $idResponse->assertViewIs('pages.partners-clients');

        $enResponse = $this->get('/en/partners-clients');
        $enResponse->assertStatus(200);
        $enResponse->assertViewIs('pages.partners-clients');
    }

    public function test_partners_clients_page_renders_active_brands_and_excludes_inactive(): void
    {
        $this->createCompanyProfile();

        Brand::create([
            'name' => 'Lovibond',
            'slug' => 'lovibond',
            'logo_path' => 'brands/lovibond.png',
            'description_id' => 'Principal pengujian kualitas air.',
            'description_en' => 'Water quality testing principal.',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        Brand::create([
            'name' => 'Neogen',
            'slug' => 'neogen',
            'logo_path' => 'brands/neogen.png',
            'description_id' => 'Principal keamanan pangan.',
            'description_en' => 'Food safety principal.',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        Brand::create([
            'name' => 'Inactive Brand',
            'slug' => 'inactive-brand',
            'logo_path' => '',
            'description_id' => 'Deskripsi nonaktif.',
            'description_en' => 'Inactive description.',
            'sort_order' => 3,
            'is_active' => false,
        ]);

        $idResponse = $this->get('/id/partners-clients');
        $idResponse->assertSee('Distributor Resmi Terdaftar');
        $idResponse->assertSee('Lovibond');
        $idResponse->assertSee('Neogen');
        $idResponse->assertSee('Principal pengujian kualitas air.');
        $idResponse->assertSee('Principal keamanan pangan.');
        $idResponse->assertDontSee('Inactive Brand');

        // Verify deterministic sorting order (Lovibond before Neogen)
        $idContent = $idResponse->getContent();
        $this->assertLessThan(
            strpos($idContent, 'Neogen'),
            strpos($idContent, 'Lovibond')
        );

        $enResponse = $this->get('/en/partners-clients');
        $enResponse->assertSee('Authorized Global Principals');
        $enResponse->assertSee('Lovibond');
        $enResponse->assertSee('Neogen');
        $enResponse->assertSee('Water quality testing principal.');
        $enResponse->assertSee('Food safety principal.');
        $enResponse->assertDontSee('Inactive Brand');
    }

    public function test_brand_with_active_products_renders_view_products_cta_with_brand_filter(): void
    {
        $this->createCompanyProfile();
        $category = $this->createCategory();

        $merck = Brand::create([
            'name' => 'Merck',
            'slug' => 'merck',
            'logo_path' => 'brands/merck.png',
            'description_id' => 'Principal reagen kimia.',
            'description_en' => 'Chemical reagent principal.',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $emptyBrand = Brand::create([
            'name' => 'Brand Without Products',
            'slug' => 'brand-without-products',
            'logo_path' => '',
            'description_id' => 'Brand tanpa produk.',
            'description_en' => 'Brand without products.',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        Product::create([
            'category_id' => $category->id,
            'brand_id' => $merck->id,
            'name_id' => 'Reagen Merck A',
            'name_en' => 'Merck Reagent A',
            'summary_id' => 'Ringkasan produk',
            'summary_en' => 'Product summary',
            'description_id' => 'Deskripsi lengkap',
            'description_en' => 'Full description',
            'primary_image_path' => 'products/sample.png',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $idResponse = $this->get('/id/partners-clients');
        $idResponse->assertSee('Lihat Produk');
        $idResponse->assertSee('href="http://127.0.0.1:8000/id/products?brand=merck"', false);
        $idResponse->assertDontSee('href="http://127.0.0.1:8000/id/products?brand=brand-without-products"', false);

        $enResponse = $this->get('/en/partners-clients');
        $enResponse->assertSee('View Products');
        $enResponse->assertSee('href="http://127.0.0.1:8000/en/products?brand=merck"', false);
        $enResponse->assertDontSee('href="http://127.0.0.1:8000/en/products?brand=brand-without-products"', false);
    }

    public function test_partners_clients_page_renders_active_clients_and_excludes_inactive(): void
    {
        $this->createCompanyProfile();

        Client::create([
            'name' => 'Kalbe Farma',
            'logo_path' => 'clients/kalbe.png',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        Client::create([
            'name' => 'Bio Farma',
            'logo_path' => '',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        Client::create([
            'name' => 'Inactive Client Corp',
            'logo_path' => '',
            'sort_order' => 3,
            'is_active' => false,
        ]);

        $idResponse = $this->get('/id/partners-clients');
        $idResponse->assertSee('Dipercaya oleh Institusi Terkemuka');
        $idResponse->assertSee('Kalbe Farma');
        $idResponse->assertSee('Bio Farma');
        $idResponse->assertDontSee('Inactive Client Corp');

        $enResponse = $this->get('/en/partners-clients');
        $enResponse->assertSee('Trusted by Leading Institutions');
        $enResponse->assertSee('Kalbe Farma');
        $enResponse->assertSee('Bio Farma');
        $enResponse->assertDontSee('Inactive Client Corp');
    }

    public function test_partners_clients_page_renders_cta_properly(): void
    {
        $this->createCompanyProfile();

        $idResponse = $this->get('/id/partners-clients');
        $idResponse->assertSee('Bergabunglah Bersama Ribuan Pelanggan Terpercaya');
        $idResponse->assertSee('Minta Penawaran Harga');
        $idResponse->assertSee('Konsultasi via WhatsApp');

        $enResponse = $this->get('/en/partners-clients');
        $enResponse->assertSee('Join Our Trusted Client Network');
        $enResponse->assertSee('Request a Quotation');
        $enResponse->assertSee('Consult via WhatsApp');
    }

    public function test_partners_clients_page_handles_empty_dataset_gracefully(): void
    {
        $this->createCompanyProfile();

        $response = $this->get('/id/partners-clients');
        $response->assertStatus(200);
        $response->assertViewIs('pages.partners-clients');
    }

    public function test_language_switcher_maintains_context_between_locales(): void
    {
        $this->createCompanyProfile();

        $idResponse = $this->get('/id/partners-clients');
        $idResponse->assertStatus(200);
        $idResponse->assertSee('/en/partners-clients');

        $enResponse = $this->get('/en/partners-clients');
        $enResponse->assertStatus(200);
        $enResponse->assertSee('/id/partners-clients');
    }
}
