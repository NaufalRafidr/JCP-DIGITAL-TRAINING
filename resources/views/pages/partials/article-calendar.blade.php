<section data-article-calendar data-calendar-month="{{ $articleCalendarMonth->format('Y-m') }}" class="mb-8 rounded-3xl border border-slate-200 bg-white p-5 shadow-sm transition-opacity sm:p-7">
    <div class="mb-5 flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
        <div>
            <p class="text-xs font-bold uppercase tracking-wider text-[#087F78]">Kalender jadwal posting</p>
            <h2 class="mt-1 text-xl font-extrabold text-[#10243A]">{{ $articleCalendarMonth->format('F Y') }}</h2>
        </div>
        <div class="flex items-center gap-2">
            <button type="button" data-calendar-navigation="today" class="rounded-lg border border-slate-200 px-3 py-2 text-xs font-bold text-slate-600 hover:border-[#087F78] hover:text-[#087F78]">Hari ini</button>
            <button type="button" data-calendar-navigation="previous" class="rounded-lg border border-slate-200 px-3 py-2 text-xs font-bold text-slate-600 hover:border-[#087F78] hover:text-[#087F78]" aria-label="Bulan sebelumnya">Sebelumnya</button>
            <button type="button" data-calendar-navigation="next" class="rounded-lg border border-slate-200 px-3 py-2 text-xs font-bold text-slate-600 hover:border-[#087F78] hover:text-[#087F78]" aria-label="Bulan berikutnya">&rarr;</button>
        </div>
    </div>
    <div class="grid gap-5 xl:grid-cols-[minmax(0,1fr)_minmax(260px,0.55fr)]">
        <div class="grid grid-cols-7 gap-2 text-center text-xs">
            @foreach(['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'] as $weekday)
                <div class="pb-1 font-bold uppercase tracking-wider text-slate-400">{{ $weekday }}</div>
            @endforeach
            @for($blankDay = 0; $blankDay < $articleCalendarLeadingDays; $blankDay++)
                <div></div>
            @endfor
            @for($calendarDay = 1; $calendarDay <= $articleCalendarMonth->daysInMonth; $calendarDay++)
                @php
                    $calendarDate = $articleCalendarMonth->copy()->day($calendarDay)->toDateString();
                    $dayArticles = $articleCalendarArticles->filter(fn ($calendarArticle) => $calendarArticle->published_at?->toDateString() === $calendarDate);
                @endphp
                <button type="button" data-article-calendar-date="{{ $calendarDate }}" class="min-h-20 w-full rounded-xl border {{ $dayArticles->isNotEmpty() ? 'border-[#087F78]/40 bg-[#E2F6F2]' : 'border-slate-100 bg-slate-50/50' }} p-2 text-left transition-colors hover:border-[#087F78] hover:bg-[#E2F6F2]" title="Pilih tanggal {{ $calendarDate }}">
                    <div class="flex items-center justify-between gap-1">
                        <span class="font-bold text-[#10243A]">{{ $calendarDay }}</span>
                        @if($dayArticles->isNotEmpty())<span class="h-2 w-2 rounded-full bg-[#087F78]"></span>@endif
                    </div>
                    @foreach($dayArticles as $calendarArticle)
                        <p class="mt-2 line-clamp-2 text-[10px] font-semibold leading-tight text-[#087F78]">{{ $calendarArticle->title }}</p>
                    @endforeach
                </button>
            @endfor
        </div>
        <div class="rounded-2xl bg-slate-50 p-4">
            <div class="mb-3 flex items-center justify-between gap-2">
                <h3 class="text-sm font-extrabold text-[#10243A]">Agenda bulan ini</h3>
                <span class="rounded-full bg-white px-2 py-1 text-[10px] font-bold text-slate-500">{{ $articleCalendarArticles->count() }} agenda</span>
            </div>
            <div class="space-y-2">
                @forelse($articleCalendarArticles as $calendarArticle)
                    <div class="rounded-xl border border-white bg-white p-3">
                        <time data-article-local-time datetime="{{ $calendarArticle->published_at?->toIso8601String() }}" class="text-[10px] font-bold uppercase tracking-wider text-[#087F78]">{{ $calendarArticle->published_at?->format('d M Y, H:i') }}</time>
                        <p class="mt-1 text-xs font-bold leading-relaxed text-[#10243A]">{{ $calendarArticle->title }}</p>
                        <span class="mt-2 inline-flex rounded-full px-2 py-1 text-[10px] font-bold {{ $calendarArticle->status === 'Publish' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">{{ $calendarArticle->status === 'Publish' ? 'Publish' : 'Terjadwalkan' }}</span>
                    </div>
                @empty
                    <p class="rounded-xl border border-dashed border-slate-200 bg-white p-4 text-xs text-slate-500">Belum ada artikel yang dijadwalkan pada bulan ini.</p>
                @endforelse
            </div>
        </div>
    </div>
</section>
