@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between gap-4">
        {{-- Mobile View --}}
        <div class="flex justify-between items-center w-full sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center gap-1.5 px-3.5 py-2 text-sm font-semibold text-slate-300 bg-slate-50 border border-slate-200/80 rounded-xl cursor-not-allowed pointer-events-none shadow-2xs">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                    </svg>
                    <span>{{ app()->getLocale() === 'en' ? 'Previous' : 'Sebelumnya' }}</span>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-sm font-semibold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 hover:border-slate-300 hover:text-teal-700 rounded-xl transition-all focus-ring shadow-2xs active:scale-[0.98]">
                    <svg class="w-4 h-4 text-slate-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                    </svg>
                    <span>{{ app()->getLocale() === 'en' ? 'Previous' : 'Sebelumnya' }}</span>
                </a>
            @endif

            <span class="text-xs font-semibold text-slate-500 px-2 text-center">
                {{ app()->getLocale() === 'en'
                    ? "Page {$paginator->currentPage()} of {$paginator->lastPage()}"
                    : "Halaman {$paginator->currentPage()} dari {$paginator->lastPage()}" }}
            </span>

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-sm font-semibold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 hover:border-slate-300 hover:text-teal-700 rounded-xl transition-all focus-ring shadow-2xs active:scale-[0.98]">
                    <span>{{ app()->getLocale() === 'en' ? 'Next' : 'Berikutnya' }}</span>
                    <svg class="w-4 h-4 text-slate-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </a>
            @else
                <span class="inline-flex items-center gap-1.5 px-3.5 py-2 text-sm font-semibold text-slate-300 bg-slate-50 border border-slate-200/80 rounded-xl cursor-not-allowed pointer-events-none shadow-2xs">
                    <span>{{ app()->getLocale() === 'en' ? 'Next' : 'Berikutnya' }}</span>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </span>
            @endif
        </div>

        {{-- Desktop / Tablet View --}}
        <div class="hidden sm:flex sm:items-center sm:justify-between w-full">
            <div>
                <p class="text-sm text-slate-500 font-medium">
                    @php
                        $itemLabel = $itemName ?? (app()->getLocale() === 'en' ? 'results' : 'hasil');
                    @endphp
                    @if (app()->getLocale() === 'en')
                        Showing <span class="font-semibold text-slate-700">{{ $paginator->firstItem() }}–{{ $paginator->lastItem() }}</span> of <span class="font-semibold text-slate-700">{{ $paginator->total() }}</span> {{ $itemLabel }}
                    @else
                        Menampilkan <span class="font-semibold text-slate-700">{{ $paginator->firstItem() }}–{{ $paginator->lastItem() }}</span> dari <span class="font-semibold text-slate-700">{{ $paginator->total() }}</span> {{ $itemLabel }}
                    @endif
                </p>
            </div>

            <div>
                <span class="inline-flex items-center gap-1 rounded-xl shadow-2xs bg-white p-1 border border-slate-200">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}" class="inline-flex items-center justify-center w-9 h-9 text-slate-300 rounded-lg cursor-not-allowed">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                            </svg>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="{{ __('pagination.previous') }}" class="inline-flex items-center justify-center w-9 h-9 text-slate-600 hover:text-teal-700 hover:bg-teal-50/70 rounded-lg transition-colors focus-ring">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                            </svg>
                        </a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <span aria-disabled="true" class="inline-flex items-center justify-center w-9 h-9 text-xs font-semibold text-slate-400">
                                {{ $element }}
                            </span>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page" class="inline-flex items-center justify-center w-9 h-9 text-sm font-bold bg-teal-700 text-white rounded-lg shadow-xs">
                                        {{ $page }}
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="inline-flex items-center justify-center w-9 h-9 text-sm font-medium text-slate-700 hover:text-teal-700 hover:bg-teal-50/70 rounded-lg transition-colors focus-ring">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="{{ __('pagination.next') }}" class="inline-flex items-center justify-center w-9 h-9 text-slate-600 hover:text-teal-700 hover:bg-teal-50/70 rounded-lg transition-colors focus-ring">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                            </svg>
                        </a>
                    @else
                        <span aria-disabled="true" aria-label="{{ __('pagination.next') }}" class="inline-flex items-center justify-center w-9 h-9 text-slate-300 rounded-lg cursor-not-allowed">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                            </svg>
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif
