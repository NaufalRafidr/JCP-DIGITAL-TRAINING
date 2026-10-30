@extends('layouts.app')

@section('title', 'Tentang Kami • JCP Training')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-12">
    
    <div>
        <div class="flex items-center gap-2 text-xs font-semibold text-slate-400 mb-2">
            <a href="{{ route('home') }}" class="hover:text-[#087F78]">Beranda</a>
            <span>/</span>
            <span class="text-[#087F78]">Tentang Kami</span>
        </div>
        <span class="text-xs font-bold uppercase tracking-wider text-[#087F78] block mb-1">Profil Lembaga</span>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-[#10243A] tracking-tight">
            {{ $profile->organization_name }}
        </h1>
        <p class="text-base text-slate-600 mt-2 leading-relaxed">
            {{ $profile->tagline }}
        </p>
    </div>

    <div class="bg-[#10243A] text-white rounded-3xl p-8 sm:p-12 space-y-6">
        <h2 class="text-2xl font-bold text-white">{{ $profile->vision_title }}</h2>
        <p class="text-slate-300 text-sm sm:text-base leading-relaxed">
            {{ $profile->vision_content }}
        </p>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-4 border-t border-slate-800 text-center">
            <div class="p-4 rounded-2xl bg-slate-800/60">
                <div class="text-3xl font-extrabold text-[#087F78]">{{ $profile->programs_stat }}</div>
                <div class="text-xs text-slate-400 mt-1">{{ $profile->programs_label }}</div>
            </div>
            <div class="p-4 rounded-2xl bg-slate-800/60">
                <div class="text-3xl font-extrabold text-[#E5AD49]">{{ $profile->expertise_stat }}</div>
                <div class="text-xs text-slate-400 mt-1">{{ $profile->expertise_label }}</div>
            </div>
            <div class="p-4 rounded-2xl bg-slate-800/60">
                <div class="text-3xl font-extrabold text-teal-400">{{ $profile->practice_stat }}</div>
                <div class="text-xs text-slate-400 mt-1">{{ $profile->practice_label }}</div>
            </div>
        </div>
    </div>

</div>
@endsection

