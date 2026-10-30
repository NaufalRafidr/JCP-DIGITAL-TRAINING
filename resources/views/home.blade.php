@extends('layouts.app')

@section('title', 'JCP TRAINING • Skill baru, peluang baru.')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 sm:pt-8 pb-16 space-y-12 sm:space-y-16">

    <!-- ==========================================
         01 / HERO SECTION (Fluid Palette • 5 Bidang Pelatihan)
         ========================================== -->
    <section class="relative bg-[#10243A] text-white rounded-[28px] sm:rounded-3xl p-8 sm:p-12 lg:p-14 overflow-hidden shadow-xl border border-slate-700/50">
        
        <!-- Background Illustration mencakup 5 Bidang Pelatihan dengan warna Navy, Teal, Mint & Amber yang mengalir dinamis -->
        <div class="absolute inset-0 z-0 pointer-events-none">
            <img 
                src="{{ asset('images/jcp-hero-banner.jpg') }}" 
                alt="JCP Training - 5 Bidang Pelatihan" 
                class="w-full h-full object-cover object-center sm:object-right"
            >
            <!-- Gradasi halus dari kiri agar warna navy menyatu secara organik dan teks tetap tajam terbaca -->
            <div class="absolute inset-0 bg-gradient-to-r from-[#10243A]/85 via-[#10243A]/40 to-transparent md:w-3/5"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-center relative z-10 min-h-[220px]">
            
            <!-- Left Column: Copy & Value Proposition -->
            <div class="md:col-span-7 lg:col-span-6 space-y-4 sm:space-y-5">
                <!-- Badge UPGRADE YOUR SKILLS -->
                <div>
                    <span class="inline-block px-5 py-2 rounded-full bg-[#087F78] text-white text-xs font-bold tracking-wider uppercase shadow-md border border-teal-400/30">
                        UPGRADE YOUR SKILLS
                    </span>
                </div>

                <!-- Main H1: Skill baru, peluang baru. -->
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white tracking-tight leading-tight drop-shadow-sm">
                    Skill baru, peluang baru.
                </h1>

                <!-- Subtitle: Praktik langsung • mentor berpengalaman -->
                <p class="text-slate-200 text-sm sm:text-base font-normal">
                    Praktik langsung • mentor berpengalaman
                </p>

                <!-- Tag 5 Bidang Pelatihan Terpadu (Sesuai Color Palette) -->
                <!-- <div class="flex flex-wrap items-center gap-2 pt-1 text-xs font-semibold">
                    <span class="px-2.5 py-1 rounded-md bg-[#087F78]/80 text-teal-100 border border-teal-400/30 backdrop-blur-xs">Foto & Video</span>
                    <span class="px-2.5 py-1 rounded-md bg-[#087F78]/80 text-teal-100 border border-teal-400/30 backdrop-blur-xs">Drone Tech</span>
                    <span class="px-2.5 py-1 rounded-md bg-[#087F78]/80 text-teal-100 border border-teal-400/30 backdrop-blur-xs">AI</span>
                    <span class="px-2.5 py-1 rounded-md bg-[#087F78]/80 text-teal-100 border border-teal-400/30 backdrop-blur-xs">IT</span>
                    <span class="px-2.5 py-1 rounded-md bg-[#E5AD49]/90 text-slate-900 border border-amber-300/40 backdrop-blur-xs font-bold">Marketing</span>
                </div> -->
            </div>

            <!-- Right Column: Memberikan ruang visual leluasa untuk ilustrasi 5 bidang terpadu -->
            <div class="hidden md:block md:col-span-5 lg:col-span-6"></div>

        </div>
    </section>

    <!-- ==========================================
         02 / SEARCH BAR (Slide 5 Item 02)
         ========================================== -->
    <section>
        <form action="{{ route('courses.index') }}" method="GET" class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-2 sm:p-3 flex items-center justify-between gap-3">
            <div class="flex items-center gap-3.5 pl-3 sm:pl-5 flex-1">
                <svg class="w-6 h-6 sm:w-7 sm:h-7 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <input 
                    type="text" 
                    name="q" 
                    placeholder="Cari pelatihan: AI, foto, drone, website..." 
                    class="w-full text-sm sm:text-base text-slate-700 placeholder-slate-400 bg-transparent border-0 focus:outline-none focus:ring-0"
                >
            </div>
            <button type="submit" class="bg-[#087F78] hover:bg-[#066761] text-white font-bold text-sm sm:text-base px-6 sm:px-8 py-3 rounded-xl transition-all shrink-0">
                Cari kelas
            </button>
        </form>
    </section>

    <!-- ==========================================
         03 / PILIH BIDANG PELATIHAN (Slide 5 Item 03)
         ========================================== -->
    <section class="space-y-4">
        <h2 class="text-xl sm:text-2xl font-bold text-[#10243A] tracking-tight">
            Pilih bidang pelatihan
        </h2>

        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 sm:gap-4 lg:grid-cols-5">
            @foreach($categories as $category)
                <button
                    type="button"
                    data-category="{{ $category->slug }}"
                    onclick="selectCategory(this.dataset.category, this)"
                    class="category-btn h-14 rounded-2xl border border-slate-200 bg-white text-sm font-bold text-slate-700 shadow-xs transition-all hover:border-[#087F78] hover:text-[#087F78] sm:h-16 sm:text-base"
                >
                    {{ $category->short_name ?? $category->name }}
                </button>
            @endforeach
        </div>
    </section>

    <!-- ==========================================
         04 / KELAS UNGGULAN (Slide 5 Item 04)
         ========================================== -->
    <section class="space-y-6 pt-2">
        <div class="flex items-center justify-between">
            <h2 class="text-xl sm:text-2xl font-bold text-[#10243A] tracking-tight">
                Kelas unggulan
            </h2>
            <a href="{{ route('courses.index') }}" class="text-xs sm:text-sm font-bold text-[#087F78] hover:underline">
                Lihat semua kelas &rarr;
            </a>
        </div>

        <!-- Kartu Kelas Unggulan -->
        <div id="course-cards-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($allCourses as $course)
                @php
                    $courseSession = $course->sessions->firstWhere('status', '!=', 'Penuh') ?? $course->sessions->first();
                @endphp
                <div 
                    data-category="{{ $course->category->slug }}"
                    class="course-card bg-white rounded-2xl border border-slate-200/90 hover:border-[#087F78]/50 shadow-xs hover:shadow-lg transition-all duration-200 flex flex-col justify-between p-6">
                    
                    <div class="space-y-3">
                        <!-- Chip Kategori Mint & Durasi -->
                        <div class="flex items-center justify-between">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-[#E2F6F2] text-[#087F78]">
                                {{ $course->category->short_name ?? $course->category->name }}
                            </span>
                            <span class="text-xs font-medium text-slate-500">
                                ⏱ {{ $courseSession?->duration ?? 'Belum ditentukan' }}
                            </span>
                        </div>

                        <!-- Judul Pelatihan -->
                        <h3 class="text-lg font-bold text-[#10243A] hover:text-[#087F78] transition-colors">
                            <a href="{{ route('courses.show', $course->slug) }}">
                                {{ $course->title }}
                            </a>
                        </h3>

                        <!-- Deskripsi Singkat -->
                        <p class="text-sm text-slate-600 line-clamp-2 leading-relaxed">
                            {{ $course->description }}
                        </p>

                        <!-- Level & Format -->
                        <div class="flex flex-wrap items-center gap-2 pt-1 text-xs text-slate-500">
                            <span class="px-2.5 py-1 rounded-md bg-slate-100">◉ {{ $course->format }}</span>
                            <span class="px-2.5 py-1 rounded-md bg-slate-100">✓ {{ $course->level }}</span>
                        </div>
                    </div>

                    <!-- Tombol Lihat Detail -->
                    <div class="pt-5 mt-5 border-t border-slate-100 flex items-center justify-between">
                        <div>
                            <span class="text-[11px] uppercase tracking-wider text-slate-400 block font-semibold">Investasi</span>
                            <span class="text-sm font-bold text-[#10243A]">{{ $course->formatted_price }}</span>
                        </div>
                        <a href="{{ route('courses.show', $course->slug) }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold text-[#087F78] bg-[#E2F6F2] hover:bg-[#087F78] hover:text-white transition-all">
                            <span>Lihat detail</span>
                            <span>&rarr;</span>
                        </a>
                    </div>

                </div>
            @endforeach
        </div>
    </section>

    <!-- ========================================================
         05 / JADWAL & COHORT (Tepat sesuai Slide 5 Item 05 & Slide 10)
         ======================================================== -->
    <section class="space-y-6 pt-6">
        <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 sm:p-10 space-y-6">
            
            <!-- Header Bagian Jadwal -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-[#087F78] block mb-1">
                        09 / Jadwal & Cohort
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-[#10243A] tracking-tight">
                        Jadwal Pelatihan
                    </h2>
                    <p class="text-slate-500 text-xs sm:text-sm mt-1">
                        Setiap kelas dapat memiliki beberapa batch, kuota, lokasi, dan status pendaftaran.
                    </p>
                </div>

                <!-- Kategori Filter Badge & Link Kalender (Slide 10) -->
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-xs font-bold bg-[#E2F6F2] text-[#087F78]">
                        Semua kategori
                    </span>
                    <a href="{{ route('courses.schedule') }}" class="text-xs font-bold text-slate-600 hover:text-[#087F78] underline">
                        Lihat semua jadwal &rarr;
                    </a>
                </div>
            </div>

            <!-- Tabel Jadwal Pelatihan (Slide 10 Table Layout) -->
            <div class="overflow-x-auto rounded-2xl border border-slate-200">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-[#10243A] text-white text-xs font-bold uppercase tracking-wider">
                            <th class="py-4 px-6">Batch / Jadwal</th>
                            <th class="py-4 px-6">Program</th>
                            <th class="py-4 px-6 hidden md:table-cell">Lokasi & Mode</th>
                            <th class="py-4 px-6 text-center">Status</th>
                            <th class="py-4 px-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @foreach($upcomingSessions as $session)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                
                                <!-- Kolom Batch / Jadwal -->
                                <td class="py-4 px-6">
                                    <div class="font-bold text-[#10243A]">
                                        {{ $session->start_date->translatedFormat('d M Y') }}
                                        @if($session->end_date && $session->end_date != $session->start_date)
                                            - {{ $session->end_date->translatedFormat('d M Y') }}
                                        @endif
                                    </div>
                                    <span class="text-xs text-slate-400 font-medium block">
                                        {{ $session->batch_name }}
                                    </span>
                                </td>

                                <!-- Kolom Program -->
                                <td class="py-4 px-6">
                                    <a href="{{ route('courses.show', $session->course->slug) }}" class="font-bold text-[#10243A] hover:text-[#087F78] transition-colors">
                                        {{ $session->course->title }}
                                    </a>
                                    <span class="block text-xs text-slate-400">
                                        {{ $session->course->category->name }} • ⏱ {{ $session->duration }}
                                    </span>
                                </td>

                                <!-- Kolom Lokasi & Mode (Hidden on mobile) -->
                                <td class="py-4 px-6 text-xs text-slate-600 hidden md:table-cell">
                                    <div class="font-medium text-[#10243A]">{{ $session->location }}</div>
                                    <span class="text-slate-400">Sisa Kuota: {{ max(0, $session->quota - $session->registrations_count) }} kursi</span>
                                </td>

                                <!-- Kolom Status (Buka = Hijau, Segera = Amber/Kuning, Penuh = Abu-abu/Merah) -->
                                <td class="py-4 px-6 text-center">
                                    @if($session->status === 'Buka')
                                        <span class="inline-flex items-center px-3.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                                            Buka
                                        </span>
                                    @elseif($session->status === 'Segera')
                                        <span class="inline-flex items-center px-3.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800">
                                            Segera
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3.5 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-800">
                                            Penuh
                                        </span>
                                    @endif
                                </td>

                                <!-- Kolom Aksi -->
                                <td class="py-4 px-6 text-right">
                                    @if($session->status === 'Buka')
                                        <a href="{{ route('register', ['course_id' => $session->course_id, 'session_id' => $session->id]) }}" class="inline-block px-4 py-2 rounded-xl text-xs font-bold text-white bg-[#087F78] hover:bg-[#066761] transition-all shadow-xs">
                                            Daftar
                                        </a>
                                    @elseif($session->status === 'Segera')
                                        <span class="inline-block cursor-default rounded-xl bg-[#E2F6F2] px-4 py-2 text-xs font-bold text-[#087F78]">
                                            Segera dibuka
                                        </span>
                                    @else
                                        @if(config('services.whatsapp.admin_number'))
                                            <a href="https://wa.me/{{ preg_replace('/\D+/', '', config('services.whatsapp.admin_number')) }}?text={{ rawurlencode('Halo Admin JCP, saya ingin masuk Waiting List kelas '.$session->course->title) }}" target="_blank" rel="noopener" class="inline-block px-3 py-1.5 rounded-xl text-xs font-semibold text-slate-400 bg-slate-100 hover:text-slate-600">
                                                Waitlist
                                            </a>
                                        @endif
                                    @endif
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Footer Catatan Jadwal -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs text-slate-500 pt-2 border-t border-slate-100">
                <div>
                    💡 <em>Butuh jadwal khusus untuk tim perusahaan / instansi?</em>
                </div>
                <a href="{{ route('corporate') }}" class="font-bold text-[#087F78] hover:underline">
                    Ajukan Jadwal In-House Training &rarr;
                </a>
            </div>

        </div>
    </section>

</div>

@push('scripts')
<script>
    // Inisialisasi filter kategori saat pertama kali dimuat – default: Foto / Video
    document.addEventListener('DOMContentLoaded', () => {
        // Aktifkan bidang pertama yang tersedia dari database.
        const firstBtn = document.querySelector('.category-btn');
        if (firstBtn) selectCategory(firstBtn.dataset.category, firstBtn);
    });

    function selectCategory(categorySlug, btnElement) {
        // Reset styling semua tombol kategori
        document.querySelectorAll('.category-btn').forEach(btn => {
            btn.classList.remove('bg-[#E2F6F2]', 'text-[#087F78]', 'border-[#087F78]/30', 'active');
            btn.classList.add('bg-white', 'text-slate-700', 'border-slate-200');
        });

        // Set tombol yang diklik menjadi aktif
        btnElement.classList.remove('bg-white', 'text-slate-700', 'border-slate-200');
        btnElement.classList.add('bg-[#E2F6F2]', 'text-[#087F78]', 'border-[#087F78]/30', 'active');

        // Tampilkan kartu yang sesuai
        filterCategoryDisplay(categorySlug);
    }

    function filterCategoryDisplay(categorySlug) {
        const cards = document.querySelectorAll('.course-card');
        cards.forEach(card => {
            if (!categorySlug || card.dataset.category === categorySlug) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });
    }
</script>
@endpush
@endsection
