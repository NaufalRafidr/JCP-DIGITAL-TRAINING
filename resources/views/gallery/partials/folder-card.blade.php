<a href="{{ route('gallery.show', $folder) }}" class="group overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm transition-shadow hover:shadow-lg">
    <div class="aspect-[4/3] overflow-hidden bg-slate-100">
        @if($folder->photos->isNotEmpty())
            <img src="{{ $folder->photos->first()->image_url ?: Storage::disk('public')->url($folder->photos->first()->thumbnail_path ?: $folder->photos->first()->path) }}" alt="Foto {{ $folder->name }}" class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105">
        @else
            <div class="flex h-full items-center justify-center text-slate-400">
                <svg class="h-14 w-14" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 7a2 2 0 0 1 2-2h4l2 2h8a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7Z"/></svg>
            </div>
        @endif
    </div>
    <div class="p-5">
        <div class="flex items-start justify-between gap-3">
            <div>
                <h3 class="text-lg font-bold text-[#10243A] group-hover:text-[#087F78]">{{ $folder->name }}</h3>
                <p class="mt-1 line-clamp-2 text-sm leading-5 text-slate-500">{{ $folder->description ? \Illuminate\Support\Str::limit($folder->description, 105) : 'Album foto kegiatan JCP Training.' }}</p>
            </div>
            <span class="shrink-0 rounded-full bg-teal-50 px-3 py-1 text-xs font-bold text-[#087F78]">{{ $folder->photos_count }} foto</span>
        </div>
    </div>
</a>
