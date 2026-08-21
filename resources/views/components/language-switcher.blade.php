@props([
    'class' => '',
])

@php
    $currentLocale = app()->getLocale();
    $localizationService = app(\App\Services\LocalizationService::class);
@endphp

<div class="inline-flex items-center bg-slate-100 rounded-full p-1 gap-0.5 {{ $class }}" role="group" aria-label="{{ __('Select Language') }}">
    <a
        href="{{ $localizationService->getSwitchUrl('id') }}"
        class="px-3 py-1 rounded-full text-xs font-semibold transition-all focus-ring min-h-[32px] sm:min-h-[28px] inline-flex items-center justify-center {{ $currentLocale === 'id' ? 'bg-teal-700 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-200/60' }}"
        aria-label="{{ __('Switch language to Indonesian') }}"
        @if($currentLocale === 'id') aria-current="true" @endif
    >
        ID
    </a>
    <a
        href="{{ $localizationService->getSwitchUrl('en') }}"
        class="px-3 py-1 rounded-full text-xs font-semibold transition-all focus-ring min-h-[32px] sm:min-h-[28px] inline-flex items-center justify-center {{ $currentLocale === 'en' ? 'bg-teal-700 text-white shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-200/60' }}"
        aria-label="{{ __('Switch language to English') }}"
        @if($currentLocale === 'en') aria-current="true" @endif
    >
        EN
    </a>
</div>
