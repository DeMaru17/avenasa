@props(['clients' => collect()])

@php
    $currentLocale = app()->getLocale();
@endphp

@if ($clients->isNotEmpty())
    <section class="py-16 lg:py-24 bg-slate-50 border-b border-slate-100" aria-labelledby="clients-heading">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Section Header --}}
            <div class="text-center max-w-3xl mx-auto mb-12 lg:mb-16">
                <div class="text-teal-700 text-xs sm:text-sm font-bold tracking-widest uppercase mb-3">
                    {{ $currentLocale === 'en' ? 'Trusted Clients' : 'Klien Terpercaya' }}
                </div>
                <h2 id="clients-heading" class="text-2xl sm:text-3xl lg:text-4xl font-bold text-slate-900 tracking-tight mb-3">
                    {{ $currentLocale === 'en' ? 'Trusted by Leading Institutions' : 'Dipercaya oleh Institusi Terkemuka' }}
                </h2>
                <p class="text-slate-600 text-sm sm:text-base leading-relaxed max-w-2xl mx-auto">
                    {{ $currentLocale === 'en'
                        ? 'Abhipraya Nawasena Sejahtera products and services are trusted and utilized by pharmaceutical industries, food & beverage, hospitals, accredited testing labs, universities, and research institutions across Indonesia.'
                        : 'Produk dan layanan Abhipraya Nawasena Sejahtera telah dipercaya dan digunakan oleh industri farmasi, makanan & minuman, rumah sakit, laboratorium uji terakreditasi, universitas, dan lembaga riset di Indonesia.' }}
                </p>
            </div>

            {{-- Responsive Multi-Column Grid: All Active Clients --}}
            <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-5 lg:gap-6">
                @foreach ($clients as $client)
                    <div class="bg-white border border-slate-200/90 rounded-2xl p-5 sm:p-6 text-center flex flex-col items-center justify-center min-h-[96px] hover:border-teal-300 hover:shadow-sm transition-all duration-200 group">
                        @if (!empty($client->logo_path))
                            <img
                                src="{{ asset('storage/' . $client->logo_path) }}"
                                alt="Logo {{ $client->name }}"
                                class="max-h-10 w-auto object-contain mb-2.5 transition-transform duration-200 group-hover:scale-105"
                                loading="lazy"
                            >
                        @endif

                        <span class="text-sm sm:text-base font-semibold text-slate-800 tracking-tight leading-snug">
                            {{ $client->name }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
