@extends('layouts.public')

@php
    $currentLocale = app()->getLocale();
    $metaTitle = $article->title . ' | PT Abhipraya Nawasena Sejahtera';
    $metaDescription = !empty($article->excerpt)
        ? $article->excerpt
        : Str::limit(strip_tags($article->content ?? ''), 160);
    $coverUrl = !empty($article->cover_image_path) ? asset('storage/' . $article->cover_image_path) : null;
@endphp

@section('title', $metaTitle)
@section('meta_description', $metaDescription)
@section('og_type', 'article')

@if ($coverUrl)
    @section('og_image', $coverUrl)
@endif

@section('structured_data')
@php
    $articleSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'Article',
        'headline' => $article->title,
        'description' => $metaDescription,
        'datePublished' => $article->published_at ? $article->published_at->toIso8601String() : $article->created_at->toIso8601String(),
        'dateModified' => $article->updated_at ? $article->updated_at->toIso8601String() : $article->created_at->toIso8601String(),
        'inLanguage' => $currentLocale === 'en' ? 'en-US' : 'id-ID',
        'author' => [
            '@type' => 'Organization',
            'name' => 'PT Abhipraya Nawasena Sejahtera',
            'url' => url('/'),
        ],
        'publisher' => [
            '@type' => 'Organization',
            'name' => 'PT Abhipraya Nawasena Sejahtera',
            'logo' => [
                '@type' => 'ImageObject',
                'url' => asset('images/logo-ans.png'),
            ],
        ],
    ];

    if ($coverUrl) {
        $articleSchema['image'] = [$coverUrl];
    }

    $breadcrumbSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            [
                '@type' => 'ListItem',
                'position' => 1,
                'name' => $currentLocale === 'en' ? 'Home' : 'Beranda',
                'item' => url('/' . $currentLocale),
            ],
            [
                '@type' => 'ListItem',
                'position' => 2,
                'name' => $currentLocale === 'en' ? 'Articles' : 'Artikel',
                'item' => url('/' . $currentLocale . '/articles'),
            ],
            [
                '@type' => 'ListItem',
                'position' => 3,
                'name' => $article->title,
                'item' => url('/' . $currentLocale . '/articles/' . $article->slug),
            ],
        ],
    ];
@endphp
<script type="application/ld+json">
{!! json_encode($articleSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
<script type="application/ld+json">
{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endsection

@section('content')
<article class="py-8 sm:py-10 lg:py-14 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- 1. Hierarchical Breadcrumb --}}
        <nav class="flex items-center gap-2 text-xs sm:text-sm text-slate-500 mb-6 sm:mb-8" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="hover:text-teal-700 transition-colors">
                {{ $currentLocale === 'en' ? 'Home' : 'Beranda' }}
            </a>
            <span class="text-slate-300">/</span>
            <a href="{{ route('articles.index') }}" class="hover:text-teal-700 transition-colors">
                {{ $currentLocale === 'en' ? 'Articles' : 'Artikel' }}
            </a>
            <span class="text-slate-300">/</span>
            <span class="text-teal-700 font-medium truncate max-w-[200px] sm:max-w-xs" aria-current="page">
                {{ $article->title }}
            </span>
        </nav>

        {{-- 2. Article Header --}}
        <header class="mb-8 sm:mb-10">
            <div class="flex flex-wrap items-center gap-3 mb-4">
                <span class="inline-flex items-center px-3 py-1 rounded-md text-xs font-bold uppercase tracking-wider
                    @if($article->type === 'News') bg-teal-50 text-teal-800 border border-teal-200
                    @elseif($article->type === 'Event') bg-amber-50 text-amber-800 border border-amber-200
                    @elseif($article->type === 'Product Update') bg-emerald-50 text-emerald-800 border border-emerald-200
                    @elseif($article->type === 'Company Update') bg-sky-50 text-sky-800 border border-sky-200
                    @else bg-slate-100 text-slate-700 border border-slate-200
                    @endif
                ">
                    {{ $article->type }}
                </span>

                @if ($article->published_at)
                    <time datetime="{{ $article->published_at->toIso8601String() }}" class="text-xs sm:text-sm text-slate-500 font-medium">
                        {{ $article->published_at->translatedFormat('d F Y') }}
                    </time>
                @endif
            </div>

            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black text-slate-900 tracking-tight leading-tight mb-4">
                {{ $article->title }}
            </h1>

            @if (!empty($article->excerpt))
                <p class="text-base sm:text-lg text-slate-600 leading-relaxed font-normal">
                    {{ $article->excerpt }}
                </p>
            @endif
        </header>

        {{-- 3. Cover Image --}}
        @if ($coverUrl)
            <div class="mb-10 sm:mb-12 rounded-2xl overflow-hidden shadow-sm border border-slate-100 bg-slate-100 aspect-[16/9] max-h-[480px]">
                <img
                    src="{{ $coverUrl }}"
                    alt="{{ $article->title }}"
                    class="w-full h-full object-cover object-center"
                >
            </div>
        @endif

        {{-- 4. Main RichEditor Content --}}
        <div class="prose prose-slate lg:prose-lg max-w-none text-slate-800 leading-relaxed
            [&_h2]:text-2xl [&_h2]:font-bold [&_h2]:text-slate-900 [&_h2]:tracking-tight [&_h2]:mt-10 [&_h2]:mb-4
            [&_h3]:text-xl [&_h3]:font-bold [&_h3]:text-slate-900 [&_h3]:mt-8 [&_h3]:mb-3
            [&_p]:mb-6 [&_p]:text-slate-700 [&_p]:leading-relaxed
            [&_ul]:list-disc [&_ul]:pl-6 [&_ul]:mb-6 [&_ul]:space-y-2
            [&_ol]:list-decimal [&_ol]:pl-6 [&_ol]:mb-6 [&_ol]:space-y-2
            [&_blockquote]:border-l-4 [&_blockquote]:border-teal-600 [&_blockquote]:bg-slate-50 [&_blockquote]:p-4 [&_blockquote]:my-6 [&_blockquote]:italic [&_blockquote]:text-slate-700 [&_blockquote]:rounded-r-xl
            [&_a]:text-teal-700 [&_a]:underline [&_a]:underline-offset-2 [&_a]:hover:text-teal-900 [&_a]:transition-colors
            [&_img]:max-w-full [&_img]:h-auto [&_img]:rounded-2xl [&_img]:shadow-xs [&_img]:my-8 [&_img]:mx-auto [&_img]:block
            [&_table]:w-full [&_table]:my-6 [&_table]:border-collapse [&_table]:border [&_table]:border-slate-200
            [&_th]:bg-slate-100 [&_th]:p-3 [&_th]:text-left [&_th]:font-semibold [&_th]:border [&_th]:border-slate-200
            [&_td]:p-3 [&_td]:border [&_td]:border-slate-200
        ">
            {!! $article->content !!}
        </div>

        {{-- 5. Related Products Showcase --}}
        @if ($article->relatedProducts->isNotEmpty())
            <section class="mt-14 sm:mt-16 pt-10 border-t border-slate-200" aria-labelledby="related-products-heading">
                <div class="flex items-center justify-between gap-4 mb-6">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-teal-700 block mb-1">
                            {{ $currentLocale === 'en' ? 'ANS Product Catalog' : 'Katalog Produk ANS' }}
                        </span>
                        <h2 id="related-products-heading" class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                            {{ $currentLocale === 'en' ? 'Related Products' : 'Produk Terkait' }}
                        </h2>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($article->relatedProducts as $product)
                        <x-products.card :product="$product" />
                    @endforeach
                </div>
            </section>
        @endif

        {{-- 6. Bottom Navigation Bar --}}
        <div class="mt-12 pt-8 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
            <a
                href="{{ route('articles.index') }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 hover:text-teal-700 font-semibold text-sm shadow-xs transition-all focus-ring"
            >
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                <span>{{ $currentLocale === 'en' ? 'Back to All Articles' : 'Kembali ke Semua Artikel' }}</span>
            </a>

            <a
                href="{{ route('contact') }}"
                class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-teal-700 hover:bg-teal-800 text-white font-bold text-sm shadow-xs transition-all focus-ring"
            >
                <span>{{ $currentLocale === 'en' ? 'Inquire with ANS' : 'Hubungi Tim ANS' }}</span>
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                </svg>
            </a>
        </div>
    </div>
</article>
@endsection
