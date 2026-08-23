@extends('layouts.public')

@php
    $currentLocale = app()->getLocale();
@endphp

@section('title', $currentLocale === 'en' ? 'Contact Us & Quotation Request | PT Abhipraya Nawasena Sejahtera' : 'Hubungi Kami & Permintaan Penawaran | PT Abhipraya Nawasena Sejahtera')
@section('meta_description', $currentLocale === 'en'
    ? 'Contact PT Abhipraya Nawasena Sejahtera (ANS) for technical consultation, laboratory procurement, and price quotation requests in Indonesia.'
    : 'Hubungi PT Abhipraya Nawasena Sejahtera (ANS) untuk konsultasi teknis, pengadaan alat laboratorium, dan permintaan penawaran harga resmi.')

@if (!empty($requestedProduct))
    @section('canonical', route('contact', ['locale' => $currentLocale]))
    @section('robots', 'noindex, follow')
@endif

@section('structured_data')
@php
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
                'name' => $currentLocale === 'en' ? 'Contact' : 'Kontak',
                'item' => url('/' . $currentLocale . '/contact'),
            ],
        ],
    ];
@endphp
<script type="application/ld+json">
{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endsection

@section('content')
    {{-- 1. Hero / Page Header --}}
    <x-contact.hero />

    {{-- 2. Main Content Section (2 Columns on Desktop) --}}
    <section class="py-12 lg:py-16 bg-white border-b border-slate-100" aria-label="{{ __('Contact and Quotation') }}">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start">
                {{-- Left Column: Official Contact Channels & Google Maps --}}
                <div class="lg:col-span-5 space-y-8">
                    <x-contact.information :profile="$companyProfile" />
                    <x-contact.map :profile="$companyProfile" />
                </div>

                {{-- Right Column: Quotation Request Form --}}
                <div class="lg:col-span-7">
                    <x-contact.form-shell
                        :requestedProduct="$requestedProduct ?? null"
                        :defaultSubject="$defaultSubject ?? ''"
                    />
                </div>
            </div>
        </div>
    </section>
@endsection
