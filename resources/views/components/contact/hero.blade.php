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
                        {{ $currentLocale === 'en' ? 'Contact' : 'Kontak' }}
                    </span>
                </li>
            </ol>
        </nav>

        {{-- Eyebrow --}}
        <div class="text-teal-400 text-xs sm:text-sm font-semibold tracking-widest uppercase mb-3">
            {{ $currentLocale === 'en' ? 'Contact Us' : 'Hubungi Kami' }}
        </div>

        {{-- Page Heading --}}
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white tracking-tight leading-tight mb-4">
            {{ $currentLocale === 'en' ? 'Contact Us & Quotation Request' : 'Hubungi Kami & Permintaan Penawaran' }}
        </h1>

        {{-- Subtitle Narrative --}}
        <p class="text-slate-300 text-base sm:text-lg max-w-3xl leading-relaxed">
            {{ $currentLocale === 'en'
                ? 'Submit a price quotation request or technical inquiries to the Abhipraya Nawasena Sejahtera team. We will respond within 1×24 business hours.'
                : 'Ajukan permintaan penawaran harga atau pertanyaan teknis kepada tim Abhipraya Nawasena Sejahtera. Kami akan merespons dalam 1×24 jam kerja.' }}
        </p>
    </div>
</section>
