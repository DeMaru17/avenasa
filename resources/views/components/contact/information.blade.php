@props(['profile' => null])

@php
    $currentLocale = app()->getLocale();

    $phone = $profile?->phone;
    $phoneClean = $phone ? preg_replace('/[^0-9+]/', '', $phone) : '';

    $whatsapp = $profile?->whatsapp;
    $whatsappClean = $whatsapp ? preg_replace('/[^0-9]/', '', $whatsapp) : '';
    if (str_starts_with($whatsappClean, '0')) {
        $whatsappClean = '62' . substr($whatsappClean, 1);
    }

    $email = $profile?->email;
    $address = $profile?->address;
@endphp

<div class="space-y-6">
    <h2 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
        {{ $currentLocale === 'en' ? 'Official Contact Information' : 'Informasi Kontak Resmi' }}
    </h2>

    <div class="space-y-5">
        {{-- Office Address --}}
        @if (!empty($address))
            <div class="flex items-start gap-4 p-4 sm:p-5 rounded-2xl bg-white border border-slate-200/80 hover:border-teal-300 transition-all duration-200 group">
                <div class="w-12 h-12 rounded-xl bg-teal-50 border border-teal-100 flex items-center justify-center flex-shrink-0 text-teal-700 group-hover:bg-teal-700 group-hover:text-white transition-colors duration-200" aria-hidden="true">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">
                        {{ $currentLocale === 'en' ? 'Office Address' : 'Alamat Kantor' }}
                    </h3>
                    <p class="text-sm sm:text-base text-slate-800 font-medium leading-relaxed">
                        {{ $address }}
                    </p>
                </div>
            </div>
        @endif

        {{-- Telephone --}}
        @if (!empty($phone))
            <div class="flex items-start gap-4 p-4 sm:p-5 rounded-2xl bg-white border border-slate-200/80 hover:border-teal-300 transition-all duration-200 group">
                <div class="w-12 h-12 rounded-xl bg-teal-50 border border-teal-100 flex items-center justify-center flex-shrink-0 text-teal-700 group-hover:bg-teal-700 group-hover:text-white transition-colors duration-200" aria-hidden="true">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">
                        {{ $currentLocale === 'en' ? 'Telephone' : 'Telepon' }}
                    </h3>
                    <a
                        href="tel:{{ $phoneClean }}"
                        class="text-sm sm:text-base font-semibold text-teal-700 hover:text-teal-800 transition-colors focus-ring rounded"
                    >
                        {{ $phone }}
                    </a>
                </div>
            </div>
        @endif

        {{-- WhatsApp --}}
        @if (!empty($whatsapp))
            <div class="flex items-start gap-4 p-4 sm:p-5 rounded-2xl bg-white border border-slate-200/80 hover:border-teal-300 transition-all duration-200 group">
                <div class="w-12 h-12 rounded-xl bg-teal-50 border border-teal-100 flex items-center justify-center flex-shrink-0 text-teal-700 group-hover:bg-teal-700 group-hover:text-white transition-colors duration-200" aria-hidden="true">
                    <svg class="w-6 h-6 text-emerald-600 group-hover:text-white transition-colors duration-200" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.699c.969.54 1.777.818 2.796.818 3.18 0 5.766-2.586 5.767-5.766.001-3.18-2.585-5.766-5.767-5.766zm0 10.362c-.886 0-1.611-.26-2.316-.678l-.165-.098-1.58.415.422-1.54-.108-.172c-.496-.79-1.026-1.57-1.026-2.523 0-2.536 2.064-4.6 4.601-4.6 2.537 0 4.601 2.064 4.6 4.6 0 2.536-2.064 4.6-4.601 4.6zm6.816-12.188c-1.803-1.805-4.2-2.798-6.755-2.799-5.26 0-9.54 4.28-9.542 9.54 0 1.68.438 3.32 1.272 4.764l-1.35 4.935 5.048-1.324c1.391.76 2.96 1.16 4.568 1.16h.004c5.259 0 9.539-4.28 9.541-9.541 0-2.551-.994-4.949-2.798-6.755z" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">
                        {{ $currentLocale === 'en' ? 'WhatsApp Direct' : 'WhatsApp' }}
                    </h3>
                    <a
                        href="https://wa.me/{{ $whatsappClean }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="text-sm sm:text-base font-semibold text-teal-700 hover:text-teal-800 transition-colors focus-ring rounded"
                    >
                        {{ $whatsapp }}
                    </a>
                </div>
            </div>
        @endif

        {{-- Email --}}
        @if (!empty($email))
            <div class="flex items-start gap-4 p-4 sm:p-5 rounded-2xl bg-white border border-slate-200/80 hover:border-teal-300 transition-all duration-200 group">
                <div class="w-12 h-12 rounded-xl bg-teal-50 border border-teal-100 flex items-center justify-center flex-shrink-0 text-teal-700 group-hover:bg-teal-700 group-hover:text-white transition-colors duration-200" aria-hidden="true">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">
                        {{ $currentLocale === 'en' ? 'Email Address' : 'Email' }}
                    </h3>
                    <a
                        href="mailto:{{ $email }}"
                        class="text-sm sm:text-base font-semibold text-teal-700 hover:text-teal-800 transition-colors focus-ring rounded break-all"
                    >
                        {{ $email }}
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
