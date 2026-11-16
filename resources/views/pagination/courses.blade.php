@if ($paginator->hasPages())
    <nav class="flex flex-col items-center justify-between gap-4 border-t border-slate-200 pt-5 sm:flex-row" aria-label="Pagination program pelatihan">
        <p class="text-center text-xs font-semibold text-slate-500 sm:text-left">
            Menampilkan {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} dari {{ $paginator->total() }} program
        </p>

        <div class="flex flex-wrap items-center justify-center gap-1.5">
            @if ($paginator->onFirstPage())
                <span class="inline-flex h-10 items-center gap-2 rounded-xl border border-slate-200 bg-slate-100 px-3 text-sm font-bold text-slate-400" aria-disabled="true" aria-label="Halaman sebelumnya">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 18-6-6 6-6"></path>
                    </svg>
                    <span class="hidden sm:inline">Sebelumnya</span>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex h-10 items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 text-sm font-bold text-slate-600 transition-colors hover:border-[#087F78] hover:text-[#087F78] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#087F78]" aria-label="Ke halaman sebelumnya">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 18-6-6 6-6"></path>
                    </svg>
                    <span class="hidden sm:inline">Sebelumnya</span>
                </a>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="inline-flex h-10 min-w-10 items-center justify-center px-1 text-sm font-bold text-slate-400" aria-hidden="true">
                        {{ $element }}
                    </span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page === $paginator->currentPage())
                            <span class="inline-flex h-10 min-w-10 items-center justify-center rounded-xl bg-[#087F78] px-3 text-sm font-extrabold text-white shadow-sm" aria-current="page" aria-label="Halaman {{ $page }}">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" class="inline-flex h-10 min-w-10 items-center justify-center rounded-xl border border-slate-200 bg-white px-3 text-sm font-bold text-slate-600 transition-colors hover:border-[#087F78] hover:bg-[#F2FAF8] hover:text-[#087F78] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#087F78]" aria-label="Ke halaman {{ $page }}">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex h-10 items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 text-sm font-bold text-slate-600 transition-colors hover:border-[#087F78] hover:text-[#087F78] focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#087F78]" aria-label="Ke halaman berikutnya">
                    <span class="hidden sm:inline">Berikutnya</span>
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 18 6-6-6-6"></path>
                    </svg>
                </a>
            @else
                <span class="inline-flex h-10 items-center gap-2 rounded-xl border border-slate-200 bg-slate-100 px-3 text-sm font-bold text-slate-400" aria-disabled="true" aria-label="Halaman berikutnya">
                    <span class="hidden sm:inline">Berikutnya</span>
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 18 6-6-6-6"></path>
                    </svg>
                </span>
            @endif
        </div>
    </nav>
@endif
