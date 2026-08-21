@extends('layouts.public')

@section('title', __('Page Not Found'))
@section('meta_description', __('The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.'))

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28 text-center">
    <div class="max-w-md mx-auto">
        <span class="text-6xl sm:text-7xl font-extrabold text-teal-700">404</span>
        <h1 class="mt-4 text-2xl sm:text-3xl font-bold text-slate-900">
            {{ __('Page Not Found') }}
        </h1>
        <p class="mt-3 text-sm sm:text-base text-slate-600 leading-relaxed">
            {{ __('The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.') }}
        </p>
        <div class="mt-8">
            <a href="{{ route('home') }}" class="inline-flex items-center justify-center bg-teal-700 hover:bg-teal-800 text-white font-semibold px-6 py-3 rounded-lg shadow-sm transition-colors focus-ring">
                {{ __('Back to Home') }}
            </a>
        </div>
    </div>
</div>
@endsection
