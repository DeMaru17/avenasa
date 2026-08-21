@props([
    'hero' => null,
])

@php
    $localizationService = app(\App\Services\LocalizationService::class);
    $currentLocale = app()->getLocale();
@endphp

<section class="relative w-full overflow-hidden bg-slate-900 min-h-[480px] lg:min-h-[580px] flex items-center">
    @if ($hero && !empty($hero->image_path))
        {{-- Background Image with Responsive Art Direction --}}
        <div class="absolute inset-0 z-0">
            @if (!empty($hero->mobile_image_path))
                <picture>
                    <source media="(max-width: 640px)" srcset="{{ asset('storage/' . $hero->mobile_image_path) }}">
                    <img
                        src="{{ asset('storage/' . $hero->image_path) }}"
                        alt="{{ $hero->title }}"
                        class="w-full h-full object-cover object-center"
                        loading="eager"
                    >
                </picture>
            @else
                <img
                    src="{{ asset('storage/' . $hero->image_path) }}"
                    alt="{{ $hero->title }}"
                    class="w-full h-full object-cover object-center"
                    loading="eager"
                >
            @endif
            {{-- Dark Overlay for WCAG 2.2 AA text legibility --}}
            <div class="absolute inset-0 bg-gradient-to-r from-slate-950/90 via-slate-900/75 to-slate-900/40"></div>
        </div>
    @else
        {{-- Elegant Gradient Background when no image is uploaded --}}
        <div class="absolute inset-0 z-0 bg-gradient-to-br from-slate-900 via-teal-950 to-slate-900">
            <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#0f766e_1px,transparent_1px)] [background-size:16px_16px]"></div>
        </div>
    @endif

    {{-- Hero Content --}}
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24 w-full">
        <div class="max-w-2xl">
            {{-- Trust Badge --}}
            <div class="inline-flex items-center gap-2 bg-teal-700/30 backdrop-blur-sm border border-teal-400/30 rounded-full px-4 py-1.5 mb-6">
                <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                <span class="text-teal-100 text-xs font-semibold tracking-wide uppercase">
                    {{ $currentLocale === 'en' ? 'Trusted Official Distributor' : 'Distributor Resmi Terpercaya' }}
                </span>
            </div>

            @if ($hero)
                {{-- Localized Title --}}
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white leading-tight mb-5 tracking-tight">
                    {{ $hero->title }}
                </h1>

                {{-- Localized Subtitle --}}
                @if (!empty($hero->subtitle))
                    <p class="text-base sm:text-lg text-teal-100/90 leading-relaxed mb-8 max-w-xl">
                        {{ $hero->subtitle }}
                    </p>
                @endif

                {{-- Single CMS CTA from Database --}}
                @if (!empty($hero->buttonText) && !empty($hero->button_url))
                    @php
                        $ctaUrl = $localizationService->localizeUrl($hero->button_url);
                    @endphp
                    <div class="flex flex-wrap gap-4">
                        <a
                            href="{{ $ctaUrl }}"
                            class="inline-flex items-center justify-center gap-2 bg-teal-600 hover:bg-teal-500 text-white font-semibold px-6 py-3.5 rounded-lg shadow-lg transition-all focus-ring text-base active:scale-[0.98]"
                        >
                            <span>{{ $hero->buttonText }}</span>
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </a>
                    </div>
                @endif
            @else
                {{-- Safe Empty Fallback State --}}
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white leading-tight mb-5 tracking-tight">
                    PT Abhipraya Nawasena Sejahtera
                </h1>
                <p class="text-base sm:text-lg text-teal-100/90 leading-relaxed mb-8 max-w-xl">
                    {{ $currentLocale === 'en' ? 'Leading distributor of laboratory, medical, and diagnostic equipment in Indonesia.' : 'Distributor terkemuka peralatan laboratorium, medis, dan diagnostik di Indonesia.' }}
                </p>
                <div class="flex flex-wrap gap-4">
                    <a
                        href="{{ $localizationService->localizeUrl('/products') }}"
                        class="inline-flex items-center justify-center gap-2 bg-teal-600 hover:bg-teal-500 text-white font-semibold px-6 py-3.5 rounded-lg shadow-lg transition-all focus-ring text-base active:scale-[0.98]"
                    >
                        <span>{{ $currentLocale === 'en' ? 'Product Catalog' : 'Katalog Produk' }}</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                        </svg>
                    </a>
                </div>
            @endif
        </div>
    </div>
</section>
