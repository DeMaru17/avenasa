@props(['product'])

@php
    $currentLocale = app()->getLocale();
    $description = $product->description;
@endphp

@if (!empty($description))
    <section class="mt-12 lg:mt-16 pt-8 border-t border-slate-100" aria-label="{{ __('Full Product Description') }}">
        <h2 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight mb-6">
            {{ $currentLocale === 'en' ? 'Full Product Description' : 'Deskripsi Lengkap Produk' }}
        </h2>

        <div class="bg-white border border-slate-200/90 rounded-2xl p-6 sm:p-8 shadow-xs text-slate-700 leading-relaxed text-sm sm:text-base prose prose-slate max-w-none">
            {!! nl2br(e(strip_tags($description))) !!}
        </div>
    </section>
@endif
