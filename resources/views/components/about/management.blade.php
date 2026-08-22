@props(['managements' => collect()])

@php
    $currentLocale = app()->getLocale();
@endphp

@if ($managements->isNotEmpty())
    <section class="py-16 lg:py-24 bg-slate-50 border-b border-slate-100" aria-labelledby="management-heading">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Section Header --}}
            <div class="text-center max-w-3xl mx-auto mb-12 lg:mb-16">
                <div class="text-teal-700 text-xs sm:text-sm font-bold tracking-widest uppercase mb-3">
                    {{ $currentLocale === 'en' ? 'Management & Leadership' : 'Manajemen & Pendiri' }}
                </div>
                <h2 id="management-heading" class="text-2xl sm:text-3xl lg:text-4xl font-bold text-slate-900 tracking-tight mb-3">
                    {{ $currentLocale === 'en' ? 'Leaders Behind ANS Commitment' : 'Pemimpin di Balik Komitmen ANS' }}
                </h2>
                <p class="text-slate-600 text-sm sm:text-base leading-relaxed max-w-2xl mx-auto">
                    {{ $currentLocale === 'en'
                        ? 'Dedicated leadership steering scientific excellence, trusted distribution, and sustainable growth.'
                        : 'Dedikasi kepemimpinan yang mengarahkan keunggulan saintifik, distribusi terpercaya, dan pertumbuhan berkelanjutan.' }}
                </p>
            </div>

            {{-- Responsive Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($managements as $person)
                    <div class="bg-white border border-slate-200/90 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-shadow flex flex-col">
                        {{-- Photo or Neutral Fallback Container --}}
                        <div class="aspect-[4/5] w-full bg-slate-100 relative overflow-hidden flex items-center justify-center">
                            @if (!empty($person->photo_path))
                                <img
                                    src="{{ asset('storage/' . $person->photo_path) }}"
                                    alt="{{ $person->name }}"
                                    class="w-full h-full object-cover object-top"
                                    loading="lazy"
                                >
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center bg-slate-100 text-slate-400 p-6" aria-hidden="true">
                                    <svg class="w-24 h-24 text-slate-300" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </div>
                            @endif
                        </div>

                        {{-- Details --}}
                        <div class="p-6 flex-1 flex flex-col">
                            <h3 class="text-lg sm:text-xl font-bold text-slate-900">
                                {{ $person->name }}
                            </h3>
                            <p class="text-sm font-semibold text-teal-700 mt-1">
                                {{ $person->position }}
                            </p>

                            @if (!empty($person->bio))
                                <div class="text-slate-600 text-xs sm:text-sm leading-relaxed mt-3 pt-3 border-t border-slate-100 space-y-1.5 flex-1">
                                    @foreach (explode("\n", $person->bio) as $line)
                                        @if (trim($line) !== '')
                                            <p>{{ trim($line) }}</p>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
