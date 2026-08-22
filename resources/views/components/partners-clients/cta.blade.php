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
                    <x-icons.whatsapp class="w-5 h-5 text-emerald-600 flex-shrink-0" />
                    <span>{{ $currentLocale === 'en' ? 'Consult via WhatsApp' : 'Konsultasi via WhatsApp' }}</span>
                </a>
            </div>
        </div>
    </div>
</section>
