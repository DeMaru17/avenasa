<?php

namespace Tests\Feature\Localization;

use App\Models\CompanyProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactPageTest extends TestCase
{
    use RefreshDatabase;

    private function createCompanyProfile(?array $overrides = []): CompanyProfile
    {
        return CompanyProfile::create(array_merge([
            'tagline_id' => 'Memberdayakan Sains untuk Masa Depan yang Sejahtera',
            'tagline_en' => 'Empowering Science for a Prosperous Future',
            'about_id' => 'PT Abhipraya Nawasena Sejahtera adalah distributor resmi.',
            'about_en' => 'PT Abhipraya Nawasena Sejahtera is an authorized distributor.',
            'vision_id' => 'Visi ANS',
            'vision_en' => 'ANS Vision',
            'mission_id' => 'Misi ANS',
            'mission_en' => 'ANS Mission',
            'address' => 'Mensana Tower Lt. 15, Jl. Raya Kranggan RT.002/RW.016, Cibubur, Bekasi 17433',
            'phone' => '021 39722772',
            'whatsapp' => '0822-614-614-00',
            'email' => 'admin@avenasa.co.id',
            'maps_embed_url' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3965.3408018314136!2d106.92349797499138!3d-6.350541793639343',
        ], $overrides));
    }

    public function test_contact_page_returns_successful_response_for_both_locales(): void
    {
        $this->createCompanyProfile();

        $idResponse = $this->get('/id/contact');
        $idResponse->assertStatus(200);
        $idResponse->assertViewIs('pages.contact');

        $enResponse = $this->get('/en/contact');
        $enResponse->assertStatus(200);
        $enResponse->assertViewIs('pages.contact');
    }

    public function test_contact_page_renders_official_contact_information(): void
    {
        $this->createCompanyProfile();

        $idResponse = $this->get('/id/contact');
        $idResponse->assertSee('Informasi Kontak Resmi');
        $idResponse->assertSee('Alamat Kantor');
        $idResponse->assertSee('Mensana Tower Lt. 15, Jl. Raya Kranggan RT.002/RW.016, Cibubur, Bekasi 17433');
        $idResponse->assertSee('Telepon');
        $idResponse->assertSee('021 39722772');
        $idResponse->assertSee('href="tel:02139722772"', false);
        $idResponse->assertSee('WhatsApp');
        $idResponse->assertSee('0822-614-614-00');
        $idResponse->assertSee('https://wa.me/6282261461400');
        $idResponse->assertSee('Email');
        $idResponse->assertSee('admin@avenasa.co.id');
        $idResponse->assertSee('href="mailto:admin@avenasa.co.id"', false);

        $enResponse = $this->get('/en/contact');
        $enResponse->assertSee('Official Contact Information');
        $enResponse->assertSee('Office Address');
        $enResponse->assertSee('Telephone');
        $enResponse->assertSee('WhatsApp Direct');
        $enResponse->assertSee('Email Address');
    }

    public function test_contact_page_renders_google_maps_embed_with_accessible_title(): void
    {
        $this->createCompanyProfile();

        $idResponse = $this->get('/id/contact');
        $idResponse->assertSee('title="Lokasi Kantor PT Abhipraya Nawasena Sejahtera"', false);
        $idResponse->assertSee('Buka di Google Maps');

        $enResponse = $this->get('/en/contact');
        $enResponse->assertSee('title="Office Location of PT Abhipraya Nawasena Sejahtera"', false);
        $enResponse->assertSee('Open in Google Maps');
    }

    public function test_contact_page_handles_missing_contact_data_gracefully(): void
    {
        $this->createCompanyProfile([
            'address' => '',
            'phone' => '',
            'whatsapp' => '',
            'email' => '',
            'maps_embed_url' => '',
        ]);

        $response = $this->get('/id/contact');
        $response->assertStatus(200);
        $response->assertViewIs('pages.contact');
    }

    public function test_contact_page_handles_null_company_profile_gracefully(): void
    {
        // No company profile created
        $response = $this->get('/id/contact');
        $response->assertStatus(200);
        $response->assertViewIs('pages.contact');
    }

    public function test_language_switcher_maintains_context_between_locales(): void
    {
        $this->createCompanyProfile();

        $idResponse = $this->get('/id/contact');
        $idResponse->assertStatus(200);
        $idResponse->assertSee('/en/contact');

        $enResponse = $this->get('/en/contact');
        $enResponse->assertStatus(200);
        $enResponse->assertSee('/id/contact');
    }
}
