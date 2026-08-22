@props(['profile' => null])

@php
    $currentLocale = app()->getLocale();
    $tagline = 'Empowering Science for a Prosperous Future';
    $aboutText = $profile?->about ?: ($currentLocale === 'en'
        ? 'PT Abhipraya Nawasena Sejahtera is a company that moving on marketer and distribution product life science for pharmacy industry, FnB, Biotechnology, cosmetic, service lab, research center, university & Hospitals.<br><br>We believe that science if directed with good intention, can do big power to bring our life towards for prosperous future.<br><br>Based on our experiences for more than 15 years, we growth because our commitment and dedication to our customers with best services, high quality product and professional technical support, as quick after sales service appropriate regulation.'
        : 'PT Abhipraya Nawasena Sejahtera adalah perusahaan yang bergerak di bidang pemasaran dan distribusi produk ilmu hayati (life science) untuk industri farmasi, makanan & minuman (FnB), bioteknologi, kosmetik, laboratorium uji, pusat penelitian, universitas, dan rumah sakit.<br><br>Kami meyakini bahwa sains jika diarahkan dengan niat baik dapat memberikan kekuatan besar untuk membawa kehidupan kita menuju masa depan yang sejahtera.<br><br>Berdasarkan pengalaman kami selama lebih dari 15 tahun, kami berkembang berkat komitmen dan dedikasi kepada pelanggan melalui layanan terbaik, produk berkualitas tinggi, dan dukungan teknis profesional, serta layanan purnajual yang cepat sesuai regulasi.');
@endphp

<section class="py-16 lg:py-24 bg-white border-b border-slate-100" aria-labelledby="about-company-heading">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-center">
            {{-- Left Column: Narrative Content --}}
            <div class="lg:col-span-7">
                <div class="text-teal-700 text-xs sm:text-sm font-bold tracking-widest uppercase mb-3">
                    {{ $currentLocale === 'en' ? 'Company Profile' : 'Profil Perusahaan' }}
                </div>

                <h2 id="about-company-heading" class="text-2xl sm:text-3xl lg:text-4xl font-bold text-slate-900 leading-tight tracking-tight mb-6">
                    {{ $tagline }}
                </h2>

                <div class="text-slate-600 text-base leading-relaxed space-y-4">
                    @foreach (explode('<br><br>', $aboutText) as $paragraph)
                        <p>{!! strip_tags($paragraph, '<strong><b><em><i><br>') !!}</p>
                    @endforeach
                </div>
            </div>

            {{-- Right Column: Corporate Identity Panel (Seamless & Clean) --}}
            <div class="lg:col-span-5 flex items-center justify-center">
                <div class="w-full text-center flex flex-col items-center justify-center py-4">
                    {{-- Logo (Large Focal Point) --}}
                    <div class="mb-6 flex items-center justify-center">
                        <img
                            src="{{ asset('images/logo-ans.png') }}"
                            alt="PT Abhipraya Nawasena Sejahtera"
                            class="h-28 sm:h-32 lg:h-36 w-auto object-contain"
                            width="140"
                            height="140"
                            loading="lazy"
                        >
                    </div>

                    {{-- Company Name --}}
                    <h3 class="text-lg sm:text-xl font-bold tracking-wider text-slate-900 uppercase">
                        PT Abhipraya Nawasena
                    </h3>
                    <p class="text-sm font-semibold tracking-widest text-teal-700 uppercase mt-0.5">
                        Sejahtera
                    </p>

                    {{-- Supporting Focus Areas --}}
                    <div class="mt-5 flex flex-wrap items-center justify-center gap-x-3 gap-y-1 text-xs font-semibold uppercase tracking-widest text-teal-700">
                        <span>Life Science</span>
                        <span class="text-slate-300" aria-hidden="true">•</span>
                        <span>Laboratory Solutions</span>
                        <span class="text-slate-300" aria-hidden="true">•</span>
                        <span>Diagnostics</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
