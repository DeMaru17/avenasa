@props(['profile' => null])

@php
    $currentLocale = app()->getLocale();
    $mapsEmbedUrl = $profile?->maps_embed_url;
    $googleMapsSearchUrl = 'https://www.google.com/maps/search/?api=1&query=' . urlencode('Mensana Tower Cibubur Bekasi');
@endphp

<div class="bg-slate-50 border border-slate-200/90 rounded-2xl p-6 sm:p-7 flex flex-col justify-between">
    {{-- Map Preview / Embed --}}
    @if (!empty($mapsEmbedUrl))
        <div class="relative w-full h-64 sm:h-72 rounded-xl overflow-hidden border border-slate-200 bg-slate-100 mb-5 shadow-inner">
            <iframe
                src="{{ $mapsEmbedUrl }}"
                width="100%"
                height="100%"
                style="border:0;"
                allowfullscreen=""
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
                title="{{ $currentLocale === 'en' ? 'Office Location of PT Abhipraya Nawasena Sejahtera' : 'Lokasi Kantor PT Abhipraya Nawasena Sejahtera' }}"
                class="w-full h-full object-cover"
            ></iframe>
        </div>
    @endif

    {{-- Location Card Info & External Link --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pt-1">
        <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-xl bg-teal-50 border border-teal-100 flex items-center justify-center flex-shrink-0 text-teal-700 mt-0.5" aria-hidden="true">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-bold text-slate-900 leading-snug">
                    Mensana Tower, Cibubur
                </h3>
                <p class="text-xs text-slate-500 mt-0.5">
                    Jl. Raya Kranggan Kav. 1, Jatisampurna, Bekasi
                </p>
            </div>
        </div>

        <a
            href="{{ $googleMapsSearchUrl }}"
            target="_blank"
            rel="noopener noreferrer"
            class="inline-flex items-center gap-1.5 text-xs font-bold text-teal-700 hover:text-teal-800 bg-white border border-slate-200 hover:border-teal-300 px-4 py-2.5 rounded-lg shadow-sm transition-all focus-ring whitespace-nowrap active:scale-[0.98]"
        >
            <span>{{ $currentLocale === 'en' ? 'Open in Google Maps' : 'Buka di Google Maps' }}</span>
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
            </svg>
        </a>
    </div>
</div>
