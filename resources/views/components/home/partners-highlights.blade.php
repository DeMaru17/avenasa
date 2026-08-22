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
@endphp

@if ($brandList->isNotEmpty() || $clientList->isNotEmpty())
    <section class="py-16 lg:py-24 bg-white border-b border-slate-100 overflow-hidden" aria-labelledby="partners-highlights-heading">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Section Header --}}
            <div class="text-center max-w-3xl mx-auto mb-14">
                <div class="text-teal-700 text-xs sm:text-sm font-bold tracking-widest uppercase mb-3">
                    {{ $currentLocale === 'en' ? 'Official Principals & Clients' : 'Prinsipal Resmi & Klien' }}
                </div>
                <h2 id="partners-highlights-heading" class="text-2xl sm:text-3xl lg:text-4xl font-bold text-slate-900 tracking-tight">
                    {{ $currentLocale === 'en' ? 'Trusted Global Principal Network' : 'Jaringan Prinsipal Global Terpercaya' }}
                </h2>
                <p class="text-slate-600 text-sm sm:text-base mt-3 leading-relaxed">
                    {{ $currentLocale === 'en'
                        ? 'Official authorized distributor representing world-class manufacturers in life sciences, water analysis, food safety, and diagnostics.'
                        : 'Distributor resmi terdaftar yang mewakili prinsipal terkemuka dunia di bidang ilmu hayati, uji kualitas air, keamanan pangan, dan diagnostik.' }}
                </p>
            </div>

            {{-- 1. Brands / Principals Paginated Carousel (4 per page on desktop) --}}
            @if ($brandList->isNotEmpty())
                <div
                    x-data="{
                        currentPage: 0,
                        perPage: 4,
                        total: {{ $brandCount }},
                        touchStartX: 0,
                        touchEndX: 0,
                        init() {
                            this.updatePerPage();
                        },
                        updatePerPage() {
                            if (window.innerWidth >= 1024) {
                                this.perPage = 4;
                            } else if (window.innerWidth >= 640) {
                                this.perPage = 2;
                            } else {
                                this.perPage = 2;
                            }
                            if (this.currentPage >= this.totalPages()) {
                                this.currentPage = Math.max(0, this.totalPages() - 1);
                            }
                        },
                        totalPages() {
                            return Math.ceil(this.total / this.perPage) || 1;
                        },
                        next() {
                            this.currentPage = (this.currentPage + 1) % this.totalPages();
                        },
                        prev() {
                            this.currentPage = (this.currentPage - 1 + this.totalPages()) % this.totalPages();
                        },
                        goTo(page) {
                            this.currentPage = page;
                        },
                        handleTouchStart(e) {
                            this.touchStartX = e.changedTouches[0].screenX;
                        },
                        handleTouchEnd(e) {
                            this.touchEndX = e.changedTouches[0].screenX;
                            if (this.touchStartX - this.touchEndX > 50) {
                                this.next();
                            } else if (this.touchEndX - this.touchStartX > 50) {
                                this.prev();
                            }
                        }
                    }"
                    @resize.window.debounce.150ms="updatePerPage()"
                    @touchstart.passive="handleTouchStart($event)"
                    @touchend.passive="handleTouchEnd($event)"
                    class="mb-14 relative select-none"
                    role="region"
                    aria-label="{{ __('Principals Carousel') }}"
                >
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest">
                                {{ $currentLocale === 'en' ? 'Global Manufacturer Principals' : 'Prinsipal Manufaktur Global' }}
                            </h3>
                        </div>
                        <div class="flex items-center gap-2">
                            <button
                                type="button"
                                @click="prev()"
                                class="w-9 h-9 rounded-lg border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 hover:text-teal-700 shadow-sm flex items-center justify-center transition-all focus-ring active:scale-95"
                                aria-label="{{ __('Previous Principals Page') }}"
                            >
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                                </svg>
                            </button>
                            <button
                                type="button"
                                @click="next()"
                                class="w-9 h-9 rounded-lg border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 hover:text-teal-700 shadow-sm flex items-center justify-center transition-all focus-ring active:scale-95"
                                aria-label="{{ __('Next Principals Page') }}"
                            >
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Principals Slider Track (Grouped by Page Shift) --}}
                    <div class="overflow-hidden -mx-3 px-3 py-1">
                        <div
                            class="flex transition-transform duration-500 ease-out"
                            :style="'transform: translateX(-' + (currentPage * 100) + '%)'"
                        >
                            @foreach ($brandList as $brand)
                                <div class="w-1/2 sm:w-1/2 lg:w-1/4 flex-shrink-0 px-3">
                                    <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-5 flex flex-col items-center justify-center text-center h-[130px] hover:border-teal-400 hover:bg-white hover:shadow-sm transition-all group">
                                        @if (!empty($brand->logo_path))
                                            <div class="h-10 flex items-center justify-center mb-2">
                                                <img
                                                    src="{{ asset('storage/' . $brand->logo_path) }}"
                                                    alt="{{ $brand->name }}"
                                                    class="max-h-9 max-w-[130px] w-auto object-contain transition-transform group-hover:scale-105"
                                                    loading="lazy"
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
                        </div>
                    </div>

                    {{-- Pagination Indicator (1 / 3 and Dots) --}}
                    <div class="mt-6 flex items-center justify-center gap-3">
                        <div class="text-xs font-semibold text-slate-500 tracking-wider">
                            <span x-text="(currentPage + 1) + ' / ' + totalPages()"></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <template x-for="p in totalPages()" :key="p">
                                <button
                                    type="button"
                                    @click="goTo(p - 1)"
                                    :class="currentPage === (p - 1) ? 'w-6 h-2 bg-teal-600' : 'w-2 h-2 bg-slate-300 hover:bg-slate-400'"
                                    class="rounded-full transition-all duration-300 focus-ring"
                                    :aria-label="'Go to slide ' + p"
                                    :aria-current="currentPage === (p - 1) ? 'true' : 'false'"
                                ></button>
                            </template>
                        </div>
                    </div>
                </div>
            @endif

            {{-- 2. Clients Paginated Carousel (4 per page on desktop) --}}
            @if ($clientList->isNotEmpty())
                <div
                    x-data="{
                        currentPage: 0,
                        perPage: 4,
                        total: {{ $clientCount }},
                        touchStartX: 0,
                        touchEndX: 0,
                        init() {
                            this.updatePerPage();
                        },
                        updatePerPage() {
                            if (window.innerWidth >= 1024) {
                                this.perPage = 4;
                            } else if (window.innerWidth >= 640) {
                                this.perPage = 2;
                            } else {
                                this.perPage = 2;
                            }
                            if (this.currentPage >= this.totalPages()) {
                                this.currentPage = Math.max(0, this.totalPages() - 1);
                            }
                        },
                        totalPages() {
                            return Math.ceil(this.total / this.perPage) || 1;
                        },
                        next() {
                            this.currentPage = (this.currentPage + 1) % this.totalPages();
                        },
                        prev() {
                            this.currentPage = (this.currentPage - 1 + this.totalPages()) % this.totalPages();
                        },
                        goTo(page) {
                            this.currentPage = page;
                        },
                        handleTouchStart(e) {
                            this.touchStartX = e.changedTouches[0].screenX;
                        },
                        handleTouchEnd(e) {
                            this.touchEndX = e.changedTouches[0].screenX;
                            if (this.touchStartX - this.touchEndX > 50) {
                                this.next();
                            } else if (this.touchEndX - this.touchStartX > 50) {
                                this.prev();
                            }
                        }
                    }"
                    @resize.window.debounce.150ms="updatePerPage()"
                    @touchstart.passive="handleTouchStart($event)"
                    @touchend.passive="handleTouchEnd($event)"
                    class="mb-10 relative select-none"
                    role="region"
                    aria-label="{{ __('Clients Carousel') }}"
                >
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest">
                                {{ $currentLocale === 'en' ? 'Institutional & Industry Clients' : 'Klien Institusi & Industri' }}
                            </h3>
                        </div>
                        <div class="flex items-center gap-2">
                            <button
                                type="button"
                                @click="prev()"
                                class="w-9 h-9 rounded-lg border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 hover:text-teal-700 shadow-sm flex items-center justify-center transition-all focus-ring active:scale-95"
                                aria-label="{{ __('Previous Clients Page') }}"
                            >
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                                </svg>
                            </button>
                            <button
                                type="button"
                                @click="next()"
                                class="w-9 h-9 rounded-lg border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 hover:text-teal-700 shadow-sm flex items-center justify-center transition-all focus-ring active:scale-95"
                                aria-label="{{ __('Next Clients Page') }}"
                            >
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Clients Slider Track (Grouped by Page Shift) --}}
                    <div class="overflow-hidden -mx-3 px-3 py-1">
                        <div
                            class="flex transition-transform duration-500 ease-out"
                            :style="'transform: translateX(-' + (currentPage * 100) + '%)'"
                        >
                            @foreach ($clientList as $client)
                                <div class="w-1/2 sm:w-1/2 lg:w-1/4 flex-shrink-0 px-3">
                                    <div class="bg-white border border-slate-200/80 rounded-xl p-4 flex flex-col items-center justify-center text-center h-[120px] hover:border-teal-400 hover:shadow-sm transition-all group">
                                        @if (!empty($client->logo_path))
                                            <div class="h-9 flex items-center justify-center mb-2">
                                                <img
                                                    src="{{ asset('storage/' . $client->logo_path) }}"
                                                    alt="{{ $client->name }}"
                                                    class="max-h-8 max-w-[120px] w-auto object-contain"
                                                    loading="lazy"
                                                >
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
                        </div>
                    </div>

                    {{-- Pagination Indicator (1 / 3 and Dots) --}}
                    <div class="mt-6 flex items-center justify-center gap-3">
                        <div class="text-xs font-semibold text-slate-500 tracking-wider">
                            <span x-text="(currentPage + 1) + ' / ' + totalPages()"></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <template x-for="p in totalPages()" :key="p">
                                <button
                                    type="button"
                                    @click="goTo(p - 1)"
                                    :class="currentPage === (p - 1) ? 'w-6 h-2 bg-teal-600' : 'w-2 h-2 bg-slate-300 hover:bg-slate-400'"
                                    class="rounded-full transition-all duration-300 focus-ring"
                                    :aria-label="'Go to slide ' + p"
                                    :aria-current="currentPage === (p - 1) ? 'true' : 'false'"
                                ></button>
                            </template>
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
