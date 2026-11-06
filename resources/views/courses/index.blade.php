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
                        placeholder="Cari judul atau topik pelatihan..." 
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
                    <span>{{ $courses->count() }} program</span>
                    @if($category)
                        <span class="text-slate-400 font-normal">dalam kategori ini</span>
                    @endif
                </div>
            </div>

            <!-- Courses Grid (Slide 7: Card Design) -->
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
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    @foreach($courses as $course)
                        @php
                            $courseSession = $course->sessions->firstWhere('status', '!=', 'Penuh') ?? $course->sessions->first();
                        @endphp
                        <div class="bg-white rounded-2xl border border-slate-200/90 hover:border-[#087F78]/60 shadow-xs hover:shadow-md transition-all flex flex-col justify-between p-6">
                            <div class="space-y-3">
                                <!-- Chip Tag Mini Kategori -->
                                <div class="flex items-center justify-between">
                                    <span class="inline-block px-2.5 py-1 rounded-md text-[11px] font-extrabold uppercase tracking-wider bg-[#E2F6F2] text-[#087F78]">
                                        {{ $course->category->short_name ?? $course->category->name }}
                                    </span>
                                    <span class="text-xs text-slate-400 font-medium">⏱ {{ $courseSession?->duration ?? 'Belum ditentukan' }}</span>
                                </div>

                                <h3 class="font-bold text-base text-[#10243A] leading-snug hover:text-[#087F78] transition-colors">
                                    <a href="{{ route('courses.show', $course->slug) }}">
                                        {{ $course->title }}
                                    </a>
                                </h3>

                                <p class="text-xs text-slate-600 line-clamp-2 leading-relaxed">
                                    {{ $course->description }}
                                </p>
                            </div>

                            <div class="pt-4 mt-4 border-t border-slate-100 flex items-center justify-between">
                                <span class="text-xs font-semibold text-slate-500">{{ $course->level }} • {{ $course->format }}</span>
                                <a href="{{ route('courses.show', $course->slug) }}" class="flex items-center text-xs font-bold text-[#087F78] hover:text-[#066761]">
                                    <span>Lihat detail</span>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>

    </div>

</div>
@endsection

