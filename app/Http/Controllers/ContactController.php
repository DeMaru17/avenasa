<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuotationRequest;
use App\Mail\QuotationAdminNotificationMail;
use App\Mail\QuotationConfirmationMail;
use App\Models\CompanyProfile;
use App\Models\Product;
use App\Models\Quotation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ContactController extends Controller
{
    /**
     * Display the contact and quotation inquiry page.
     */
    public function index(string $locale, Request $request): View
    {
        $companyProfile = CompanyProfile::first();

        // Server-side authoritative product context resolution
        $requestedProduct = null;
        $defaultSubject = '';

        $productId = $request->query('product_id');
        if ($productId && is_numeric($productId)) {
            $requestedProduct = Product::where('is_active', true)->find((int) $productId);

            if ($requestedProduct) {
                $productName = $locale === 'en' && ! empty($requestedProduct->name_en)
                    ? $requestedProduct->name_en
                    : $requestedProduct->name_id;

                $defaultSubject = $locale === 'en'
                    ? "Quotation Request - {$productName}"
                    : "Permintaan Penawaran Harga - {$productName}";
            }
        }

        return view('pages.contact', [
            'companyProfile' => $companyProfile,
            'requestedProduct' => $requestedProduct,
            'defaultSubject' => $defaultSubject,
        ]);
    }

    /**
     * Store a new quotation request / inquiry.
     */
    public function store(string $locale, StoreQuotationRequest $request): RedirectResponse
    {
        $successMessage = $locale === 'en'
            ? 'Your request has been received successfully. The ANS team will contact you regarding your inquiry.'
            : 'Permintaan Anda telah berhasil diterima. Tim ANS akan menghubungi Anda sesuai kebutuhan yang disampaikan.';

        // 1. Honeypot check: Silent drop if bot filled website_url_hp
        if ($request->filled('website_url_hp')) {
            Log::info('Quotation honeypot triggered; silently dropped.', [
                'ip' => $request->ip(),
            ]);

            return redirect()
                ->route('contact', ['locale' => $locale])
                ->with('success', $successMessage);
        }

        // 2. Resolve active product context from database
        $productId = $request->input('product_id');
        $activeProduct = ($productId && is_numeric($productId))
            ? Product::where('is_active', true)->find((int) $productId)
            : null;

        $validProductId = $activeProduct?->id;
        $source = $validProductId ? 'product_detail' : 'contact_page';

        // 3. Database Persistence (Single Source of Truth)
        $quotation = Quotation::create([
            'product_id' => $validProductId,
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
            'phone' => $request->validated('phone'),
            'company' => $request->validated('company'),
            'subject' => $request->validated('subject'),
            'message' => $request->validated('message'),
            'status' => 'New',
            'locale' => $locale,
        ]);

        // 4. Isolated Admin Notification Email Attempt
        try {
            $adminRecipient = config('mail.admin_address', 'admin@avenasa.co.id');
            Mail::to($adminRecipient)->send(new QuotationAdminNotificationMail($quotation));
        } catch (\Throwable $e) {
            Log::error('Failed to send admin quotation notification email', [
                'quotation_id' => $quotation->id,
                'error' => $e->getMessage(),
            ]);
        }

        // 5. Isolated User Confirmation Email Attempt
        try {
            Mail::to($quotation->email)->send(new QuotationConfirmationMail($quotation));
        } catch (\Throwable $e) {
            Log::error('Failed to send user quotation confirmation email', [
                'quotation_id' => $quotation->id,
                'email' => $quotation->email,
                'error' => $e->getMessage(),
            ]);
        }

        // 6. Non-PII GA4 Conversion Event Payload
        $ga4Payload = [
            'event' => 'submit_quotation',
            'product_id' => $validProductId,
            'has_company' => ! empty($quotation->company),
            'source' => $source,
            'locale' => $locale,
        ];

        // 7. Post-Redirect-Get with Flash Session
        return redirect()
            ->route('contact', ['locale' => $locale])
            ->with('success', $successMessage)
            ->with('ga4_event', $ga4Payload);
    }
}
