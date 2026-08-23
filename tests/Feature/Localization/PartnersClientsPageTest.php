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

    public function test_principal_pagination_limits_to_12_per_page_and_allows_navigation_to_page_2(): void
    {
        $this->createCompanyProfile();

        // Create 19 active brands + 1 inactive
        for ($i = 1; $i <= 19; $i++) {
            Brand::create([
                'name' => sprintf('Brand %02d', $i),
                'slug' => sprintf('brand-%02d', $i),
                'logo_path' => 'brands/sample.png',
                'sort_order' => $i,
                'is_active' => true,
            ]);
        }
        Brand::create([
            'name' => 'Inactive Brand 99',
            'slug' => 'inactive-brand-99',
            'logo_path' => '',
            'sort_order' => 99,
            'is_active' => false,
        ]);

        // Page 1: Should show Brand 01 to Brand 12, but NOT Brand 13
        $page1Response = $this->get('/id/partners-clients');
        $page1Response->assertStatus(200);
        $page1Response->assertSee('Brand 01');
        $page1Response->assertSee('Brand 12');
        $page1Response->assertDontSee('Brand 13');
        $page1Response->assertSee('19 principal resmi');
        $page1Response->assertSeeText('Menampilkan 1–12 dari 19 principal');

        // Page 2: Should show Brand 13 to Brand 19, but NOT Brand 12 in the grid
        $page2Response = $this->get('/id/partners-clients?principal_page=2');
        $page2Response->assertStatus(200);
        $page2Response->assertSee('Brand 13');
        $page2Response->assertSee('Brand 19');
        $page2Response->assertDontSee('Brand 12');
        $page2Response->assertDontSee('Logo Brand 01');
        $page2Response->assertSeeText('Menampilkan 13–19 dari 19 principal');
    }

    public function test_client_pagination_limits_to_24_per_page_and_allows_navigation_to_subsequent_pages(): void
    {
        $this->createCompanyProfile();

        // Create 57 active clients
        for ($i = 1; $i <= 57; $i++) {
            Client::create([
                'name' => sprintf('Client %02d Corp', $i),
                'logo_path' => 'clients/sample.png',
                'sort_order' => $i,
                'is_active' => true,
            ]);
        }

        // Page 1: Client 01 to Client 24
        $page1Response = $this->get('/en/partners-clients');
        $page1Response->assertStatus(200);
        $page1Response->assertSee('Client 01 Corp');
        $page1Response->assertSee('Client 24 Corp');
        $page1Response->assertDontSee('Client 25 Corp');
        $page1Response->assertSee('Collaborating with 57 leading institutions');
        $page1Response->assertSeeText('Showing 1–24 of 57 clients');

        // Page 2: Client 25 to Client 48
        $page2Response = $this->get('/en/partners-clients?client_page=2');
        $page2Response->assertStatus(200);
        $page2Response->assertSee('Client 25 Corp');
        $page2Response->assertSee('Client 48 Corp');
        $page2Response->assertDontSee('Client 01 Corp');
        $page2Response->assertDontSee('Client 49 Corp');
        $page2Response->assertSeeText('Showing 25–48 of 57 clients');

        // Page 3: Client 49 to Client 57
        $page3Response = $this->get('/en/partners-clients?client_page=3');
        $page3Response->assertStatus(200);
        $page3Response->assertSee('Client 49 Corp');
        $page3Response->assertSee('Client 57 Corp');
        $page3Response->assertDontSee('Client 24 Corp');
        $page3Response->assertSeeText('Showing 49–57 of 57 clients');
    }

    public function test_independent_pagination_preserves_other_section_query_parameter(): void
    {
        $this->createCompanyProfile();

        for ($i = 1; $i <= 15; $i++) {
            Brand::create([
                'name' => "Brand {$i}",
                'slug' => "brand-{$i}",
                'logo_path' => 'brands/sample.png',
                'sort_order' => $i,
                'is_active' => true,
            ]);
        }

        for ($j = 1; $j <= 30; $j++) {
            Client::create([
                'name' => "Client {$j}",
                'logo_path' => 'clients/sample.png',
                'sort_order' => $j,
                'is_active' => true,
            ]);
        }

        $response = $this->get('/id/partners-clients?principal_page=2&client_page=2');
        $response->assertStatus(200);

        // Principal page 2 displays Brand 13 to Brand 15
        $response->assertSee('Brand 13');
        $response->assertDontSee('Brand 12');

        // Client page 2 displays Client 25 to Client 30
        $response->assertSee('Client 25');
        $response->assertDontSee('Client 01');

        // Pagination links for brands preserve client_page=2
        $response->assertSee('client_page=2');
        // Pagination links for clients preserve principal_page=2
        $response->assertSee('principal_page=2');
    }

    public function test_dynamic_dataset_counts_reflect_actual_active_database_totals(): void
    {
        $this->createCompanyProfile();

        // Dataset size 1: 5 principals, 10 clients
        for ($i = 1; $i <= 5; $i++) {
            Brand::create(['name' => "P{$i}", 'slug' => "p-{$i}", 'logo_path' => 'brands/sample.png', 'is_active' => true, 'sort_order' => $i]);
        }
        for ($j = 1; $j <= 10; $j++) {
            Client::create(['name' => "C{$j}", 'logo_path' => 'clients/sample.png', 'is_active' => true, 'sort_order' => $j]);
        }

        $response1 = $this->get('/id/partners-clients');
        $response1->assertSee('5 principal resmi');
        $response1->assertSee('Menjalin hubungan dengan 10 institusi');
        $response1->assertSeeText('Menampilkan 1–5 dari 5 principal');
        $response1->assertSeeText('Menampilkan 1–10 dari 10 klien');

        // Dataset size 2: Add more to verify dynamic responsiveness
        for ($i = 6; $i <= 32; $i++) {
            Brand::create(['name' => "P{$i}", 'slug' => "p-{$i}", 'logo_path' => 'brands/sample.png', 'is_active' => true, 'sort_order' => $i]);
        }
        for ($j = 11; $j <= 100; $j++) {
            Client::create(['name' => "C{$j}", 'logo_path' => 'clients/sample.png', 'is_active' => true, 'sort_order' => $j]);
        }

        $response2 = $this->get('/en/partners-clients');
        $response2->assertSee('32 authorized principals');
        $response2->assertSee('Collaborating with 100 leading institutions');
        $response2->assertSeeText('Showing 1–12 of 32 principals');
        $response2->assertSeeText('Showing 1–24 of 100 clients');
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

    public function test_partners_clients_page_handles_empty_dataset_gracefully(): void
    {
        $this->createCompanyProfile();

        $idResponse = $this->get('/id/partners-clients');
        $idResponse->assertStatus(200);
        $idResponse->assertSee('Belum ada data principal resmi yang aktif saat ini.');
        $idResponse->assertSee('Belum ada data klien yang aktif saat ini.');

        $enResponse = $this->get('/en/partners-clients');
        $enResponse->assertStatus(200);
        $enResponse->assertSee('No active principals available at the moment.');
        $enResponse->assertSee('No active clients available at the moment.');
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
