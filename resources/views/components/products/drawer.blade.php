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

<div
    x-show="open"
    x-cloak
    class="relative z-50 lg:hidden"
    aria-labelledby="drawer-title"
    role="dialog"
    aria-modal="true"
    @keydown.escape.window="open = false"
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
    {{-- Backdrop --}}
    <div
        x-show="open"
        x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs"
        @click="open = false"
        aria-hidden="true"
    ></div>

    {{-- Slide-over Panel --}}
    <div class="fixed inset-0 overflow-hidden flex justify-end">
        <div
            x-show="open"
            x-transition:enter="transform transition ease-in-out duration-300"
            x-transition:enter-start="translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transform transition ease-in-out duration-300"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="translate-x-full"
            class="w-full max-w-md bg-white shadow-2xl flex flex-col h-full overflow-hidden"
        >
            {{-- Drawer Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 bg-slate-50/70">
                <h2 id="drawer-title" class="text-lg font-bold text-slate-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-teal-700" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75" />
                    </svg>
                    <span>{{ $currentLocale === 'en' ? 'Filter Products' : 'Filter Produk' }}</span>
                </h2>

                <button
                    type="button"
                    @click="open = false"
                    class="p-2 -mr-2 text-slate-400 hover:text-slate-600 rounded-lg focus-ring"
                    aria-label="{{ $currentLocale === 'en' ? 'Close Filter Drawer' : 'Tutup Filter' }}"
                >
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Form for Filter Selection --}}
            <form method="GET" action="{{ route('products.index') }}" class="flex flex-col flex-1 overflow-hidden">
                <div class="flex-1 overflow-y-auto p-6 space-y-6">
                    {{-- Category Radio Selection --}}
                    <div>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">
                            {{ $currentLocale === 'en' ? 'Product Category' : 'Kategori Produk' }}
                        </h3>

                        <div class="space-y-2">
                            <label class="flex items-center gap-3 p-3 rounded-xl border transition-all cursor-pointer {{ empty($selectedCategorySlug) ? 'border-teal-400 bg-teal-50/50 text-teal-900 font-semibold' : 'border-slate-200 text-slate-700 hover:bg-slate-50' }}">
                                <input
                                    type="radio"
                                    name="category"
                                    value=""
                                    class="w-4 h-4 text-teal-700 focus:ring-teal-500"
                                    {{ empty($selectedCategorySlug) ? 'checked' : '' }}
                                >
                                <span class="text-sm">{{ $currentLocale === 'en' ? 'All Categories' : 'Semua Kategori' }}</span>
                            </label>

                            @foreach ($categories as $category)
                                @php
                                    $catSlug = $category->slug;
                                    $isSelected = $selectedCategory && $selectedCategory->id === $category->id;
                                @endphp
                                <label class="flex items-center gap-3 p-3 rounded-xl border transition-all cursor-pointer {{ $isSelected ? 'border-teal-400 bg-teal-50/50 text-teal-900 font-semibold' : 'border-slate-200 text-slate-700 hover:bg-slate-50' }}">
                                    <input
                                        type="radio"
                                        name="category"
                                        value="{{ $catSlug }}"
                                        class="w-4 h-4 text-teal-700 focus:ring-teal-500"
                                        {{ $isSelected ? 'checked' : '' }}
                                    >
                                    <span class="text-sm line-clamp-1">{{ $category->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Brand Radio Selection (Scalable: Search + Bounded Scrollable List) --}}
                    <div class="pt-6 border-t border-slate-100">
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
                                class="w-full pl-8 pr-8 py-2 text-xs bg-slate-50 border border-slate-200 rounded-lg text-slate-800 placeholder-slate-400 focus:bg-white focus:border-teal-500 focus:ring-1 focus:ring-teal-500 transition-colors"
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
                        <div class="max-h-60 overflow-y-auto overscroll-contain pr-1 space-y-2">
                            {{-- All Brands Option --}}
                            <label
                                x-show="!brandSearch.trim()"
                                class="flex items-center gap-3 p-3 rounded-xl border transition-all cursor-pointer {{ empty($selectedBrandSlug) ? 'border-teal-400 bg-teal-50/50 text-teal-900 font-semibold' : 'border-slate-200 text-slate-700 hover:bg-slate-50' }}"
                            >
                                <input
                                    type="radio"
                                    name="brand"
                                    value=""
                                    class="w-4 h-4 text-teal-700 focus:ring-teal-500"
                                    {{ empty($selectedBrandSlug) ? 'checked' : '' }}
                                >
                                <span class="text-sm">{{ $currentLocale === 'en' ? 'All Brands' : 'Semua Brand' }}</span>
                            </label>

                            @foreach ($brands as $brand)
                                @php
                                    $isSelected = $selectedBrand && $selectedBrand->id === $brand->id;
                                @endphp
                                <label
                                    x-show="matches('{{ addslashes($brand->name) }}')"
                                    class="flex items-center gap-3 p-3 rounded-xl border transition-all cursor-pointer {{ $isSelected ? 'border-teal-400 bg-teal-50/50 text-teal-900 font-semibold' : 'border-slate-200 text-slate-700 hover:bg-slate-50' }}"
                                >
                                    <input
                                        type="radio"
                                        name="brand"
                                        value="{{ $brand->slug }}"
                                        class="w-4 h-4 text-teal-700 focus:ring-teal-500"
                                        {{ $isSelected ? 'checked' : '' }}
                                    >
                                    <span class="text-sm line-clamp-1">{{ $brand->name }}</span>
                                </label>
                            @endforeach

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

                {{-- Drawer Footer Actions --}}
                <div class="p-4 border-t border-slate-100 bg-slate-50 flex items-center gap-3">
                    <a
                        href="{{ route('products.index') }}"
                        class="flex-1 py-3 px-4 rounded-xl border border-slate-300 bg-white hover:bg-slate-100 text-slate-700 font-semibold text-center text-sm transition-all focus-ring"
                    >
                        {{ $currentLocale === 'en' ? 'Reset' : 'Reset' }}
                    </a>

                    <button
                        type="submit"
                        class="flex-1 py-3 px-4 rounded-xl bg-teal-700 hover:bg-teal-800 text-white font-bold text-center text-sm shadow-sm transition-all focus-ring active:scale-[0.98]"
                    >
                        {{ $currentLocale === 'en' ? 'Apply Filters' : 'Terapkan Filter' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
