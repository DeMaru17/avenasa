@props(['product'])

@php
    $currentLocale = app()->getLocale();
    $specs = $product->specifications;

    // Filter out invalid items
    $validSpecs = [];
    if (is_array($specs)) {
        foreach ($specs as $item) {
            if (is_array($item)) {
                $key = $currentLocale === 'en'
                    ? ($item['key_en'] ?? $item['key_id'] ?? null)
                    : ($item['key_id'] ?? $item['key_en'] ?? null);

                $value = $currentLocale === 'en'
                    ? ($item['value_en'] ?? $item['value_id'] ?? null)
                    : ($item['value_id'] ?? $item['value_en'] ?? null);

                if (!empty($key) && !empty($value)) {
                    $validSpecs[] = [
                        'key' => $key,
                        'value' => $value,
                    ];
                }
            }
        }
    }
@endphp

@if (!empty($validSpecs))
    <section class="mt-12 lg:mt-16 pt-8 border-t border-slate-100" aria-label="{{ __('Technical Specifications') }}">
        <h2 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight mb-6">
            {{ $currentLocale === 'en' ? 'Technical Specifications' : 'Spesifikasi Teknis' }}
        </h2>

        <div class="bg-white border border-slate-200/90 rounded-2xl overflow-hidden shadow-xs">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($validSpecs as $spec)
                            <tr class="hover:bg-slate-50/70 transition-colors {{ $loop->even ? 'bg-slate-50/40' : 'bg-white' }}">
                                <th scope="row" class="py-4 px-5 sm:px-6 font-medium text-slate-600 w-1/3 min-w-[140px] sm:min-w-[200px] align-top">
                                    {{ $spec['key'] }}
                                </th>
                                <td class="py-4 px-5 sm:px-6 font-semibold text-slate-900 align-top leading-relaxed">
                                    {{ $spec['value'] }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endif
