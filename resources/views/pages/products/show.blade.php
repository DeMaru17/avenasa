@extends('layouts.public')

@section('title', $product->name)
@section('meta_description', $product->summary ?? $product->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
    <div class="mb-6">
        <a href="{{ route('products.index') }}" class="text-sm font-medium text-teal-700 hover:text-teal-800 focus-ring rounded">
            &larr; {{ __('Back to Product Catalog') }}
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-slate-900">
                {{ $product->name }}
            </h1>
            @if ($product->category)
                <p class="text-sm font-semibold text-teal-700 mt-1">
                    {{ $product->category->name }}
                </p>
            @endif
            @if ($product->summary)
                <p class="text-slate-600 mt-4 leading-relaxed">
                    {{ $product->summary }}
                </p>
            @endif
        </div>
    </div>
</div>
@endsection
