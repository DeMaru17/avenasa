@php
    $currentLocale = app()->getLocale();
@endphp

<div class="bg-white border border-slate-200/90 rounded-3xl p-6 sm:p-8 lg:p-10 shadow-sm">
    <h2 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight mb-6">
        {{ $currentLocale === 'en' ? 'Quotation Request Form' : 'Formulir Permintaan Penawaran' }}
    </h2>

    <form class="space-y-5" onsubmit="return false;" aria-disabled="true">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            {{-- Full Name --}}
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    {{ $currentLocale === 'en' ? 'Full Name' : 'Nama Lengkap' }} <span class="text-rose-500">*</span>
                </label>
                <input
                    type="text"
                    placeholder="{{ $currentLocale === 'en' ? 'Dr. John Doe' : 'Dr. Ahmad Prasetyo' }}"
                    class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:border-teal-500 focus:ring-1 focus:ring-teal-500 transition-colors"
                    readonly
                >
            </div>

            {{-- Email Address --}}
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    {{ $currentLocale === 'en' ? 'Email Address' : 'Alamat Email' }} <span class="text-rose-500">*</span>
                </label>
                <input
                    type="email"
                    placeholder="{{ $currentLocale === 'en' ? 'john@laboratory.com' : 'ahmad@laboratorium.co.id' }}"
                    class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:border-teal-500 focus:ring-1 focus:ring-teal-500 transition-colors"
                    readonly
                >
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            {{-- Phone / WhatsApp --}}
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    {{ $currentLocale === 'en' ? 'Phone / WhatsApp' : 'Telepon / WhatsApp' }} <span class="text-xs font-normal text-slate-400">({{ $currentLocale === 'en' ? 'optional' : 'opsional' }})</span>
                </label>
                <input
                    type="tel"
                    placeholder="0812-3456-7890"
                    class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:border-teal-500 focus:ring-1 focus:ring-teal-500 transition-colors"
                    readonly
                >
            </div>

            {{-- Company / Institution Name --}}
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    {{ $currentLocale === 'en' ? 'Company / Institution' : 'Nama Perusahaan / Institusi' }} <span class="text-xs font-normal text-slate-400">({{ $currentLocale === 'en' ? 'optional' : 'opsional' }})</span>
                </label>
                <input
                    type="text"
                    placeholder="{{ $currentLocale === 'en' ? 'National Research Institute' : 'RS Cipto Mangunkusumo' }}"
                    class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:border-teal-500 focus:ring-1 focus:ring-teal-500 transition-colors"
                    readonly
                >
            </div>
        </div>

        {{-- Subject --}}
        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                {{ $currentLocale === 'en' ? 'Request Subject' : 'Subjek Permintaan' }} <span class="text-rose-500">*</span>
            </label>
            <input
                type="text"
                placeholder="{{ $currentLocale === 'en' ? 'Quotation Request for Laboratory Instruments' : 'Permintaan Penawaran Harga Alat Uji Laboratorium' }}"
                class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:border-teal-500 focus:ring-1 focus:ring-teal-500 transition-colors"
                readonly
            >
        </div>

        {{-- Message / Details --}}
        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                {{ $currentLocale === 'en' ? 'Message / Requirement Details' : 'Pesan / Rincian Kebutuhan' }} <span class="text-rose-500">*</span>
            </label>
            <textarea
                rows="4"
                placeholder="{{ $currentLocale === 'en' ? 'Please describe your procurement requirements in detail...' : 'Jelaskan kebutuhan pengadaan Anda secara detail, termasuk jumlah unit, spesifikasi yang dibutuhkan, dan jadwal pengadaan yang diharapkan...' }}"
                class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:border-teal-500 focus:ring-1 focus:ring-teal-500 transition-colors resize-none"
                readonly
            ></textarea>
        </div>

        {{-- Submit Button --}}
        <div>
            <button
                type="button"
                class="w-full inline-flex items-center justify-center gap-2 bg-teal-700 hover:bg-teal-800 text-white font-bold py-3.5 px-6 rounded-xl shadow-sm transition-all focus-ring text-base active:scale-[0.99] cursor-pointer"
            >
                <span>{{ $currentLocale === 'en' ? 'Send Quotation Request' : 'Kirim Permintaan Penawaran' }}</span>
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                </svg>
            </button>
        </div>
    </form>
</div>
