@extends('layouts.public')

@section('title', __('Home'))
@section('meta_description', $companyProfile?->tagline ?: ($companyProfile?->about ?: 'PT Abhipraya Nawasena Sejahtera'))

@section('content')
    {{-- Section 1: Hero Banner Carousel --}}
    <x-hero-carousel :banners="$heroBanners ?? collect([$hero]->filter())" />

    {{-- Section 2: Company Introduction --}}
    <x-home.company-intro :profile="$companyProfile" />

    {{-- Section 3: Product Highlights --}}
    <x-home.product-highlights :products="$featuredProducts ?? collect()" />

    {{-- Section 4: Principals & Clients Highlights --}}
    <x-home.partners-highlights :brands="$brands ?? collect()" :clients="$clients ?? collect()" />

    {{-- Section 5: Strategic Call to Action --}}
    <x-home.strategic-cta :profile="$companyProfile" />
@endsection
