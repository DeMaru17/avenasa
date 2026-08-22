@props(['profile' => null])

@php
    $currentLocale = app()->getLocale();
    $visionText = $profile?->vision ?: ($currentLocale === 'en'
        ? 'To be a driving force of life science and industrial advancement for a prosperous future.'
        : 'Menjadi motor penggerak kemajuan ilmu hayati (life science) dan industri untuk masa depan yang sejahtera.');
    $missionRaw = $profile?->mission ?: ($currentLocale === 'en'
        ? "Realizing integrated laboratory innovation to support science, industry and environment progress.\nProviding responsible and continues solution that creating real benefits for society and the earth.\nBuiding strategic cooperation with customer, business partner and principal for the growth.\nIncreasing life value trough science, technology and professional services."
        : "Mewujudkan inovasi laboratorium terpadu untuk mendukung kemajuan sains, industri, dan lingkungan.\nMenyediakan solusi yang bertanggung jawab dan berkelanjutan yang menciptakan manfaat nyata bagi masyarakat dan bumi.\nMembangun kerja sama strategis dengan pelanggan, mitra bisnis, dan prinsipal untuk pertumbuhan bersama.\nMeningkatkan nilai kehidupan melalui sains, teknologi, dan layanan profesional.");

    $missionPoints = array_values(array_filter(
        array_map('trim', preg_split('/\r\n|\r|\n/', $missionRaw)),
        fn ($line) => !empty($line)
    ));
@endphp

<section class="py-16 lg:py-24 bg-slate-50 border-b border-slate-100" aria-labelledby="vision-mission-heading">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Section Header --}}
        <div class="text-center max-w-3xl mx-auto mb-12 lg:mb-16">
            <div class="text-teal-700 text-xs sm:text-sm font-bold tracking-widest uppercase mb-3">
                {{ $currentLocale === 'en' ? 'Vision & Mission' : 'Visi & Misi' }}
            </div>
            <h2 id="vision-mission-heading" class="text-2xl sm:text-3xl lg:text-4xl font-bold text-slate-900 tracking-tight">
                {{ $currentLocale === 'en' ? 'ANS Direction & Purpose' : 'Arah & Tujuan ANS' }}
            </h2>
        </div>

        {{-- 2-Column Balanced Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-stretch">
            {{-- Left Column: Vision Card --}}
            <div class="bg-teal-700 rounded-2xl p-8 lg:p-10 text-white shadow-sm flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-xl bg-white/15 border border-white/20 flex items-center justify-center mb-6 text-white" aria-hidden="true">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                        </svg>
                    </div>

                    <h3 class="text-sm font-bold text-teal-200 uppercase tracking-widest mb-4">
                        {{ $currentLocale === 'en' ? 'Vision' : 'Visi' }}
                    </h3>

                    <blockquote class="text-teal-50 text-lg sm:text-xl leading-relaxed font-light italic">
                        &ldquo;{{ $visionText }}&rdquo;
                    </blockquote>
                </div>
            </div>

            {{-- Right Column: Mission Card --}}
            <div class="bg-white border border-slate-200/90 rounded-2xl p-8 lg:p-10 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-xl bg-teal-50 border border-teal-200 flex items-center justify-center mb-6 text-teal-700" aria-hidden="true">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                    </div>

                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-widest mb-4">
                        {{ $currentLocale === 'en' ? 'Mission' : 'Misi' }}
                    </h3>

                    <ul class="space-y-3.5" role="list">
                        @foreach ($missionPoints as $point)
                            <li class="flex items-start gap-3.5">
                                <div class="w-5 h-5 rounded-full bg-teal-50 border border-teal-200 flex items-center justify-center shrink-0 mt-0.5" aria-hidden="true">
                                    <div class="w-2 h-2 rounded-full bg-teal-700"></div>
                                </div>
                                <span class="text-slate-600 text-sm sm:text-base leading-relaxed">
                                    {{ $point }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
