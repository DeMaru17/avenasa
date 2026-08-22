@extends('layouts.public')

@php
    $currentLocale = app()->getLocale();
@endphp

@section('title', $currentLocale === 'en' ? 'About Us - PT Abhipraya Nawasena Sejahtera' : 'Tentang Kami - PT Abhipraya Nawasena Sejahtera')
@section('meta_description', $currentLocale === 'en'
    ? 'Learn about PT Abhipraya Nawasena Sejahtera (ANS) - Official distributor of life science, laboratory, medical, and diagnostic equipment in Indonesia.'
    : 'Tentang PT Abhipraya Nawasena Sejahtera (ANS) - Distributor resmi terkemuka peralatan ilmu hayati (life science), laboratorium, medis, dan diagnostik di Indonesia.')

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
