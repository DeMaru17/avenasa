@php
    $currentLocale = app()->getLocale();
@endphp

<div class="bg-white border border-slate-200/90 rounded-3xl p-8 sm:p-12 text-center max-w-2xl mx-auto shadow-sm my-6">
    <div class="w-16 h-16 rounded-2xl bg-teal-50 border border-teal-100 flex items-center justify-center mx-auto mb-4 text-teal-700" aria-hidden="true">
        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
        </svg>
    </div>

    <h2 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight mb-2">
        {{ $currentLocale === 'en' ? 'No Products Found' : 'Tidak Ada Produk yang Sesuai' }}
    </h2>

    <p class="text-sm sm:text-base text-slate-600 max-w-md mx-auto leading-relaxed mb-6">
        {{ $currentLocale === 'en'
            ? 'We could not find any products matching your selected filter combination. Please try adjusting or clearing your filters.'
            : 'Kami tidak menemukan produk yang cocok dengan kombinasi filter yang Anda pilih. Coba sesuaikan atau hapus filter Anda.' }}
    </p>

    <a
        href="{{ route('products.index') }}"
        class="inline-flex items-center justify-center gap-2 bg-teal-700 hover:bg-teal-800 text-white font-bold py-3 px-6 rounded-xl shadow-sm transition-all focus-ring text-sm active:scale-[0.98]"
    >
        <span>{{ $currentLocale === 'en' ? 'Reset All Filters' : 'Reset Semua Filter' }}</span>
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
        </svg>
    </a>
</div>
