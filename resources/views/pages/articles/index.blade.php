@extends('layouts.public')

@php
    $currentLocale = app()->getLocale();
    $metaTitle = $currentLocale === 'en'
        ? 'Articles & Scientific Updates - PT Abhipraya Nawasena Sejahtera'
        : 'Artikel & Informasi Ilmiah - PT Abhipraya Nawasena Sejahtera';
    $metaDescription = $currentLocale === 'en'
        ? 'Explore the latest scientific insights, corporate updates, events, and medical laboratory innovations from PT Abhipraya Nawasena Sejahtera.'
        : 'Temukan wawasan ilmiah terkini, berita perusahaan, kegiatan, dan inovasi alat laboratorium & medis dari PT Abhipraya Nawasena Sejahtera.';
@endphp

@section('title', $metaTitle)
@section('meta_description', $metaDescription)

@section('content')
<div class="bg-slate-50 py-10 sm:py-12 lg:py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Page Header & Breadcrumb --}}
        <div class="mb-10 lg:mb-12">
            <nav class="flex items-center gap-2 text-xs sm:text-sm text-slate-500 mb-4" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="hover:text-teal-700 transition-colors">
                    {{ $currentLocale === 'en' ? 'Home' : 'Beranda' }}
                </a>
                <span class="text-slate-300">/</span>
                <span class="text-teal-700 font-semibold" aria-current="page">
                    {{ $currentLocale === 'en' ? 'Articles' : 'Artikel' }}
                </span>
            </nav>

            <div class="max-w-3xl">
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black text-slate-900 tracking-tight leading-tight mb-3">
                    {{ $currentLocale === 'en' ? 'Articles & Scientific Updates' : 'Artikel & Informasi Ilmiah' }}
                </h1>
                <p class="text-sm sm:text-base text-slate-600 leading-relaxed">
                    {{ $currentLocale === 'en'
                        ? 'Stay informed with our latest research insights, product developments, event coverage, and corporate announcements.'
                        : 'Dapatkan wawasan ilmiah terkini, pembaruan inovasi produk, liputan kegiatan, dan informasi resmi seputar ANS.' }}
                </p>
            </div>
        </div>

        {{-- Articles Listing Grid --}}
        @if ($articles->isNotEmpty())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                @foreach ($articles as $article)
                    <x-articles.card :article="$article" />
                @endforeach
            </div>

            {{-- Pagination Container --}}
            <div class="mt-12 lg:mt-16 flex justify-center">
                {{ $articles->links() }}
            </div>
        @else
            {{-- Graceful Empty State --}}
            <div class="bg-white border border-slate-200 rounded-2xl p-10 sm:p-16 text-center max-w-lg mx-auto shadow-xs">
                <div class="w-16 h-16 rounded-2xl bg-teal-50 text-teal-700 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25M16.5 7.5V18a2.25 2.25 0 002.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 002.25 2.25h13.5M6 7.5h3v3H6v-3z" />
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-slate-900 mb-2">
                    {{ $currentLocale === 'en' ? 'No Articles Published Yet' : 'Belum Ada Artikel yang Dipublikasikan' }}
                </h2>
                <p class="text-sm text-slate-600 mb-6">
                    {{ $currentLocale === 'en'
                        ? 'We are preparing insightful scientific and corporate content. Please check back soon!'
                        : 'Kami sedang menyiapkan konten ilmiah dan pembaruan perusahaan yang bermanfaat. Silakan kunjungi kembali segera!' }}
                </p>
                <a
                    href="{{ route('home') }}"
                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-teal-700 hover:bg-teal-800 text-white text-sm font-semibold shadow-xs transition-colors"
                >
                    <span>{{ $currentLocale === 'en' ? 'Back to Home' : 'Kembali ke Beranda' }}</span>
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
