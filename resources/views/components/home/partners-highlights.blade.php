@props([
    'brands' => collect(),
    'clients' => collect(),
])

@php
    $currentLocale = app()->getLocale();
    $brandList = $brands instanceof \Illuminate\Support\Collection ? $brands : collect($brands ?: []);
    $clientList = $clients instanceof \Illuminate\Support\Collection ? $clients : collect($clients ?: []);
    $brandCount = $brandList->count();
    $clientCount = $clientList->count();

    // Ensure enough base items exist before cloning so the track exceeds screen width on any device
    $minBrands = 8;
    $brandMultiplier = $brandCount > 0 ? max(1, (int) ceil($minBrands / $brandCount)) : 1;
    $baseBrands = collect();
    for ($i = 0; $i < $brandMultiplier; $i++) {
        $baseBrands = $baseBrands->concat($brandList);
    }

    $minClients = 8;
    $clientMultiplier = $clientCount > 0 ? max(1, (int) ceil($minClients / $clientCount)) : 1;
    $baseClients = collect();
    for ($i = 0; $i < $clientMultiplier; $i++) {
        $baseClients = $baseClients->concat($clientList);
    }
@endphp

@if ($brandList->isNotEmpty() || $clientList->isNotEmpty())
    <section class="py-16 lg:py-24 bg-white border-b border-slate-100 overflow-hidden" aria-labelledby="partners-highlights-heading">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Section Header --}}
            <div class="text-center max-w-3xl mx-auto mb-14">
                <div class="text-teal-700 text-xs sm:text-sm font-bold tracking-widest uppercase mb-3">
                    {{ $currentLocale === 'en' ? 'Business Partners & Clients' : 'Mitra Bisnis & Klien' }}
                </div>
                <h2 id="partners-highlights-heading" class="text-2xl sm:text-3xl lg:text-4xl font-bold text-slate-900 tracking-tight">
                    {{ $currentLocale === 'en' ? 'Global Business Partner Network' : 'Jaringan Mitra Bisnis Global' }}
                </h2>
                <p class="text-slate-600 text-sm sm:text-base mt-3 leading-relaxed">
                    {{ $currentLocale === 'en'
                        ? 'Leading distributor representing world-class manufacturers in life sciences, water analysis, food safety, and diagnostics.'
                        : 'Distributor terkemuka yang mewakili mitra manufaktur dunia di bidang ilmu hayati, uji kualitas air, keamanan pangan, dan diagnostik.' }}
                </p>
            </div>

            {{-- 1. Brands / Principals Interactive Auto-Slide Carousel --}}
            @if ($brandList->isNotEmpty())
                <div
                    x-data="smoothCarousel({ speed: 0.55 })"
                    @mouseenter="handleMouseEnter()"
                    @mouseleave="handleMouseLeave()"
                    @focusin="handleFocusIn()"
                    @focusout="handleFocusOut()"
                    @resize.window.debounce.150ms="recalculate()"
                    @pointerdown="handlePointerDown($event)"
                    @pointermove="handlePointerMove($event)"
                    @pointerup="handlePointerUp($event)"
                    @pointercancel="handlePointerCancel($event)"
                    @click.capture="handleClickCapture($event)"
                    class="mb-14 relative carousel-container cursor-grab active:cursor-grabbing select-none"
                    :class="{ 'cursor-grabbing': isDragging }"
                    role="region"
                    aria-roledescription="carousel"
                    aria-label="{{ __('Principals Carousel') }}"
                >
                    <div class="mb-6">
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest">
                            {{ $currentLocale === 'en' ? 'Global Business Partners' : 'Mitra Bisnis Global' }}
                        </h3>
                    </div>

                    {{-- Principals Interactive Track --}}
                    <div class="overflow-hidden -mx-3 px-3 py-1">
                        <div
                            x-ref="track"
                            class="flex flex-nowrap items-center will-change-transform"
                        >
                            {{-- Render Set A and Set B for 100% Seamless Infinite Looping --}}
                            @foreach ([$baseBrands, $baseBrands] as $setIndex => $brandSet)
                                @foreach ($brandSet as $brand)
                                    <div
                                        class="w-[180px] sm:w-[220px] lg:w-[250px] flex-shrink-0 px-3"
                                        @if($setIndex === 1) aria-hidden="true" @endif
                                    >
                                        <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-5 flex flex-col items-center justify-center text-center h-[130px] hover:border-teal-400 hover:bg-white hover:shadow-sm transition-all group">
                                            @if (!empty($brand->logo_path))
                                                <div class="h-10 flex items-center justify-center mb-2">
                                                    <img
                                                        src="{{ asset('storage/' . $brand->logo_path) }}"
                                                        alt="{{ $brand->name }}"
                                                        class="max-h-9 max-w-[130px] w-auto object-contain transition-transform group-hover:scale-105"
                                                        loading="lazy"
                                                        draggable="false"
                                                    >
                                                </div>
                                            @else
                                                <div class="h-10 flex items-center justify-center mb-2">
                                                    <svg class="w-7 h-7 text-teal-600/70" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418" />
                                                    </svg>
                                                </div>
                                            @endif
                                            {{-- Brand Name (Always Rendered) --}}
                                            <span class="text-xs sm:text-sm font-bold text-slate-800 group-hover:text-teal-700 transition-colors line-clamp-1">
                                                {{ $brand->name }}
                                            </span>
                                            @if ($brand->is_new_principal)
                                                <span class="mt-1 text-[9px] font-semibold text-teal-700 bg-teal-100 px-1.5 py-0.5 rounded">
                                                    {{ $currentLocale === 'en' ? 'New Principal' : 'Prinsipal Baru' }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            {{-- 2. Clients Interactive Auto-Slide Carousel --}}
            @if ($clientList->isNotEmpty())
                <div
                    x-data="smoothCarousel({ speed: 0.55 })"
                    @mouseenter="handleMouseEnter()"
                    @mouseleave="handleMouseLeave()"
                    @focusin="handleFocusIn()"
                    @focusout="handleFocusOut()"
                    @resize.window.debounce.150ms="recalculate()"
                    @pointerdown="handlePointerDown($event)"
                    @pointermove="handlePointerMove($event)"
                    @pointerup="handlePointerUp($event)"
                    @pointercancel="handlePointerCancel($event)"
                    @click.capture="handleClickCapture($event)"
                    class="mb-10 relative carousel-container cursor-grab active:cursor-grabbing select-none"
                    :class="{ 'cursor-grabbing': isDragging }"
                    role="region"
                    aria-roledescription="carousel"
                    aria-label="{{ __('Clients Carousel') }}"
                >
                    <div class="mb-6">
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest">
                            {{ $currentLocale === 'en' ? 'Institutional & Industry Clients' : 'Klien Institusi & Industri' }}
                        </h3>
                    </div>

                    {{-- Clients Interactive Track --}}
                    <div class="overflow-hidden -mx-3 px-3 py-1">
                        <div
                            x-ref="track"
                            class="flex flex-nowrap items-center will-change-transform"
                        >
                            {{-- Render Set A and Set B for 100% Seamless Infinite Looping --}}
                            @foreach ([$baseClients, $baseClients] as $setIndex => $clientSet)
                                @foreach ($clientSet as $client)
                                    <div
                                        class="w-[170px] sm:w-[200px] lg:w-[230px] flex-shrink-0 px-3"
                                        @if($setIndex === 1) aria-hidden="true" @endif
                                    >
                                        <div class="bg-white border border-slate-200/80 rounded-xl p-4 flex flex-col items-center justify-center text-center h-[120px] hover:border-teal-400 hover:shadow-sm transition-all group">
                                            @if (!empty($client->logo_path))
                                                <div class="h-9 flex items-center justify-center mb-2">
                                                    <img
                                                        src="{{ asset('storage/' . $client->logo_path) }}"
                                                        alt="{{ $client->name }}"
                                                        class="max-h-8 max-w-[120px] w-auto object-contain"
                                                        loading="lazy"
                                                        draggable="false"
                                                    />
                                                </div>
                                            @else
                                                <div class="h-9 flex items-center justify-center mb-2">
                                                    <svg class="w-6 h-6 text-slate-400 group-hover:text-teal-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z" />
                                                    </svg>
                                                </div>
                                            @endif
                                            {{-- Client Name (Always Rendered) --}}
                                            <span class="text-xs sm:text-sm font-semibold text-slate-700 group-hover:text-teal-700 transition-colors line-clamp-1">
                                                {{ $client->name }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            {{-- CTA Button --}}
            <div class="text-center mt-8">
                <a
                    href="{{ route('partners-clients') }}"
                    class="inline-flex items-center gap-2 text-teal-700 hover:text-teal-900 font-semibold text-sm hover:bg-teal-50 px-5 py-2.5 rounded-lg transition-colors focus-ring"
                >
                    <span>{{ $currentLocale === 'en' ? 'View All Partners & Clients' : 'Lihat Semua Mitra & Klien' }}</span>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                    </svg>
                </a>
            </div>
        </div>
    </section>
@endif
