@php
    $navItems = [
        ['route' => 'home', 'label' => __('Home'), 'active' => request()->routeIs('home')],
        ['route' => 'about', 'label' => __('About Us'), 'active' => request()->routeIs('about')],
        ['route' => 'products.index', 'label' => __('Products'), 'active' => request()->routeIs('products.*')],
        ['route' => 'partners-clients', 'label' => __('Partners & Clients'), 'active' => request()->routeIs('partners-clients')],
        ['route' => 'contact', 'label' => __('Contact'), 'active' => request()->routeIs('contact')],
    ];
@endphp

<header id="site-header" class="fixed top-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-sm transition-all duration-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 md:h-18">
            {{-- Brand Logo --}}
            <a href="{{ route('home') }}" class="flex-shrink-0 flex items-center gap-2.5 sm:gap-3 focus-ring rounded-lg py-1" aria-label="{{ __('Home') }} - PT Abhipraya Nawasena Sejahtera">
                <img src="{{ asset('images/logo-ans.png') }}" alt="ANS Logo" class="h-9 md:h-10 w-auto object-contain" width="40" height="40">
                <span class="text-xs sm:text-sm md:text-[15px] font-bold text-slate-900 tracking-tight whitespace-nowrap">
                    PT Abhipraya Nawasena Sejahtera
                </span>
            </a>

            {{-- Desktop Navigation --}}
            <nav class="hidden lg:flex items-center gap-1" aria-label="{{ __('Toggle navigation menu') }}">
                @foreach ($navItems as $item)
                    <a
                        href="{{ route($item['route']) }}"
                        class="px-3.5 py-2 rounded-md text-sm font-medium transition-colors focus-ring {{ $item['active'] ? 'text-teal-700 bg-teal-50 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}"
                        @if($item['active']) aria-current="page" @endif
                    >
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>

            {{-- Header Actions (Language Switcher + Contact CTA + Mobile Menu Button) --}}
            <div class="flex items-center gap-2 sm:gap-3">
                {{-- Language Switcher --}}
                <x-language-switcher />

                {{-- Contact CTA (Desktop) --}}
                <a
                    href="{{ route('contact') }}"
                    class="hidden lg:inline-flex items-center gap-2 bg-teal-700 hover:bg-teal-800 text-white text-sm font-semibold px-4 py-2 rounded-lg shadow-sm transition-colors focus-ring"
                >
                    {{ __('Contact Us') }}
                </a>

                {{-- Mobile Menu Hamburger Button --}}
                <button
                    type="button"
                    id="mobile-menu-toggle"
                    class="lg:hidden p-2 rounded-md text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition-colors focus-ring min-h-[44px] min-w-[44px] inline-flex items-center justify-center"
                    aria-controls="mobile-menu-drawer"
                    aria-expanded="false"
                    aria-label="{{ __('Toggle navigation menu') }}"
                >
                    {{-- Open Icon (Hamburger) --}}
                    <svg id="mobile-menu-open-icon" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                    {{-- Close Icon (X) --}}
                    <svg id="mobile-menu-close-icon" class="hidden w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile Navigation Drawer --}}
    <div id="mobile-menu-drawer" class="hidden lg:hidden border-t border-slate-200 bg-white shadow-lg">
        <nav class="px-4 py-3 space-y-1" aria-label="Mobile Navigation">
            @foreach ($navItems as $item)
                <a
                    href="{{ route($item['route']) }}"
                    class="block px-3.5 py-2.5 rounded-lg text-base font-medium transition-colors focus-ring {{ $item['active'] ? 'text-teal-700 bg-teal-50 font-semibold' : 'text-slate-700 hover:text-slate-900 hover:bg-slate-50' }}"
                    @if($item['active']) aria-current="page" @endif
                >
                    {{ $item['label'] }}
                </a>
            @endforeach

            <div class="pt-3 pb-2 border-t border-slate-100 mt-2">
                <a
                    href="{{ route('contact') }}"
                    class="w-full flex items-center justify-center gap-2 bg-teal-700 hover:bg-teal-800 text-white font-semibold px-4 py-3 rounded-lg shadow-sm transition-colors focus-ring text-base"
                >
                    {{ __('Contact Us') }}
                </a>
            </div>
        </nav>
    </div>
</header>
