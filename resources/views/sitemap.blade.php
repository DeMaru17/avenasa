{!! '<'.'?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:xhtml="http://www.w3.org/1999/xhtml">
@foreach ($staticRoutes as $route)
    {{-- Indonesian Route --}}
    <url>
        <loc>{{ $route['id'] }}</loc>
        <xhtml:link rel="alternate" hreflang="id" href="{{ $route['id'] }}"/>
        <xhtml:link rel="alternate" hreflang="en" href="{{ $route['en'] }}"/>
        <xhtml:link rel="alternate" hreflang="x-default" href="{{ $route['id'] }}"/>
    </url>

    {{-- English Route --}}
    <url>
        <loc>{{ $route['en'] }}</loc>
        <xhtml:link rel="alternate" hreflang="en" href="{{ $route['en'] }}"/>
        <xhtml:link rel="alternate" hreflang="id" href="{{ $route['id'] }}"/>
        <xhtml:link rel="alternate" hreflang="x-default" href="{{ $route['id'] }}"/>
    </url>
@endforeach

@foreach ($products as $product)
    @if (!empty($product->slug_id))
        <url>
            <loc>{{ url('/id/products/' . $product->slug_id) }}</loc>
            <xhtml:link rel="alternate" hreflang="id" href="{{ url('/id/products/' . $product->slug_id) }}"/>
            @if (!empty($product->slug_en))
                <xhtml:link rel="alternate" hreflang="en" href="{{ url('/en/products/' . $product->slug_en) }}"/>
            @endif
            <xhtml:link rel="alternate" hreflang="x-default" href="{{ url('/id/products/' . $product->slug_id) }}"/>
            @if ($product->updated_at)
                <lastmod>{{ $product->updated_at->format('Y-m-d') }}</lastmod>
            @endif
        </url>
    @endif

    @if (!empty($product->slug_en))
        <url>
            <loc>{{ url('/en/products/' . $product->slug_en) }}</loc>
            <xhtml:link rel="alternate" hreflang="en" href="{{ url('/en/products/' . $product->slug_en) }}"/>
            @if (!empty($product->slug_id))
                <xhtml:link rel="alternate" hreflang="id" href="{{ url('/id/products/' . $product->slug_id) }}"/>
                <xhtml:link rel="alternate" hreflang="x-default" href="{{ url('/id/products/' . $product->slug_id) }}"/>
            @endif
            @if ($product->updated_at)
                <lastmod>{{ $product->updated_at->format('Y-m-d') }}</lastmod>
            @endif
        </url>
    @endif
@endforeach

@if (!empty($articles))
@foreach ($articles as $article)
    @if (!empty($article->slug_id))
        <url>
            <loc>{{ url('/id/articles/' . $article->slug_id) }}</loc>
            <xhtml:link rel="alternate" hreflang="id" href="{{ url('/id/articles/' . $article->slug_id) }}"/>
            @if (!empty($article->slug_en))
                <xhtml:link rel="alternate" hreflang="en" href="{{ url('/en/articles/' . $article->slug_en) }}"/>
                <xhtml:link rel="alternate" hreflang="x-default" href="{{ url('/en/articles/' . $article->slug_en) }}"/>
            @else
                <xhtml:link rel="alternate" hreflang="x-default" href="{{ url('/id/articles/' . $article->slug_id) }}"/>
            @endif
            @if ($article->updated_at)
                <lastmod>{{ $article->updated_at->format('Y-m-d') }}</lastmod>
            @endif
        </url>
    @endif

    @if (!empty($article->slug_en))
        <url>
            <loc>{{ url('/en/articles/' . $article->slug_en) }}</loc>
            <xhtml:link rel="alternate" hreflang="en" href="{{ url('/en/articles/' . $article->slug_en) }}"/>
            @if (!empty($article->slug_id))
                <xhtml:link rel="alternate" hreflang="id" href="{{ url('/id/articles/' . $article->slug_id) }}"/>
            @endif
            <xhtml:link rel="alternate" hreflang="x-default" href="{{ url('/en/articles/' . $article->slug_en) }}"/>
            @if ($article->updated_at)
                <lastmod>{{ $article->updated_at->format('Y-m-d') }}</lastmod>
            @endif
        </url>
    @endif
@endforeach
@endif
</urlset>
