<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Page Title --}}
    <title>@hasSection('title')@yield('title') - @endif{{ __('Official Website of PT Abhipraya Nawasena Sejahtera') }}</title>

    {{-- Meta Description --}}
    <meta name="description" content="@yield('meta_description', __('Leading distributor of laboratory, medical, and diagnostic equipment in Indonesia.'))">

    {{-- Meta Robots --}}
    @hasSection('robots')
        <meta name="robots" content="@yield('robots')">
    @endif

    {{-- SEO Canonical & Hreflang Alternate URLs --}}
    @php
        $localizationService = app(\App\Services\LocalizationService::class);
        $canonicalUrl = $localizationService->getCanonicalUrl();
        $hreflangs = $localizationService->getHreflangUrls();
    @endphp
    <link rel="canonical" href="{{ $canonicalUrl }}">
    <link rel="alternate" hreflang="id" href="{{ $hreflangs['id'] }}">
    <link rel="alternate" hreflang="en" href="{{ $hreflangs['en'] }}">
    <link rel="alternate" hreflang="x-default" href="{{ $hreflangs['x-default'] }}">

    {{-- OpenGraph Basic Foundation --}}
    <meta property="og:locale" content="{{ app()->getLocale() === 'id' ? 'id_ID' : 'en_US' }}">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="PT Abhipraya Nawasena Sejahtera">

    {{-- Favicon --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('images/favicon/favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon/favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/favicon/apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('images/favicon/site.webmanifest') }}">

    {{-- Vite Assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('head')
</head>
<body class="bg-white text-slate-900 font-sans antialiased min-h-screen flex flex-col selection:bg-teal-100 selection:text-teal-900">
    {{-- Skip link for keyboard accessibility (WCAG 2.2 AA) --}}
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-[100] focus:px-4 focus:py-2 focus:bg-teal-700 focus:text-white focus:rounded-md focus:shadow-lg focus-ring">
        {{ __('Skip to main content') }}
    </a>

    {{-- Global Site Header --}}
    <x-header />

    {{-- Main Content Area --}}
    <main id="main-content" class="pt-16 md:pt-18 flex-1">
        @yield('content')
        {{ $slot ?? '' }}
    </main>

    {{-- Global Site Footer --}}
    <x-footer />

    @stack('scripts')
</body>
</html>
