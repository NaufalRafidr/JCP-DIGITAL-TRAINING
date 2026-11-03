@extends('layouts.app')

@section('title', 'Jadwal Pelatihan • JCP Training')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">

    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs font-semibold text-slate-400 mb-2">
                <a href="{{ route('home') }}" class="hover:text-[#087F78]">Beranda</a>
                <span>/</span>
                <span class="text-[#087F78]">Jadwal Pelatihan</span>
            </div>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-[#10243A] tracking-tight">
                Jadwal Pelatihan
            </h1>
            <p class="text-slate-600 text-sm mt-1">
                Jadwal terintegrasi sistem: kuota dinamis, lokasi terverifikasi, dan status pendaftaran real-time.
            </p>
        </div>

        <!-- Category Filter -->
        <div class="flex items-center gap-2">
            <span class="text-xs font-bold text-slate-500 uppercase">Filter:</span>
            <form action="{{ route('courses.schedule') }}" method="GET" class="inline">
                <select name="kategori" onchange="this.form.submit()" class="py-2 px-3 text-xs font-semibold bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-[#087F78]">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->slug }}" {{ ($selectedCategory ?? '') === $cat->slug ? 'selected' : '' }}>
                            {{ $cat->short_name ?? $cat->name }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>

    <!-- Schedule Table (Slide 10) -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#10243A] text-white text-xs font-bold uppercase tracking-wider">
                        <th class="py-4 px-6">Batch / Jadwal</th>
                        <th class="py-4 px-6">Program</th>
                        <th class="py-4 px-6">Bidang</th>
                        <th class="py-4 px-6">Lokasi / Mode</th>
                        <th class="py-4 px-6 text-center">Status</th>
                        <th class="py-4 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($sessions as $session)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-4 px-6 font-semibold text-[#10243A]">
                                <div class="flex items-center gap-2">
                                    <span>📅</span>
                                    <span>{{ $session->start_date->format('d M Y') }}</span>
                                </div>
                                <span class="text-xs text-slate-400 font-normal block pl-6">
                                    {{ $session->batch_name }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <a href="{{ route('courses.show', $session->course->slug) }}" class="font-bold text-[#10243A] hover:text-[#087F78]">
                                    {{ $session->course->title }}
                                </a>
                                <span class="block text-xs text-slate-400 font-medium">⏱ {{ $session->duration }}</span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#E2F6F2] text-[#087F78]">
                                    {{ $session->course->category->short_name ?? $session->course->category->name }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-slate-600 text-xs">
                                {{ $session->location }}
                            </td>
                            <td class="py-4 px-6 text-center">
                                @if($session->status === 'Buka')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                                        Buka
                                    </span>
                                @elseif($session->status === 'Segera')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800">
                                        Segera
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-slate-200 text-slate-700">
                                        Penuh
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-right">
                                @if($session->status === 'Buka')
                                    <a href="{{ route('register', ['course_id' => $session->course_id, 'session_id' => $session->id]) }}" class="btn-teal px-4 py-1.5 rounded-lg text-xs font-bold inline-block">
                                        Daftar
                                    </a>
                                @else
                                    <span class="text-xs font-semibold text-slate-400">
                                        {{ $session->status === 'Segera' ? 'Segera dibuka' : 'Penuh' }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-500">
                                Belum ada jadwal tersedia untuk filter ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($sessions->hasPages())
        <div class="border-t border-slate-200 pt-6">
            {{ $sessions->links('pagination.schedule') }}
        </div>
    @endif

</div>
@endsection

