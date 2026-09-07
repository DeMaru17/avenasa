@props(['articles' => collect()])

@php
    $currentLocale = app()->getLocale();
@endphp

@if ($articles->isNotEmpty())
    <section class="py-14 sm:py-18 lg:py-24 bg-slate-50 border-t border-slate-200/80" aria-labelledby="latest-articles-title">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Section Header --}}
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10 lg:mb-14">
                <div class="max-w-2xl">
                    <span class="text-xs sm:text-sm font-bold uppercase tracking-wider text-teal-700 block mb-2">
                        {{ $currentLocale === 'en' ? 'News & Insights' : 'Berita & Informasi' }}
                    </span>
                    <h2 id="latest-articles-title" class="text-2xl sm:text-3xl lg:text-4xl font-black text-slate-900 tracking-tight leading-tight">
                        {{ $currentLocale === 'en' ? 'Latest Articles & Corporate Updates' : 'Artikel & Informasi Perusahaan Terkini' }}
                    </h2>
                    <p class="mt-2 text-sm sm:text-base text-slate-600 leading-relaxed">
                        {{ $currentLocale === 'en'
                            ? 'Discover scientific advances, laboratory equipment guidelines, and key announcements from PT Abhipraya Nawasena Sejahtera.'
                            : 'Pelajari perkembangan ilmiah, panduan instrumen laboratorium, serta berita resmi dari PT Abhipraya Nawasena Sejahtera.' }}
                    </p>
                </div>

                {{-- View All CTA Button (Desktop) --}}
                <div class="flex-shrink-0 hidden md:block">
                    <a
                        href="{{ route('articles.index') }}"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl border border-slate-300 bg-white hover:bg-teal-50 hover:border-teal-400 hover:text-teal-700 text-slate-700 font-semibold text-sm shadow-xs transition-all focus-ring"
                    >
                        <span>{{ $currentLocale === 'en' ? 'View All Articles' : 'Lihat Semua Artikel' }}</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                        </svg>
                    </a>
                </div>
            </div>

            {{-- Articles Cards Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                @foreach ($articles as $article)
                    <x-articles.card :article="$article" />
                @endforeach
            </div>

            {{-- View All CTA Button (Mobile) --}}
            <div class="mt-8 text-center md:hidden">
                <a
                    href="{{ route('articles.index') }}"
                    class="inline-flex items-center justify-center gap-2 w-full px-5 py-3 rounded-xl border border-slate-300 bg-white text-slate-700 font-semibold text-sm shadow-xs hover:bg-slate-50 transition-colors"
                >
                    <span>{{ $currentLocale === 'en' ? 'View All Articles' : 'Lihat Semua Artikel' }}</span>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                    </svg>
                </a>
            </div>
        </div>
    </section>
@endif
