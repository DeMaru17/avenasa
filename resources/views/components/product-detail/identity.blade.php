@props([
    'product',
    'profile' => null,
])

@php
    $currentLocale = app()->getLocale();

    $quotationUrl = route('contact', ['product_id' => $product->id]);

    // Check brochure availability safely
    $hasBrochure = !empty($product->brochure_path) && \Illuminate\Support\Facades\Storage::disk('public')->exists($product->brochure_path);
    $brochureUrl = $hasBrochure ? route('products.brochure', ['slug' => $product->slug]) : null;

    // WhatsApp Contact URL with product context
    $whatsapp = $profile?->whatsapp;
    $whatsappClean = $whatsapp ? preg_replace('/[^0-9]/', '', $whatsapp) : '';
    if (str_starts_with($whatsappClean, '0')) {
        $whatsappClean = '62' . substr($whatsappClean, 1);
    }

    $waMessage = $currentLocale === 'en'
        ? "Hello PT Abhipraya Nawasena Sejahtera, I would like to inquire about the product: {$product->name}"
        : "Halo PT Abhipraya Nawasena Sejahtera, saya ingin berkonsultasi mengenai produk: {$product->name}";

    $whatsappUrl = !empty($whatsappClean)
        ? "https://wa.me/{$whatsappClean}?text=" . urlencode($waMessage)
        : null;
@endphp

<div class="flex flex-col justify-between h-full space-y-6">
    <div class="space-y-4">
        {{-- Classification Badges --}}
        <div class="flex flex-wrap items-center gap-2">
            @if ($product->category)
                <a
                    href="{{ route('products.index', ['category' => $product->category->slug]) }}"
                    class="text-xs font-semibold bg-teal-50 text-teal-800 border border-teal-200/90 px-3 py-1 rounded-lg hover:bg-teal-100 transition-colors focus-ring"
                >
                    {{ $product->category->name }}
                </a>
            @endif

            @if ($product->brand)
                <a
                    href="{{ route('products.index', ['brand' => $product->brand->slug]) }}"
                    class="text-xs font-medium bg-slate-100 text-slate-700 border border-slate-200 px-3 py-1 rounded-lg hover:bg-slate-200 transition-colors focus-ring"
                >
                    {{ $product->brand->name }}
                </a>
            @endif
        </div>

        {{-- Product Name (H1) --}}
        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-slate-900 tracking-tight leading-tight">
            {{ $product->name }}
        </h1>

        {{-- Summary Statement --}}
        @if (!empty($product->summary))
            <div class="border-l-4 border-teal-600 pl-4 py-1 text-sm sm:text-base text-slate-600 leading-relaxed">
                {{ $product->summary }}
            </div>
        @endif
    </div>

    {{-- Action Card Container --}}
    <div class="bg-white border border-slate-200/90 rounded-2xl p-5 sm:p-6 shadow-sm space-y-3.5 mt-6">
        {{-- Primary Quotation CTA --}}
        <a
            href="{{ $quotationUrl }}"
            onclick="if(window.ANSAnalytics) window.ANSAnalytics.trackStartQuotation({ source: 'product_detail', locale: '{{ $currentLocale }}', productId: {{ $product->id }} });"
            class="w-full inline-flex items-center justify-center gap-2 bg-teal-700 hover:bg-teal-800 text-white font-bold py-3.5 px-6 rounded-xl shadow-sm transition-all focus-ring text-base active:scale-[0.99]"
        >
            <span>{{ $currentLocale === 'en' ? 'Request a Quotation' : 'Minta Penawaran Harga' }}</span>
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
            </svg>
        </a>

        {{-- PDF Brochure Download Button (only if file exists) --}}
        @if ($hasBrochure)
            <a
                href="{{ $brochureUrl }}"
                target="_blank"
                onclick="if(window.ANSAnalytics) window.ANSAnalytics.trackDownloadBrochure({ productId: {{ $product->id }}, productName: '{{ addslashes($product->name) }}', locale: '{{ $currentLocale }}' });"
                class="w-full inline-flex items-center justify-center gap-2 bg-white hover:bg-slate-50 border border-slate-300 text-slate-800 font-semibold py-3 px-6 rounded-xl shadow-xs transition-all focus-ring text-sm active:scale-[0.99]"
            >
                <svg class="w-4 h-4 text-rose-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                </svg>
                <span>{{ $currentLocale === 'en' ? 'Download Product Brochure (PDF)' : 'Unduh Brosur Produk (PDF)' }}</span>
            </a>
        @endif

        {{-- WhatsApp Direct Inquiry --}}
        @if ($whatsappUrl)
            <a
                href="{{ $whatsappUrl }}"
                target="_blank"
                rel="noopener noreferrer"
                onclick="if(window.ANSAnalytics) window.ANSAnalytics.trackWhatsApp({ sourcePage: 'product_detail', locale: '{{ $currentLocale }}', productId: {{ $product->id }} });"
                class="w-full inline-flex items-center justify-center gap-2 bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-700 font-medium py-2.5 px-6 rounded-xl transition-all focus-ring text-sm active:scale-[0.99]"
            >
                <x-icons.whatsapp class="w-4 h-4 text-emerald-600 flex-shrink-0" />
                <span>{{ $currentLocale === 'en' ? 'Ask via Official WhatsApp' : 'Tanya via WhatsApp Resmi' }}</span>
            </a>
        @endif
    </div>
</div>
