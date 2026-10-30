@extends('layouts.app')

@section('title', 'Katalog Pelatihan • 15 Program dalam 5 Bidang')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">

    <!-- Header & Breadcrumbs (Slide 7: 06 / KATALOG PELATIHAN) -->
    <div>
        <div class="flex items-center gap-2 text-xs font-semibold text-slate-400 mb-2">
            <a href="{{ route('home') }}" class="hover:text-[#087F78]">Beranda</a>
            <span>/</span>
            <span class="text-[#087F78]">Katalog Pelatihan</span>
        </div>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-[#10243A] tracking-tight">
            Semua Pelatihan
        </h1>
        <p class="text-slate-600 text-sm mt-1">
            Temukan kelas dalam beberapa langkah. Pencarian, filter, dan sort bekerja bersama.
        </p>
    </div>

    <!-- Main Container Layout: 2 Kolom (Content Kiri + Sidebar Filter Kanan Slide 7) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Left Side: Search + Category Selector + Courses Grid (8 Kolom) -->
        <div class="lg:col-span-8 space-y-6">
            
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

            <!-- Kategori Radio List / Horizontal Pill (Slide 7: Kategori [Semua bidang, Foto & Video, Digital, AI, Drone, IT]) -->
            <div class="bg-white rounded-2xl border border-slate-200/90 p-4 sm:p-5">
                <div class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Kategori Bidang</div>
                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('courses.index', array_filter(['q' => $searchQuery ?? null])) }}" 
                       class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ empty($selectedCategorySlug) ? 'bg-[#087F78] text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                        Semua bidang
                    </a>
                    @foreach($categories as $cat)
                        <a href="{{ route('courses.index', array_filter(['kategori' => $cat->slug, 'q' => $searchQuery ?? null])) }}" 
                           class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ ($selectedCategorySlug ?? '') === $cat->slug ? 'bg-[#087F78] text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                            {{ $cat->short_name ?? $cat->name }}
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- List Header: Count Program & Sort Dropdown (Slide 7: 15 program • Urutkan: Terbaru) -->
            <div class="flex items-center justify-between text-sm px-1">
                <div class="font-extrabold text-[#10243A]">
                    <span>{{ $courses->count() }} program</span>
                    @if(!empty($selectedCategorySlug))
                        <span class="text-slate-400 font-normal">dalam kategori ini</span>
                    @endif
                </div>
                <div class="flex items-center gap-2 text-xs font-semibold text-slate-500">
                    <span>Urutkan:</span>
                    <select onchange="window.location.href=this.value" class="bg-transparent border-0 font-bold text-[#087F78] focus:ring-0 cursor-pointer">
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'terbaru']) }}">Terbaru</option>
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'populer']) }}" {{ request('sort') == 'populer' ? 'selected' : '' }}>Populer</option>
                    </select>
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
                    <a href="{{ route('courses.index') }}" class="mt-4 inline-block bg-[#087F78] text-white px-5 py-2 rounded-xl text-xs font-bold hover:bg-[#066761]">
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
                                <a href="{{ route('courses.show', $course->slug) }}" class="text-xs font-bold text-[#087F78] hover:text-[#066761] flex items-center gap-1 group">
                                    <span>Lihat detail</span>
                                    <span class="group-hover:translate-x-0.5 transition-transform">&rarr;</span>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>

        <!-- Right Side: Dark Navy Filter Box (Slide 7 Sidebar FILTER) -->
        <div class="lg:col-span-4 bg-[#10243A] text-white rounded-3xl p-6 sm:p-7 shadow-xl space-y-6 lg:sticky lg:top-24">
            <div class="border-b border-slate-700/80 pb-3 flex items-center justify-between">
                <span class="text-xs font-extrabold tracking-widest text-[#087F78] uppercase">FILTER KATALOG</span>
                <a href="{{ route('courses.index') }}" class="text-[11px] text-slate-400 hover:text-white underline">Reset</a>
            </div>

            <!-- 1. Bidang -->
            <div class="space-y-2">
                <div class="text-xs font-bold uppercase tracking-wider text-slate-300">Bidang</div>
                <div class="text-xs text-slate-400">5 kategori pilihan: Foto/Video, Digital, AI, Drone, IT</div>
                <div class="space-y-1 pt-1">
                    @foreach($categories as $cat)
                        <a href="{{ route('courses.index', ['kategori' => $cat->slug]) }}" 
                           class="flex items-center justify-between py-1.5 px-2.5 rounded-lg text-xs {{ ($selectedCategorySlug ?? '') === $cat->slug ? 'bg-[#087F78] text-white font-bold' : 'text-slate-300 hover:bg-slate-800' }}">
                            <span>{{ $cat->name }}</span>
                            <span class="text-[10px] opacity-70">{{ $cat->courses_count ?? '' }}</span>
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- 2. Format -->
            <div class="space-y-2 pt-2 border-t border-slate-800">
                <div class="text-xs font-bold uppercase tracking-wider text-slate-300">Format</div>
                <div class="flex flex-wrap gap-1.5 text-xs">
                    <span class="px-2.5 py-1 rounded-md bg-slate-800 text-slate-300 border border-slate-700">Offline / Studio</span>
                    <span class="px-2.5 py-1 rounded-md bg-slate-800 text-slate-300 border border-slate-700">Online</span>
                    <span class="px-2.5 py-1 rounded-md bg-slate-800 text-slate-300 border border-slate-700">Hybrid</span>
                </div>
            </div>

            <!-- 3. Level -->
            <div class="space-y-2 pt-2 border-t border-slate-800">
                <div class="text-xs font-bold uppercase tracking-wider text-slate-300">Level</div>
                <div class="text-xs text-slate-400">Basic / Intermediate / Advanced</div>
            </div>

            <!-- 4. Durasi -->
            <div class="space-y-2 pt-2 border-t border-slate-800">
                <div class="text-xs font-bold uppercase tracking-wider text-slate-300">Durasi</div>
                <div class="text-xs text-slate-400">1 – 5+ hari workshop intensif</div>
            </div>

            <!-- 5. Jadwal -->
            <div class="space-y-2 pt-2 border-t border-slate-800">
                <div class="text-xs font-bold uppercase tracking-wider text-slate-300">Jadwal</div>
                <div class="flex items-center gap-2 pt-1">
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                    <span class="text-xs text-slate-300">Tersedia / Segera hadir</span>
                </div>
            </div>

            <!-- CTA Bawah Sidebar -->
            <div class="pt-4 border-t border-slate-800">
                <a href="{{ route('courses.schedule') }}" class="w-full block text-center py-2.5 px-4 rounded-xl bg-[#087F78] hover:bg-[#066761] text-white text-xs font-bold transition-all shadow-md">
                    Lihat Kalender Jadwal &rarr;
                </a>
            </div>

        </div>

    </div>

</div>
@endsection

