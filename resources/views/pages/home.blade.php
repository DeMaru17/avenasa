@extends('layouts.public')

@section('title', __('Home'))
@section('meta_description', $companyProfile?->tagline ?: ($companyProfile?->about ?: 'PT Abhipraya Nawasena Sejahtera'))

@section('content')
    {{-- Single CMS-Driven Hero Banner Component --}}
    <x-hero-banner :hero="$hero" />
@endsection
