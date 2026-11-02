@extends('layouts.app')

@section('title', $course->title . ' • JCP Training')

@section('content')
@php
    $categoryArtwork = [
        'photo-video' => [
            'image' => asset('images/hero-bg.jpg'),
            'position' => 'center 74%',
        ],
        'digital-marketing' => [
            'image' => asset('images/jcp-hero-banner.jpg'),
            'position' => '82% 62%',
        ],
        'artificial-intelligence' => [
            'image' => asset('images/jcp-hero-banner.jpg'),
            'position' => '52% 48%',
        ],
        'drone-technology' => [
            'image' => asset('images/hero-light-banner.jpg'),
            'position' => '50% 18%',
        ],
        'information-technology' => [
            'image' => asset('images/hero-light-banner.jpg'),
            'position' => '78% 68%',
        ],
    ];
    $titleArtwork = $categoryArtwork[$course->category->slug] ?? [
        'image' => asset('images/hero-bg-alt.jpg'),
        'position' => 'center',
    ];
    $backgroundImage = $course->image_url ?: ($course->image
        ? Storage::disk('public')->url($course->image)
        : $titleArtwork['image']);
    $backgroundPosition = ($course->image || $course->image_url) && \App\Models\Course::isValidImagePosition($course->image_position)
        ? $course->image_position
        : $titleArtwork['position'];
    $hasOpenSession = $course->sessions->contains('status', 'Buka');
    $activityImages = [
        'photo-video' => asset('images/course-activity-photo-video.png'),
        'digital-marketing' => asset('images/course-activity-digital-marketing.png'),
        'artificial-intelligence' => asset('images/course-activity-ai.png'),
        'drone-technology' => asset('images/course-activity-drone.png'),
        'information-technology' => asset('images/course-activity-it.png'),
    ];
    $activityImage = $activityImages[$course->category->slug] ?? asset('images/course-activity-ai.png');
@endphp
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-12">

    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-xs font-semibold text-slate-400">
        <a href="{{ route('home') }}" class="hover:text-[#087F78]">Beranda</a>
        <span>/</span>
        <a href="{{ route('courses.index') }}" class="hover:text-[#087F78]">Pelatihan</a>
        <span>/</span>
        <span class="text-[#087F78] truncate max-w-xs">{{ $course->title }}</span>
    </div>

    <!-- Main Detail Layout (Slide 9 / 08 HALAMAN DETAIL) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Left Column: Course Main Details (Slide 9 left card) -->
        <div class="lg:col-span-7 bg-white rounded-3xl p-6 sm:p-10 border border-slate-200/90 shadow-xs space-y-8">
            
            <!-- Category Artwork & Title -->
            <div class="space-y-4">
                <div class="relative isolate overflow-hidden rounded-2xl border border-[#1A4961] bg-[#10243A] px-5 py-6 sm:px-7 sm:py-8">
                    <img
                        src="{{ $backgroundImage }}"
                        alt=""
                        aria-hidden="true"
                        style="object-position: {{ $backgroundPosition }}"
                        class="absolute inset-0 z-0 h-full w-full object-cover"
                    >
                    <div class="absolute inset-0 z-10 {{ $course->category->slug === 'drone-technology' ? 'bg-gradient-to-r from-[#10243A]/90 via-[#10243A]/65 to-[#10243A]/15' : 'bg-gradient-to-r from-[#10243A]/55 via-[#10243A]/20 to-transparent' }}"></div>
                    <div class="relative z-20 space-y-3">
                        <span class="inline-flex items-center rounded-full bg-white/15 px-3.5 py-1 text-xs font-bold uppercase tracking-wider text-teal-100 ring-1 ring-white/20">
                            {{ $course->category->name }}
                        </span>
                        <h1 class="max-w-3xl text-3xl font-extrabold tracking-tight text-white sm:text-4xl">
                            {{ $course->title }}
                        </h1>
                    </div>
                </div>

                <p class="text-base sm:text-lg text-slate-600 leading-relaxed">
                    {{ $course->description }}
                </p>
            </div>

            <!-- Key Metadata Strip (Slide 9: ⏱ 3 hari ◉ Praktik lapangan ✓ Basic–Intermediate) -->
            <div class="bg-[#F6F9FC] rounded-2xl p-4 sm:p-5 flex flex-wrap items-center gap-4 sm:gap-8 border border-slate-200/70 text-sm font-semibold text-[#10243A]">
                <div class="flex items-center gap-2">
                    <span class="text-base text-slate-500">⏱</span>
                    <span>{{ $summarySession?->duration ?? 'Belum ditentukan' }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-base text-[#087F78]">◉</span>
                    <span>{{ $course->format }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-base text-emerald-600">✓</span>
                    <span>{{ $course->level }}</span>
                </div>
            </div>

            <!-- What You Will Learn (Slide 9: Yang akan dipelajari) -->
            <div class="space-y-4 pt-4 border-t border-slate-100">
                <h2 class="text-xl font-extrabold text-[#10243A] tracking-tight">
                    Yang akan dipelajari
                </h2>

                @if(!empty($course->syllabus) && is_array($course->syllabus))
                    <ul class="space-y-3">
                        @foreach($course->syllabus as $item)
                            <li class="flex items-start gap-3 text-slate-700 text-sm sm:text-base">
                                <span class="w-2 h-2 rounded-full bg-[#087F78] shrink-0 mt-2"></span>
                                <span>{{ $item }}</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-sm text-slate-600">Silabus komprehensif disesuaikan dengan standar industri.</p>
                @endif
            </div>

            <!-- Outcome / Kompetensi Lulusan -->
            @if($course->outcome)
                <div class="bg-emerald-50/70 border border-emerald-200/80 rounded-2xl p-5 space-y-2">
                    <div class="text-xs font-bold uppercase tracking-wider text-emerald-800">Target Kompetensi & Output</div>
                    <p class="text-sm text-emerald-950 font-medium leading-relaxed">
                        {{ $course->outcome }}
                    </p>
                </div>
            @endif

            <!-- Cohort Schedule Details (Slide 9 & 10) -->
            <div class="space-y-4 pt-4 border-t border-slate-100">
                <h2 class="text-xl font-extrabold text-[#10243A] tracking-tight">
                    Pilihan Batch / Jadwal Terbuka
                </h2>

                <div class="space-y-3">
                    @forelse($course->sessions as $session)
                        <div class="p-4 rounded-xl border border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 hover:border-[#087F78] transition-colors">
                            <div>
                                <div class="font-bold text-[#10243A] text-sm">{{ $session->batch_name }}</div>
                                <div class="text-xs text-slate-500 mt-0.5">
                                    📅 {{ $session->start_date->format('d M Y') }} • 📍 {{ $session->location }} • Durasi {{ $session->duration }}
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $session->status === 'Buka' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                    {{ $session->status }}
                                </span>
                                @if($session->status === 'Buka')
                                    <a href="{{ route('register', ['course_id' => $course->id, 'session_id' => $session->id]) }}" class="btn-teal px-4 py-1.5 rounded-lg text-xs font-bold">
                                        Pilih Batch
                                    </a>
                                @else
                                    <span class="px-4 py-1.5 text-xs font-bold text-slate-400">
                                        {{ $session->status === 'Segera' ? 'Segera dibuka' : 'Penuh' }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">Jadwal batch berikutnya segera dibuka oleh admin.</p>
                    @endforelse
                </div>
            </div>

        </div>

        <!-- Right Column: Registration Sidebar Card (Slide 9 right card) -->
        <div class="lg:col-span-5 overflow-hidden rounded-3xl border border-slate-200/90 bg-[#10243A] text-white shadow-xl">
            <div class="relative aspect-video w-full overflow-hidden bg-[#061D2E]">
                <img
                    src="{{ $activityImage }}"
                    alt="Ilustrasi kegiatan {{ $course->category->name }}"
                    class="absolute inset-0 h-full w-full object-cover"
                    loading="lazy"
                >
                <div class="absolute inset-0 bg-gradient-to-t from-[#061D2E]/25 via-transparent to-transparent"></div>
            </div>

            <div class="space-y-6 p-6 sm:p-7">
                @if($hasOpenSession)
                    <a href="{{ route('register', ['course_id' => $course->id]) }}" class="block w-full rounded-xl bg-[#087F78] py-3.5 text-center text-sm font-bold text-white transition-all hover:bg-[#066761] shadow-md">
                        Daftar pelatihan
                    </a>
                @else
                    <span class="block w-full cursor-default rounded-xl bg-slate-700 py-3.5 text-center text-sm font-bold text-slate-300">
                        Pendaftaran belum dibuka
                    </span>
                @endif

                <div class="text-center">
                    <a href="{{ route('corporate') }}" class="text-sm font-semibold text-slate-300 underline transition-colors hover:text-white">
                        Butuh kelas untuk tim? Hubungi admin
                    </a>
                </div>

                <div class="border-t border-slate-700/80 pt-5 text-center text-xs leading-relaxed text-slate-400">
                    Pendaftaran aman & terenkripsi • Konfirmasi cepat via WhatsApp
                </div>
            </div>

        </div>

    </div>

</div>
@endsection

