@extends('layouts.app')

@section('title', 'Galeri Kegiatan')

@section('content')
<section class="mx-auto max-w-7xl space-y-10 px-4 py-12 sm:px-6 lg:px-8">
    <div>
        <div class="mb-2 text-xs font-semibold text-slate-400"><a href="{{ route('home') }}" class="hover:text-[#087F78]">Beranda</a> / <span class="text-[#087F78]">Galeri</span></div>
        <h1 class="text-3xl font-extrabold text-[#10243A] sm:text-4xl">Galeri Kegiatan</h1>
        <p class="mt-2 text-slate-600">Dokumentasi kegiatan dan keseruan belajar bersama JCP Training.</p>
    </div>

    <div class="space-y-4">
        <h2 class="text-2xl font-extrabold text-[#10243A]">Pilih bidang galeri</h2>
        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-6">
            <a href="{{ route('gallery.index') }}" class="flex min-h-16 items-center justify-center rounded-2xl border px-4 py-3 text-center text-sm font-bold transition-all {{ $selectedCategorySlug === null ? 'border-[#9ADFD7] bg-[#E0F6F3] text-[#087F78] shadow-sm' : 'border-slate-200 bg-white text-[#10243A] hover:border-[#9ADFD7] hover:text-[#087F78]' }}">
                Semua Bidang
            </a>
            @foreach($categories as $category)
                <a href="{{ route('gallery.index', ['bidang' => $category->slug]) }}" class="flex min-h-16 items-center justify-center rounded-2xl border px-4 py-3 text-center text-sm font-bold transition-all {{ $selectedCategorySlug === $category->slug ? 'border-[#9ADFD7] bg-[#E0F6F3] text-[#087F78] shadow-sm' : 'border-slate-200 bg-white text-[#10243A] hover:border-[#9ADFD7] hover:text-[#087F78]' }}">
                    {{ $category->short_name ?: $category->name }}
                </a>
            @endforeach
        </div>
    </div>

    @if($foldersByCategory->isEmpty() && $uncategorizedFolders->isEmpty())
        <div class="rounded-3xl border border-dashed border-slate-300 p-12 text-center text-slate-500">Belum ada album foto yang tersedia.</div>
    @else
        @if($selectedCategorySlug === null)
            @php
                $allGalleryFolders = $categories
                    ->flatMap(fn ($category) => $foldersByCategory->get($category->id, collect()))
                    ->concat($uncategorizedFolders)
                    ->values();
            @endphp
            <section class="space-y-5" aria-labelledby="gallery-all-folders">
                <div class="flex items-end justify-between gap-4 border-b border-slate-200 pb-3">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#087F78]">Galeri</p>
                        <h2 id="gallery-all-folders" class="mt-1 text-2xl font-extrabold text-[#10243A]">Semua album</h2>
                    </div>
                    <span class="text-sm font-semibold text-slate-400">{{ $allGalleryFolders->count() }} folder</span>
                </div>
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($allGalleryFolders as $folder)
                        @include('gallery.partials.folder-card', ['folder' => $folder, 'showCategory' => true])
                    @endforeach
                </div>
            </section>
        @else
            @foreach($categories as $category)
                @php($categoryFolders = $foldersByCategory->get($category->id, collect()))
                @if($categoryFolders->isNotEmpty())
                    <section class="space-y-5" aria-labelledby="gallery-category-{{ $category->id }}">
                        <div class="flex items-end justify-between gap-4 border-b border-slate-200 pb-3">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#087F78]">Bidang</p>
                                <h2 id="gallery-category-{{ $category->id }}" class="mt-1 text-2xl font-extrabold text-[#10243A]">{{ $category->short_name ?: $category->name }}</h2>
                            </div>
                            <span class="text-sm font-semibold text-slate-400">{{ $categoryFolders->count() }} folder</span>
                        </div>
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                            @foreach($categoryFolders as $folder)
                                @include('gallery.partials.folder-card', ['folder' => $folder])
                            @endforeach
                        </div>
                    </section>
                @endif
            @endforeach

            @if($uncategorizedFolders->isNotEmpty())
                <section class="space-y-5" aria-labelledby="gallery-category-uncategorized">
                    <div class="flex items-end justify-between gap-4 border-b border-slate-200 pb-3">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-400">Bidang</p>
                            <h2 id="gallery-category-uncategorized" class="mt-1 text-2xl font-extrabold text-[#10243A]">Lainnya</h2>
                        </div>
                        <span class="text-sm font-semibold text-slate-400">{{ $uncategorizedFolders->count() }} folder</span>
                    </div>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach($uncategorizedFolders as $folder)
                            @include('gallery.partials.folder-card', ['folder' => $folder])
                        @endforeach
                    </div>
                </section>
            @endif
        @endif
    @endif
</section>
@endsection
