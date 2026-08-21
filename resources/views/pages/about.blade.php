@extends('layouts.public')

@section('title', __('About Us'))
@section('meta_description', __('Tentang PT Abhipraya Nawasena Sejahtera - Sejarah, komitmen mutu, visi misi, dan nilai inti perusahaan.'))

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
    <div class="max-w-3xl mx-auto">
        <h1 class="text-3xl sm:text-4xl font-bold text-slate-900 tracking-tight">
            {{ __('About Us') }}
        </h1>
        <div class="mt-6 prose prose-slate max-w-none text-slate-600 leading-relaxed space-y-4">
            <p>
                {{ __('Distributor resmi peralatan kesehatan, diagnostik, dan laboratorium terkemuka di Indonesia. Melayani dengan standar mutu internasional sejak lebih dari 15 tahun.') }}
            </p>
        </div>
    </div>
</div>
@endsection
