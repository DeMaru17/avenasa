@props(['brands'])

@php
    $currentLocale = app()->getLocale();
@endphp

<section class="py-16 lg:py-24 bg-white border-b border-slate-100" id="principals-section" aria-labelledby="principals-heading">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Section Header --}}
        <div class="text-center max-w-3xl mx-auto mb-12 lg:mb-16">
            <div class="text-teal-700 text-xs sm:text-sm font-bold tracking-widest uppercase mb-3">
                {{ $currentLocale === 'en' ? 'Official Principals' : 'Prinsipal Resmi' }}
            </div>
            <h2 id="principals-heading" class="text-2xl sm:text-3xl lg:text-4xl font-bold text-slate-900 tracking-tight mb-3">
                {{ $currentLocale === 'en' ? 'Authorized Global Principals' : 'Distributor Resmi Terdaftar' }}
            </h2>
            <p class="text-slate-600 text-sm sm:text-base leading-relaxed max-w-2xl mx-auto">
                @if ($currentLocale === 'en')
                    {{ $brands->total() }} authorized principals across life sciences, laboratory instruments, and diagnostic fields.
                @else
                    {{ $brands->total() }} principal resmi dari berbagai bidang life science, laboratory, dan diagnostics.
                @endif
            </p>
        </div>

        @if ($brands->total() > 0)
            {{-- Responsive Grid: 12 Active Principals per page (3 columns x 4 rows on desktop) --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                @foreach ($brands as $brand)
                    <div class="bg-white border border-slate-200/90 rounded-2xl p-6 sm:p-8 flex flex-col justify-start hover:border-teal-300 hover:shadow-md transition-all duration-200 group">
                        {{-- Logo Box with Neutral Surface --}}
                        <div class="rounded-xl bg-slate-50 border border-slate-100 p-6 flex items-center justify-center min-h-[110px] mb-5">
                            @if (!empty($brand->logo_path))
                                <img
                                    src="{{ asset('storage/' . $brand->logo_path) }}"
                                    alt="Logo {{ $brand->name }}"
                                    class="max-h-12 w-auto object-contain transition-transform duration-200 group-hover:scale-105"
                                    loading="lazy"
                                >
                            @else
                                <span class="text-xl sm:text-2xl font-bold tracking-tight text-teal-800 text-center">
                                    {{ $brand->name }}
                                </span>
                            @endif
                        </div>

                        {{-- Brand Name --}}
                        <h3 class="text-lg font-bold text-slate-900 mb-2">
                            {{ $brand->name }}
                        </h3>

                        {{-- Localized Description if Available in Database --}}
                        @if (!empty($brand->description))
                            <p class="text-slate-600 text-sm leading-relaxed mb-5">
                                {{ $brand->description }}
                            </p>
                        @endif

                        {{-- View Products CTA (when brand has active products) --}}
                        @if (($brand->products_count ?? 0) > 0)
                            <div class="mt-auto pt-4 border-t border-slate-100">
                                <a
                                    href="{{ route('products.index', ['brand' => $brand->slug]) }}"
                                    class="inline-flex items-center gap-1.5 text-sm font-semibold text-teal-700 hover:text-teal-800 transition-colors focus-ring rounded group/link"
                                >
                                    <span>{{ $currentLocale === 'en' ? 'View Products' : 'Lihat Produk' }}</span>
                                    <svg class="w-4 h-4 transition-transform duration-200 group-hover/link:translate-x-1" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                    </svg>
                                </a>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            {{-- Pagination & Result Indicator --}}
            <div class="mt-10 lg:mt-12 pt-6 border-t border-slate-100">
                @if ($brands->hasPages())
                    {{ $brands->fragment('principals-section')->links('vendor.pagination.tailwind', ['itemName' => $currentLocale === 'en' ? 'principals' : 'principal']) }}
                @else
                    <div class="text-center sm:text-left text-sm text-slate-500 font-medium">
                        @if ($currentLocale === 'en')
                            Showing <span class="font-semibold text-slate-700">{{ $brands->firstItem() }}–{{ $brands->lastItem() }}</span> of <span class="font-semibold text-slate-700">{{ $brands->total() }}</span> principals
                        @else
                            Menampilkan <span class="font-semibold text-slate-700">{{ $brands->firstItem() }}–{{ $brands->lastItem() }}</span> dari <span class="font-semibold text-slate-700">{{ $brands->total() }}</span> principal
                        @endif
                    </div>
                @endif
            </div>
        @else
            {{-- Graceful Empty State --}}
            <div class="text-center py-12 px-4 rounded-2xl bg-slate-50 border border-slate-200/80 max-w-lg mx-auto">
                <p class="text-slate-500 text-sm font-medium">
                    {{ $currentLocale === 'en' ? 'No active principals available at the moment.' : 'Belum ada data principal resmi yang aktif saat ini.' }}
                </p>
            </div>
        @endif
    </div>
</section>
