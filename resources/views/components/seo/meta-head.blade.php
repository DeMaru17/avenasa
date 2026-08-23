@php
    $currentLocale = app()->getLocale();
    $localizationService = app(\App\Services\LocalizationService::class);
    $defaultCanonical = $localizationService->getCanonicalUrl();
    $defaultHreflangs = $localizationService->getHreflangUrls();
    $companyProfile = \App\Models\CompanyProfile::first();
@endphp

{{-- 1. Primary Meta Tags --}}
<title>{!! View::hasSection('title') ? trim(View::getSection('title')) : ($currentLocale === 'en' ? 'PT Abhipraya Nawasena Sejahtera - Medical &amp; Laboratory Equipment Distributor' : 'PT Abhipraya Nawasena Sejahtera - Distributor Alat Kesehatan &amp; Laboratorium') !!}</title>
<meta name="description" content="{!! View::hasSection('meta_description') ? e(trim(strip_tags(View::getSection('meta_description')))) : ($companyProfile ? e(app()->getLocale() === 'en' ? ($companyProfile->tagline_en ?: $companyProfile->tagline_id) : $companyProfile->tagline_id) : ($currentLocale === 'en' ? 'PT Abhipraya Nawasena Sejahtera (ANS) is an authorized distributor of world-class laboratory, medical, and diagnostic equipment in Indonesia.' : 'PT Abhipraya Nawasena Sejahtera (ANS) adalah distributor resmi peralatan laboratorium, medis, dan diagnostik terkemuka di Indonesia.')) !!}">
<meta name="robots" content="{{ View::hasSection('robots') ? trim(View::getSection('robots')) : 'index, follow' }}">

{{-- 2. Canonical & Reciprocal Hreflang Tags --}}
@php
    $canonicalUrl = View::hasSection('canonical') ? trim(View::getSection('canonical')) : $defaultCanonical;
@endphp
<link rel="canonical" href="{{ $canonicalUrl }}">

@if (View::hasSection('hreflang_id'))
    <link rel="alternate" hreflang="id" href="@yield('hreflang_id')">
@elseif (!empty($defaultHreflangs['id']))
    <link rel="alternate" hreflang="id" href="{{ $defaultHreflangs['id'] }}">
@endif

@if (View::hasSection('omit_hreflang_en'))
    {{-- Omit EN hreflang for products with no EN counterpart per SPEC-10 AC-05 --}}
@elseif (View::hasSection('hreflang_en'))
    <link rel="alternate" hreflang="en" href="@yield('hreflang_en')">
@elseif (!empty($defaultHreflangs['en']))
    <link rel="alternate" hreflang="en" href="{{ $defaultHreflangs['en'] }}">
@endif

@if (View::hasSection('hreflang_x_default'))
    <link rel="alternate" hreflang="x-default" href="@yield('hreflang_x_default')">
@elseif (!empty($defaultHreflangs['x-default']))
    <link rel="alternate" hreflang="x-default" href="{{ $defaultHreflangs['x-default'] }}">
@endif

{{-- 3. Open Graph Metadata --}}
<meta property="og:site_name" content="PT Abhipraya Nawasena Sejahtera">
<meta property="og:type" content="@yield('og_type', 'website')">
<meta property="og:title" content="@yield('og_title', View::hasSection('title') ? View::getSection('title') : 'PT Abhipraya Nawasena Sejahtera')">
<meta property="og:description" content="@yield('og_description', View::hasSection('meta_description') ? View::getSection('meta_description') : ($currentLocale === 'en' ? 'PT Abhipraya Nawasena Sejahtera (ANS) is an authorized distributor of world-class laboratory, medical, and diagnostic equipment in Indonesia.' : 'PT Abhipraya Nawasena Sejahtera (ANS) adalah distributor resmi peralatan laboratorium, medis, dan diagnostik terkemuka di Indonesia.'))">
<meta property="og:url" content="{{ $canonicalUrl }}">
<meta property="og:locale" content="{{ $currentLocale === 'id' ? 'id_ID' : 'en_US' }}">
<meta property="og:locale:alternate" content="{{ $currentLocale === 'id' ? 'en_US' : 'id_ID' }}">
<meta property="og:image" content="@yield('og_image', asset('images/logo-ans.png'))">

{{-- 4. JSON-LD Structured Data: Organization & WebSite Graph --}}
@php
    $appUrl = url('/');
    $orgPhone = $companyProfile->phone ?? '021 39722772';
    $orgAddress = $companyProfile->address ?? 'Mensana Tower Lt. 15, Jl. Raya Kranggan RT.002/RW.016, Cibubur, Bekasi 17433';

    $schemaGraph = [
        [
            '@type' => 'Organization',
            '@id' => $appUrl . '/#organization',
            'name' => 'PT Abhipraya Nawasena Sejahtera',
            'url' => $appUrl,
            'logo' => asset('images/logo-ans.png'),
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'telephone' => $orgPhone,
                'contactType' => 'customer service',
                'areaServed' => 'ID',
                'availableLanguage' => ['Indonesian', 'English'],
            ],
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => $orgAddress,
                'addressLocality' => 'Bekasi',
                'addressRegion' => 'Jawa Barat',
                'postalCode' => '17433',
                'addressCountry' => 'ID',
            ],
        ],
        [
            '@type' => 'WebSite',
            '@id' => $appUrl . '/#website',
            'url' => $appUrl,
            'name' => 'PT Abhipraya Nawasena Sejahtera',
            'publisher' => ['@id' => $appUrl . '/#organization'],
            'inLanguage' => ['id-ID', 'en-US'],
        ],
    ];
@endphp
<script type="application/ld+json">
{!! json_encode(['@context' => 'https://schema.org', '@graph' => $schemaGraph], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>

{{-- Additional Structured Data (Product Schema, Breadcrumbs) --}}
@yield('structured_data')
