<?php

namespace Tests\Feature\Localization;

use App\Models\CompanyProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicLayoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_layout_renders_semantic_html_structure(): void
    {
        $response = $this->get('/id');

        $response->assertStatus(200);
        $response->assertSee('<header', false);
        $response->assertSee('<nav', false);
        $response->assertSee('<main id="main-content"', false);
        $response->assertSee('<footer', false);
    }

    public function test_layout_includes_accessibility_skip_link(): void
    {
        $response = $this->get('/id');

        $response->assertStatus(200);
        $response->assertSee('href="#main-content"', false);
        $response->assertSee('Lewati ke konten utama');
    }

    public function test_layout_includes_responsive_mobile_drawer(): void
    {
        $response = $this->get('/id');

        $response->assertStatus(200);
        $response->assertSee('id="mobile-menu-toggle"', false);
        $response->assertSee('id="mobile-menu-drawer"', false);
        $response->assertSee('aria-controls="mobile-menu-drawer"', false);
    }

    public function test_footer_displays_company_information_from_database(): void
    {
        CompanyProfile::create([
            'tagline_id' => 'Mitra Solusi Laboratorium Terpercaya',
            'tagline_en' => 'Your Trusted Laboratory Partner',
            'about_id' => 'Deskripsi perusahaan tentang ANS.',
            'about_en' => 'Company profile description for ANS.',
            'vision_id' => 'Visi perusahaan.',
            'vision_en' => 'Company vision.',
            'mission_id' => 'Misi perusahaan.',
            'mission_en' => 'Company mission.',
            'address' => 'Mensana Tower Lt. 15, Jl. Raya Kranggan Kav. 1, Cibubur, Bekasi',
            'phone' => '(021) 39722772',
            'whatsapp' => '0822-614-614-00',
            'email' => 'admin@avenasa.co.id',
        ]);

        $responseId = $this->get('/id');
        $responseId->assertStatus(200);
        $responseId->assertSee('Mensana Tower Lt. 15');
        $responseId->assertSee('(021) 39722772');
        $responseId->assertSee('admin@avenasa.co.id');
        $responseId->assertSee('Your Trusted Laboratory Partner');

        $responseEn = $this->get('/en');
        $responseEn->assertStatus(200);
        $responseEn->assertSee('Your Trusted Laboratory Partner');
    }
}
