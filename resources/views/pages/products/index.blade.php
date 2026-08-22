@extends('layouts.public')

@php
    $currentLocale = app()->getLocale();
    $hasFilterQuery = request()->has('category') || request()->has('brand');
@endphp

@section('title', $currentLocale === 'en' ? 'Product Catalog - PT Abhipraya Nawasena Sejahtera' : 'Katalog Produk - PT Abhipraya Nawasena Sejahtera')
@section('meta_description', $currentLocale === 'en'
    ? 'Browse our complete catalog of laboratory instruments, diagnostics equipment, reagents, and life science solutions.'
    : 'Jelajahi katalog lengkap peralatan laboratorium, alat diagnostik, reagen kimia, dan solusi life science PT Abhipraya Nawasena Sejahtera.')

@if ($hasFilterQuery)
    @section('robots', 'noindex, follow')
@endif

@section('content')
<div x-data="{ open: false }">
    {{-- 1. Hero / Header --}}
    <x-products.hero />

    {{-- 2. Main Catalog Section --}}
    <section class="py-10 lg:py-16 bg-white border-b border-slate-100" aria-label="{{ __('Product Catalog') }}">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-start">
                {{-- Desktop Sidebar Filter (>= 1024px) --}}
                <div class="hidden lg:block lg:col-span-3 xl:col-span-3">
                    <x-products.sidebar
                        :categories="$categories"
                        :brands="$brands"
                        :selectedCategory="$selectedCategory"
                        :selectedBrand="$selectedBrand"
                        :selectedCategorySlug="$selectedCategorySlug"
                        :selectedBrandSlug="$selectedBrandSlug"
                        :activeFilterCount="$activeFilterCount"
                    />
                </div>

                {{-- Product List Column --}}
                <div class="lg:col-span-9 xl:col-span-9">
                    {{-- Mobile Filter Bar (< 1024px) --}}
                    <div class="lg:hidden flex items-center justify-between gap-4 mb-6 pb-4 border-b border-slate-200">
                        <button
                            type="button"
                            @click="open = true"
                            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-800 text-sm font-semibold shadow-xs transition-all focus-ring active:scale-95"
                            aria-expanded="false"
                            aria-controls="drawer-title"
                        >
                            <svg class="w-4 h-4 text-teal-700" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75" />
                            </svg>
                            <span>{{ $currentLocale === 'en' ? 'Filter Products' : 'Filter Produk' }}</span>
                            @if ($activeFilterCount > 0)
                                <span class="ml-1 inline-flex items-center justify-center w-5 h-5 rounded-full bg-teal-700 text-white text-[11px] font-bold">
                                    {{ $activeFilterCount }}
                                </span>
                            @endif
                        </button>

                        <div class="text-xs sm:text-sm text-slate-500 font-medium">
                            {{ $products->total() }} {{ $currentLocale === 'en' ? 'products' : 'produk' }}
                        </div>
                    </div>

                    {{-- Desktop Header Bar --}}
                    <div class="hidden lg:flex items-center justify-between mb-6">
                        <div class="text-sm text-slate-600 font-medium">
                            @if ($products->total() > 0)
                                {{ $currentLocale === 'en'
                                    ? "Showing {$products->firstItem()}–{$products->lastItem()} of {$products->total()} products"
                                    : "Menampilkan {$products->firstItem()}–{$products->lastItem()} dari {$products->total()} produk" }}
                            @else
                                {{ $currentLocale === 'en' ? '0 products found' : '0 produk ditemukan' }}
                            @endif
                        </div>
                    </div>

                    {{-- Active Filter Chips --}}
                    <x-products.filter-chips
                        :selectedCategory="$selectedCategory"
                        :selectedBrand="$selectedBrand"
                        :selectedCategorySlug="$selectedCategorySlug"
                        :selectedBrandSlug="$selectedBrandSlug"
                    />

                    {{-- Product Grid / Empty State --}}
                    @if ($products->total() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                            @foreach ($products as $product)
                                <x-products.card :product="$product" />
                            @endforeach
                        </div>

                        {{-- Server-Side Pagination --}}
                        <div class="mt-10 pt-6 border-t border-slate-100">
                            {{ $products->links() }}
                        </div>
                    @else
                        {{-- Graceful Empty State --}}
                        <x-products.empty-state />
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- Mobile Filter Drawer (< 1024px) --}}
    <x-products.drawer
        :categories="$categories"
        :brands="$brands"
        :selectedCategory="$selectedCategory"
        :selectedBrand="$selectedBrand"
        :selectedCategorySlug="$selectedCategorySlug"
        :selectedBrandSlug="$selectedBrandSlug"
        :activeFilterCount="$activeFilterCount"
    />
</div>
@endsection
