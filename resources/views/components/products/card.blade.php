@props(['product'])

@php
    $currentLocale = app()->getLocale();
    $detailUrl = route('products.show', ['slug' => $product->slug]);
@endphp

<article class="bg-white border border-slate-200/90 rounded-2xl overflow-hidden shadow-sm hover:shadow-md hover:border-teal-400/80 transition-all duration-200 flex flex-col h-full group focus-within:ring-2 focus-within:ring-teal-600">
    {{-- Product Image / Fallback Container --}}
    <a href="{{ $detailUrl }}" class="aspect-[4/3] bg-slate-100 overflow-hidden relative flex items-center justify-center block flex-shrink-0" tabindex="-1" aria-hidden="true">
        @if (!empty($product->primary_image_path))
            <img
                src="{{ asset('storage/' . $product->primary_image_path) }}"
                alt="{{ $product->name }}"
                class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-300"
                loading="lazy"
            >
        @else
            {{-- Graceful Professional Fallback Placeholder --}}
            <div class="flex flex-col items-center justify-center p-6 text-center text-slate-400">
                <svg class="w-12 h-12 text-slate-300 mb-2 group-hover:text-teal-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke-width="1.2" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0112 15a9.065 9.065 0 00-6.23-.693L5 14.5m14.8.8l1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0112 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.61L5 14.5" />
                </svg>
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">PT ANS</span>
            </div>
        @endif
    </a>

    {{-- Card Body --}}
    <div class="p-5 flex flex-col flex-1 justify-between">
        <div>
            {{-- Badges --}}
            <div class="flex flex-wrap items-center gap-1.5 mb-2.5">
                @if ($product->category)
                    <span class="text-[11px] font-semibold bg-teal-50 text-teal-800 border border-teal-200/80 px-2 py-0.5 rounded-md">
                        {{ $product->category->name }}
                    </span>
                @endif
                @if ($product->brand)
                    <span class="text-[11px] font-medium bg-slate-100 text-slate-700 border border-slate-200 px-2 py-0.5 rounded-md">
                        {{ $product->brand->name }}
                    </span>
                @endif
            </div>

            {{-- Product Name --}}
            <h3 class="text-base font-bold text-slate-900 group-hover:text-teal-700 transition-colors leading-snug line-clamp-2 mb-2">
                <a href="{{ $detailUrl }}" class="focus:outline-none">
                    {{ $product->name }}
                </a>
            </h3>

            {{-- Product Summary --}}
            @if (!empty($product->summary))
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed line-clamp-2 mb-4">
                    {{ $product->summary }}
                </p>
            @endif
        </div>

        {{-- Card Action Footer --}}
        <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-teal-700">
            <a
                href="{{ $detailUrl }}"
                class="inline-flex items-center gap-1 hover:text-teal-800 transition-colors focus-ring rounded"
            >
                <span>{{ $currentLocale === 'en' ? 'View Details' : 'Lihat Detail' }}</span>
                <svg class="w-3.5 h-3.5 group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                </svg>
            </a>
        </div>
    </div>
</article>
