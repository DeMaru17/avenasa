@props([
    'profile' => null,
])

@php
    $currentLocale = app()->getLocale();
    $whatsapp = $profile?->whatsapp ?? '0822-614-614-00';
    $whatsappClean = preg_replace('/[^0-9]/', '', $whatsapp);
    if (str_starts_with($whatsappClean, '0')) {
        $whatsappClean = '62' . substr($whatsappClean, 1);
    }
@endphp

<section class="py-16 lg:py-20 bg-gradient-to-r from-teal-800 via-teal-900 to-slate-950 text-white relative overflow-hidden" aria-labelledby="strategic-cta-heading">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <div class="max-w-3xl mx-auto">
            <h2 id="strategic-cta-heading" class="text-2xl sm:text-3xl lg:text-4xl font-bold text-white mb-4 tracking-tight">
                {{ $currentLocale === 'en'
                    ? 'Ready to Discuss Your Laboratory Needs?'
                    : 'Siap Mendiskusikan Kebutuhan Laboratorium Anda?' }}
            </h2>
            <p class="text-teal-100/90 text-base sm:text-lg mb-8 leading-relaxed max-w-2xl mx-auto">
                {{ $currentLocale === 'en'
                    ? 'Our sales and technical support team is ready to assist you in selecting the ideal instruments, reagents, and diagnostic solutions.'
                    : 'Tim penjualan dan dukungan teknis ANS siap membantu Anda menemukan solusi instrumen, reagen, dan diagnostik yang tepat.' }}
            </p>
            <div class="flex flex-wrap gap-4 justify-center items-center">
                <a
                    href="{{ route('contact') }}"
                    class="inline-flex items-center gap-2 bg-white text-teal-900 hover:bg-teal-50 font-bold px-7 py-3.5 rounded-lg shadow-lg transition-all focus-ring text-base active:scale-[0.98]"
                >
                    <span>{{ $currentLocale === 'en' ? 'Request a Quotation' : 'Minta Penawaran Harga' }}</span>
                    <svg class="w-4 h-4 text-teal-800" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                    </svg>
                </a>
                <a
                    href="https://wa.me/{{ $whatsappClean }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="inline-flex items-center gap-2 bg-teal-700/80 hover:bg-teal-700 text-white font-semibold px-6 py-3.5 rounded-lg border border-teal-500/30 transition-all focus-ring text-base active:scale-[0.98]"
                >
                    <svg class="w-5 h-5 text-emerald-400" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.698c.97.534 1.766.819 2.796.819 3.183 0 5.769-2.587 5.77-5.766.001-3.182-2.585-5.766-5.77-5.766zm8.482 5.765c-.002 4.673-3.805 8.475-8.483 8.475-1.428 0-2.83-.357-4.072-1.034l-4.526 1.187 1.207-4.41c-.74-1.282-1.13-2.748-1.13-4.218 0-4.673 3.802-8.475 8.48-8.475 4.678 0 8.481 3.802 8.483 8.475z" />
                    </svg>
                    <span>{{ $currentLocale === 'en' ? 'Chat via WhatsApp' : 'Konsultasi via WhatsApp' }}</span>
                </a>
            </div>
        </div>
    </div>
</section>
