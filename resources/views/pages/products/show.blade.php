@extends('layouts.public')

@php
    $currentLocale = app()->getLocale();
    $brandName = $product->brand->name ?? 'ANS';
    $metaTitle = $product->name . ' - ' . $brandName . ' | PT Abhipraya Nawasena Sejahtera';
    $metaDescription = !empty($product->summary) ? $product->summary : Str::limit(strip_tags($product->description ?? ''), 160);
@endphp

@section('title', $metaTitle)
@section('meta_description', $metaDescription)

@if (!empty($product->primary_image_path))
    @section('og_image', asset('storage/' . $product->primary_image_path))
@endif

@if (empty($product->slug_en))
    @section('omit_hreflang_en', 'true')
@endif

@section('structured_data')
@php
    $productSchema = [
        '@context' => 'https://schema.org/',
        '@type' => 'Product',
        'name' => $product->name,
        'image' => !empty($product->primary_image_path) ? [asset('storage/' . $product->primary_image_path)] : [],
        'description' => $metaDescription,
    ];
    if ($product->brand) {
        $productSchema['brand'] = [
            '@type' => 'Brand',
            'name' => $product->brand->name,
        ];
        if (!empty($product->brand->website_url)) {
            $productSchema['brand']['url'] = $product->brand->website_url;
        }
    }
    if ($product->category) {
        $productSchema['category'] = $product->category->name;
    }

    $breadcrumbSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            [
                '@type' => 'ListItem',
                'position' => 1,
                'name' => $currentLocale === 'en' ? 'Home' : 'Beranda',
                'item' => url('/' . $currentLocale),
            ],
            [
                '@type' => 'ListItem',
                'position' => 2,
                'name' => $currentLocale === 'en' ? 'Products' : 'Produk',
                'item' => url('/' . $currentLocale . '/products'),
            ],
            [
                '@type' => 'ListItem',
                'position' => 3,
                'name' => $product->name,
                'item' => url('/' . $currentLocale . '/products/' . ($currentLocale === 'en' ? ($product->slug_en ?: $product->slug_id) : $product->slug_id)),
            ],
        ],
    ];
@endphp
<script type="application/ld+json">
{!! json_encode($productSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
<script type="application/ld+json">
{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        if (window.ANSAnalytics) {
            window.ANSAnalytics.trackViewProduct({
                productId: {{ $product->id }},
                productName: "{{ addslashes($product->name) }}",
                locale: "{{ $currentLocale }}",
                categoryName: "{{ addslashes($product->category->name ?? '') }}",
                brandName: "{{ addslashes($product->brand->name ?? '') }}"
            });
        }
    });
</script>
@endpush

@section('content')
<div class="py-8 sm:py-10 lg:py-14 pb-24 md:pb-14">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- 1. Hierarchical Breadcrumb --}}
        <x-product-detail.breadcrumb :product="$product" />

        {{-- 2. Main Product Showcase Grid (Gallery on Left, Identity & Action on Right) --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start">
            {{-- Left Column: Product Gallery --}}
            <div class="lg:col-span-6">
                <x-product-detail.gallery :product="$product" />
            </div>

            {{-- Right Column: Identity & Primary CTA Card --}}
            <div class="lg:col-span-6">
                <x-product-detail.identity :product="$product" :profile="$companyProfile ?? null" />
            </div>
        </div>

        {{-- 3. Technical Specifications --}}
        <x-product-detail.specifications :product="$product" />

        {{-- 4. Full Product Description --}}
        <x-product-detail.description :product="$product" />

        {{-- 5. Bottom Navigation Bar --}}
        <div class="mt-12 lg:mt-16 pt-8 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
            <a
                href="{{ route('products.index') }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 hover:text-teal-700 font-semibold text-sm shadow-xs transition-all focus-ring"
            >
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                <span>{{ $currentLocale === 'en' ? 'Back to Product Catalog' : 'Kembali ke Katalog Produk' }}</span>
            </a>

            <a
                href="{{ route('contact', ['product_id' => $product->id]) }}"
                onclick="if(window.ANSAnalytics) window.ANSAnalytics.trackStartQuotation({ source: 'product_detail', locale: '{{ $currentLocale }}', productId: {{ $product->id }} });"
                class="hidden sm:inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-teal-700 hover:bg-teal-800 text-white font-bold text-sm shadow-sm transition-all focus-ring active:scale-[0.98]"
            >
                <span>{{ $currentLocale === 'en' ? 'Request a Quotation' : 'Minta Penawaran' }}</span>
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                </svg>
            </a>
        </div>
    </div>
</div>

{{-- 6. Mobile Sticky Bottom Action Bar (< 768px) --}}
<x-product-detail.mobile-sticky-cta :product="$product" :profile="$companyProfile ?? null" />
@endsection
