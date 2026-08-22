@props(['values' => collect()])

@php
    $currentLocale = app()->getLocale();
@endphp

<section class="py-16 lg:py-24 bg-white border-b border-slate-100" aria-labelledby="core-values-heading">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Section Header --}}
        <div class="text-center max-w-3xl mx-auto mb-12 lg:mb-16">
            <div class="text-teal-700 text-xs sm:text-sm font-bold tracking-widest uppercase mb-3">
                {{ $currentLocale === 'en' ? 'Core Values' : 'Nilai Inti' }}
            </div>
            <h2 id="core-values-heading" class="text-2xl sm:text-3xl lg:text-4xl font-bold text-slate-900 tracking-tight mb-3">
                {{ $currentLocale === 'en' ? '6 ANS Core Values' : '6 Nilai Inti ANS' }}
            </h2>
            <p class="text-slate-600 text-sm sm:text-base leading-relaxed max-w-2xl mx-auto">
                {{ $currentLocale === 'en'
                    ? 'Six fundamental values that guide every decision, interaction, and innovation at ANS.'
                    : 'Enam nilai fundamental yang memandu setiap keputusan, interaksi, dan inovasi di ANS.' }}
            </p>
        </div>

        {{-- 3-Column Desktop, 2-Column Tablet, 1-Column Mobile Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
            @foreach ($values as $value)
                @php
                    $iconIdentifier = $value->icon_name ?: 'sparkles';
                    try {
                        $iconSvg = svg("heroicon-o-{$iconIdentifier}", 'w-6 h-6 transition-colors duration-200')->toHtml();
                    } catch (\Throwable) {
                        $iconSvg = '<svg class="w-6 h-6 transition-colors duration-200" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>';
                    }
                @endphp
                <div class="bg-white border border-slate-200/90 rounded-2xl p-7 lg:p-8 hover:border-teal-300 hover:shadow-md transition-all duration-200 group flex flex-col justify-start">
                    <div class="w-12 h-12 rounded-xl bg-teal-50 border border-teal-200 flex items-center justify-center mb-5 text-teal-700 group-hover:bg-teal-700 group-hover:text-white transition-all duration-200" aria-hidden="true">
                        {!! $iconSvg !!}
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2.5">
                        {{ $value->title }}
                    </h3>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        {{ $value->description }}
                    </p>
                </div>
            @endforeach
        </div>
    </div>
</section>
