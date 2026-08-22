@props([
    'profile' => null,
])

@php
    $currentLocale = app()->getLocale();
    $tagline = 'Empowering Science for a Prosperous Future';
    $aboutText = $profile?->about ?: ($currentLocale === 'en'
        ? 'PT Abhipraya Nawasena Sejahtera (ANS) is a leading distributor of life science, laboratory, medical, and diagnostic equipment in Indonesia, serving pharmaceutical, biotechnology, research centers, universities, and hospitals.'
        : 'PT Abhipraya Nawasena Sejahtera (ANS) adalah distributor resmi terkemuka peralatan ilmu hayati (life science), laboratorium, medis, dan diagnostik di Indonesia yang melayani industri farmasi, bioteknologi, pusat riset, universitas, dan rumah sakit.');
    $officeAddress = $profile?->address ?: 'Mensana Tower Lt. 15, Jl. Raya Kranggan Kav. 1, Cibubur, Bekasi';
@endphp

<section class="py-16 lg:py-24 bg-white border-b border-slate-100" aria-labelledby="company-intro-heading">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">
            {{-- Left Column: Narrative Content --}}
            <div class="lg:col-span-7">
                <div class="text-teal-700 text-xs sm:text-sm font-bold tracking-widest uppercase mb-3">
                    {{ $currentLocale === 'en' ? 'About Us' : 'Tentang Kami' }}
                </div>

                <h2 id="company-intro-heading" class="text-2xl sm:text-3xl lg:text-4xl font-bold text-slate-900 leading-tight tracking-tight mb-4">
                    {{ $tagline }}
                </h2>

                <div class="text-slate-600 text-base leading-relaxed space-y-4 mb-8">
                    @foreach (explode('<br><br>', $aboutText) as $paragraph)
                        <p>{!! strip_tags($paragraph, '<strong><b><em><i><br>') !!}</p>
                    @endforeach
                </div>

                <div class="pt-2">
                    <a
                        href="{{ route('about') }}"
                        class="inline-flex items-center gap-2 bg-slate-900 hover:bg-teal-800 text-white font-semibold px-6 py-3.5 rounded-lg shadow-sm transition-all focus-ring text-sm active:scale-[0.98]"
                    >
                        <span>{{ $currentLocale === 'en' ? 'Learn About Us' : 'Pelajari Profil Kami' }}</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                        </svg>
                    </a>
                </div>
            </div>

            {{-- Right Column: Office Photo Card --}}
            <div class="lg:col-span-5">
                <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300">
                    {{-- Office Photo Container with 4:3 Aspect Ratio and Professional Crop --}}
                    <div class="relative aspect-[4/3] bg-slate-100 overflow-hidden">
                        <img
                            src="{{ asset('images/mensana-tower.png') }}"
                            alt="{{ $currentLocale === 'en' ? 'PT Abhipraya Nawasena Sejahtera Head Office - Mensana Tower Cibubur' : 'Gedung Kantor Pusat PT Abhipraya Nawasena Sejahtera - Mensana Tower Cibubur' }}"
                            class="w-full h-full object-cover object-[center_35%]"
                            loading="lazy"
                            width="640"
                            height="480"
                        >
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/40 via-transparent to-transparent"></div>
                    </div>

                    {{-- Office Location Caption --}}
                    <div class="p-5 sm:p-6 bg-slate-50/80 border-t border-slate-200/80">
                        <div class="flex items-start gap-3.5">
                            <div class="w-9 h-9 rounded-lg bg-teal-100 text-teal-700 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                </svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <span class="text-[11px] font-bold text-teal-700 uppercase tracking-widest block">
                                    {{ $currentLocale === 'en' ? 'Head Office' : 'Kantor Pusat' }}
                                </span>
                                <p class="text-sm font-semibold text-slate-900 mt-0.5">
                                    Mensana Tower, Cibubur
                                </p>
                                <p class="text-xs text-slate-500 mt-0.5 line-clamp-2 leading-relaxed">
                                    {{ $officeAddress }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
