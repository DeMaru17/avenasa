@props(['profile' => null])

@php
    $currentLocale = app()->getLocale();
    $whatsapp = $profile?->whatsapp ?? '0822-614-614-00';
    $whatsappClean = preg_replace('/[^0-9]/', '', $whatsapp);
    if (str_starts_with($whatsappClean, '0')) {
        $whatsappClean = '62' . substr($whatsappClean, 1);
    }
@endphp

<section class="py-16 lg:py-20 bg-white" aria-labelledby="partners-cta-heading">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="bg-slate-50 border border-slate-200/90 rounded-3xl p-8 sm:p-12 lg:p-16 max-w-4xl mx-auto">
            <h2 id="partners-cta-heading" class="text-2xl sm:text-3xl lg:text-4xl font-bold text-slate-900 tracking-tight mb-4">
                {{ $currentLocale === 'en' ? 'Join Our Trusted Client Network' : 'Bergabunglah Bersama Ribuan Pelanggan Terpercaya' }}
            </h2>
            <p class="text-slate-600 text-sm sm:text-base mb-8 max-w-xl mx-auto leading-relaxed">
                {{ $currentLocale === 'en'
                    ? 'Contact the Abhipraya Nawasena Sejahtera team today for more information regarding partnership programs, product availability, and official quotations.'
                    : 'Hubungi tim Abhipraya Nawasena Sejahtera hari ini untuk informasi lebih lanjut tentang program kemitraan, ketersediaan produk, dan penawaran harga terbaik.' }}
            </p>
            <div class="flex flex-wrap gap-4 justify-center items-center">
                <a
                    href="{{ route('contact') }}"
                    class="inline-flex items-center justify-center gap-2 bg-teal-700 hover:bg-teal-800 text-white font-bold px-7 py-3.5 rounded-lg shadow-sm transition-all focus-ring text-base active:scale-[0.98]"
                >
                    <span>{{ $currentLocale === 'en' ? 'Request a Quotation' : 'Minta Penawaran Harga' }}</span>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                    </svg>
                </a>
                <a
                    href="https://wa.me/{{ $whatsappClean }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="inline-flex items-center justify-center gap-2 bg-white hover:bg-slate-100 border border-slate-300 text-slate-800 font-semibold px-6 py-3.5 rounded-lg transition-all focus-ring text-base"
                >
                    <svg class="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.699c.969.54 1.777.818 2.796.818 3.18 0 5.766-2.586 5.767-5.766.001-3.18-2.585-5.766-5.767-5.766zm0 10.362c-.886 0-1.611-.26-2.316-.678l-.165-.098-1.58.415.422-1.54-.108-.172c-.496-.79-1.026-1.57-1.026-2.523 0-2.536 2.064-4.6 4.601-4.6 2.537 0 4.601 2.064 4.6 4.6 0 2.536-2.064 4.6-4.601 4.6zm6.816-12.188c-1.803-1.805-4.2-2.798-6.755-2.799-5.26 0-9.54 4.28-9.542 9.54 0 1.68.438 3.32 1.272 4.764l-1.35 4.935 5.048-1.324c1.391.76 2.96 1.16 4.568 1.16h.004c5.259 0 9.539-4.28 9.541-9.541 0-2.551-.994-4.949-2.798-6.755z" />
                    </svg>
                    <span>{{ $currentLocale === 'en' ? 'Consult via WhatsApp' : 'Konsultasi via WhatsApp' }}</span>
                </a>
            </div>
        </div>
    </div>
</section>
