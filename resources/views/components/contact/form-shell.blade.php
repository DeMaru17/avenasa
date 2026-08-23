@props([
    'requestedProduct' => null,
    'defaultSubject' => '',
])

@php
    $currentLocale = app()->getLocale();
@endphp

<div class="bg-white border border-slate-200/90 rounded-3xl p-6 sm:p-8 lg:p-10 shadow-sm" id="quotation-form-card">
    <h2 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight mb-2">
        {{ $currentLocale === 'en' ? 'Quotation Request Form' : 'Formulir Permintaan Penawaran' }}
    </h2>
    <p class="text-sm text-slate-500 mb-6 leading-relaxed">
        {{ $currentLocale === 'en'
            ? 'Submit your inquiry and our technical sales team will assist you with tailored specifications and official quotation letters.'
            : 'Sampaikan kebutuhan pengadaan Anda dan tim sales teknis kami akan membantu memberikan rincian spesifikasi serta surat penawaran harga resmi.' }}
    </p>

    {{-- Success Feedback Alert --}}
    @if (session('success'))
        <div class="mb-6 p-4 sm:p-5 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 flex items-start gap-3.5 shadow-2xs" role="alert" tabindex="-1">
            <svg class="w-5 h-5 text-emerald-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div class="text-sm leading-relaxed font-medium">
                {{ session('success') }}
            </div>
        </div>
    @endif

    {{-- GA4 Conversion Event Trigger (Post-Success Only, Non-PII) --}}
    @if (session('ga4_event'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                if (window.ANSAnalytics) {
                    window.ANSAnalytics.trackSubmitQuotation({
                        hasCompany: {{ session('ga4_event.has_company') ? 'true' : 'false' }},
                        source: '{{ session('ga4_event.source') }}',
                        locale: '{{ session('ga4_event.locale') }}',
                        productId: {{ !empty(session('ga4_event.product_id')) ? session('ga4_event.product_id') : 'null' }}
                    });
                }
            });
        </script>
    @endif

    {{-- Start Quotation Funnel Tracker (Direct Entry & Deduplication) --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.querySelector('#quotation-form-card form');
            if (form) {
                form.addEventListener('focusin', () => {
                    if (window.ANSAnalytics) {
                        window.ANSAnalytics.trackStartQuotation({
                            source: '{{ $requestedProduct ? 'product_detail' : 'contact_page' }}',
                            locale: '{{ $currentLocale }}',
                            productId: {{ $requestedProduct ? $requestedProduct->id : 'null' }}
                        });
                    }
                }, { once: true });
            }
        });
    </script>

    {{-- Product Context Banner (when accessed via Product Detail CTA) --}}
    @if ($requestedProduct)
        <div class="mb-6 p-4 rounded-2xl bg-teal-50/70 border border-teal-200/80 flex items-center justify-between gap-4">
            <div class="min-w-0">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-teal-100/80 text-teal-800 mb-1">
                    {{ $currentLocale === 'en' ? 'Requested Product' : 'Produk yang Diminta' }}
                </span>
                <div class="text-sm sm:text-base font-bold text-slate-900 truncate">
                    {{ $currentLocale === 'en' && !empty($requestedProduct->name_en) ? $requestedProduct->name_en : $requestedProduct->name_id }}
                </div>
            </div>
            @if ($requestedProduct->brand)
                <span class="text-xs font-semibold text-slate-500 bg-white border border-slate-200 px-2.5 py-1 rounded-lg flex-shrink-0">
                    {{ $requestedProduct->brand->name }}
                </span>
            @endif
        </div>
    @endif

    {{-- Active Quotation Submission Form --}}
    <form
        method="POST"
        action="{{ route('contact.store', ['locale' => $currentLocale]) }}"
        class="space-y-5"
        x-data="{ submitting: false }"
        @submit="submitting = true"
    >
        @csrf

        {{-- Hidden Honeypot Anti-Spam Field --}}
        <div class="hidden" aria-hidden="true">
            <input
                type="text"
                name="website_url_hp"
                id="website_url_hp"
                value=""
                tabindex="-1"
                autocomplete="off"
            >
        </div>

        {{-- Hidden Product ID (if contextual) --}}
        @if ($requestedProduct)
            <input type="hidden" name="product_id" value="{{ $requestedProduct->id }}">
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            {{-- Full Name --}}
            <div>
                <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    {{ $currentLocale === 'en' ? 'Full Name' : 'Nama Lengkap' }} <span class="text-rose-500">*</span>
                </label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="{{ $currentLocale === 'en' ? 'Dr. John Doe' : 'Dr. Ahmad Prasetyo' }}"
                    required
                    maxlength="255"
                    class="w-full rounded-xl border {{ $errors->has('name') ? 'border-rose-300 ring-1 ring-rose-300' : 'border-slate-200' }} px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:border-teal-500 focus:ring-1 focus:ring-teal-500 transition-colors"
                    aria-describedby="{{ $errors->has('name') ? 'name-error' : '' }}"
                >
                @error('name')
                    <p id="name-error" class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email Address --}}
            <div>
                <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    {{ $currentLocale === 'en' ? 'Email Address' : 'Alamat Email' }} <span class="text-rose-500">*</span>
                </label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="{{ $currentLocale === 'en' ? 'john@laboratory.com' : 'ahmad@laboratorium.co.id' }}"
                    required
                    maxlength="255"
                    class="w-full rounded-xl border {{ $errors->has('email') ? 'border-rose-300 ring-1 ring-rose-300' : 'border-slate-200' }} px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:border-teal-500 focus:ring-1 focus:ring-teal-500 transition-colors"
                    aria-describedby="{{ $errors->has('email') ? 'email-error' : '' }}"
                >
                @error('email')
                    <p id="email-error" class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            {{-- Phone / WhatsApp --}}
            <div>
                <label for="phone" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    {{ $currentLocale === 'en' ? 'Phone / WhatsApp' : 'Telepon / WhatsApp' }} <span class="text-xs font-normal text-slate-400">({{ $currentLocale === 'en' ? 'optional' : 'opsional' }})</span>
                </label>
                <input
                    type="tel"
                    id="phone"
                    name="phone"
                    value="{{ old('phone') }}"
                    placeholder="0812-3456-7890"
                    maxlength="50"
                    class="w-full rounded-xl border {{ $errors->has('phone') ? 'border-rose-300 ring-1 ring-rose-300' : 'border-slate-200' }} px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:border-teal-500 focus:ring-1 focus:ring-teal-500 transition-colors"
                    aria-describedby="{{ $errors->has('phone') ? 'phone-error' : '' }}"
                >
                @error('phone')
                    <p id="phone-error" class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            {{-- Company / Institution Name --}}
            <div>
                <label for="company" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    {{ $currentLocale === 'en' ? 'Company / Institution' : 'Nama Perusahaan / Institusi' }} <span class="text-xs font-normal text-slate-400">({{ $currentLocale === 'en' ? 'optional' : 'opsional' }})</span>
                </label>
                <input
                    type="text"
                    id="company"
                    name="company"
                    value="{{ old('company') }}"
                    placeholder="{{ $currentLocale === 'en' ? 'National Research Institute' : 'RS Cipto Mangunkusumo' }}"
                    maxlength="255"
                    class="w-full rounded-xl border {{ $errors->has('company') ? 'border-rose-300 ring-1 ring-rose-300' : 'border-slate-200' }} px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:border-teal-500 focus:ring-1 focus:ring-teal-500 transition-colors"
                    aria-describedby="{{ $errors->has('company') ? 'company-error' : '' }}"
                >
                @error('company')
                    <p id="company-error" class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Subject --}}
        <div>
            <label for="subject" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                {{ $currentLocale === 'en' ? 'Request Subject' : 'Subjek Permintaan' }} <span class="text-rose-500">*</span>
            </label>
            <input
                type="text"
                id="subject"
                name="subject"
                value="{{ old('subject', $defaultSubject) }}"
                placeholder="{{ $currentLocale === 'en' ? 'Quotation Request for Laboratory Instruments' : 'Permintaan Penawaran Harga Alat Uji Laboratorium' }}"
                required
                maxlength="255"
                class="w-full rounded-xl border {{ $errors->has('subject') ? 'border-rose-300 ring-1 ring-rose-300' : 'border-slate-200' }} px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:border-teal-500 focus:ring-1 focus:ring-teal-500 transition-colors"
                aria-describedby="{{ $errors->has('subject') ? 'subject-error' : '' }}"
            >
            @error('subject')
                <p id="subject-error" class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
            @enderror
        </div>

        {{-- Message / Details --}}
        <div>
            <label for="message" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                {{ $currentLocale === 'en' ? 'Message / Requirement Details' : 'Pesan / Rincian Kebutuhan' }} <span class="text-rose-500">*</span>
            </label>
            <textarea
                id="message"
                name="message"
                rows="4"
                required
                minlength="10"
                maxlength="5000"
                placeholder="{{ $currentLocale === 'en' ? 'Please describe your procurement requirements in detail, such as unit quantity, expected delivery schedule, or specific test parameters...' : 'Jelaskan kebutuhan pengadaan Anda secara detail, termasuk jumlah unit, spesifikasi yang dibutuhkan, dan jadwal pengadaan yang diharapkan...' }}"
                class="w-full rounded-xl border {{ $errors->has('message') ? 'border-rose-300 ring-1 ring-rose-300' : 'border-slate-200' }} px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:border-teal-500 focus:ring-1 focus:ring-teal-500 transition-colors resize-none"
                aria-describedby="{{ $errors->has('message') ? 'message-error' : '' }}"
            >{{ old('message') }}</textarea>
            @error('message')
                <p id="message-error" class="mt-1.5 text-xs text-rose-600 font-medium">{{ $message }}</p>
            @enderror
        </div>

        {{-- Submit Button with Double-Click Protection --}}
        <div>
            <button
                type="submit"
                :disabled="submitting"
                :class="{ 'opacity-75 cursor-not-allowed': submitting }"
                class="w-full inline-flex items-center justify-center gap-2 bg-teal-700 hover:bg-teal-800 text-white font-bold py-3.5 px-6 rounded-xl shadow-sm transition-all focus-ring text-base active:scale-[0.99] cursor-pointer min-h-[48px]"
            >
                <span x-show="!submitting">{{ $currentLocale === 'en' ? 'Send Quotation Request' : 'Kirim Permintaan Penawaran' }}</span>
                <span x-show="submitting" style="display: none;">{{ $currentLocale === 'en' ? 'Sending Request...' : 'Mengirimkan Permintaan...' }}</span>
                <svg x-show="!submitting" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                </svg>
            </button>
        </div>
    </form>
</div>
