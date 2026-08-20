<?php

namespace Tests\Feature\Seeder;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Client;
use App\Models\CompanyProfile;
use App\Models\CoreValue;
use App\Models\Management;
use App\Models\Product;
use Database\Seeders\ClientSeeder;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientSeederTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var list<string> Approved client inventory from Company Profile ANS 2026 Rev.02 (Pages 11 & 12)
     */
    protected array $expectedClients = [
        'Kalbe Farma',
        'Sakafarma Laboratories',
        'Dankos Farma',
        'Dexa Medica',
        'Beta Pharmacon',
        'Mahakam Beta Farma',
        'Bayer',
        'Prosweal Indomax',
        'Medifarma Laboratories',
        'Darya-Varia Laboratoria',
        'Actavis',
        'Sanbe Farma',
        'Bio Farma',
        'Lapi Laboratories',
        'Meiji',
        'Molex Ayus',
        'Otsuka',
        'Widatra Bhakti',
        'B. Braun',
        'Erela',
        'Pharos',
        'Prima Medika Laboratories',
        'Fahrenheit',
        'Rohto',
        'Tempo Scan',
        'Combiphar',
        'Kalbe Nutritionals',
        'Indofood',
        'Nutrifood',
        'Wings Food',
        'Mayora',
        'Danone',
        'Unilever',
        'Orang Tua',
        'Sosro',
        'Garudafood',
        'Salim Ivomas Pratama',
        'Cimory',
        'Charoen Pokphand',
        'Japfa Food',
        'Nestle',
        'Sarihusada',
        'Global Dairi Alami',
        'Indolakto',
        'Ultrajaya',
        'Diamond',
        'Greenfields',
        'SIG',
        'SGS',
        'TUV NORD',
        'Intertek',
        'Sucofindo',
        'Kemenkes BB Binomika',
        'Alkesda',
        'Universitas Islam Indonesia',
        'UIN Sunan Kalijaga Yogyakarta',
        'Universitas Gadjah Mada',
        'Universitas Indonesia',
        'Universitas Pelita Harapan',
        'Prodia',
        'CITO',
    ];

    public function test_client_seeder_creates_all_confirmed_clients_from_inventory(): void
    {
        $this->seed(ClientSeeder::class);

        $this->assertCount(count($this->expectedClients), $this->expectedClients);
        $this->assertEquals(count($this->expectedClients), Client::count());
        $this->assertEquals(count($this->expectedClients), Client::active()->count());
    }

    public function test_all_seeded_clients_have_valid_identities_and_empty_logo_contract(): void
    {
        $this->seed(ClientSeeder::class);

        $clients = Client::all();

        foreach ($clients as $client) {
            $this->assertNotEmpty($client->name, 'Client name must not be empty.');
            $this->assertTrue($client->is_active, 'Seeded client must be active.');
            $this->assertSame('', $client->logo_path, 'Initial logo_path must be empty string per contract.');
            $this->assertGreaterThan(0, $client->sort_order, 'sort_order must be greater than zero.');
        }
    }

    public function test_client_names_are_unique_and_match_approved_inventory(): void
    {
        $this->seed(ClientSeeder::class);

        $dbNames = Client::pluck('name')->all();

        $this->assertCount(count($this->expectedClients), array_unique($dbNames), 'All client names must be unique.');
        $this->assertEqualsCanonicalizing($this->expectedClients, $dbNames, 'Database client names must exactly match approved source inventory.');
    }

    public function test_client_sort_order_is_deterministic_and_sequential(): void
    {
        $this->seed(ClientSeeder::class);

        $sortOrders = Client::orderBy('sort_order')->pluck('sort_order')->all();
        $expectedOrders = range(1, count($this->expectedClients));

        $this->assertEquals($expectedOrders, $sortOrders, 'sort_order must be sequential from 1 to N without duplicates.');
    }

    public function test_client_seeder_is_idempotent(): void
    {
        $this->seed(ClientSeeder::class);
        $initialCount = Client::count();

        // Run seeder second time
        $this->seed(ClientSeeder::class);
        $this->assertEquals($initialCount, Client::count(), 'Re-running ClientSeeder must not duplicate records.');
    }

    public function test_client_seeder_does_not_modify_other_seeded_entities(): void
    {
        $this->seed(DatabaseSeeder::class);

        $companyProfileCount = CompanyProfile::count();
        $coreValueCount = CoreValue::count();
        $managementCount = Management::count();
        $categoryCount = Category::count();
        $brandCount = Brand::count();
        $productCount = Product::count();
        $clientCount = Client::count();

        // Re-run ClientSeeder independently
        $this->seed(ClientSeeder::class);

        $this->assertEquals($companyProfileCount, CompanyProfile::count());
        $this->assertEquals($coreValueCount, CoreValue::count());
        $this->assertEquals($managementCount, Management::count());
        $this->assertEquals($categoryCount, Category::count());
        $this->assertEquals($brandCount, Brand::count());
        $this->assertEquals($productCount, Product::count());
        $this->assertEquals($clientCount, Client::count());
    }

    public function test_database_seeder_runs_cleanly_including_clients(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertEquals(1, CompanyProfile::count());
        $this->assertEquals(6, CoreValue::count());
        $this->assertEquals(3, Management::count());
        $this->assertEquals(7, Category::count());
        $this->assertEquals(18, Brand::count());
        $this->assertEquals(50, Product::count());
        $this->assertEquals(count($this->expectedClients), Client::count());
    }
}
