<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Centralized SEO Metadata & Structured Data --}}
    <x-seo.meta-head />

    {{-- Favicon --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('images/favicon/favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon/favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/favicon/apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('images/favicon/site.webmanifest') }}">

    {{-- Centralized Google Analytics 4 (Client-Side, Environment-Aware, Strictly Non-Blocking) --}}
    @php
        $gaId = config('services.google.analytics_id');
        $isLocal = app()->isLocal();
        $gaEnabled = !empty($gaId) && (!$isLocal || env('GA_FORCE_ENABLE', false));
    @endphp
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){window.dataLayer.push(arguments);}
    </script>
    @if ($gaEnabled)
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ $gaId }}"></script>
        <script>
            gtag('js', new Date());
            gtag('config', '{{ $gaId }}');
        </script>
    @endif

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
