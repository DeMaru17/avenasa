@props([
    'banners' => collect(),
])

@php
    $localizationService = app(\App\Services\LocalizationService::class);
    $currentLocale = app()->getLocale();
    $bannerList = $banners instanceof \Illuminate\Support\Collection ? $banners : collect($banners ?: []);
    $bannerCount = $bannerList->count();
@endphp

@if ($bannerCount === 0)
    {{-- Safe Empty Fallback Hero State --}}
    <section class="relative w-full overflow-hidden bg-slate-900 min-h-[520px] lg:min-h-[620px] flex items-center" aria-label="{{ __('Hero Banner') }}">
        <div class="absolute inset-0 z-0 bg-gradient-to-br from-slate-900 via-teal-950 to-slate-900">
            <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#0f766e_1px,transparent_1px)] [background-size:16px_16px]"></div>
        </div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24 w-full">
            <div class="max-w-2xl">
                <div class="text-teal-300 text-xs sm:text-sm font-semibold tracking-widest uppercase mb-3 sm:mb-4">
                    {{ $currentLocale === 'en' ? 'Trusted Official Distributor' : 'Distributor Resmi Terpercaya' }}
                </div>
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white leading-tight mb-5 tracking-tight">
                    PT Abhipraya Nawasena Sejahtera
                </h1>
                <p class="text-base sm:text-lg text-teal-100/90 leading-relaxed mb-8 max-w-xl">
                    {{ $currentLocale === 'en' ? 'Leading distributor of laboratory, medical, and diagnostic equipment in Indonesia.' : 'Distributor terkemuka peralatan laboratorium, medis, dan diagnostik di Indonesia.' }}
                </p>
                <div class="flex flex-wrap gap-4">
                    <a
                        href="{{ route('products.index') }}"
                        onclick="if(window.ANSAnalytics) window.ANSAnalytics.trackHeroCta({ bannerId: 0, locale: '{{ $currentLocale }}', ctaType: 'primary_cta', destinationType: 'internal_catalog' });"
                        class="inline-flex items-center justify-center gap-2 bg-teal-600 hover:bg-teal-500 text-white font-semibold px-6 py-3.5 rounded-lg shadow-lg transition-all focus-ring text-base active:scale-[0.98]"
                    >
                        <span>{{ $currentLocale === 'en' ? 'Explore Product Catalog' : 'Jelajahi Katalog Produk' }}</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                        </svg>
                    </a>
                    <a
                        href="{{ route('about') }}"
                        onclick="if(window.ANSAnalytics) window.ANSAnalytics.trackHeroCta({ bannerId: 0, locale: '{{ $currentLocale }}', ctaType: 'secondary_cta', destinationType: 'internal_page' });"
                        class="inline-flex items-center justify-center gap-2 bg-white/15 backdrop-blur-sm hover:bg-white/25 border border-white/30 text-white font-semibold px-6 py-3.5 rounded-lg transition-all focus-ring text-base"
                    >
                        <span>{{ $currentLocale === 'en' ? 'Company Profile' : 'Profil Perusahaan' }}</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

@elseif ($bannerCount === 1)
    {{-- Single Banner: Static Hero with Gentle Motion (No Carousel Controls) --}}
    @php
        $singleHero = $bannerList->first();
        $isExternal = !empty($singleHero->button_url) && (str_starts_with($singleHero->button_url, 'http://') || str_starts_with($singleHero->button_url, 'https://') || str_starts_with($singleHero->button_url, '//'));
        $ctaUrl = !empty($singleHero->button_url) ? ($isExternal ? $singleHero->button_url : $localizationService->localizeUrl($singleHero->button_url)) : null;
    @endphp
    <section class="relative w-full overflow-hidden bg-slate-900 min-h-[520px] lg:min-h-[620px] flex items-center" aria-label="{{ __('Hero Banner') }}">
        @if (!empty($singleHero->image_path))
            <div class="absolute inset-0 z-0 overflow-hidden">
                <picture>
                    @if (!empty($singleHero->mobile_image_path))
                        <source media="(max-width: 640px)" srcset="{{ asset('storage/' . $singleHero->mobile_image_path) }}">
                    @endif
                    <img
                        src="{{ asset('storage/' . $singleHero->image_path) }}"
                        alt="{{ $singleHero->title }}"
                        class="w-full h-full object-cover object-center animate-hero-zoom-out transform"
                        loading="eager"
                        fetchpriority="high"
                    >
                </picture>
                <div class="absolute inset-0 bg-gradient-to-r from-slate-950/90 via-slate-900/75 to-slate-900/40"></div>
            </div>
        @else
            <div class="absolute inset-0 z-0 bg-gradient-to-br from-slate-900 via-teal-950 to-slate-900">
                <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#0f766e_1px,transparent_1px)] [background-size:16px_16px]"></div>
            </div>
        @endif

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24 w-full">
            <div class="max-w-2xl">
                <div class="text-teal-300 text-xs sm:text-sm font-semibold tracking-widest uppercase mb-3 sm:mb-4">
                    {{ $currentLocale === 'en' ? 'Trusted Official Distributor' : 'Distributor Resmi Terpercaya' }}
                </div>

                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white leading-tight mb-5 tracking-tight">
                    {{ $singleHero->title }}
                </h1>

                @if (!empty($singleHero->subtitle))
                    <p class="text-base sm:text-lg text-teal-100/90 leading-relaxed mb-8 max-w-xl">
                        {{ $singleHero->subtitle }}
                    </p>
                @endif

                <div class="flex flex-wrap gap-4">
                    @if (!empty($singleHero->buttonText) && !empty($ctaUrl))
                        <a
                            href="{{ $ctaUrl }}"
                            onclick="if(window.ANSAnalytics) window.ANSAnalytics.trackHeroCta({ bannerId: {{ $singleHero->id }}, locale: '{{ $currentLocale }}', ctaType: 'primary_cta', destinationType: '{{ $isExternal ? 'external' : (str_contains($ctaUrl, 'products') ? 'internal_catalog' : 'internal_page') }}' });"
                            @if($isExternal) target="_blank" rel="noopener noreferrer" @endif
                            class="inline-flex items-center justify-center gap-2 bg-teal-600 hover:bg-teal-500 text-white font-semibold px-6 py-3.5 rounded-lg shadow-lg transition-all focus-ring text-base active:scale-[0.98]"
                        >
                            <span>{{ $singleHero->buttonText }}</span>
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </a>
                    @endif
                    <a
                        href="{{ route('about') }}"
                        onclick="if(window.ANSAnalytics) window.ANSAnalytics.trackHeroCta({ bannerId: {{ $singleHero->id }}, locale: '{{ $currentLocale }}', ctaType: 'secondary_cta', destinationType: 'internal_page' });"
                        class="inline-flex items-center justify-center gap-2 bg-white/15 backdrop-blur-sm hover:bg-white/25 border border-white/30 text-white font-semibold px-6 py-3.5 rounded-lg transition-all focus-ring text-base"
                    >
                        <span>{{ $currentLocale === 'en' ? 'Company Profile' : 'Profil Perusahaan' }}</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

@else
    {{-- Multiple Banners: True Single-Slide Interactive Alpine.js Carousel with Slow Zoom-Out --}}
    <section
        x-data="{
            current: 0,
            total: {{ $bannerCount }},
            autoplayTimer: null,
            isPaused: false,
            touchStartX: 0,
            touchEndX: 0,
            init() {
                const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
                if (!prefersReducedMotion) {
                    this.startAutoplay();
                }
            },
            startAutoplay() {
                if (this.autoplayTimer) clearInterval(this.autoplayTimer);
                this.autoplayTimer = setInterval(() => {
                    if (!this.isPaused) {
                        this.next();
                    }
                }, 6000);
            },
            stopAutoplay() {
                if (this.autoplayTimer) clearInterval(this.autoplayTimer);
            },
            next() {
                this.current = (this.current + 1) % this.total;
            },
            prev() {
                this.current = (this.current - 1 + this.total) % this.total;
            },
            goTo(index) {
                this.current = index;
            },
            handleTouchStart(e) {
                this.touchStartX = e.changedTouches[0].screenX;
                this.isPaused = true;
            },
            handleTouchEnd(e) {
                this.touchEndX = e.changedTouches[0].screenX;
                this.isPaused = false;
                if (this.touchStartX - this.touchEndX > 50) {
                    this.next();
                } else if (this.touchEndX - this.touchStartX > 50) {
                    this.prev();
                }
            }
        }"
        @mouseenter="isPaused = true"
        @mouseleave="isPaused = false"
        @focusin="isPaused = true"
        @focusout="isPaused = false"
        @touchstart.passive="handleTouchStart($event)"
        @touchend.passive="handleTouchEnd($event)"
        @keydown.left.prevent="prev()"
        @keydown.right.prevent="next()"
        class="relative w-full overflow-hidden bg-slate-900 min-h-[520px] lg:min-h-[620px] select-none"
        role="region"
        aria-roledescription="carousel"
        aria-label="{{ __('Hero Banner') }}"
        tabindex="0"
    >
        {{-- Slide Stack: Absolutely Positioned so Only One Slide is Visible at a Time --}}
        @foreach ($bannerList as $index => $banner)
            @php
                $isExternal = !empty($banner->button_url) && (str_starts_with($banner->button_url, 'http://') || str_starts_with($banner->button_url, 'https://') || str_starts_with($banner->button_url, '//'));
                $ctaUrl = !empty($banner->button_url) ? ($isExternal ? $banner->button_url : $localizationService->localizeUrl($banner->button_url)) : null;
            @endphp
            <div
                x-show="current === {{ $index }}"
                x-transition:enter="transition-opacity duration-1200 ease-in-out"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity duration-1200 ease-in-out"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="absolute inset-0 w-full h-full flex items-center z-10"
                role="group"
                aria-roledescription="slide"
                aria-label="{{ __('Slide :current of :total', ['current' => $index + 1, 'total' => $bannerCount]) }}"
                x-cloak
            >
                {{-- Background Image with Slow Zoom-Out --}}
                @if (!empty($banner->image_path))
                    <div class="absolute inset-0 z-0 overflow-hidden">
                        <picture>
                            @if (!empty($banner->mobile_image_path))
                                <source media="(max-width: 640px)" srcset="{{ asset('storage/' . $banner->mobile_image_path) }}">
                            @endif
                            <img
                                src="{{ asset('storage/' . $banner->image_path) }}"
                                alt="{{ $banner->title }}"
                                :class="current === {{ $index }} ? 'animate-hero-zoom-out' : 'scale-110'"
                                class="w-full h-full object-cover object-center transform"
                                loading="{{ $index === 0 ? 'eager' : 'lazy' }}"
                                @if($index === 0) fetchpriority="high" @endif
                            >
                        </picture>
                        <div class="absolute inset-0 bg-gradient-to-r from-slate-950/90 via-slate-900/75 to-slate-900/40"></div>
                    </div>
                @else
                    <div class="absolute inset-0 z-0 bg-gradient-to-br from-slate-900 via-teal-950 to-slate-900">
                        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#0f766e_1px,transparent_1px)] [background-size:16px_16px]"></div>
                    </div>
                @endif

                {{-- Slide Content Container --}}
                <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 pb-24 md:py-24 w-full">
                    <div class="max-w-2xl">
                        <div class="text-teal-300 text-xs sm:text-sm font-semibold tracking-widest uppercase mb-3 sm:mb-4">
                            {{ $currentLocale === 'en' ? 'Trusted Official Distributor' : 'Distributor Resmi Terpercaya' }}
                        </div>

                        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white leading-tight mb-5 tracking-tight">
                            {{ $banner->title }}
                        </h1>

                        @if (!empty($banner->subtitle))
                            <p class="text-base sm:text-lg text-teal-100/90 leading-relaxed mb-8 max-w-xl">
                                {{ $banner->subtitle }}
                            </p>
                        @endif

                        <div class="flex flex-wrap gap-4">
                            @if (!empty($banner->buttonText) && !empty($ctaUrl))
                                <a
                                    href="{{ $ctaUrl }}"
                                    onclick="if(window.ANSAnalytics) window.ANSAnalytics.trackHeroCta({ bannerId: {{ $banner->id }}, locale: '{{ $currentLocale }}', ctaType: 'primary_cta', destinationType: '{{ $isExternal ? 'external' : (str_contains($ctaUrl, 'products') ? 'internal_catalog' : 'internal_page') }}' });"
                                    @if($isExternal) target="_blank" rel="noopener noreferrer" @endif
                                    class="inline-flex items-center justify-center gap-2 bg-teal-600 hover:bg-teal-500 text-white font-semibold px-6 py-3.5 rounded-lg shadow-lg transition-all focus-ring text-base active:scale-[0.98]"
                                >
                                    <span>{{ $banner->buttonText }}</span>
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                    </svg>
                                </a>
                            @endif
                            <a
                                href="{{ route('about') }}"
                                onclick="if(window.ANSAnalytics) window.ANSAnalytics.trackHeroCta({ bannerId: {{ $banner->id }}, locale: '{{ $currentLocale }}', ctaType: 'secondary_cta', destinationType: 'internal_page' });"
                                class="inline-flex items-center justify-center gap-2 bg-white/15 backdrop-blur-sm hover:bg-white/25 border border-white/30 text-white font-semibold px-6 py-3.5 rounded-lg transition-all focus-ring text-base"
                            >
                                <span>{{ $currentLocale === 'en' ? 'Company Profile' : 'Profil Perusahaan' }}</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        {{-- Controls: Previous and Next Buttons (Desktop: middle left/right; Mobile: bottom-right area) --}}
        <button
            type="button"
            @click="prev()"
            class="absolute bottom-5 right-17 md:bottom-auto md:right-auto md:left-4 md:top-1/2 md:-translate-y-1/2 z-30 w-11 h-11 rounded-full bg-slate-950/40 backdrop-blur-sm hover:bg-slate-950/70 border border-white/20 flex items-center justify-center text-white transition-all focus-ring active:scale-95"
            aria-label="{{ __('Previous Slide') }}"
        >
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
            </svg>
        </button>
        <button
            type="button"
            @click="next()"
            class="absolute bottom-5 right-4 md:bottom-auto md:right-4 md:top-1/2 md:-translate-y-1/2 z-30 w-11 h-11 rounded-full bg-slate-950/40 backdrop-blur-sm hover:bg-slate-950/70 border border-white/20 flex items-center justify-center text-white transition-all focus-ring active:scale-95"
            aria-label="{{ __('Next Slide') }}"
        >
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
            </svg>
        </button>

        {{-- Controls: Dot Indicators (Desktop: centered; Mobile: bottom-left) --}}
        <div class="absolute bottom-7 left-4 sm:left-6 md:bottom-6 md:left-1/2 md:-translate-x-1/2 flex items-center gap-2 z-30">
            @for ($i = 0; $i < $bannerCount; $i++)
                <button
                    type="button"
                    @click="goTo({{ $i }})"
                    :class="current === {{ $i }} ? 'w-8 h-2.5 bg-teal-400' : 'w-2.5 h-2.5 bg-white/50 hover:bg-white/80'"
                    class="rounded-full transition-all duration-300 focus-ring"
                    aria-label="{{ __('Slide :number', ['number' => $i + 1]) }}"
                    :aria-current="current === {{ $i }} ? 'true' : 'false'"
                ></button>
            @endfor
        </div>
    </section>
@endif
