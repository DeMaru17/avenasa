<?php

namespace Tests\Feature\Localization;

use App\Models\CompanyProfile;
use App\Models\HeroBanner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HeroBannerIntegrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_displays_active_hero_banner_content_in_indonesian(): void
    {
        HeroBanner::create([
            'title_id' => 'Solusi Laboratorium Terpadu & Terpercaya',
            'title_en' => 'Integrated & Trusted Laboratory Solutions',
            'subtitle_id' => 'Menyediakan instrumen laboratorium mutakhir dari prinsipal global.',
            'subtitle_en' => 'Providing cutting-edge laboratory instruments from global principals.',
            'image_path' => '',
            'button_text_id' => 'Jelajahi Produk',
            'button_text_en' => 'Explore Products',
            'button_url' => '/products',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $response = $this->get('/id');

        $response->assertStatus(200);
        $response->assertSee('Solusi Laboratorium Terpadu & Terpercaya');
        $response->assertSee('Menyediakan instrumen laboratorium mutakhir dari prinsipal global.');
        $response->assertSee('Jelajahi Produk');
        $response->assertSee(url('/id/products'));
        $response->assertDontSee('Integrated & Trusted Laboratory Solutions');
    }

    public function test_homepage_displays_active_hero_banner_content_in_english(): void
    {
        HeroBanner::create([
            'title_id' => 'Solusi Laboratorium Terpadu & Terpercaya',
            'title_en' => 'Integrated & Trusted Laboratory Solutions',
            'subtitle_id' => 'Menyediakan instrumen laboratorium mutakhir dari prinsipal global.',
            'subtitle_en' => 'Providing cutting-edge laboratory instruments from global principals.',
            'image_path' => '',
            'button_text_id' => 'Jelajahi Produk',
            'button_text_en' => 'Explore Products',
            'button_url' => '/products',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $response = $this->get('/en');

        $response->assertStatus(200);
        $response->assertSee('Integrated & Trusted Laboratory Solutions');
        $response->assertSee('Providing cutting-edge laboratory instruments from global principals.');
        $response->assertSee('Explore Products');
        $response->assertSee(url('/en/products'));
        $response->assertDontSee('Solusi Laboratorium Terpadu & Terpercaya');
    }

    public function test_inactive_hero_banner_is_not_displayed(): void
    {
        HeroBanner::create([
            'title_id' => 'Hero Banner Nonaktif',
            'title_en' => 'Inactive Hero Banner',
            'subtitle_id' => 'Subtitle nonaktif',
            'subtitle_en' => 'Inactive subtitle',
            'image_path' => '',
            'button_text_id' => 'Klik',
            'button_text_en' => 'Click',
            'button_url' => '/products',
            'sort_order' => 1,
            'is_active' => false,
        ]);

        $response = $this->get('/id');

        $response->assertStatus(200);
        $response->assertDontSee('Hero Banner Nonaktif');
    }

    public function test_hero_banners_are_ordered_by_sort_order(): void
    {
        HeroBanner::create([
            'title_id' => 'Hero Kedua',
            'title_en' => 'Second Hero',
            'subtitle_id' => 'Subtitle 2',
            'subtitle_en' => 'Subtitle 2 EN',
            'image_path' => '',
            'button_text_id' => 'CTA 2',
            'button_text_en' => 'CTA 2 EN',
            'button_url' => '/about',
            'sort_order' => 10,
            'is_active' => true,
        ]);

        HeroBanner::create([
            'title_id' => 'Hero Pertama',
            'title_en' => 'First Hero',
            'subtitle_id' => 'Subtitle 1',
            'subtitle_en' => 'Subtitle 1 EN',
            'image_path' => '',
            'button_text_id' => 'CTA 1',
            'button_text_en' => 'CTA 1 EN',
            'button_url' => '/products',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $response = $this->get('/id');

        $response->assertStatus(200);
        $content = $response->getContent();
        $this->assertNotFalse(strpos($content, 'Hero Pertama'));
        $this->assertNotFalse(strpos($content, 'Hero Kedua'));
        // Verify Hero Pertama is ordered before Hero Kedua
        $this->assertTrue(strpos($content, 'Hero Pertama') < strpos($content, 'Hero Kedua'));
    }

    public function test_single_hero_banner_renders_without_carousel_navigation_controls(): void
    {
        HeroBanner::create([
            'title_id' => 'Hero Tunggal',
            'title_en' => 'Single Hero',
            'subtitle_id' => 'Subtitle Tunggal',
            'subtitle_en' => 'Single Subtitle',
            'image_path' => '',
            'button_text_id' => 'CTA Tunggal',
            'button_text_en' => 'Single CTA',
            'button_url' => '/products',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $response = $this->get('/id');

        $response->assertStatus(200);
        $response->assertSee('Hero Tunggal');
        $response->assertDontSee('aria-roledescription="carousel"', false);
    }

    public function test_multiple_hero_banners_render_with_carousel_navigation_controls(): void
    {
        HeroBanner::create([
            'title_id' => 'Slide 1',
            'title_en' => 'Slide 1 EN',
            'image_path' => '',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        HeroBanner::create([
            'title_id' => 'Slide 2',
            'title_en' => 'Slide 2 EN',
            'image_path' => '',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        $response = $this->get('/id');

        $response->assertStatus(200);
        $response->assertSee('aria-roledescription="carousel"', false);
        $response->assertSee('aria-label="Slide 1 of 2"', false);
        $response->assertSee('aria-label="Slide 2 of 2"', false);
    }

    public function test_hero_banner_external_url_is_not_localized(): void
    {
        HeroBanner::create([
            'title_id' => 'Hero Eksternal',
            'title_en' => 'External Hero',
            'image_path' => 'hero-banners/ext.jpg',
            'button_text_id' => 'Kunjungi Mitra',
            'button_text_en' => 'Visit Partner',
            'button_url' => 'https://example.com/partner',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $response = $this->get('/id');

        $response->assertStatus(200);
        $response->assertSee('href="https://example.com/partner"', false);
        $response->assertDontSee('href="http://127.0.0.1:8000/id/https://example.com/partner"', false);
    }

    public function test_homepage_handles_empty_hero_banner_without_error(): void
    {
        $response = $this->get('/id');

        $response->assertStatus(200);
        $response->assertSee('PT Abhipraya Nawasena Sejahtera');
    }

    public function test_homepage_meta_description_uses_company_profile_tagline(): void
    {
        CompanyProfile::create([
            'tagline_id' => 'Mitra Solusi Laboratorium Terbaik di Indonesia',
            'tagline_en' => 'Best Laboratory Solution Partner in Indonesia',
            'about_id' => 'Tentang ANS',
            'about_en' => 'About ANS',
            'vision_id' => 'Visi',
            'vision_en' => 'Vision',
            'mission_id' => 'Misi',
            'mission_en' => 'Mission',
            'address' => 'Mensana Tower',
            'phone' => '02139722772',
            'whatsapp' => '082261461400',
            'email' => 'admin@avenasa.co.id',
        ]);

        $idResponse = $this->get('/id');
        $idResponse->assertStatus(200);
        $idResponse->assertSee('<meta name="description" content="Mitra Solusi Laboratorium Terbaik di Indonesia">', false);

        $enResponse = $this->get('/en');
        $enResponse->assertStatus(200);
        $enResponse->assertSee('<meta name="description" content="Best Laboratory Solution Partner in Indonesia">', false);
    }
}
