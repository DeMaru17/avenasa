<?php

namespace Tests\Feature\Localization;

use App\Filament\Resources\Quotations\QuotationResource;
use App\Mail\QuotationAdminNotificationMail;
use App\Mail\QuotationConfirmationMail;
use App\Models\Brand;
use App\Models\Category;
use App\Models\CompanyProfile;
use App\Models\Product;
use App\Models\Quotation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class QuotationInquiryTest extends TestCase
{
    use RefreshDatabase;

    private function createCompanyProfile(): CompanyProfile
    {
        return CompanyProfile::create([
            'tagline_id' => 'Memberdayakan Sains',
            'tagline_en' => 'Empowering Science',
            'about_id' => 'Tentang ANS',
            'about_en' => 'About ANS',
            'vision_id' => 'Visi ANS',
            'vision_en' => 'Vision ANS',
            'mission_id' => 'Misi ANS',
            'mission_en' => 'Mission ANS',
            'address' => 'Mensana Tower Lt. 15',
            'phone' => '021 39722772',
            'whatsapp' => '0822-614-614-00',
            'email' => 'admin@avenasa.co.id',
            'maps_embed_url' => 'https://maps.google.com',
        ]);
    }

    private function createProduct(array $overrides = []): Product
    {
        $category = Category::create([
            'name_id' => 'Mikrobiologi',
            'name_en' => 'Microbiology',
            'slug_id' => 'mikrobiologi',
            'slug_en' => 'microbiology',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $brand = Brand::create([
            'name' => 'Lovibond',
            'slug' => 'lovibond',
            'logo_path' => 'brands/lovibond.svg',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        return Product::create(array_merge([
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'name_id' => 'Spectrophotometer XD 7500',
            'name_en' => 'XD 7500 Spectrophotometer',
            'slug_id' => 'spectrophotometer-xd-7500',
            'slug_en' => 'xd-7500-spectrophotometer',
            'primary_image_path' => 'products/sample.jpg',
            'description_id' => 'Deskripsi spektrofotometer presisi tinggi.',
            'description_en' => 'High precision spectrophotometer description.',
            'specifications' => [['key_id' => 'Rentang', 'key_en' => 'Range', 'value_id' => '190-1100 nm', 'value_en' => '190-1100 nm']],
            'sort_order' => 1,
            'is_active' => true,
        ], $overrides));
    }

    public function test_contact_page_renders_clean_form_for_general_inquiry(): void
    {
        $this->createCompanyProfile();

        $idResponse = $this->get('/id/contact');
        $idResponse->assertStatus(200);
        $idResponse->assertSee('Formulir Permintaan Penawaran');
        $idResponse->assertSee('name="name"', false);
        $idResponse->assertSee('name="email"', false);
        $idResponse->assertSee('name="website_url_hp"', false);
        $idResponse->assertDontSee('Produk yang Diminta');

        $enResponse = $this->get('/en/contact');
        $enResponse->assertStatus(200);
        $enResponse->assertSee('Quotation Request Form');
        $enResponse->assertDontSee('Requested Product');
    }

    public function test_contact_page_resolves_active_product_context_and_default_subject(): void
    {
        $this->createCompanyProfile();
        $product = $this->createProduct();

        $idResponse = $this->get("/id/contact?product_id={$product->id}");
        $idResponse->assertStatus(200);
        $idResponse->assertSee('Produk yang Diminta');
        $idResponse->assertSee('Spectrophotometer XD 7500');
        $idResponse->assertSee('Permintaan Penawaran Harga - Spectrophotometer XD 7500');
        $idResponse->assertSee('value="'.$product->id.'"', false);

        $enResponse = $this->get("/en/contact?product_id={$product->id}");
        $enResponse->assertStatus(200);
        $enResponse->assertSee('Requested Product');
        $enResponse->assertSee('XD 7500 Spectrophotometer');
        $enResponse->assertSee('Quotation Request - XD 7500 Spectrophotometer');
    }

    public function test_contact_page_ignores_invalid_or_inactive_product_id(): void
    {
        $this->createCompanyProfile();
        $inactiveProduct = $this->createProduct(['is_active' => false]);

        // Inactive product should fall back to clean general inquiry
        $response = $this->get("/id/contact?product_id={$inactiveProduct->id}");
        $response->assertStatus(200);
        $response->assertDontSee('Produk yang Diminta');
        $response->assertDontSee('name="product_id"', false);

        // Non-existent product ID
        $nonExistentResponse = $this->get('/id/contact?product_id=99999');
        $nonExistentResponse->assertStatus(200);
        $nonExistentResponse->assertDontSee('Produk yang Diminta');
    }

    public function test_general_quotation_submission_persists_to_database_with_new_status(): void
    {
        Mail::fake();

        $payload = [
            'name' => 'Dr. Ahmad Prasetyo',
            'email' => 'ahmad.prasetyo@laboratorium.co.id',
            'phone' => '081234567890',
            'company' => 'RS Cipto Mangunkusumo',
            'subject' => 'Permintaan Penawaran Reagen PCR',
            'message' => 'Mohon dikirimkan penawaran harga untuk 10 kit reagen PCR dengan estimasi pengiriman bulan depan.',
            'website_url_hp' => '',
        ];

        $response = $this->post('/id/contact', $payload);
        $response->assertRedirect('/id/contact');
        $response->assertSessionHas('success');
        $response->assertSessionHas('ga4_event');

        $this->assertDatabaseHas('quotations', [
            'name' => 'Dr. Ahmad Prasetyo',
            'email' => 'ahmad.prasetyo@laboratorium.co.id',
            'company' => 'RS Cipto Mangunkusumo',
            'subject' => 'Permintaan Penawaran Reagen PCR',
            'status' => 'New',
            'locale' => 'id',
            'product_id' => null,
        ]);
    }

    public function test_contextual_quotation_submission_persists_product_relation(): void
    {
        Mail::fake();
        $product = $this->createProduct();

        $payload = [
            'name' => 'Dr. Sarah Jenkins',
            'email' => 'sarah.j@bio-research.org',
            'phone' => '081198765432',
            'company' => 'National Diagnostic Center',
            'subject' => 'Quotation Request - XD 7500 Spectrophotometer',
            'message' => 'Please provide an official price quotation for 2 units of the Spectrophotometer.',
            'product_id' => (string) $product->id,
            'website_url_hp' => '',
        ];

        $response = $this->post('/en/contact', $payload);
        $response->assertRedirect('/en/contact');

        $this->assertDatabaseHas('quotations', [
            'name' => 'Dr. Sarah Jenkins',
            'email' => 'sarah.j@bio-research.org',
            'product_id' => $product->id,
            'locale' => 'en',
            'status' => 'New',
        ]);
    }

    public function test_contextual_submission_with_inactive_product_normalizes_to_null(): void
    {
        Mail::fake();
        $inactiveProduct = $this->createProduct(['is_active' => false]);

        $payload = [
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'subject' => 'Permintaan Penawaran',
            'message' => 'Mohon informasi penawaran harga alat.',
            'product_id' => (string) $inactiveProduct->id,
            'website_url_hp' => '',
        ];

        $response = $this->post('/id/contact', $payload);
        $response->assertRedirect('/id/contact');

        $this->assertDatabaseHas('quotations', [
            'name' => 'Budi Santoso',
            'product_id' => null,
        ]);
    }

    public function test_validation_rules_and_preservation_of_old_inputs(): void
    {
        $response = $this->post('/id/contact', [
            'name' => '',
            'email' => 'invalid-email-format',
            'subject' => '',
            'message' => 'short', // less than 10 characters
            'website_url_hp' => '',
        ]);

        $response->assertSessionHasErrors(['name', 'email', 'subject', 'message']);
        $this->assertEquals(0, Quotation::count());
    }

    public function test_honeypot_silently_drops_submission_without_database_persistence_or_emails(): void
    {
        Mail::fake();

        $payload = [
            'name' => 'Spam Bot',
            'email' => 'spambot@automated-marketing.com',
            'subject' => 'Cheap SEO Services',
            'message' => 'We offer top ranking SEO services for your website.',
            'website_url_hp' => 'http://spam-link.com', // Filled honeypot
        ];

        $response = $this->post('/id/contact', $payload);

        // Silent drop: Redirects with success feedback to deceive bot
        $response->assertRedirect('/id/contact');
        $response->assertSessionHas('success');
        $response->assertSessionMissing('ga4_event');

        // Zero records in database
        $this->assertEquals(0, Quotation::count());

        // Zero emails dispatched
        Mail::assertNothingSent();
    }

    public function test_throttling_rejects_more_than_5_requests_per_minute(): void
    {
        Mail::fake();

        $payload = [
            'name' => 'Tester',
            'email' => 'test@example.com',
            'subject' => 'Inquiry Test',
            'message' => 'This is a valid inquiry test message for rate limiting.',
            'website_url_hp' => '',
        ];

        // 5 allowed requests
        for ($i = 1; $i <= 5; $i++) {
            $response = $this->post('/id/contact', $payload);
            $response->assertRedirect('/id/contact');
        }

        // 6th request within same minute should receive HTTP 429
        $sixthResponse = $this->post('/id/contact', $payload);
        $sixthResponse->assertStatus(429);
    }

    public function test_mail_header_injection_characters_are_stripped(): void
    {
        Mail::fake();

        $payload = [
            'name' => "Dr. Budi\r\nBcc: hacker@evil.com",
            'email' => "budi@hospital.com\r\n",
            'subject' => "Quotation\r\nSubject-Injection: true",
            'message' => 'Pesan pengadaan alat laboratorium terakreditasi.',
            'website_url_hp' => '',
        ];

        $this->post('/id/contact', $payload);

        $this->assertDatabaseHas('quotations', [
            'name' => 'Dr. BudiBcc: hacker@evil.com',
            'email' => 'budi@hospital.com',
            'subject' => 'QuotationSubject-Injection: true',
        ]);
    }

    public function test_admin_and_user_confirmation_emails_are_dispatched_with_correct_payloads(): void
    {
        Mail::fake();
        $product = $this->createProduct();

        $payload = [
            'name' => 'Dewi Sartika',
            'email' => 'dewi.sartika@klinik-sehat.co.id',
            'phone' => '081322334455',
            'company' => 'Klinik Sehat Utama',
            'subject' => 'Permintaan Penawaran Spektrofotometer',
            'message' => 'Mohon dikirimkan surat penawaran harga resmi.',
            'product_id' => (string) $product->id,
            'website_url_hp' => '',
        ];

        $this->post('/id/contact', $payload);

        // Assert Admin Mail sent to admin@avenasa.co.id with customer Reply-To
        Mail::assertSent(QuotationAdminNotificationMail::class, function ($mail) {
            return $mail->hasTo('admin@avenasa.co.id') &&
                $mail->hasReplyTo('dewi.sartika@klinik-sehat.co.id', 'Dewi Sartika') &&
                $mail->quotation->name === 'Dewi Sartika';
        });

        // Assert User Confirmation Mail sent to user email
        Mail::assertSent(QuotationConfirmationMail::class, function ($mail) {
            return $mail->hasTo('dewi.sartika@klinik-sehat.co.id') &&
                $mail->quotation->locale === 'id';
        });
    }

    public function test_admin_notification_mailable_envelope_contains_customer_reply_to(): void
    {
        $quotation = Quotation::create([
            'name' => 'John Doe',
            'email' => 'john.doe@custom-lab.com',
            'subject' => 'Equipment Inquiry',
            'message' => 'Need pricing details for instruments.',
            'status' => 'New',
            'locale' => 'en',
        ]);

        $mailable = new QuotationAdminNotificationMail($quotation);
        $envelope = $mailable->envelope();

        $this->assertEquals('[New Inquiry] - Equipment Inquiry - John Doe', $envelope->subject);
        $this->assertCount(1, $envelope->replyTo);
        $this->assertEquals('john.doe@custom-lab.com', $envelope->replyTo[0]->address);
        $this->assertEquals('John Doe', $envelope->replyTo[0]->name);
    }

    public function test_database_persistence_succeeds_when_admin_smtp_fails(): void
    {
        Mail::shouldReceive('to')
            ->with('admin@avenasa.co.id')
            ->andReturnSelf();
        Mail::shouldReceive('send')
            ->with(\Mockery::type(QuotationAdminNotificationMail::class))
            ->andThrow(new \Exception('SMTP Connection Timed Out'));

        Mail::shouldReceive('to')
            ->with('client@hospital.com')
            ->andReturnSelf();
        Mail::shouldReceive('send')
            ->with(\Mockery::type(QuotationConfirmationMail::class))
            ->andReturnNull();

        $payload = [
            'name' => 'Dr. Hendra',
            'email' => 'client@hospital.com',
            'subject' => 'Permintaan Penawaran',
            'message' => 'Rincian kebutuhan pengadaan alat laboratorium.',
            'website_url_hp' => '',
        ];

        $response = $this->post('/id/contact', $payload);
        $response->assertRedirect('/id/contact');
        $response->assertSessionHas('success');

        // Database record remains persisted
        $this->assertDatabaseHas('quotations', [
            'name' => 'Dr. Hendra',
            'email' => 'client@hospital.com',
            'status' => 'New',
        ]);
    }

    public function test_database_persistence_succeeds_when_user_smtp_fails(): void
    {
        Mail::shouldReceive('to')
            ->with('admin@avenasa.co.id')
            ->andReturnSelf();
        Mail::shouldReceive('send')
            ->with(\Mockery::type(QuotationAdminNotificationMail::class))
            ->andReturnNull();

        Mail::shouldReceive('to')
            ->with('client2@hospital.com')
            ->andReturnSelf();
        Mail::shouldReceive('send')
            ->with(\Mockery::type(QuotationConfirmationMail::class))
            ->andThrow(new \Exception('SMTP Authentication Failed'));

        $payload = [
            'name' => 'Dr. Maya',
            'email' => 'client2@hospital.com',
            'subject' => 'Inquiry Equipment',
            'message' => 'Detailed procurement requirements for hospital.',
            'website_url_hp' => '',
        ];

        $response = $this->post('/en/contact', $payload);
        $response->assertRedirect('/en/contact');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('quotations', [
            'name' => 'Dr. Maya',
            'email' => 'client2@hospital.com',
            'status' => 'New',
        ]);
    }

    public function test_database_persistence_succeeds_when_both_smtp_fail(): void
    {
        Mail::shouldReceive('to')->andReturnSelf();
        Mail::shouldReceive('send')->andThrow(new \Exception('Network unreachable'));

        $payload = [
            'name' => 'Dr. Joko',
            'email' => 'joko@lab.co.id',
            'subject' => 'Permintaan Reagen',
            'message' => 'Rincian kebutuhan reagen diagnostik klinik.',
            'website_url_hp' => '',
        ];

        $response = $this->post('/id/contact', $payload);
        $response->assertRedirect('/id/contact');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('quotations', [
            'name' => 'Dr. Joko',
            'email' => 'joko@lab.co.id',
            'status' => 'New',
        ]);
    }

    public function test_ga4_conversion_event_session_payload_contains_no_pii(): void
    {
        Mail::fake();
        $product = $this->createProduct();

        $payload = [
            'name' => 'Budi Santoso PII',
            'email' => 'budi.pii@sensitive-domain.com',
            'phone' => '081299998888',
            'company' => 'Sensitive Company Name',
            'subject' => 'Secret Subject Details',
            'message' => 'Very confidential requirements text here.',
            'product_id' => (string) $product->id,
            'website_url_hp' => '',
        ];

        $response = $this->post('/id/contact', $payload);
        $ga4Event = session('ga4_event');

        $this->assertNotNull($ga4Event);
        $this->assertEquals('submit_quotation', $ga4Event['event']);
        $this->assertEquals($product->id, $ga4Event['product_id']);
        $this->assertTrue($ga4Event['has_company']);
        $this->assertEquals('product_detail', $ga4Event['source']);
        $this->assertEquals('id', $ga4Event['locale']);

        // Strict No-PII Assertion
        $this->assertArrayNotHasKey('name', $ga4Event);
        $this->assertArrayNotHasKey('email', $ga4Event);
        $this->assertArrayNotHasKey('phone', $ga4Event);
        $this->assertArrayNotHasKey('company', $ga4Event);
        $this->assertArrayNotHasKey('subject', $ga4Event);
        $this->assertArrayNotHasKey('message', $ga4Event);
        $this->assertArrayNotHasKey('quotation_id', $ga4Event);
    }

    public function test_filament_quotation_resource_badge_reflects_new_quotations_count(): void
    {
        $user = User::factory()->create();

        Quotation::create([
            'name' => 'Quotation 1',
            'email' => 'q1@test.com',
            'subject' => 'Subject 1',
            'message' => 'Message requirement 1.',
            'status' => 'New',
            'locale' => 'id',
        ]);

        Quotation::create([
            'name' => 'Quotation 2',
            'email' => 'q2@test.com',
            'subject' => 'Subject 2',
            'message' => 'Message requirement 2.',
            'status' => 'Contacted',
            'locale' => 'id',
        ]);

        $badge = QuotationResource::getNavigationBadge();
        $this->assertEquals('1', $badge);
    }

    public function test_filament_quotation_status_and_admin_notes_can_be_updated(): void
    {
        $quotation = Quotation::create([
            'name' => 'Dr. Bambang',
            'email' => 'bambang@rs.co.id',
            'subject' => 'Penawaran Alat',
            'message' => 'Rincian pesan kebutuhan alat.',
            'status' => 'New',
            'locale' => 'id',
        ]);

        $quotation->update([
            'status' => 'Contacted',
            'admin_notes' => 'Telah dihubungi via WhatsApp oleh sales ANS.',
        ]);

        $this->assertDatabaseHas('quotations', [
            'id' => $quotation->id,
            'status' => 'Contacted',
            'admin_notes' => 'Telah dihubungi via WhatsApp oleh sales ANS.',
        ]);
    }
}
