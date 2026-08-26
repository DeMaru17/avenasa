@props([
    'products' => collect(),
])

@php
    $currentLocale = app()->getLocale();
    $productList = $products instanceof \Illuminate\Support\Collection ? $products : collect($products ?: []);
    $productCount = $productList->count();

    // Ensure enough base items exist before cloning so the track exceeds screen width on any device
    $minProducts = 6;
    $multiplier = $productCount > 0 ? max(1, (int) ceil($minProducts / $productCount)) : 1;
    $baseProducts = collect();
    for ($i = 0; $i < $multiplier; $i++) {
        $baseProducts = $baseProducts->concat($productList);
    }
@endphp

@if ($productList->isNotEmpty())
    <section class="py-16 lg:py-24 bg-slate-50 border-b border-slate-200 overflow-hidden" aria-labelledby="product-highlights-heading">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
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
                class="relative carousel-container cursor-grab active:cursor-grabbing select-none"
                :class="{ 'cursor-grabbing': isDragging }"
                role="region"
                aria-roledescription="carousel"
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
                                ? 'High-precision instruments, reagents, and diagnostic solutions from leading global manufacturers.'
                                : 'Instrumen presisi tinggi, reagen, dan solusi diagnostik dari manufaktur terkemuka dunia.' }}
                        </p>
                    </div>

                    <div class="self-start md:self-auto">
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

                {{-- Interactive Infinite Carousel Track --}}
                <div class="overflow-hidden -mx-3 px-3 py-1">
                    <div
                        x-ref="track"
                        class="flex flex-nowrap items-stretch will-change-transform"
                    >
                        {{-- Render Set A and Set B for 100% Seamless Infinite Looping --}}
                        @foreach ([$baseProducts, $baseProducts] as $setIndex => $productSet)
                            @foreach ($productSet as $product)
                                <div
                                    class="w-[280px] sm:w-[320px] lg:w-[340px] flex-shrink-0 px-3 flex flex-col"
                                    @if($setIndex === 1) aria-hidden="true" @endif
                                >
                                    <article class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm hover:shadow-md hover:border-teal-400 transition-all flex flex-col h-full group">
                                        {{-- Product Image / Fallback Container --}}
                                        <div class="aspect-square bg-slate-100 overflow-hidden relative flex items-center justify-center">
                                            @if (!empty($product->primary_image_path))
                                                <img
                                                    src="{{ asset('storage/' . $product->primary_image_path) }}"
                                                    alt="{{ $product->name }}"
                                                    class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-300"
                                                    loading="lazy"
                                                    draggable="false"
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
                                                <a
                                                    href="{{ route('products.show', ['slug' => $product->slug]) }}"
                                                    class="focus-ring rounded"
                                                    @if($setIndex === 1) tabindex="-1" @endif
                                                >
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
                                                    @if($setIndex === 1) tabindex="-1" @endif
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
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
