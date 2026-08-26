@php
    $currentLocale = app()->getLocale();
@endphp

<section class="py-16 lg:py-20 bg-slate-50" aria-labelledby="about-cta-heading">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 id="about-cta-heading" class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight mb-4">
            {{ $currentLocale === 'en' ? 'View Our Product Portfolio' : 'Lihat Portofolio Produk Kami' }}
        </h2>
        <p class="text-slate-600 text-sm sm:text-base mb-8 max-w-xl mx-auto leading-relaxed">
            {{ $currentLocale === 'en'
                ? 'Discover high-precision laboratory instruments, reagents, and diagnostic solutions from leading global manufacturers.'
                : 'Temukan instrumen laboratorium presisi tinggi, reagen, dan solusi diagnostik dari manufaktur terkemuka dunia.' }}
        </p>
        <div>
            <a
                href="{{ route('products.index') }}"
                class="inline-flex items-center justify-center gap-2.5 bg-teal-700 hover:bg-teal-800 text-white font-bold px-7 py-3.5 rounded-lg shadow-sm transition-all focus-ring text-base active:scale-[0.98]"
            >
                <span>{{ $currentLocale === 'en' ? 'Explore Product Catalog' : 'Jelajahi Katalog Produk' }}</span>
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                </svg>
            </a>
        </div>
    </div>
</section>
