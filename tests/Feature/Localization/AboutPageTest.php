<?php

namespace Tests\Feature\Localization;

use App\Models\CompanyProfile;
use App\Models\CoreValue;
use App\Models\Management;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AboutPageTest extends TestCase
{
    use RefreshDatabase;

    private function createCompanyProfile(): CompanyProfile
    {
        return CompanyProfile::create([
            'tagline_id' => 'Empowering Science for a Prosperous Future',
            'tagline_en' => 'Empowering Science for a Prosperous Future',
            'about_id' => 'PT Abhipraya Nawasena Sejahtera (ANS) adalah perusahaan distributor alat laboratorium.',
            'about_en' => 'PT Abhipraya Nawasena Sejahtera (ANS) is a laboratory equipment distributor.',
            'vision_id' => 'Menjadi motor penggerak kemajuan ilmu hayati di Indonesia.',
            'vision_en' => 'To be a driving force of life science advancement in Indonesia.',
            'mission_id' => "Mewujudkan inovasi laboratorium terpadu.\nMenyediakan solusi bertanggung jawab.\nMembangun kerja sama strategis.",
            'mission_en' => "Realizing integrated laboratory innovation.\nProviding responsible solutions.\nBuilding strategic cooperation.",
            'address' => 'Mensana Tower Lt. 15, Jl. Raya Kranggan Kav. 1, Cibubur, Bekasi',
            'phone' => '021 39722772',
            'whatsapp' => '0822-614-614-00',
            'email' => 'admin@avenasa.co.id',
        ]);
    }

    public function test_about_page_returns_successful_response_for_both_locales(): void
    {
        $this->createCompanyProfile();

        $idResponse = $this->get('/id/about');
        $idResponse->assertStatus(200);
        $idResponse->assertViewIs('pages.about');

        $enResponse = $this->get('/en/about');
        $enResponse->assertStatus(200);
        $enResponse->assertViewIs('pages.about');
    }

    public function test_about_page_renders_hero_and_company_profile_correctly(): void
    {
        $this->createCompanyProfile();

        $idResponse = $this->get('/id/about');
        $idResponse->assertSee('Tentang PT Abhipraya Nawasena Sejahtera');
        $idResponse->assertSee('Empowering Science for a Prosperous Future');
        $idResponse->assertSee('PT Abhipraya Nawasena Sejahtera (ANS) adalah perusahaan distributor alat laboratorium.');
        $idResponse->assertSee('logo-ans.png');
        $idResponse->assertSee('PT Abhipraya Nawasena');
        $idResponse->assertSee('Sejahtera');
        $idResponse->assertSee('Life Science');
        $idResponse->assertSee('Laboratory Solutions');
        $idResponse->assertSee('Diagnostics');
        $idResponse->assertDontSee('mensana-tower.png');

        $enResponse = $this->get('/en/about');
        $enResponse->assertSee('About PT Abhipraya Nawasena Sejahtera');
        $enResponse->assertSee('Empowering Science for a Prosperous Future');
        $enResponse->assertSee('PT Abhipraya Nawasena Sejahtera (ANS) is a laboratory equipment distributor.');
        $enResponse->assertSee('logo-ans.png');
        $enResponse->assertSee('PT Abhipraya Nawasena');
        $enResponse->assertSee('Sejahtera');
        $enResponse->assertSee('Life Science');
        $enResponse->assertSee('Laboratory Solutions');
        $enResponse->assertSee('Diagnostics');
        $enResponse->assertDontSee('mensana-tower.png');
    }

    public function test_about_page_renders_vision_and_mission_correctly(): void
    {
        $this->createCompanyProfile();

        $idResponse = $this->get('/id/about');
        $idResponse->assertSee('Visi &amp; Misi', false);
        $idResponse->assertSee('Menjadi Bagian dari Masa Depan yang Lebih Baik');
        $idResponse->assertSee('Menjadi motor penggerak kemajuan ilmu hayati di Indonesia.');
        $idResponse->assertSee('Mewujudkan inovasi laboratorium terpadu.');
        $idResponse->assertSee('Menyediakan solusi bertanggung jawab.');
        $idResponse->assertSee('Membangun kerja sama strategis.');

        $enResponse = $this->get('/en/about');
        $enResponse->assertSee('Vision &amp; Mission', false);
        $enResponse->assertSee('Being Part of a Better Future');
        $enResponse->assertSee('To be a driving force of life science advancement in Indonesia.');
        $enResponse->assertSee('Realizing integrated laboratory innovation.');
        $enResponse->assertSee('Providing responsible solutions.');
        $enResponse->assertSee('Building strategic cooperation.');
    }

    public function test_about_page_renders_active_core_values_in_correct_order_and_excludes_inactive(): void
    {
        $this->createCompanyProfile();

        CoreValue::create([
            'title_id' => 'Inovasi',
            'title_en' => 'INNOVATION',
            'description_id' => 'Deskripsi inovasi saintifik.',
            'description_en' => 'Description of scientific innovation.',
            'icon_name' => 'light-bulb',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        CoreValue::create([
            'title_id' => 'Integritas',
            'title_en' => 'INTEGRITY',
            'description_id' => 'Deskripsi integritas bisnis.',
            'description_en' => 'Description of business integrity.',
            'icon_name' => 'shield-check',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        CoreValue::create([
            'title_id' => 'Nilai Nonaktif',
            'title_en' => 'INACTIVE VALUE',
            'description_id' => 'Deskripsi nilai nonaktif.',
            'description_en' => 'Description of inactive value.',
            'icon_name' => 'x-mark',
            'sort_order' => 3,
            'is_active' => false,
        ]);

        $idResponse = $this->get('/id/about');
        $idResponse->assertSee('Nilai yang Menjadi Landasan Kami');
        $idResponse->assertSee('Integritas');
        $idResponse->assertSee('Inovasi');
        $idResponse->assertSee('Deskripsi integritas bisnis.');
        $idResponse->assertSee('Deskripsi inovasi saintifik.');
        $idResponse->assertDontSee('Nilai Nonaktif');

        // Order check: Integritas (sort_order 1) must appear before Inovasi (sort_order 2)
        $idContent = $idResponse->getContent();
        $this->assertLessThan(
            strpos($idContent, 'Inovasi'),
            strpos($idContent, 'Integritas')
        );

        $enResponse = $this->get('/en/about');
        $enResponse->assertSee('Our Foundational Values');
        $enResponse->assertSee('INTEGRITY');
        $enResponse->assertSee('INNOVATION');
        $enResponse->assertSee('Description of business integrity.');
        $enResponse->assertSee('Description of scientific innovation.');
        $enResponse->assertDontSee('INACTIVE VALUE');
    }

    public function test_management_section_hides_when_all_records_are_inactive(): void
    {
        $this->createCompanyProfile();

        Management::create([
            'name' => 'Erik Haryanto',
            'position_id' => 'Komisaris',
            'position_en' => 'Commissioner',
            'bio_id' => 'Bio komisaris',
            'bio_en' => 'Commissioner bio',
            'photo_path' => null,
            'sort_order' => 1,
            'is_active' => false,
        ]);

        $idResponse = $this->get('/id/about');
        $idResponse->assertStatus(200);
        $idResponse->assertDontSee('Manajemen & Pendiri');
        $idResponse->assertDontSee('Erik Haryanto');
    }

    public function test_management_section_renders_when_records_are_active(): void
    {
        $this->createCompanyProfile();

        Management::create([
            'name' => 'Hazin Yusuf',
            'position_id' => 'Direktur / Manajer Pemasaran',
            'position_en' => 'Marketing Manager and Director',
            'bio_id' => '2001 – Sekarang: Merintis bisnis life science.',
            'bio_en' => '2001 – Present: Pioneering life science business.',
            'photo_path' => null,
            'sort_order' => 1,
            'is_active' => true,
        ]);

        Management::create([
            'name' => 'Inactive Founder',
            'position_id' => 'Penasihat',
            'position_en' => 'Advisor',
            'bio_id' => 'Bio penasihat',
            'bio_en' => 'Advisor bio',
            'photo_path' => null,
            'sort_order' => 2,
            'is_active' => false,
        ]);

        $idResponse = $this->get('/id/about');
        $idResponse->assertStatus(200);
        $idResponse->assertSee('Manajemen & Pendiri');
        $idResponse->assertSee('Pemimpin di Balik Komitmen ANS');
        $idResponse->assertSee('Hazin Yusuf');
        $idResponse->assertSee('Direktur / Manajer Pemasaran');
        $idResponse->assertSee('2001 – Sekarang: Merintis bisnis life science.');
        $idResponse->assertDontSee('Inactive Founder');

        $enResponse = $this->get('/en/about');
        $enResponse->assertStatus(200);
        $enResponse->assertSee('Management & Leadership');
        $enResponse->assertSee('Leaders Behind ANS Commitment');
        $enResponse->assertSee('Hazin Yusuf');
        $enResponse->assertSee('Marketing Manager and Director');
        $enResponse->assertSee('2001 – Present: Pioneering life science business.');
        $enResponse->assertDontSee('Inactive Founder');
    }

    public function test_about_page_renders_catalog_cta(): void
    {
        $this->createCompanyProfile();

        $idResponse = $this->get('/id/about');
        $idResponse->assertSee('Lihat Portofolio Produk Kami');
        $idResponse->assertSee('Jelajahi Katalog Produk');

        $enResponse = $this->get('/en/about');
        $enResponse->assertSee('View Our Product Portfolio');
        $enResponse->assertSee('Explore Product Catalog');
    }
}
