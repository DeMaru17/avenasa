@props(['product', 'profile' => null])

@php
    $currentLocale = app()->getLocale();
    $quotationUrl = route('contact', ['product_id' => $product->id]);

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

<div
    class="md:hidden fixed bottom-0 inset-x-0 bg-white/95 backdrop-blur-md border-t border-slate-200/90 p-3 z-40 shadow-lg"
    aria-label="{{ __('Mobile Action Bar') }}"
>
    <div class="max-w-md mx-auto flex items-center gap-2.5">
        {{-- Primary Quotation CTA --}}
        <a
            href="{{ $quotationUrl }}"
            class="flex-1 inline-flex items-center justify-center gap-2 bg-teal-700 hover:bg-teal-800 text-white font-bold py-3 px-4 rounded-xl shadow-sm transition-all focus-ring text-sm min-h-[44px] active:scale-[0.99]"
        >
            <span>{{ $currentLocale === 'en' ? 'Request a Quotation' : 'Minta Penawaran' }}</span>
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
            </svg>
        </a>

        {{-- WhatsApp Action Button (if available) --}}
        @if ($whatsappUrl)
            <a
                href="{{ $whatsappUrl }}"
                target="_blank"
                rel="noopener noreferrer"
                class="w-12 h-12 flex-shrink-0 flex items-center justify-center rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-emerald-600 shadow-xs transition-all focus-ring active:scale-[0.95]"
                aria-label="{{ $currentLocale === 'en' ? 'Inquire via WhatsApp' : 'Konsultasi via WhatsApp' }}"
            >
                <x-icons.whatsapp class="w-6 h-6" />
            </a>
        @endif
    </div>
</div>
