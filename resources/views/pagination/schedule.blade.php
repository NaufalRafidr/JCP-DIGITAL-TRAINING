@if ($paginator->hasPages())
    <nav class="flex flex-col items-center justify-between gap-4 sm:flex-row" aria-label="Pagination jadwal pelatihan">
        <div>
            @if ($paginator->onFirstPage())
                <span class="inline-flex cursor-not-allowed items-center rounded-xl border border-slate-200 bg-slate-100 px-4 py-2.5 text-sm font-bold text-slate-400" aria-disabled="true">
                    Sebelumnya
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-700 transition hover:border-[#087F78] hover:text-[#087F78] focus:outline-none focus:ring-2 focus:ring-[#087F78] focus:ring-offset-2">
                    Sebelumnya
                </a>
            @endif
        </div>

        <p class="text-sm font-semibold text-slate-500">
            Halaman {{ $paginator->currentPage() }} dari {{ $paginator->lastPage() }}
        </p>

        <div>
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-700 transition hover:border-[#087F78] hover:text-[#087F78] focus:outline-none focus:ring-2 focus:ring-[#087F78] focus:ring-offset-2">
                    Berikutnya
                </a>
            @else
                <span class="inline-flex cursor-not-allowed items-center rounded-xl border border-slate-200 bg-slate-100 px-4 py-2.5 text-sm font-bold text-slate-400" aria-disabled="true">
                    Berikutnya
                </span>
            @endif
        </div>
    </nav>
@endif
