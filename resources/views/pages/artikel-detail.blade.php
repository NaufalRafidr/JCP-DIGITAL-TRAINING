@extends('layouts.app')

@section('title', $article->title.' • Artikel JCP Training')

@section('content')
<article class="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="mb-8 flex items-center gap-2 text-xs font-semibold text-slate-400">
        <a href="{{ route('home') }}" class="hover:text-[#087F78]">Beranda</a>
        <span>/</span>
        <a href="{{ route('artikel') }}" class="hover:text-[#087F78]">Artikel</a>
        <span>/</span>
        <span class="truncate text-[#087F78]">{{ $article->title }}</span>
    </div>

    <header class="border-b border-slate-200 pb-8">
        <span class="text-xs font-bold uppercase tracking-wider text-[#087F78]">{{ $article->category }}</span>
        <h1 class="mt-3 text-3xl font-extrabold tracking-tight text-[#10243A] sm:text-5xl">{{ $article->title }}</h1>
        <p class="mt-4 text-sm text-slate-500">
            {{ ($article->published_at ?? $article->created_at)->format('d M Y') }}
        </p>
    </header>

    @if($article->image_path)
        <img src="{{ Storage::disk('public')->url($article->image_path) }}" alt="{{ $article->title }}" class="mt-8 aspect-video w-full rounded-3xl object-cover shadow-sm">
    @endif

    <div class="mt-8 rounded-2xl bg-slate-50 p-5 text-base font-semibold leading-relaxed text-slate-700 sm:p-6">
        {{ $article->excerpt }}
    </div>

    <div class="mt-8 whitespace-pre-line text-base leading-8 text-slate-700">
        {{ $article->content }}
    </div>
</article>
@endsection
