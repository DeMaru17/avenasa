@props([
    'categories' => collect(),
    'brands' => collect(),
    'selectedCategory' => null,
    'selectedBrand' => null,
    'selectedCategorySlug' => null,
    'selectedBrandSlug' => null,
    'activeFilterCount' => 0,
])

@php
    $currentLocale = app()->getLocale();
    $brandData = $brands->map(fn ($b) => ['id' => $b->id, 'name' => $b->name, 'slug' => $b->slug]);
@endphp

<aside class="bg-white rounded-2xl border border-slate-200/90 p-5 sm:p-6 shadow-sm sticky top-24" aria-label="{{ __('Filter Sidebar') }}">
    <div class="flex items-center justify-between pb-4 mb-5 border-b border-slate-100">
        <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
            <svg class="w-4 h-4 text-teal-700" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75" />
            </svg>
            <span>{{ $currentLocale === 'en' ? 'Filter Products' : 'Filter Produk' }}</span>
        </h2>

        @if ($activeFilterCount > 0)
            <a
                href="{{ route('products.index') }}"
                class="text-xs font-semibold text-rose-600 hover:text-rose-700 transition-colors focus-ring rounded"
            >
                {{ $currentLocale === 'en' ? 'Reset All' : 'Reset Semua' }}
            </a>
        @endif
    </div>

    <div class="space-y-6">
        {{-- Category Section (Standard List) --}}
        <div>
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">
                {{ $currentLocale === 'en' ? 'Product Category' : 'Kategori Produk' }}
            </h3>

            <ul class="space-y-1" role="list">
                {{-- All Categories Option --}}
                <li>
                    @php
                        $allCatUrl = route('products.index', array_filter(['brand' => $selectedBrandSlug]));
                        $isAllCatActive = empty($selectedCategorySlug);
                    @endphp
                    <a
                        href="{{ $allCatUrl }}"
                        class="flex items-center justify-between px-3 py-2 rounded-lg text-sm transition-all focus-ring {{ $isAllCatActive ? 'bg-teal-50 text-teal-800 font-semibold border border-teal-200/70' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 font-medium' }}"
                        @if ($isAllCatActive) aria-current="true" @endif
                    >
                        <span>{{ $currentLocale === 'en' ? 'All Categories' : 'Semua Kategori' }}</span>
                        @if ($isAllCatActive)
                            <svg class="w-4 h-4 text-teal-700 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                            </svg>
                        @endif
                    </a>
                </li>

                {{-- Individual Categories --}}
                @foreach ($categories as $category)
                    @php
                        $catSlug = $category->slug;
                        $catUrl = route('products.index', array_filter(['category' => $catSlug, 'brand' => $selectedBrandSlug]));
                        $isCatActive = $selectedCategory && $selectedCategory->id === $category->id;
                    @endphp
                    <li>
                        <a
                            href="{{ $catUrl }}"
                            class="flex items-center justify-between px-3 py-2 rounded-lg text-sm transition-all focus-ring {{ $isCatActive ? 'bg-teal-50 text-teal-800 font-semibold border border-teal-200/70' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 font-medium' }}"
                            @if ($isCatActive) aria-current="true" @endif
                        >
                            <span class="line-clamp-1">{{ $category->name }}</span>
                            @if ($isCatActive)
                                <svg class="w-4 h-4 text-teal-700 flex-shrink-0 ml-1.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                            @endif
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- Brand Section (Scalable: Search + Bounded Scrollable List) --}}
        <div
            class="pt-5 border-t border-slate-100"
            x-data="{
                brandSearch: '',
                brandList: {{ Js::from($brandData) }},
                get hasBrandResults() {
                    let q = this.brandSearch.toLowerCase().trim();
                    if (!q) return true;
                    return this.brandList.some(b => b.name.toLowerCase().includes(q));
                },
                matches(name) {
                    let q = this.brandSearch.toLowerCase().trim();
                    if (!q) return true;
                    return name.toLowerCase().includes(q);
                }
            }"
        >
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">
                    {{ $currentLocale === 'en' ? 'Brand / Principal' : 'Brand / Prinsipal' }}
                </h3>
            </div>

            {{-- Real-time Brand Search Input --}}
            <div class="relative mb-3">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                </div>
                <input
                    type="text"
                    x-model="brandSearch"
                    placeholder="{{ $currentLocale === 'en' ? 'Search brands...' : 'Cari brand...' }}"
                    aria-label="{{ $currentLocale === 'en' ? 'Search brands' : 'Cari brand' }}"
                    class="w-full pl-8 pr-8 py-1.5 text-xs bg-slate-50 border border-slate-200 rounded-lg text-slate-800 placeholder-slate-400 focus:bg-white focus:border-teal-500 focus:ring-1 focus:ring-teal-500 transition-colors"
                >
                {{-- Clear Search Action --}}
                <button
                    type="button"
                    x-show="brandSearch.length > 0"
                    x-cloak
                    @click="brandSearch = ''"
                    class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-slate-400 hover:text-slate-600 focus-ring rounded"
                    aria-label="{{ $currentLocale === 'en' ? 'Clear search' : 'Hapus pencarian' }}"
                >
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Bounded Scrollable Brand List Container --}}
            <div class="max-h-60 overflow-y-auto overscroll-contain pr-1 space-y-1">
                {{-- All Brands Option (visible when search is empty or matches query) --}}
                <div x-show="!brandSearch.trim()">
                    @php
                        $allBrandUrl = route('products.index', array_filter(['category' => $selectedCategorySlug]));
                        $isAllBrandActive = empty($selectedBrandSlug);
                    @endphp
                    <a
                        href="{{ $allBrandUrl }}"
                        class="flex items-center justify-between px-3 py-2 rounded-lg text-sm transition-all focus-ring {{ $isAllBrandActive ? 'bg-teal-50 text-teal-800 font-semibold border border-teal-200/70' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 font-medium' }}"
                        @if ($isAllBrandActive) aria-current="true" @endif
                    >
                        <span>{{ $currentLocale === 'en' ? 'All Brands' : 'Semua Brand' }}</span>
                        @if ($isAllBrandActive)
                            <svg class="w-4 h-4 text-teal-700 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                            </svg>
                        @endif
                    </a>
                </div>

                {{-- Individual Brands --}}
                <ul class="space-y-1" role="list">
                    @foreach ($brands as $brand)
                        @php
                            $brandUrl = route('products.index', array_filter(['category' => $selectedCategorySlug, 'brand' => $brand->slug]));
                            $isBrandActive = $selectedBrand && $selectedBrand->id === $brand->id;
                        @endphp
                        <li x-show="matches('{{ addslashes($brand->name) }}')">
                            <a
                                href="{{ $brandUrl }}"
                                class="flex items-center justify-between px-3 py-2 rounded-lg text-sm transition-all focus-ring {{ $isBrandActive ? 'bg-teal-50 text-teal-800 font-semibold border border-teal-200/70' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 font-medium' }}"
                                @if ($isBrandActive) aria-current="true" @endif
                            >
                                <span class="line-clamp-1">{{ $brand->name }}</span>
                                @if ($isBrandActive)
                                    <svg class="w-4 h-4 text-teal-700 flex-shrink-0 ml-1.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                    </svg>
                                @endif
                            </a>
                        </li>
                    @endforeach
                </ul>

                {{-- Empty Search State --}}
                <div
                    x-show="!hasBrandResults"
                    x-cloak
                    class="py-4 px-2 text-center text-xs text-slate-400 italic"
                >
                    {{ $currentLocale === 'en' ? 'No brands found.' : 'Brand tidak ditemukan.' }}
                </div>
            </div>
        </div>
    </div>
</aside>
