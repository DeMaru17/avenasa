@props(['article'])

@php
    $currentLocale = app()->getLocale();
    $detailUrl = route('articles.show', ['slug' => $article->slug]);
    $dateFormatted = $article->published_at ? $article->published_at->translatedFormat('d F Y') : null;
@endphp

<article class="bg-white border border-slate-200/90 rounded-2xl overflow-hidden shadow-sm hover:shadow-md hover:border-teal-400/80 transition-all duration-200 flex flex-col h-full group focus-within:ring-2 focus-within:ring-teal-600">
    {{-- Cover Image / Fallback Container --}}
    <a href="{{ $detailUrl }}" class="aspect-[16/9] bg-slate-100 overflow-hidden relative flex items-center justify-center block flex-shrink-0" tabindex="-1" aria-hidden="true">
        @if (!empty($article->cover_image_path))
            <img
                src="{{ asset('storage/' . $article->cover_image_path) }}"
                alt="{{ $article->title }}"
                class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-300"
                loading="lazy"
            >
        @else
            {{-- Professional Fallback Placeholder --}}
            <div class="flex flex-col items-center justify-center p-6 text-center text-slate-400">
                <svg class="w-12 h-12 text-slate-300 mb-2 group-hover:text-teal-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke-width="1.2" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25M16.5 7.5V18a2.25 2.25 0 002.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 002.25 2.25h13.5M6 7.5h3v3H6v-3z" />
                </svg>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">PT ANS</span>
            </div>
        @endif

        {{-- Type Badge Floating on Top-Left --}}
        <div class="absolute top-3 left-3">
            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold shadow-xs backdrop-blur-xs
                @if($article->type === 'News') bg-teal-800/90 text-white
                @elseif($article->type === 'Event') bg-amber-700/90 text-white
                @elseif($article->type === 'Product Update') bg-emerald-800/90 text-white
                @elseif($article->type === 'Company Update') bg-sky-800/90 text-white
                @else bg-slate-800/90 text-white
                @endif
            ">
                {{ $article->type }}
            </span>
        </div>
    </a>

    {{-- Card Body --}}
    <div class="p-5 flex flex-col flex-1 justify-between">
        <div>
            {{-- Published Date --}}
            @if ($dateFormatted)
                <div class="flex items-center gap-1.5 text-xs text-slate-500 mb-2">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h14.25A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 9v7.5" />
                    </svg>
                    <span>{{ $dateFormatted }}</span>
                </div>
            @endif

            {{-- Title --}}
            <h3 class="text-base font-bold text-slate-900 group-hover:text-teal-700 transition-colors leading-snug line-clamp-2 mb-2">
                <a href="{{ $detailUrl }}" class="focus:outline-none">
                    {{ $article->title }}
                </a>
            </h3>

            {{-- Excerpt --}}
            @if (!empty($article->excerpt))
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed line-clamp-2 mb-4">
                    {{ $article->excerpt }}
                </p>
            @endif
        </div>

        {{-- Card Footer CTA --}}
        <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-teal-700">
            <a
                href="{{ $detailUrl }}"
                class="inline-flex items-center gap-1 hover:text-teal-800 transition-colors focus-ring rounded"
            >
                <span>{{ $currentLocale === 'en' ? 'Read More' : 'Baca Selengkapnya' }}</span>
                <svg class="w-3.5 h-3.5 group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                </svg>
            </a>
        </div>
    </div>
</article>
