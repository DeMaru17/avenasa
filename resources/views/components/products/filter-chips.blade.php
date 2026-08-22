@props([
    'selectedCategory' => null,
    'selectedBrand' => null,
    'selectedCategorySlug' => null,
    'selectedBrandSlug' => null,
])

@php
    $currentLocale = app()->getLocale();
    $hasActiveFilters = $selectedCategory || $selectedBrand;
@endphp

@if ($hasActiveFilters)
    <div class="flex flex-wrap items-center gap-2 mb-6 p-3 sm:p-4 rounded-xl bg-slate-50 border border-slate-200/80">
        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider mr-1">
            {{ $currentLocale === 'en' ? 'Active Filters:' : 'Filter Aktif:' }}
        </span>

        {{-- Category Chip --}}
        @if ($selectedCategory)
            @php
                $removeCatUrl = route('products.index', array_filter(['brand' => $selectedBrandSlug]));
            @endphp
            <a
                href="{{ $removeCatUrl }}"
                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-teal-50 text-teal-800 border border-teal-200 hover:bg-teal-100 transition-colors focus-ring"
                title="{{ $currentLocale === 'en' ? 'Remove Category Filter' : 'Hapus Filter Kategori' }}"
            >
                <span>{{ $currentLocale === 'en' ? 'Category:' : 'Kategori:' }} {{ $selectedCategory->name }}</span>
                <svg class="w-3.5 h-3.5 text-teal-700 hover:text-teal-900" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </a>
        @endif

        {{-- Brand Chip --}}
        @if ($selectedBrand)
            @php
                $removeBrandUrl = route('products.index', array_filter(['category' => $selectedCategorySlug]));
            @endphp
            <a
                href="{{ $removeBrandUrl }}"
                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-slate-200/80 text-slate-800 border border-slate-300 hover:bg-slate-300 transition-colors focus-ring"
                title="{{ $currentLocale === 'en' ? 'Remove Brand Filter' : 'Hapus Filter Brand' }}"
            >
                <span>Brand: {{ $selectedBrand->name }}</span>
                <svg class="w-3.5 h-3.5 text-slate-600 hover:text-slate-900" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </a>
        @endif

        {{-- Clear All Link --}}
        <a
            href="{{ route('products.index') }}"
            class="text-xs font-semibold text-rose-600 hover:text-rose-700 hover:underline ml-auto pl-2 transition-colors focus-ring rounded"
        >
            {{ $currentLocale === 'en' ? 'Clear All' : 'Hapus Semua' }}
        </a>
    </div>
@endif
