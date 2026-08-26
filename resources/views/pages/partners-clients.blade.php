@extends('layouts.public')

@php
    $currentLocale = app()->getLocale();
@endphp

@section('title', $currentLocale === 'en' ? 'Business Partners & Clients | PT Abhipraya Nawasena Sejahtera' : 'Mitra Bisnis & Klien | PT Abhipraya Nawasena Sejahtera')
@section('meta_description', $currentLocale === 'en'
    ? 'Manufacturing business partners and trusted institutional client network of PT Abhipraya Nawasena Sejahtera (ANS) in Indonesia.'
    : 'Jaringan mitra bisnis manufaktur dan daftar klien institusional terpercaya PT Abhipraya Nawasena Sejahtera (ANS) di Indonesia.')

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
                'name' => $currentLocale === 'en' ? 'Business Partners & Clients' : 'Mitra Bisnis & Klien',
                'item' => url('/' . $currentLocale . '/partners-clients'),
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
    <x-partners-clients.hero />

    {{-- 2. Official Principals / Brands Showcase --}}
    <x-partners-clients.principals :brands="$brands" />

    {{-- 3. Corporate & Institutional Clients Showcase --}}
    <x-partners-clients.clients :clients="$clients" />

    {{-- 4. Strategic Consultation & Quotation CTA --}}
    <x-partners-clients.cta :profile="$companyProfile" />
@endsection
