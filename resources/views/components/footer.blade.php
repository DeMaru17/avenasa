@php
    $profile = $companyProfile ?? null;
    $address = $profile?->address ?? 'Mensana Tower Lt. 15, Jl. Raya Kranggan Kav. 1, Cibubur, Bekasi, Jawa Barat 17433';
    $phone = $profile?->phone ?? '(021) 39722772';
    $whatsapp = $profile?->whatsapp ?? '0822-614-614-00';
    $whatsappClean = preg_replace('/[^0-9]/', '', $whatsapp);
    if (str_starts_with($whatsappClean, '0')) {
        $whatsappClean = '62' . substr($whatsappClean, 1);
    }
    $email = $profile?->email ?? 'admin@avenasa.co.id';
    $principalsList = $principals ?? collect();
@endphp

<footer class="bg-slate-900 text-white border-t border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 lg:py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 lg:gap-12">
            {{-- Col 1: Brand & Profile Summary --}}
            <div class="space-y-4">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/logo-ans.png') }}" alt="ANS" class="h-10 w-auto brightness-0 invert object-contain" width="40" height="40">
                    <div>
                        <div class="text-[10px] font-semibold tracking-widest text-teal-400 uppercase" style="letter-spacing: 0.15em">PT Abhipraya Nawasena</div>
                        <div class="text-[14px] font-semibold text-white tracking-tight">Sejahtera</div>
                    </div>
                </div>
                <p class="text-slate-400 text-sm leading-relaxed font-semibold">
                    {{ $profile?->tagline_en ?? 'Empowering Science for a Prosperous Future' }}
                </p>
            </div>

            {{-- Col 2: Quick Links --}}
            <div>
                <h3 class="text-sm font-semibold text-white uppercase tracking-wider mb-4 text-teal-400">{{ __('Quick Links') }}</h3>
                <ul class="space-y-2.5">
                    <li>
                        <a href="{{ route('home') }}" class="text-slate-400 hover:text-teal-400 text-sm transition-colors focus-ring rounded">
                            {{ __('Home') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('about') }}" class="text-slate-400 hover:text-teal-400 text-sm transition-colors focus-ring rounded">
                            {{ __('About Us') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('products.index') }}" class="text-slate-400 hover:text-teal-400 text-sm transition-colors focus-ring rounded">
                            {{ __('Product Catalog') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('partners-clients') }}" class="text-slate-400 hover:text-teal-400 text-sm transition-colors focus-ring rounded">
                            {{ __('Partners & Clients') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('contact') }}" class="text-slate-400 hover:text-teal-400 text-sm transition-colors focus-ring rounded">
                            {{ __('Contact') }}
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Col 3: Contact Information --}}
            <div>
                <h3 class="text-sm font-semibold text-white uppercase tracking-wider mb-4 text-teal-400">{{ __('Contact Information') }}</h3>
                <ul class="space-y-3">
                    <li class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-teal-400 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                        </svg>
                        <span class="text-slate-400 text-sm leading-relaxed">{{ $address }}</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-teal-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                        </svg>
                        <a href="tel:{{ preg_replace('/[^0-9]/', '', $phone) }}" class="text-slate-400 hover:text-teal-400 text-sm transition-colors focus-ring rounded">
                            {{ $phone }}
                        </a>
                    </li>
                    <li class="flex items-center gap-3">
                        <x-icons.whatsapp class="w-5 h-5 text-teal-400 flex-shrink-0" />
                        <a href="https://wa.me/{{ $whatsappClean }}" target="_blank" rel="noopener noreferrer" class="text-slate-400 hover:text-teal-400 text-sm transition-colors focus-ring rounded">
                            {{ $whatsapp }}
                        </a>
                    </li>
                    <li class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-teal-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                        </svg>
                        <a href="mailto:{{ $email }}" class="text-slate-400 hover:text-teal-400 text-sm transition-colors focus-ring rounded">
                            {{ $email }}
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Col 4: Official Principals & CTA --}}
            <div>
                <h3 class="text-sm font-semibold text-white uppercase tracking-wider mb-4 text-teal-400">{{ __('Official Principals') }}</h3>
                <div class="space-y-2 mb-6">
                    @if ($principalsList->isNotEmpty())
                        @foreach ($principalsList as $principal)
                            <div class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-teal-400 flex-shrink-0"></span>
                                <span class="text-slate-400 text-sm font-medium">{{ $principal->name }}</span>
                            </div>
                        @endforeach
                    @else
                        <div class="flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-teal-400 flex-shrink-0"></span>
                            <span class="text-slate-400 text-sm font-medium">Merck</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-teal-400 flex-shrink-0"></span>
                            <span class="text-slate-400 text-sm font-medium">Neogen</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-teal-400 flex-shrink-0"></span>
                            <span class="text-slate-400 text-sm font-medium">ERA Biology</span>
                        </div>
                    @endif
                </div>

                <div class="pt-4 border-t border-slate-800">
                    <a
                        href="{{ route('contact') }}"
                        class="inline-flex items-center justify-center gap-2 bg-amber-600 hover:bg-amber-700 text-white text-sm font-semibold px-4 py-2.5 rounded-lg shadow-sm transition-colors focus-ring w-full sm:w-auto"
                    >
                        {{ __('Request Quote') }}
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        {{-- Bottom Copyright Bar --}}
        <div class="mt-12 pt-8 border-t border-slate-800 text-center sm:flex sm:justify-between sm:items-center text-xs text-slate-500">
            <p>© {{ date('Y') }} PT Abhipraya Nawasena Sejahtera. {{ __('All rights reserved.') }}</p>
            <p class="mt-2 sm:mt-0">{{ __('Official Distributor of Leading Laboratory & Medical Equipment') }}</p>
        </div>
    </div>
</footer>
