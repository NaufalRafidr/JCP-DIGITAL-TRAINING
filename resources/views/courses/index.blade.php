@extends('layouts.app')

@section('title', ($category?->name ?? 'Hasil pencarian pelatihan').' • JCP Training')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">

    <!-- Header & Breadcrumbs (Slide 7: 06 / KATALOG PELATIHAN) -->
    <div>
        <div class="flex items-center gap-2 text-xs font-semibold text-slate-400 mb-2">
            <a href="{{ route('home') }}" class="hover:text-[#087F78]">Beranda</a>
            <span>/</span>
            <span class="text-[#087F78]">{{ $category?->name ?? 'Hasil pencarian' }}</span>
        </div>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-[#10243A] tracking-tight">
            {{ $category?->name ?? 'Hasil pencarian pelatihan' }}
        </h1>
        <p class="text-slate-600 text-sm mt-1">
            {{ $category?->description ?? 'Temukan program pelatihan yang sesuai dengan kata kunci pencarian Anda.' }}
        </p>
        @if($category)
            <a href="{{ route('home') }}" class="mt-4 inline-flex items-center text-xs font-bold text-[#087F78] hover:underline">
                Pilih bidang pelatihan lain
            </a>
        @endif
    </div>

    <!-- Main Container Layout -->
    <div class="grid grid-cols-1 gap-8 items-start">
        
        <!-- Left Side: Search + Category Selector + Courses Grid (8 Kolom) -->
        <div class="space-y-6">
            
            <!-- Search Bar (Slide 7: ⌕ Cari judul atau topik pelatihan [Cari]) -->
            <form action="{{ route('courses.index') }}" method="GET" class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-2 flex items-center justify-between gap-3">
                <div class="flex items-center gap-3 pl-3 sm:pl-4 flex-1">
                    <svg class="w-5 h-5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input 
                        type="text" 
                        name="q" 
                        value="{{ $searchQuery ?? '' }}"
                        placeholder="Cari bidang atau judul pelatihan..." 
                        class="w-full text-sm text-slate-700 placeholder-slate-400 bg-transparent border-0 focus:outline-none focus:ring-0"
                    >
                </div>
                @if(!empty($selectedCategorySlug))
                    <input type="hidden" name="kategori" value="{{ $selectedCategorySlug }}">
                @endif
                <button type="submit" class="bg-[#087F78] hover:bg-[#066761] text-white font-bold text-sm px-6 py-2.5 rounded-xl transition-all shrink-0">
                    Cari
                </button>
            </form>

            <!-- List Header: Count Program -->
            <div class="flex items-center justify-between px-1 text-sm">
                <div class="font-extrabold text-[#10243A]">
                    <span>{{ $courses->total() }} program</span>
                    @if($category)
                        <span class="text-slate-400 font-normal">dalam kategori ini</span>
                    @endif
                </div>
            </div>

            <!-- Courses List -->
            @if($courses->isEmpty())
                <div class="bg-white rounded-2xl p-12 text-center border border-slate-200">
                    <div class="text-4xl mb-3">🔍</div>
                    <h3 class="text-lg font-bold text-[#10243A]">Tidak ditemukan pelatihan yang cocok</h3>
                    <p class="text-sm text-slate-500 mt-1 max-w-md mx-auto">
                        Coba gunakan kata kunci pencarian yang lain atau reset filter kategori.
                    </p>
                    <a href="{{ $category ? route('courses.index', ['kategori' => $category->slug]) : route('home') }}" class="mt-4 inline-block bg-[#087F78] text-white px-5 py-2 rounded-xl text-xs font-bold hover:bg-[#066761]">
                        Reset Filter
                    </a>
                </div>
            @else
                <div class="overflow-hidden border-y border-slate-200 bg-white">
                    @foreach($courses as $course)
                        @php
                            $courseSession = $course->sessions->firstWhere('status', '!=', 'Penuh') ?? $course->sessions->first();
                            $programNumber = ($courses->firstItem() ?? 1) + $loop->index;
                        @endphp
                        <a
                            href="{{ route('courses.show', $course->slug) }}"
                            class="group grid grid-cols-[2.5rem_minmax(0,1fr)_2rem] items-center gap-x-3 border-b border-slate-200 px-3 py-5 transition-colors last:border-b-0 hover:bg-[#F2FAF8] focus-visible:bg-[#F2FAF8] focus-visible:outline-2 focus-visible:outline-offset-[-2px] focus-visible:outline-[#087F78] sm:grid-cols-[3.5rem_minmax(0,1fr)_2.5rem] sm:gap-x-5 sm:px-5 sm:py-6 lg:grid-cols-[3.5rem_minmax(0,1fr)_minmax(15rem,auto)_2.5rem]"
                            aria-label="Lihat detail program {{ $course->title }}"
                        >
                            <span class="self-start pt-0.5 font-mono text-xs font-bold tracking-wider text-[#087F78] sm:self-center sm:pt-0 sm:text-sm" data-program-number="{{ $programNumber }}">
                                {{ str_pad((string) $programNumber, 2, '0', STR_PAD_LEFT) }}
                            </span>

                            <div class="min-w-0">
                                <h3 class="text-base font-extrabold leading-snug text-[#10243A] transition-colors group-hover:text-[#087F78] sm:text-lg lg:text-xl">
                                    {{ $course->title }}
                                </h3>

                                <div class="mt-2 flex flex-wrap items-center gap-x-2 gap-y-1 text-[11px] font-semibold uppercase tracking-wide text-slate-500 lg:hidden">
                                    <span class="text-[#087F78]">{{ $course->category->short_name ?? $course->category->name }}</span>
                                    <span class="text-slate-300">/</span>
                                    <span>{{ $course->level }}</span>
                                    <span class="text-slate-300">/</span>
                                    <span>{{ $course->format }}</span>
                                    <span class="basis-full normal-case tracking-normal text-slate-400">⏱ {{ $courseSession?->duration ?? 'Belum ditentukan' }}</span>
                                </div>
                            </div>

                            <div class="hidden items-end justify-self-end text-right lg:flex lg:flex-col lg:gap-1.5">
                                <span class="text-xs font-bold uppercase tracking-[0.16em] text-[#087F78]">
                                    {{ $course->category->short_name ?? $course->category->name }}
                                </span>
                                <span class="max-w-xs text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    {{ $course->level }} / {{ $course->format }}
                                </span>
                                <span class="text-xs font-medium text-slate-400">⏱ {{ $courseSession?->duration ?? 'Belum ditentukan' }}</span>
                            </div>

                            <span class="flex h-8 w-8 items-center justify-center justify-self-end rounded-full border border-slate-200 text-[#087F78] transition-all group-hover:border-[#087F78] group-hover:bg-[#087F78] group-hover:text-white motion-safe:group-hover:translate-x-0.5" aria-hidden="true">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14m-6-6 6 6-6 6"></path>
                                </svg>
                            </span>
                        </a>
                    @endforeach
                </div>

                <div class="pt-2">
                    {{ $courses->onEachSide(1)->links('pagination.courses') }}
                </div>
            @endif

        </div>

    </div>

</div>
@endsection

