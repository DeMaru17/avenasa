@props(['product'])

@php
    $currentLocale = app()->getLocale();
@endphp

<nav aria-label="{{ __('Breadcrumb') }}" class="mb-6 lg:mb-8">
    <ol class="flex flex-wrap items-center gap-2 text-xs sm:text-sm text-slate-500">
        <li>
            <a href="{{ route('home') }}" class="hover:text-teal-700 transition-colors focus-ring rounded">
                {{ $currentLocale === 'en' ? 'Home' : 'Beranda' }}
            </a>
        </li>
        <li aria-hidden="true" class="text-slate-400">
            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
            </svg>
        </li>
        <li>
            <a href="{{ route('products.index') }}" class="hover:text-teal-700 transition-colors focus-ring rounded">
                {{ $currentLocale === 'en' ? 'Products' : 'Produk' }}
            </a>
        </li>

        @if ($product->category)
            <li aria-hidden="true" class="text-slate-400">
                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                </svg>
            </li>
            <li>
                <a
                    href="{{ route('products.index', ['category' => $product->category->slug]) }}"
                    class="hover:text-teal-700 transition-colors focus-ring rounded"
                >
                    {{ $product->category->name }}
                </a>
            </li>
        @endif

        <li aria-hidden="true" class="text-slate-400">
            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
            </svg>
        </li>
        <li>
            <span class="text-slate-900 font-semibold line-clamp-1 max-w-[200px] sm:max-w-xs md:max-w-md" aria-current="page">
                {{ $product->name }}
            </span>
        </li>
    </ol>
</nav>
