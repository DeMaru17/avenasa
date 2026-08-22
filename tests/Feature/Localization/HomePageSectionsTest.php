<?php

namespace Tests\Feature\Localization;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Client;
use App\Models\CompanyProfile;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomePageSectionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_company_intro_renders_tagline_and_localized_about_text(): void
    {
        CompanyProfile::create([
            'tagline_id' => 'Empowering Science for a Prosperous Future',
            'tagline_en' => 'Empowering Science for a Prosperous Future',
            'about_id' => 'ANS adalah distributor resmi peralatan laboratorium terpercaya.',
            'about_en' => 'ANS is a trusted official distributor of laboratory equipment.',
            'vision_id' => 'Visi',
            'vision_en' => 'Vision',
            'mission_id' => 'Misi',
            'mission_en' => 'Mission',
            'address' => 'Mensana Tower Lt. 15, Jl. Raya Kranggan Kav. 1, Cibubur, Bekasi',
            'phone' => '02139722772',
            'whatsapp' => '082261461400',
            'email' => 'admin@avenasa.co.id',
        ]);

        $idResponse = $this->get('/id');
        $idResponse->assertStatus(200);
        $idResponse->assertSee('Empowering Science for a Prosperous Future');
        $idResponse->assertSee('ANS adalah distributor resmi peralatan laboratorium terpercaya.');
        $idResponse->assertSee('mensana-tower.png');
        $idResponse->assertSee('Kantor Pusat');
        $idResponse->assertSee('Mensana Tower, Cibubur');
        $idResponse->assertSee('Pelajari Profil Kami');

        $enResponse = $this->get('/en');
        $enResponse->assertStatus(200);
        $enResponse->assertSee('Empowering Science for a Prosperous Future');
        $enResponse->assertSee('ANS is a trusted official distributor of laboratory equipment.');
        $enResponse->assertSee('mensana-tower.png');
        $enResponse->assertSee('Head Office');
        $enResponse->assertSee('Mensana Tower, Cibubur');
        $enResponse->assertSee('Learn About Us');
    }

    public function test_product_highlights_displays_active_products_and_excludes_inactive(): void
    {
        $category = Category::create([
            'name_id' => 'Uji Kualitas Air',
            'name_en' => 'Water Quality Testing',
            'slug_id' => 'uji-kualitas-air',
            'slug_en' => 'water-quality-testing',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $brand = Brand::create([
            'name' => 'Lovibond',
            'slug' => 'lovibond',
            'logo_path' => 'brands/lovibond.png',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        Product::create([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'name_id' => 'Photometer MD 200',
            'name_en' => 'MD 200 Photometer',
            'slug_id' => 'photometer-md-200',
            'slug_en' => 'md-200-photometer',
            'summary_id' => 'Fotometer multi-parameter portabel untuk uji air.',
            'summary_en' => 'Portable multi-parameter photometer for water testing.',
            'primary_image_path' => 'products/md200.jpg',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        Product::create([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'name_id' => 'Produk Nonaktif',
            'name_en' => 'Inactive Product',
            'slug_id' => 'produk-nonaktif',
            'slug_en' => 'inactive-product',
            'summary_id' => 'Produk nonaktif summary',
            'summary_en' => 'Inactive product summary',
            'primary_image_path' => 'products/inactive.jpg',
            'sort_order' => 2,
            'is_active' => false,
        ]);

        $idResponse = $this->get('/id');
        $idResponse->assertStatus(200);
        $idResponse->assertSee('Photometer MD 200');
        $idResponse->assertSee('Uji Kualitas Air');
        $idResponse->assertSee('Lovibond');
        $idResponse->assertSee('Fotometer multi-parameter portabel untuk uji air.');
        $idResponse->assertSee('aria-label="Product Carousel"', false);
        $idResponse->assertDontSee('Produk Nonaktif');

        $enResponse = $this->get('/en');
        $enResponse->assertStatus(200);
        $enResponse->assertSee('MD 200 Photometer');
        $enResponse->assertSee('Water Quality Testing');
        $enResponse->assertSee('Portable multi-parameter photometer for water testing.');
        $enResponse->assertSee('aria-label="Product Carousel"', false);
        $enResponse->assertDontSee('Inactive Product');
    }

    public function test_partners_highlights_displays_active_brands_and_clients(): void
    {
        Brand::create([
            'name' => 'Neogen',
            'slug' => 'neogen',
            'logo_path' => 'brands/neogen.png',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        Brand::create([
            'name' => 'Brand Nonaktif',
            'slug' => 'brand-nonaktif',
            'logo_path' => 'brands/nonaktif.png',
            'sort_order' => 2,
            'is_active' => false,
        ]);

        Client::create([
            'name' => 'PT Kalbe Farma',
            'logo_path' => 'clients/kalbe.png',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        Client::create([
            'name' => 'Klien Nonaktif',
            'logo_path' => 'clients/nonaktif.png',
            'sort_order' => 2,
            'is_active' => false,
        ]);

        $response = $this->get('/id');
        $response->assertStatus(200);
        $response->assertSee('Neogen');
        $response->assertDontSee('Brand Nonaktif');
        $response->assertSee('PT Kalbe Farma');
        $response->assertDontSee('Klien Nonaktif');
        $response->assertSee('aria-label="Principals Carousel"', false);
        $response->assertSee('aria-label="Clients Carousel"', false);
    }

    public function test_strategic_cta_renders_contact_and_quotation_links(): void
    {
        $idResponse = $this->get('/id');
        $idResponse->assertStatus(200);
        $idResponse->assertSee('Siap Mendiskusikan Kebutuhan Laboratorium Anda?');
        $idResponse->assertSee('Minta Penawaran Harga');
        $idResponse->assertSee(route('contact', ['locale' => 'id']));

        $enResponse = $this->get('/en');
        $enResponse->assertStatus(200);
        $enResponse->assertSee('Ready to Discuss Your Laboratory Needs?');
        $enResponse->assertSee('Request a Quotation');
        $enResponse->assertSee(route('contact', ['locale' => 'en']));
    }

    public function test_homepage_takes_up_to_12_products_and_renders_paginated_carousel(): void
    {
        $category = Category::create([
            'name_id' => 'Kategori Uji',
            'name_en' => 'Test Category',
            'slug_id' => 'kategori-uji',
            'slug_en' => 'test-category',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $brand = Brand::create([
            'name' => 'Brand Uji',
            'slug' => 'brand-uji',
            'logo_path' => 'brands/test.png',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        for ($i = 1; $i <= 14; $i++) {
            Product::create([
                'category_id' => $category->id,
                'brand_id' => $brand->id,
                'name_id' => "Produk Batch {$i}",
                'name_en' => "Batch Product {$i}",
                'slug_id' => "produk-batch-{$i}",
                'slug_en' => "batch-product-{$i}",
                'summary_id' => "Ringkasan produk {$i}",
                'summary_en' => "Product summary {$i}",
                'primary_image_path' => "products/batch_{$i}.jpg",
                'sort_order' => $i,
                'is_active' => true,
            ]);
        }

        $response = $this->get('/id');
        $response->assertStatus(200);
        // Products 1 to 12 should be rendered in the DOM
        for ($i = 1; $i <= 12; $i++) {
            $response->assertSee("Produk Batch {$i}");
        }
        // Product 13 and 14 should NOT be in the home top 12
        $response->assertDontSee('Produk Batch 13');
        $response->assertDontSee('Produk Batch 14');
        // Pagination track and buttons should be rendered
        $response->assertSee('aria-label="Product Carousel"', false);
        $response->assertSee('aria-label="Next Page"', false);
    }

    public function test_homepage_takes_up_to_12_brands_and_renders_both_logo_and_name(): void
    {
        for ($i = 1; $i <= 14; $i++) {
            Brand::create([
                'name' => "Prinsipal {$i}",
                'slug' => "prinsipal-{$i}",
                'logo_path' => "brands/principal_{$i}.png",
                'sort_order' => $i,
                'is_active' => true,
            ]);
        }

        $response = $this->get('/id');
        $response->assertStatus(200);
        // Brands 1 to 12 should be rendered
        for ($i = 1; $i <= 12; $i++) {
            $response->assertSee("Prinsipal {$i}");
        }
        // Brand 13 and 14 should NOT be in the home top 12
        $response->assertDontSee('Prinsipal 13');
        $response->assertDontSee('Prinsipal 14');
    }

    public function test_homepage_takes_up_to_12_clients_and_renders_both_logo_and_name(): void
    {
        for ($i = 1; $i <= 14; $i++) {
            Client::create([
                'name' => "Klien {$i}",
                'logo_path' => "clients/client_{$i}.png",
                'sort_order' => $i,
                'is_active' => true,
            ]);
        }

        $response = $this->get('/id');
        $response->assertStatus(200);
        // Clients 1 to 12 should be rendered
        for ($i = 1; $i <= 12; $i++) {
            $response->assertSee("Klien {$i}");
        }
        // Client 13 and 14 should NOT be in the home top 12
        $response->assertDontSee('Klien 13');
        $response->assertDontSee('Klien 14');
    }

    public function test_homepage_renders_gracefully_with_empty_database(): void
    {
        $idResponse = $this->get('/id');
        $idResponse->assertStatus(200);
        $idResponse->assertSee('PT Abhipraya Nawasena Sejahtera');

        $enResponse = $this->get('/en');
        $enResponse->assertStatus(200);
        $enResponse->assertSee('PT Abhipraya Nawasena Sejahtera');
    }
}
