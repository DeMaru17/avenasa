@extends('layouts.public')

@php
    $currentLocale = app()->getLocale();
@endphp

@section('title', $currentLocale === 'en' ? 'About Us | PT Abhipraya Nawasena Sejahtera' : 'Tentang Kami | PT Abhipraya Nawasena Sejahtera')
@section('meta_description', !empty($companyProfile?->vision) ? Str::limit(strip_tags($companyProfile->vision), 160) : ($currentLocale === 'en'
    ? 'Learn about PT Abhipraya Nawasena Sejahtera (ANS) - Official distributor of life science, laboratory, medical, and diagnostic equipment in Indonesia.'
    : 'Tentang PT Abhipraya Nawasena Sejahtera (ANS) - Distributor resmi terkemuka peralatan ilmu hayati (life science), laboratorium, medis, dan diagnostik di Indonesia.'))

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
                'name' => $currentLocale === 'en' ? 'About Us' : 'Tentang Kami',
                'item' => url('/' . $currentLocale . '/about'),
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
    <x-about.hero />

    {{-- 2. Company Profile Narrative & Office Photo --}}
    <x-about.company-profile :profile="$companyProfile" />

    {{-- 3. Vision & Mission --}}
    <x-about.vision-mission :profile="$companyProfile" />

    {{-- 4. Core Values --}}
    <x-about.core-values :values="$coreValues" />

    {{-- 5. Management / Founders (Only renders when is_active = true) --}}
    <x-about.management :managements="$managements" />

    {{-- 6. Product Portfolio CTA Banner --}}
    <x-about.cta />
@endsection
