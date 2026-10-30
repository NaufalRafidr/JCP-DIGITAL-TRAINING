@extends('layouts.app')

@section('title', 'Artikel & Wawasan • JCP Training')

@section('content')
<div class="max-w-7xl mx-auto space-y-10 px-4 py-10 sm:px-6 lg:px-8">
    <div>
        <div class="mb-2 flex items-center gap-2 text-xs font-semibold text-slate-400">
            <a href="{{ route('home') }}" class="hover:text-[#087F78]">Beranda</a>
            <span>/</span>
            <span class="text-[#087F78]">Artikel</span>
        </div>
        <h1 class="text-3xl font-extrabold tracking-tight text-[#10243A] sm:text-4xl">
            Artikel & Wawasan Industri
        </h1>
        <p class="mt-1 text-sm text-slate-600">
            Tips praktis seputar AI, teknik video, drone mapping, dan strategi pemasaran modern.
        </p>
    </div>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
        @forelse($articles as $article)
            <a href="{{ route('artikel.show', ['article' => $article->slug]) }}" class="group flex h-full flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white transition-all hover:-translate-y-1 hover:border-[#087F78]/50 hover:shadow-lg">
                @if($article->image_path || $article->image_url)
                    <img src="{{ $article->image_url ?: Storage::disk('public')->url($article->image_path) }}" alt="{{ $article->title }}" loading="lazy" class="aspect-video w-full object-cover transition-transform duration-300 group-hover:scale-[1.02]">
                @else
                    <div class="flex aspect-video w-full items-center justify-center bg-[#10243A] px-6 text-center text-sm font-bold text-teal-100">JCP Training</div>
                @endif
                <div class="flex h-full flex-col space-y-3 p-6">
                    <span class="text-xs font-bold uppercase text-[#087F78]">{{ $article->category }}</span>
                    <h2 class="text-lg font-bold text-[#10243A] group-hover:text-[#087F78]">{{ $article->title }}</h2>
                    <p class="text-sm text-slate-600">{{ $article->excerpt }}</p>
                    <div class="mt-auto flex items-center justify-between gap-3 pt-2 text-xs text-slate-400">
                        <span>{{ ($article->published_at ?? $article->created_at)->format('d M Y') }}</span>
                        @if($article->author)
                            <span class="truncate">{{ $article->author }}</span>
                        @endif
                        <span class="whitespace-nowrap font-bold text-[#087F78]">Baca &rarr;</span>
                    </div>
                </div>
            </a>
        @empty
            <div class="col-span-full rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center text-sm text-slate-500">
                Belum ada artikel yang dipublikasikan.
            </div>
        @endforelse
    </div>
</div>
@endsection
