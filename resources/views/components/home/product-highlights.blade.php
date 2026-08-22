@props([
    'products' => collect(),
])

@php
    $currentLocale = app()->getLocale();
    $productList = $products instanceof \Illuminate\Support\Collection ? $products : collect($products ?: []);
    $productCount = $productList->count();
@endphp

@if ($productList->isNotEmpty())
    <section class="py-16 lg:py-24 bg-slate-50 border-b border-slate-200 overflow-hidden" aria-labelledby="product-highlights-heading">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div
                x-data="{
                    currentPage: 0,
                    perPage: 4,
                    total: {{ $productCount }},
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
                            this.perPage = 1;
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
                class="relative select-none"
                role="region"
                aria-label="{{ __('Product Carousel') }}"
            >
                {{-- Section Header with Controls --}}
                <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-6">
                    <div>
                        <div class="text-teal-700 text-xs sm:text-sm font-bold tracking-widest uppercase mb-3">
                            {{ $currentLocale === 'en' ? 'Product Portfolio' : 'Portofolio Produk' }}
                        </div>
                        <h2 id="product-highlights-heading" class="text-2xl sm:text-3xl lg:text-4xl font-bold text-slate-900 tracking-tight">
                            {{ $currentLocale === 'en' ? 'Featured Scientific Products' : 'Produk Pilihan Laboratorium' }}
                        </h2>
                        <p class="text-slate-600 text-sm sm:text-base mt-2 max-w-2xl leading-relaxed">
                            {{ $currentLocale === 'en'
                                ? 'High-precision instruments, reagents, and diagnostic solutions from global official principals.'
                                : 'Instrumen presisi tinggi, reagen, dan solusi diagnostik dari prinsipal resmi global.' }}
                        </p>
                    </div>

                    <div class="flex items-center gap-4 self-start md:self-auto">
                        {{-- Previous / Next Page Controls --}}
                        <div class="flex items-center gap-2">
                            <button
                                type="button"
                                @click="prev()"
                                class="w-10 h-10 rounded-lg border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 hover:text-teal-700 shadow-sm flex items-center justify-center transition-all focus-ring active:scale-95"
                                aria-label="{{ __('Previous Page') }}"
                            >
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                                </svg>
                            </button>
                            <button
                                type="button"
                                @click="next()"
                                class="w-10 h-10 rounded-lg border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 hover:text-teal-700 shadow-sm flex items-center justify-center transition-all focus-ring active:scale-95"
                                aria-label="{{ __('Next Page') }}"
                            >
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                </svg>
                            </button>
                        </div>

                        {{-- Link to Full Catalog --}}
                        <a
                            href="{{ route('products.index') }}"
                            class="inline-flex items-center gap-2 border border-teal-700 text-teal-700 hover:bg-teal-700 hover:text-white font-semibold px-4 py-2.5 rounded-lg transition-all focus-ring text-sm whitespace-nowrap active:scale-[0.98]"
                        >
                            <span>{{ $currentLocale === 'en' ? 'View Catalog' : 'Lihat Katalog' }}</span>
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </a>
                    </div>
                </div>

                {{-- Paginated Track Container --}}
                <div class="overflow-hidden -mx-3 px-3 py-1">
                    <div
                        class="flex transition-transform duration-500 ease-out"
                        :style="'transform: translateX(-' + (currentPage * 100) + '%)'"
                    >
                        @foreach ($productList as $product)
                            <div class="w-full sm:w-1/2 lg:w-1/4 flex-shrink-0 px-3">
                                <article class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm hover:shadow-md hover:border-teal-400 transition-all flex flex-col h-full group">
                                    {{-- Product Image / Fallback Container --}}
                                    <div class="aspect-square bg-slate-100 overflow-hidden relative flex items-center justify-center">
                                        @if (!empty($product->primary_image_path))
                                            <img
                                                src="{{ asset('storage/' . $product->primary_image_path) }}"
                                                alt="{{ $product->name }}"
                                                class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-300"
                                                loading="lazy"
                                            >
                                        @else
                                            {{-- Graceful Professional Fallback Placeholder --}}
                                            <div class="flex flex-col items-center justify-center p-6 text-center text-slate-400">
                                                <svg class="w-12 h-12 text-slate-300 mb-2 group-hover:text-teal-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke-width="1.2" stroke="currentColor" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0112 15a9.065 9.065 0 00-6.23-.693L5 14.5m14.8.8l1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0112 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.61L5 14.5" />
                                                </svg>
                                                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">PT ANS</span>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Card Body --}}
                                    <div class="p-5 flex flex-col flex-1">
                                        {{-- Badges --}}
                                        <div class="flex flex-wrap items-center gap-1.5 mb-3">
                                            @if ($product->category)
                                                <span class="text-[11px] font-semibold bg-teal-50 text-teal-800 border border-teal-200 px-2 py-0.5 rounded-md">
                                                    {{ $product->category->name }}
                                                </span>
                                            @endif
                                            @if ($product->brand)
                                                <span class="text-[11px] font-medium bg-slate-100 text-slate-700 border border-slate-200 px-2 py-0.5 rounded-md">
                                                    {{ $product->brand->name }}
                                                </span>
                                            @endif
                                        </div>

                                        {{-- Product Name --}}
                                        <h3 class="text-base font-bold text-slate-900 line-clamp-2 mb-2 group-hover:text-teal-700 transition-colors">
                                            <a href="{{ route('products.show', ['slug' => $product->slug]) }}" class="focus-ring rounded">
                                                {{ $product->name }}
                                            </a>
                                        </h3>

                                        {{-- Summary --}}
                                        @if (!empty($product->summary))
                                            <p class="text-xs text-slate-500 line-clamp-2 flex-1 leading-relaxed mb-4">
                                                {{ $product->summary }}
                                            </p>
                                        @else
                                            <div class="flex-1"></div>
                                        @endif

                                        {{-- Card Footer CTA --}}
                                        <div class="pt-3 border-t border-slate-100 flex items-center justify-between mt-auto">
                                            <a
                                                href="{{ route('products.show', ['slug' => $product->slug]) }}"
                                                class="text-xs font-semibold text-teal-700 group-hover:text-teal-900 transition-colors flex items-center gap-1 focus-ring rounded"
                                            >
                                                <span>{{ $currentLocale === 'en' ? 'View Details' : 'Lihat Detail' }}</span>
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </article>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Pagination Indicator (1 / 3 and Dots) --}}
                <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3">
                    <div class="text-xs font-semibold text-slate-500 tracking-wider">
                        <span x-text="(currentPage + 1) + ' / ' + totalPages()"></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <template x-for="p in totalPages()" :key="p">
                            <button
                                type="button"
                                @click="goTo(p - 1)"
                                :class="currentPage === (p - 1) ? 'w-7 h-2.5 bg-teal-600' : 'w-2.5 h-2.5 bg-slate-300 hover:bg-slate-400'"
                                class="rounded-full transition-all duration-300 focus-ring"
                                :aria-label="'Go to slide ' + p"
                                :aria-current="currentPage === (p - 1) ? 'true' : 'false'"
                            ></button>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
