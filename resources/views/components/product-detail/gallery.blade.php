@props(['product'])

@php
    $currentLocale = app()->getLocale();

    // Prepare unified image list: Primary Image first, followed by supporting gallery images
    $allImages = [];

    if (!empty($product->primary_image_path)) {
        $allImages[] = [
            'src' => asset('storage/' . $product->primary_image_path),
            'alt' => $product->name,
            'caption' => null,
        ];
    }

    foreach ($product->images as $img) {
        // Skip duplicate if same path as primary image
        if (!empty($img->image_path) && $img->image_path !== $product->primary_image_path) {
            $allImages[] = [
                'src' => asset('storage/' . $img->image_path),
                'alt' => $product->name . ' - ' . ($img->caption ?? __('Product Photo')),
                'caption' => $img->caption,
            ];
        }
    }

    $imageCount = count($allImages);
@endphp

<div
    class="flex flex-col"
    x-data="{
        activeIndex: 0,
        total: {{ $imageCount }},
        touchStartX: 0,
        touchEndX: 0,
        next() {
            if (this.total > 1) {
                this.activeIndex = (this.activeIndex + 1) % this.total;
            }
        },
        prev() {
            if (this.total > 1) {
                this.activeIndex = (this.activeIndex - 1 + this.total) % this.total;
            }
        },
        handleTouchStart(e) {
            this.touchStartX = e.changedTouches[0].screenX;
        },
        handleTouchEnd(e) {
            this.touchEndX = e.changedTouches[0].screenX;
            if (this.touchStartX - this.touchEndX > 50) {
                this.next();
            }
            if (this.touchEndX - this.touchStartX > 50) {
                this.prev();
            }
        }
    }"
    @keydown.left.window="if ($el.contains(document.activeElement)) prev()"
    @keydown.right.window="if ($el.contains(document.activeElement)) next()"
>
    {{-- Main Image Container --}}
    <div
        class="aspect-[4/3] bg-white border border-slate-200/90 rounded-2xl p-4 sm:p-6 overflow-hidden relative flex items-center justify-center shadow-xs"
        @touchstart="handleTouchStart($event)"
        @touchend="handleTouchEnd($event)"
    >
        @if ($imageCount > 0)
            @foreach ($allImages as $index => $img)
                <img
                    src="{{ $img['src'] }}"
                    alt="{{ $img['alt'] }}"
                    x-show="activeIndex === {{ $index }}"
                    x-cloak
                    class="w-full h-full object-contain object-center transition-all duration-300"
                    loading="{{ $index === 0 ? 'eager' : 'lazy' }}"
                    @if ($index === 0) fetchpriority="high" @endif
                >
            @endforeach

            {{-- Mobile / Overlay Navigation Controls when multiple images --}}
            @if ($imageCount > 1)
                {{-- Previous Button --}}
                <button
                    type="button"
                    @click="prev()"
                    class="absolute left-3 top-1/2 -translate-y-1/2 w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-white/90 hover:bg-white border border-slate-200 text-slate-700 hover:text-teal-700 shadow-md flex items-center justify-center transition-all focus-ring active:scale-95 z-10"
                    aria-label="{{ $currentLocale === 'en' ? 'Previous Photo' : 'Foto Sebelumnya' }}"
                >
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                    </svg>
                </button>

                {{-- Next Button --}}
                <button
                    type="button"
                    @click="next()"
                    class="absolute right-3 top-1/2 -translate-y-1/2 w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-white/90 hover:bg-white border border-slate-200 text-slate-700 hover:text-teal-700 shadow-md flex items-center justify-center transition-all focus-ring active:scale-95 z-10"
                    aria-label="{{ $currentLocale === 'en' ? 'Next Photo' : 'Foto Berikutnya' }}"
                >
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </button>

                {{-- Image Counter Badge --}}
                <div class="absolute bottom-3 right-3 px-2.5 py-1 rounded-full bg-slate-900/75 backdrop-blur-xs text-white text-[11px] font-semibold tracking-wider z-10">
                    <span x-text="activeIndex + 1"></span> / <span>{{ $imageCount }}</span>
                </div>
            @endif
        @else
            {{-- Professional Corporate SVG Fallback --}}
            <div class="flex flex-col items-center justify-center p-8 text-center text-slate-400">
                <svg class="w-16 h-16 text-slate-300 mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1.2" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0112 15a9.065 9.065 0 00-6.23-.693L5 14.5m14.8.8l1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0112 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.61L5 14.5" />
                </svg>
                <span class="text-sm font-bold text-slate-500 tracking-wider">PT ANS</span>
                <span class="text-xs text-slate-400 mt-1">{{ $currentLocale === 'en' ? 'Product image unavailable' : 'Foto produk belum tersedia' }}</span>
            </div>
        @endif
    </div>

    {{-- Thumbnail Strip (only when multiple images) --}}
    @if ($imageCount > 1)
        <div class="flex items-center gap-3 mt-4 overflow-x-auto pb-2 scrollbar-thin">
            @foreach ($allImages as $index => $img)
                <button
                    type="button"
                    @click="activeIndex = {{ $index }}"
                    class="w-16 h-16 sm:w-20 sm:h-20 rounded-xl border-2 overflow-hidden bg-white p-1 flex-shrink-0 transition-all focus-ring cursor-pointer"
                    :class="activeIndex === {{ $index }} ? 'border-teal-600 ring-2 ring-teal-600/30' : 'border-slate-200 hover:border-slate-300 opacity-70 hover:opacity-100'"
                    aria-label="{{ ($currentLocale === 'en' ? 'Select Photo ' : 'Pilih Foto ') . ($index + 1) }}"
                >
                    <img
                        src="{{ $img['src'] }}"
                        alt=""
                        class="w-full h-full object-contain"
                        loading="lazy"
                    >
                </button>
            @endforeach

            <div class="text-xs text-slate-400 font-medium hidden sm:block ml-auto">
                <span x-text="'{{ $currentLocale === 'en' ? 'Photo ' : 'Foto ' }}' + (activeIndex + 1) + '{{ $currentLocale === 'en' ? ' of ' : ' dari ' }}' + total"></span>
            </div>
        </div>
    @endif
</div>
