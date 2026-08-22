@php
    $currentLocale = app()->getLocale();
@endphp

<section class="bg-slate-900 py-14 lg:py-20 border-b border-slate-800" aria-label="{{ __('Hero Banner') }}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Breadcrumbs --}}
        <nav aria-label="{{ __('Breadcrumb') }}" class="mb-5">
            <ol class="flex items-center gap-2 text-xs text-slate-400">
                <li>
                    <a href="{{ route('home') }}" class="hover:text-teal-400 transition-colors focus-ring rounded">
                        {{ $currentLocale === 'en' ? 'Home' : 'Beranda' }}
                    </a>
                </li>
                <li aria-hidden="true" class="text-slate-600">
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </li>
                <li>
                    <span class="text-slate-200 font-medium" aria-current="page">
                        {{ $currentLocale === 'en' ? 'Partners & Clients' : 'Mitra & Klien' }}
                    </span>
                </li>
            </ol>
        </nav>

        {{-- Eyebrow --}}
        <div class="text-teal-400 text-xs sm:text-sm font-semibold tracking-widest uppercase mb-3">
            {{ $currentLocale === 'en' ? 'Partners & Clients' : 'Mitra & Klien' }}
        </div>

        {{-- Page Heading --}}
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white tracking-tight leading-tight mb-4">
            {{ $currentLocale === 'en' ? 'Official Principals & Trusted Clients' : 'Prinsipal Resmi & Klien Terpercaya' }}
        </h1>

        {{-- Subtitle Narrative --}}
        <p class="text-slate-300 text-base sm:text-lg max-w-3xl leading-relaxed">
            {{ $currentLocale === 'en'
                ? 'Authorized manufacturing partnerships with world-leading scientific brands and trusted institutions across Indonesia.'
                : 'Jaringan kemitraan manufaktur global terkemuka dan institusi saintifik terpercaya yang bermitra dengan PT Abhipraya Nawasena Sejahtera.' }}
        </p>
    </div>
</section>
