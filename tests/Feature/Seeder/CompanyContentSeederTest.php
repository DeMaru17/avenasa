<?php

namespace Tests\Feature\Seeder;

use App\Models\CompanyProfile;
use App\Models\CoreValue;
use App\Models\Management;
use Database\Seeders\CompanyProfileSeeder;
use Database\Seeders\CoreValueSeeder;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\ManagementSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompanyContentSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_company_profile_seeder_creates_singleton_with_all_required_content(): void
    {
        $this->seed(CompanyProfileSeeder::class);

        $this->assertEquals(1, CompanyProfile::count());

        $profile = CompanyProfile::first();
        $this->assertNotNull($profile);
        $this->assertEquals('Memberdayakan Sains untuk Masa Depan yang Sejahtera', $profile->tagline_id);
        $this->assertEquals('Empowering Science for a Prosperous Future', $profile->tagline_en);
        $this->assertStringContainsString('PT Abhipraya Nawasena Sejahtera', $profile->about_id);
        $this->assertStringContainsString('PT Abhipraya Nawasena Sejahtera', $profile->about_en);
        $this->assertStringContainsString('Menjadi motor penggerak', $profile->vision_id);
        $this->assertStringNotContainsString('<ol>', $profile->mission_id);
        $this->assertStringNotContainsString('<li>', $profile->mission_id);
        $this->assertStringNotContainsString('</ol>', $profile->mission_id);
        $this->assertStringNotContainsString('</li>', $profile->mission_id);
        $this->assertStringNotContainsString('<ol>', $profile->mission_en);
        $this->assertStringNotContainsString('<li>', $profile->mission_en);
        $this->assertStringNotContainsString('</ol>', $profile->mission_en);
        $this->assertStringNotContainsString('</li>', $profile->mission_en);

        $missionIdLines = array_filter(array_map('trim', explode("\n", $profile->mission_id)));
        $missionEnLines = array_filter(array_map('trim', explode("\n", $profile->mission_en)));

        $this->assertCount(4, $missionIdLines);
        $this->assertCount(4, $missionEnLines);
        $this->assertEquals('Mewujudkan inovasi laboratorium terpadu untuk mendukung kemajuan sains, industri, dan lingkungan.', $missionIdLines[0]);
        $this->assertEquals('Realizing integrated laboratory innovation to support science, industry and environment progress.', $missionEnLines[0]);
        $this->assertEquals('021 39722772', $profile->phone);
        $this->assertEquals('0822-614-614-00', $profile->whatsapp);
        $this->assertEquals('admin@avenasa.co.id', $profile->email);
        $this->assertStringContainsString('google.com/maps', $profile->maps_embed_url);

        // Test idempotency: re-running should not create duplicate
        $this->seed(CompanyProfileSeeder::class);
        $this->assertEquals(1, CompanyProfile::count());
    }

    public function test_core_value_seeder_creates_exactly_six_active_ordered_records(): void
    {
        $this->seed(CoreValueSeeder::class);

        $this->assertEquals(6, CoreValue::count());

        $expectedValues = [
            1 => ['title_en' => 'INTEGRITY', 'icon' => 'shield-check'],
            2 => ['title_en' => 'INNOVATION', 'icon' => 'light-bulb'],
            3 => ['title_en' => 'COLLABORATION', 'icon' => 'user-group'],
            4 => ['title_en' => 'SUSTAINABILITY', 'icon' => 'arrow-path'],
            5 => ['title_en' => 'PROFESSIONALISM', 'icon' => 'briefcase'],
            6 => ['title_en' => 'WELL-BEING', 'icon' => 'heart'],
        ];

        foreach ($expectedValues as $sortOrder => $data) {
            $value = CoreValue::where('sort_order', $sortOrder)->first();
            $this->assertNotNull($value, "Core value with sort_order {$sortOrder} should exist.");
            $this->assertEquals($data['title_en'], $value->title_en);
            $this->assertEquals($data['icon'], $value->icon_name);
            $this->assertTrue($value->is_active);
            $this->assertNotEmpty($value->title_id);
            $this->assertNotEmpty($value->description_id);
            $this->assertNotEmpty($value->description_en);
        }

        // Test idempotency: re-running should not create duplicates
        $this->seed(CoreValueSeeder::class);
        $this->assertEquals(6, CoreValue::count());
    }

    public function test_management_seeder_creates_exactly_three_inactive_founders(): void
    {
        $this->seed(ManagementSeeder::class);

        $this->assertEquals(3, Management::count());

        $expectedFounders = [
            1 => ['name' => 'Erik Haryanto', 'pos_en' => 'Marketing & sales manager and commissioner'],
            2 => ['name' => 'Fernanda Ramadhan F', 'pos_en' => 'Sales and director'],
            3 => ['name' => 'Hazin Yusuf', 'pos_en' => 'Marketing manager and director'],
        ];

        foreach ($expectedFounders as $sortOrder => $data) {
            $founder = Management::where('sort_order', $sortOrder)->first();
            $this->assertNotNull($founder, "Management record with sort_order {$sortOrder} should exist.");
            $this->assertEquals($data['name'], $founder->name);
            $this->assertEquals($data['pos_en'], $founder->position_en);
            $this->assertNotEmpty($founder->position_id);
            $this->assertNotEmpty($founder->bio_id);
            $this->assertNotEmpty($founder->bio_en);
            $this->assertNull($founder->photo_path, 'Photo path must be null on initial seeding.');
            $this->assertFalse($founder->is_active, 'Founders must be initially inactive (prepared but inactive).');
        }

        // Test idempotency: re-running should not create duplicates
        $this->seed(ManagementSeeder::class);
        $this->assertEquals(3, Management::count());
    }

    public function test_database_seeder_runs_all_company_content_seeders_cleanly(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertEquals(1, CompanyProfile::count());
        $this->assertEquals(6, CoreValue::count());
        $this->assertEquals(3, Management::count());
    }
}
