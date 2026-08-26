<?php

namespace Tests\Feature\Filament;

use App\Filament\Widgets\MostRequestedProductsWidget;
use App\Filament\Widgets\OverviewStatsWidget;
use App\Filament\Widgets\QuotationStatusOverviewWidget;
use App\Filament\Widgets\RecentQuotationsWidget;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Client;
use App\Models\Product;
use App\Models\Quotation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class FilamentDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::factory()->create([
            'name' => 'Admin Test',
            'email' => 'admin@avenasa.co.id',
            'password' => Hash::make('password'),
        ]);
    }

    public function test_guest_is_redirected_from_admin_dashboard_to_login(): void
    {
        $response = $this->get('/admin');

        $response->assertStatus(302);
        $response->assertRedirect('/admin/login');
    }

    public function test_authenticated_admin_can_access_dashboard(): void
    {
        $response = $this->actingAs($this->adminUser)->get('/admin');

        $response->assertStatus(200);
        $response->assertSee('Dashboard');
    }

    public function test_overview_stats_widget_computes_dynamic_counts_accurately(): void
    {
        // 3 active products + 1 inactive product
        $category = Category::create(['name_id' => 'Cat 1', 'name_en' => 'Cat 1', 'slug_id' => 'cat-1', 'slug_en' => 'cat-1', 'is_active' => true]);
        $brand = Brand::create(['name' => 'Brand A', 'slug' => 'brand-a', 'logo_path' => 'brands/brand-a.png', 'is_active' => true]);
        Brand::create(['name' => 'Brand Inactive', 'slug' => 'brand-inactive', 'logo_path' => 'brands/brand-in.png', 'is_active' => false]);

        Product::create(['category_id' => $category->id, 'brand_id' => $brand->id, 'name_id' => 'P1', 'name_en' => 'P1', 'slug_id' => 'p1', 'slug_en' => 'p1', 'primary_image_path' => 'products/p1.jpg', 'is_active' => true]);
        Product::create(['category_id' => $category->id, 'brand_id' => $brand->id, 'name_id' => 'P2', 'name_en' => 'P2', 'slug_id' => 'p2', 'slug_en' => 'p2', 'primary_image_path' => 'products/p2.jpg', 'is_active' => true]);
        Product::create(['category_id' => $category->id, 'brand_id' => $brand->id, 'name_id' => 'P3', 'name_en' => 'P3', 'slug_id' => 'p3', 'slug_en' => 'p3', 'primary_image_path' => 'products/p3.jpg', 'is_active' => true]);
        Product::create(['category_id' => $category->id, 'brand_id' => $brand->id, 'name_id' => 'P4', 'name_en' => 'P4', 'slug_id' => 'p4', 'slug_en' => 'p4', 'primary_image_path' => 'products/p4.jpg', 'is_active' => false]);

        // 2 active clients + 1 inactive
        Client::create(['name' => 'Client 1', 'logo_path' => 'clients/c1.png', 'is_active' => true]);
        Client::create(['name' => 'Client 2', 'logo_path' => 'clients/c2.png', 'is_active' => true]);
        Client::create(['name' => 'Client Inactive', 'logo_path' => 'clients/ci.png', 'is_active' => false]);

        // Quotations: 2 New, 1 Contacted, 1 Quoted, 1 Closed -> Total 5
        Quotation::create(['name' => 'User 1', 'email' => 'u1@test.com', 'subject' => 'Sub 1', 'message' => 'Msg 1', 'status' => 'New']);
        Quotation::create(['name' => 'User 2', 'email' => 'u2@test.com', 'subject' => 'Sub 2', 'message' => 'Msg 2', 'status' => 'New']);
        Quotation::create(['name' => 'User 3', 'email' => 'u3@test.com', 'subject' => 'Sub 3', 'message' => 'Msg 3', 'status' => 'Contacted']);
        Quotation::create(['name' => 'User 4', 'email' => 'u4@test.com', 'subject' => 'Sub 4', 'message' => 'Msg 4', 'status' => 'Quoted']);
        Quotation::create(['name' => 'User 5', 'email' => 'u5@test.com', 'subject' => 'Sub 5', 'message' => 'Msg 5', 'status' => 'Closed']);

        Livewire::actingAs($this->adminUser)
            ->test(OverviewStatsWidget::class)
            ->assertSee('Active Products')
            ->assertSee('3')
            ->assertSee('Business Partners')
            ->assertSee('1')
            ->assertSee('Clients')
            ->assertSee('2')
            ->assertSee('Total Inquiries')
            ->assertSee('5')
            ->assertSee('New Inquiries')
            ->assertSee('2');
    }

    public function test_quotation_status_overview_widget_breaks_down_all_workflow_statuses(): void
    {
        Quotation::create(['name' => 'A', 'email' => 'a@test.com', 'subject' => 'S1', 'message' => 'M1', 'status' => 'New']);
        Quotation::create(['name' => 'B', 'email' => 'b@test.com', 'subject' => 'S2', 'message' => 'M2', 'status' => 'Contacted']);
        Quotation::create(['name' => 'C', 'email' => 'c@test.com', 'subject' => 'S3', 'message' => 'M3', 'status' => 'Contacted']);
        Quotation::create(['name' => 'D', 'email' => 'd@test.com', 'subject' => 'S4', 'message' => 'M4', 'status' => 'Quoted']);
        Quotation::create(['name' => 'E', 'email' => 'e@test.com', 'subject' => 'S5', 'message' => 'M5', 'status' => 'Closed']);
        Quotation::create(['name' => 'F', 'email' => 'f@test.com', 'subject' => 'S6', 'message' => 'M6', 'status' => 'Closed']);
        Quotation::create(['name' => 'G', 'email' => 'g@test.com', 'subject' => 'S7', 'message' => 'M7', 'status' => 'Closed']);

        Livewire::actingAs($this->adminUser)
            ->test(QuotationStatusOverviewWidget::class)
            ->assertSee('New (Baru)')
            ->assertSee('1')
            ->assertSee('Contacted (Dihubungi)')
            ->assertSee('2')
            ->assertSee('Quoted (Penawaran Dikirim)')
            ->assertSee('1')
            ->assertSee('Closed (Selesai)')
            ->assertSee('3');
    }

    public function test_recent_quotations_widget_renders_latest_records_with_details(): void
    {
        $category = Category::create(['name_id' => 'Cat', 'name_en' => 'Cat', 'slug_id' => 'cat', 'slug_en' => 'cat', 'is_active' => true]);
        $brand = Brand::create(['name' => 'Brand', 'slug' => 'brand', 'logo_path' => 'brands/brand.png', 'is_active' => true]);
        $product = Product::create(['category_id' => $category->id, 'brand_id' => $brand->id, 'name_id' => 'Real-Time PCR Instrument', 'name_en' => 'Real-Time PCR', 'slug_id' => 'pcr', 'slug_en' => 'pcr', 'primary_image_path' => 'products/pcr.jpg', 'is_active' => true]);

        Quotation::create([
            'name' => 'Dr. Budi Santoso',
            'company' => 'PT Laboratorium Bio Medika',
            'email' => 'budi@biomedika.co.id',
            'subject' => 'Inquiry for PCR machine procurement',
            'message' => 'Please provide official quotation and brochure.',
            'product_id' => $product->id,
            'status' => 'New',
        ]);

        Livewire::actingAs($this->adminUser)
            ->test(RecentQuotationsWidget::class)
            ->assertSee('Dr. Budi Santoso')
            ->assertSee('PT Laboratorium Bio Medika')
            ->assertSee('Real-Time PCR Instrument')
            ->assertSee('Inquiry for PCR machine procurement')
            ->assertSee('New');
    }

    public function test_most_requested_products_widget_aggregates_inquiry_counts(): void
    {
        $category = Category::create(['name_id' => 'Lab Instruments', 'name_en' => 'Lab Instruments', 'slug_id' => 'lab', 'slug_en' => 'lab', 'is_active' => true]);
        $brand = Brand::create(['name' => 'BioRad', 'slug' => 'biorad', 'logo_path' => 'brands/biorad.png', 'is_active' => true]);

        $prodA = Product::create(['category_id' => $category->id, 'brand_id' => $brand->id, 'name_id' => 'Spectrophotometer UV-Vis', 'name_en' => 'Spectrophotometer', 'slug_id' => 'spec', 'slug_en' => 'spec', 'primary_image_path' => 'products/spec.jpg', 'is_active' => true]);
        $prodB = Product::create(['category_id' => $category->id, 'brand_id' => $brand->id, 'name_id' => 'Centrifuge High Speed', 'name_en' => 'Centrifuge', 'slug_id' => 'cent', 'slug_en' => 'cent', 'primary_image_path' => 'products/cent.jpg', 'is_active' => true]);

        // Prod A has 3 quotations, Prod B has 1 quotation
        Quotation::create(['name' => 'User 1', 'email' => 'u1@test.com', 'subject' => 'S1', 'message' => 'M1', 'product_id' => $prodA->id, 'status' => 'New']);
        Quotation::create(['name' => 'User 2', 'email' => 'u2@test.com', 'subject' => 'S2', 'message' => 'M2', 'product_id' => $prodA->id, 'status' => 'Contacted']);
        Quotation::create(['name' => 'User 3', 'email' => 'u3@test.com', 'subject' => 'S3', 'message' => 'M3', 'product_id' => $prodA->id, 'status' => 'Quoted']);
        Quotation::create(['name' => 'User 4', 'email' => 'u4@test.com', 'subject' => 'S4', 'message' => 'M4', 'product_id' => $prodB->id, 'status' => 'Closed']);

        Livewire::actingAs($this->adminUser)
            ->test(MostRequestedProductsWidget::class)
            ->assertSee('Spectrophotometer UV-Vis')
            ->assertSee('3')
            ->assertSee('Centrifuge High Speed')
            ->assertSee('1');
    }
}
