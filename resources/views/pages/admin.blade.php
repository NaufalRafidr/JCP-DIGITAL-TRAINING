<!DOCTYPE html>
<html lang="id" class="h-full bg-brand-ivory">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo-jcp.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>JCP ADMIN • Back Office</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Styles & Scripts via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background-color: #FAF8F1;
        }

        @keyframes registration-detail-enter {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes registration-detail-exit {
            from {
                opacity: 1;
                transform: translateY(0);
            }

            to {
                opacity: 0;
                transform: translateY(-8px);
            }
        }

        .registration-detail-panel,
        .admin-editor-panel {
            animation: registration-detail-enter 280ms ease-out both;
        }

        .registration-detail-panel.is-closing,
        .admin-editor-panel.is-closing {
            animation: registration-detail-exit 180ms ease-in both;
        }

        @media (prefers-reduced-motion: reduce) {
            .registration-detail-panel,
            .registration-detail-panel.is-closing,
            .admin-editor-panel,
            .admin-editor-panel.is-closing {
                animation: none;
            }
        }
    </style>
</head>
<body class="flex h-screen min-h-screen overflow-hidden bg-brand-ivory p-0 m-0 text-[#10243A] antialiased">

    <!-- ========================================================
         SIDEBAR KIRI NAVY (Persis Gambar Slide 16)
         ======================================================== -->
    @php
        $activeTab = request('tab', 'dashboard');
        $galleryCategories = $galleryCategories ?? collect();
        $galleryFolders = $galleryFolders ?? collect();
        $selectedGalleryFolder = $selectedGalleryFolder ?? null;
        $categoryDefaultImages = [
            'photo-video' => asset('images/category-photo-video.png'),
            'digital-marketing' => asset('images/category-digital-marketing.png'),
            'artificial-intelligence' => asset('images/category-artificial-intelligence.png'),
            'drone-technology' => asset('images/category-drone-technology.png'),
            'information-technology' => asset('images/category-information-technology.png'),
        ];
    @endphp

    <aside id="admin-sidebar" aria-label="Navigasi dashboard admin" class="fixed inset-y-0 left-0 z-40 flex h-screen w-72 max-w-[85vw] shrink-0 -translate-x-full flex-col justify-between overflow-y-auto bg-[#10243A] p-4 text-white shadow-lg transition-transform duration-200 ease-out lg:sticky lg:top-0 lg:w-64 lg:translate-x-0 lg:p-6">
        <div class="space-y-6">
            <!-- Logo Judul JCP ADMIN -->
            <div class="pt-2">
                <a href="{{ route('home') }}" class="block text-xl font-black tracking-tight text-white hover:opacity-90">
                    JCP ADMIN
                </a>
            </div>

            <!-- List Menu Sesuai Gambar:
                 - Dashboard
                 - Program
                 - Jadwal / Batch
                 - Pendaftaran
                 - Artikel
                 - Profil JCP
                 - Pengaturan
            -->
            <nav class="space-y-1.5 text-sm font-semibold">
                <a href="{{ route('admin.dashboard', ['tab' => 'dashboard']) }}" 
                   class="block px-3 py-2 transition-colors {{ $activeTab === 'dashboard' ? 'font-bold text-white' : 'text-slate-300 hover:text-white' }}">
                    Dashboard
                </a>

                <a href="{{ route('admin.dashboard', ['tab' => 'program']) }}" 
                   class="block rounded-xl px-4 py-2.5 transition-all {{ $activeTab === 'program' ? 'border border-[#087F78]/40 bg-[#087F78]/25 font-semibold text-white shadow-xs' : 'px-3 py-2 text-slate-300 hover:text-white' }}">
                    Program
                </a>

                <a href="{{ route('admin.dashboard', ['tab' => 'bidang']) }}"
                   class="block rounded-xl px-4 py-2.5 transition-all {{ $activeTab === 'bidang' ? 'border border-[#087F78]/40 bg-[#087F78]/25 font-semibold text-white shadow-xs' : 'px-3 py-2 text-slate-300 hover:text-white' }}">
                    Bidang Pelatihan
                </a>

                <!-- Active Menu: Jadwal / Batch (Sesuai Gambar Referensi) -->
                <a href="{{ route('admin.dashboard', ['tab' => 'jadwal']) }}" 
                   class="block rounded-xl px-4 py-2.5 transition-all {{ $activeTab === 'jadwal' ? 'border border-[#087F78]/40 bg-[#087F78]/25 font-semibold text-white shadow-xs' : 'px-3 py-2 text-slate-300 hover:text-white' }}">
                    Jadwal / Batch
                </a>

                <a href="{{ route('admin.dashboard', ['tab' => 'pendaftaran']) }}" class="block rounded-xl px-4 py-2.5 transition-all {{ $activeTab === 'pendaftaran' ? 'border border-[#087F78]/40 bg-[#087F78]/25 font-semibold text-white shadow-xs' : 'px-3 py-2 text-slate-300 hover:text-white' }}">
                    Pendaftaran
                </a>

                <a href="{{ route('admin.dashboard', ['tab' => 'artikel']) }}" class="block rounded-xl px-4 py-2.5 transition-all {{ $activeTab === 'artikel' ? 'border border-[#087F78]/40 bg-[#087F78]/25 font-semibold text-white shadow-xs' : 'px-3 py-2 text-slate-300 hover:text-white' }}">
                    Artikel
                </a>

                <a href="{{ route('admin.dashboard', ['tab' => 'galeri']) }}" class="block rounded-xl px-4 py-2.5 transition-all {{ $activeTab === 'galeri' ? 'border border-[#087F78]/40 bg-[#087F78]/25 font-semibold text-white shadow-xs' : 'px-3 py-2 text-slate-300 hover:text-white' }}">
                    Galeri
                </a>

                <a href="{{ route('admin.dashboard', ['tab' => 'profil']) }}" class="block rounded-xl px-4 py-2.5 transition-all {{ $activeTab === 'profil' ? 'border border-[#087F78]/40 bg-[#087F78]/25 font-semibold text-white shadow-xs' : 'px-3 py-2 text-slate-300 hover:text-white' }}">
                    Profil JCP
                </a>

                <a href="{{ route('admin.dashboard', ['tab' => 'pengaturan']) }}" class="block px-3 py-2 transition-colors {{ $activeTab === 'pengaturan' ? 'font-bold text-white' : 'text-slate-300 hover:text-white' }}">
                    Pengaturan
                </a>
            </nav>
        </div>

        <!-- Footer Kecil Sidebar -->
        <div class="border-t border-slate-800/80 pt-4 text-xs text-slate-400">
            <a href="{{ route('home') }}" class="flex items-center gap-1.5 font-bold text-[#087F78] hover:underline">
                <span>&larr; Kembali ke Website</span>
            </a>
            <form action="{{ route('admin.logout') }}" method="POST" class="mt-3" onsubmit="return confirm('Apakah Anda yakin ingin keluar dari dashboard admin?')">
                @csrf
                <button type="submit" class="w-full rounded-lg border border-white/10 bg-white/5 px-3 py-2.5 text-left text-sm font-semibold text-slate-200 transition-colors hover:bg-rose-500/20 hover:text-white">Keluar dari Admin</button>
            </form>
        </div>
    </aside>

    <button id="admin-sidebar-overlay" type="button" aria-label="Tutup menu navigasi" class="fixed inset-0 z-30 hidden bg-[#10243A]/50 lg:hidden"></button>

    <!-- ========================================================
         MAIN CONTENT AREA
         ======================================================== -->
    <main id="admin-main-content" class="h-screen min-w-0 w-full max-w-none flex-1 overflow-y-auto overscroll-contain p-4 sm:p-6 lg:max-w-6xl lg:p-14">

        <div class="sticky top-0 z-20 -mx-4 mb-6 flex items-center justify-between gap-3 bg-brand-ivory px-4 py-4 sm:-mx-6 sm:px-6 lg:static lg:mx-0 lg:hidden">
            <a href="{{ route('home') }}" class="text-lg font-black tracking-tight text-[#10243A]">JCP ADMIN</a>
            <button id="admin-sidebar-toggle" type="button" aria-controls="admin-sidebar" aria-expanded="false" class="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-[#10243A] text-white shadow-sm transition-colors hover:bg-[#087F78]" aria-label="Buka menu navigasi">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>

        <!-- Notifikasi Berhasil -->
        @if(session('success'))
            <div class="mb-8 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-semibold flex items-center justify-between shadow-xs">
                <span>✓ {{ session('success') }}</span>
                <button type="button" onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-950 font-bold text-lg leading-none">&times;</button>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-8 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-sm space-y-1 shadow-xs">
                <div class="font-bold">Mohon periksa kesalahan berikut:</div>
                <ul class="list-disc pl-5 text-xs">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- ========================================================
             TABEL 1: JADWAL / BATCH (Ketika Menu Jadwal/Batch Aktif)
             Bisa Tambah, Edit, dan Hapus Jadwal/Batch
             ======================================================== -->
        @if($activeTab === 'dashboard')
            <div class="space-y-8">
                <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#087F78]">Ringkasan operasional</p>
                        <h1 class="mt-2 text-3xl font-extrabold tracking-tight text-[#10243A] sm:text-4xl">Dashboard Admin</h1>
                        <p class="mt-2 text-sm text-slate-500">Pantau program, jadwal terdekat, dan jumlah peserta yang sudah terdaftar.</p>
                    </div>
                    <a href="{{ route('admin.dashboard', ['tab' => 'jadwal']) }}" class="inline-flex items-center justify-center rounded-xl bg-[#087F78] px-5 py-3 text-sm font-bold text-white shadow-sm transition-colors hover:bg-[#066761]">
                        Kelola jadwal <span class="ml-2" aria-hidden="true">&rarr;</span>
                    </a>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Total program</p>
                        <p class="mt-2 text-3xl font-black text-[#10243A]">{{ number_format($totalCourses) }}</p>
                        <p class="mt-1 text-xs text-slate-500">Program pelatihan terdata</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Total jadwal</p>
                        <p class="mt-2 text-3xl font-black text-[#10243A]">{{ number_format($totalSessions) }}</p>
                        <p class="mt-1 text-xs text-slate-500">Batch yang tersedia</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Total pendaftar</p>
                        <p class="mt-2 text-3xl font-black text-[#087F78]">{{ number_format($totalRegistrations) }}</p>
                        <p class="mt-1 text-xs text-slate-500">Data pendaftaran masuk</p>
                    </div>
                </div>

                <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7">
                    <div class="flex flex-col justify-between gap-2 sm:flex-row sm:items-center">
                        <div>
                            <h2 class="text-xl font-extrabold tracking-tight text-[#10243A]">Program & jadwal terdekat</h2>
                            <p class="mt-1 text-sm text-slate-500">Urutan berdasarkan tanggal mulai pelatihan paling dekat.</p>
                        </div>
                        <span class="inline-flex w-fit rounded-full bg-[#E2F6F2] px-3 py-1.5 text-xs font-bold text-[#087F78]">{{ $upcomingSessions->count() }} jadwal terdekat</span>
                    </div>

                    <div class="mt-6 space-y-3">
                        @forelse($upcomingSessions as $upcomingSession)
                            <a href="{{ route('admin.dashboard', ['tab' => 'pendaftaran', 'course_id' => $upcomingSession->course_id, 'session_id' => $upcomingSession->id]) }}" aria-label="Lihat pendaftar {{ $upcomingSession->course->title }} {{ $upcomingSession->batch_name }}" class="grid gap-4 rounded-2xl border border-slate-200/90 bg-[#FAF8F1]/70 p-4 transition-colors hover:border-[#087F78]/50 hover:bg-white sm:grid-cols-[minmax(0,1fr)_260px_auto] sm:items-center sm:p-5">
                                <div class="flex min-w-0 items-center gap-4">
                                    <div class="flex h-16 w-16 shrink-0 flex-col items-center justify-center rounded-2xl bg-[#10243A] text-white">
                                        <span class="text-xl font-black leading-none">{{ $upcomingSession->start_date->format('d') }}</span>
                                        <span class="mt-1 text-[10px] font-bold uppercase tracking-wider text-teal-200">{{ $upcomingSession->start_date->format('M') }}</span>
                                    </div>
                                    <div class="min-w-0">
                                        <h3 class="truncate text-base font-extrabold text-[#10243A]">
                                            {{ $upcomingSession->course->title }}
                                        </h3>
                                        <p class="mt-1 truncate text-xs font-semibold text-slate-500">{{ $upcomingSession->batch_name }} &bull; {{ $upcomingSession->location }}</p>
                                        <p class="mt-1 text-xs text-slate-500">Mulai {{ $upcomingSession->start_date->format('d M Y') }} &bull; {{ $upcomingSession->duration }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between gap-6 border-t border-slate-200 pt-3 sm:border-l sm:border-t-0 sm:pl-6 sm:pt-0">
                                    <div class="w-28 text-center">
                                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Pendaftar</p>
                                        <p class="mt-1 flex items-baseline justify-center gap-1 text-[#10243A] tabular-nums">
                                            <span class="text-2xl font-black leading-none">{{ number_format($upcomingSession->registrations_count) }}</span>
                                            <span class="text-sm font-semibold text-slate-400">/ {{ number_format($upcomingSession->quota) }}</span>
                                        </p>
                                    </div>
                                    <div class="flex w-24 justify-center">
                                        <span class="whitespace-nowrap rounded-full px-3 py-1.5 text-center text-xs font-extrabold {{ $upcomingSession->status === 'Buka' ? 'bg-emerald-100 text-emerald-800' : ($upcomingSession->status === 'Segera' ? 'bg-amber-100 text-amber-800' : 'bg-rose-100 text-rose-800') }}">
                                            {{ $upcomingSession->status }}
                                        </span>
                                    </div>
                                </div>
                                <span class="whitespace-nowrap text-xs font-bold text-[#087F78]">Lihat pendaftar &rarr;</span>
                            </a>
                        @empty
                            <div class="rounded-2xl border border-dashed border-slate-300 p-10 text-center text-sm text-slate-500">
                                Belum ada jadwal dengan tanggal mulai mendatang.
                            </div>
                        @endforelse
                    </div>
                </section>
            </div>

        @elseif($activeTab === 'profil')
            <div class="max-w-4xl">
                <div class="pb-8">
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#087F78]">Konten halaman publik</p>
                    <h1 class="mt-2 text-3xl font-extrabold tracking-tight text-[#10243A] sm:text-4xl">Profil JCP</h1>
                    <p class="mt-2 text-sm text-slate-500">Perbarui informasi yang tampil pada halaman Tentang untuk pengguna.</p>
                </div>

                <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                    @csrf
                    @method('PUT')

                    <div class="grid gap-5 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <label for="organization_name" class="mb-1 block text-sm font-semibold text-slate-700">Nama lembaga</label>
                            <input id="organization_name" name="organization_name" type="text" value="{{ old('organization_name', $jcpProfile->organization_name) }}" maxlength="255" required class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-[#087F78] focus:outline-none">
                        </div>
                        <div class="sm:col-span-2">
                            <label for="tagline" class="mb-1 block text-sm font-semibold text-slate-700">Deskripsi singkat</label>
                            <textarea id="tagline" name="tagline" rows="3" maxlength="500" required class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-[#087F78] focus:outline-none">{{ old('tagline', $jcpProfile->tagline) }}</textarea>
                        </div>
                        <div class="sm:col-span-2 rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-4 sm:p-5">
                            <div class="mb-4">
                                <label class="block text-sm font-semibold text-slate-700">Gambar hero beranda</label>
                                <p class="mt-1 text-xs leading-relaxed text-slate-500">Gambar ini tampil di banner utama beranda. Gunakan JPG, PNG, WEBP, atau AVIF maksimal 10 MB.</p>
                            </div>

                            @php
                                $heroPreview = $jcpProfile->hero_image_url
                                    ?: ($jcpProfile->hero_image
                                        ? Storage::disk('public')->url($jcpProfile->hero_image)
                                        : asset('images/jcp-hero-banner.jpg'));
                            @endphp
                            <img src="{{ $heroPreview }}" alt="Pratinjau gambar hero beranda" class="mb-4 h-40 w-full rounded-2xl border border-slate-200 object-cover object-center sm:h-52">

                            <label for="hero_image" class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-600">Upload gambar baru</label>
                            <input id="hero_image" name="hero_image" type="file" accept=".jpg,.jpeg,.png,.webp,.avif,image/jpeg,image/png,image/webp,image/avif" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-700 file:mr-3 file:rounded-lg file:border-0 file:bg-[#E2F6F2] file:px-3 file:py-2 file:font-semibold file:text-[#087F78]">

                            <label for="hero_image_url" class="mt-4 mb-1 block text-xs font-bold uppercase tracking-wider text-slate-600">Atau gunakan URL gambar</label>
                            <input id="hero_image_url" name="hero_image_url" type="url" value="{{ old('hero_image_url', $jcpProfile->hero_image_url) }}" placeholder="https://contoh.com/gambar-hero.jpg" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm focus:border-[#087F78] focus:outline-none">

                            @if($jcpProfile->hero_image || $jcpProfile->hero_image_url)
                                <label class="mt-4 flex items-center gap-2 text-sm font-semibold text-rose-600">
                                    <input name="remove_hero_image" type="checkbox" value="1" class="h-4 w-4 rounded border-slate-300 text-rose-600 focus:ring-rose-500">
                                    Hapus gambar custom dan gunakan gambar default beranda
                                </label>
                            @endif
                        </div>
                        <div class="sm:col-span-2">
                            <label for="vision_title" class="mb-1 block text-sm font-semibold text-slate-700">Judul visi dan komitmen</label>
                            <input id="vision_title" name="vision_title" type="text" value="{{ old('vision_title', $jcpProfile->vision_title) }}" maxlength="255" required class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-[#087F78] focus:outline-none">
                        </div>
                        <div class="sm:col-span-2">
                            <label for="vision_content" class="mb-1 block text-sm font-semibold text-slate-700">Isi visi dan komitmen</label>
                            <textarea id="vision_content" name="vision_content" rows="5" maxlength="2000" required class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-[#087F78] focus:outline-none">{{ old('vision_content', $jcpProfile->vision_content) }}</textarea>
                        </div>
                    </div>

                    <button type="submit" class="rounded-xl bg-[#087F78] px-5 py-3 text-sm font-bold text-white transition-colors hover:bg-[#066761]">Simpan profil</button>
                </form>
            </div>

        @elseif($activeTab === 'pengaturan')
            <div class="max-w-2xl">
                <div class="pb-8">
                    <h1 class="text-3xl sm:text-4xl font-extrabold text-[#10243A] tracking-tight">Pengaturan Akun Admin</h1>
                    <p class="text-sm text-slate-500 mt-2">Reset password untuk akun admin yang sedang digunakan.</p>
                </div>

                <form action="{{ route('admin.account.update') }}" method="POST" class="rounded-3xl border border-slate-200 bg-white p-6 sm:p-8 space-y-5 shadow-sm">
                    @csrf
                    @method('PUT')
                    <div>
                        <label for="password" class="mb-1 block text-sm font-semibold text-slate-700">Password baru</label>
                        <div class="relative">
                            <input id="password" name="password" type="password" autocomplete="new-password" minlength="5" required class="w-full rounded-xl border border-slate-300 px-4 py-3 pr-12 text-sm focus:border-[#087F78] focus:outline-none">
                            <button type="button" data-password-toggle="password" aria-controls="password" aria-label="Tampilkan password" aria-pressed="false" class="absolute right-2 top-1/2 inline-flex h-10 w-10 -translate-y-1/2 items-center justify-center rounded-lg text-slate-400 transition-colors hover:bg-slate-100 hover:text-[#087F78] focus:outline-none focus:ring-2 focus:ring-[#087F78]/30">
                                <svg data-password-eye class="hidden h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7C20.268 16.057 16.477 19 12 19s-8.268-2.943-9.542-7Z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                                <svg data-password-eye-off class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m3 3 18 18M10.584 10.587a2 2 0 0 0 2.829 2.829M9.88 5.09A10.94 10.94 0 0 1 12 5c4.477 0 8.268 2.943 9.542 7a11.05 11.05 0 0 1-2.112 3.592M6.228 6.228A11.04 11.04 0 0 0 2.458 12C3.732 16.057 7.523 19 12 19c1.61 0 3.12-.397 4.449-1.098" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div>
                        <label for="password_confirmation" class="mb-1 block text-sm font-semibold text-slate-700">Konfirmasi kata sandi baru</label>
                        <div class="relative">
                            <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" minlength="5" required class="w-full rounded-xl border border-slate-300 px-4 py-3 pr-12 text-sm focus:border-[#087F78] focus:outline-none">
                            <button type="button" data-password-toggle="password_confirmation" aria-controls="password_confirmation" aria-label="Tampilkan password" aria-pressed="false" class="absolute right-2 top-1/2 inline-flex h-10 w-10 -translate-y-1/2 items-center justify-center rounded-lg text-slate-400 transition-colors hover:bg-slate-100 hover:text-[#087F78] focus:outline-none focus:ring-2 focus:ring-[#087F78]/30">
                                <svg data-password-eye class="hidden h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7C20.268 16.057 16.477 19 12 19s-8.268-2.943-9.542-7Z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                                <svg data-password-eye-off class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m3 3 18 18M10.584 10.587a2 2 0 0 0 2.829 2.829M9.88 5.09A10.94 10.94 0 0 1 12 5c4.477 0 8.268 2.943 9.542 7a11.05 11.05 0 0 1-2.112 3.592M6.228 6.228A11.04 11.04 0 0 0 2.458 12C3.732 16.057 7.523 19 12 19c1.61 0 3.12-.397 4.449-1.098" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="border-t border-slate-100 pt-5">
                        <label for="current_password" class="mb-1 block text-sm font-semibold text-slate-700">Kata sandi saat ini <span class="text-rose-600">*</span></label>
                        <div class="relative">
                            <input id="current_password" name="current_password" type="password" autocomplete="current-password" required class="w-full rounded-xl border border-slate-300 px-4 py-3 pr-12 text-sm focus:border-[#087F78] focus:outline-none">
                            <button type="button" data-password-toggle="current_password" aria-controls="current_password" aria-label="Tampilkan password" aria-pressed="false" class="absolute right-2 top-1/2 inline-flex h-10 w-10 -translate-y-1/2 items-center justify-center rounded-lg text-slate-400 transition-colors hover:bg-slate-100 hover:text-[#087F78] focus:outline-none focus:ring-2 focus:ring-[#087F78]/30">
                                <svg data-password-eye class="hidden h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7C20.268 16.057 16.477 19 12 19s-8.268-2.943-9.542-7Z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                                <svg data-password-eye-off class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m3 3 18 18M10.584 10.587a2 2 0 0 0 2.829 2.829M9.88 5.09A10.94 10.94 0 0 1 12 5c4.477 0 8.268 2.943 9.542 7a11.05 11.05 0 0 1-2.112 3.592M6.228 6.228A11.04 11.04 0 0 0 2.458 12C3.732 16.057 7.523 19 12 19c1.61 0 3.12-.397 4.449-1.098" />
                                </svg>
                            </button>
                        </div>
                        <p class="mt-1 text-xs text-slate-500">Masukkan kata sandi saat ini untuk mengonfirmasi perubahan.</p>
                    </div>
                    <button type="submit" class="rounded-xl bg-[#087F78] px-5 py-3 text-sm font-bold text-white hover:bg-[#066761]">Reset password</button>
                </form>
            </div>

        @elseif($activeTab === 'jadwal')
            <!-- Header Area Jadwal / Batch -->
            <div class="flex flex-col items-stretch justify-between gap-4 pb-8 sm:flex-row sm:items-center">
                <div>
                    <h1 class="text-3xl sm:text-4xl font-extrabold text-[#10243A] tracking-tight">
                        Jadwal / Batch
                    </h1>
                    <p class="text-xs text-slate-500 mt-1">
                        Durasi pelatihan dihitung otomatis dari tanggal mulai hingga tanggal selesai.
                    </p>
                </div>

                <button 
                    type="button" 
                    onclick="openAddSessionModal()"
                    class="flex w-full cursor-pointer items-center justify-center gap-2 rounded-2xl bg-[#087F78] px-7 py-3 text-sm font-bold text-white shadow-sm transition-all hover:bg-[#066761] focus:outline-none sm:w-auto">
                    <span class="text-base font-bold leading-none">+</span>
                    <span>Tambah jadwal / batch</span>
                </button>
            </div>

            <!-- Table Header Labels (Jadwal/Batch) -->
            <div class="grid grid-cols-12 px-8 py-3 text-sm font-bold text-[#10243A] tracking-tight mb-2">
                <div class="col-span-3">Nama Batch</div>
                <div class="col-span-3">Program Pelatihan</div>
                <div class="col-span-2">Mulai Berjalan</div>
                <div class="col-span-2">Durasi</div>
                <div class="col-span-1">Status</div>
                <div class="col-span-1" aria-hidden="true"></div>
            </div>

            <!-- List Baris Card Jadwal / Batch -->
            <div class="space-y-4">
                @forelse($scheduleSessions as $s)
                    @php
                        $sessionEditorQuery = ['tab' => 'jadwal', 'session_id' => $s->id, 'page' => $scheduleSessions->currentPage()];
                    @endphp
                    <article class="admin-editor-card cursor-pointer rounded-2xl border bg-white px-5 py-5 text-sm shadow-[0_1px_3px_rgba(0,0,0,0.03)] transition-all hover:border-[#087F78]/50 hover:shadow-md md:grid md:grid-cols-12 md:items-center md:px-8 {{ $selectedSession?->id === $s->id ? 'border-[#087F78] ring-2 ring-[#087F78]/10' : 'border-slate-200/90' }}" data-editor-url="{{ route('admin.dashboard', $sessionEditorQuery) }}" tabindex="0" role="button" aria-expanded="{{ $selectedSession?->id === $s->id ? 'true' : 'false' }}" aria-label="Edit jadwal {{ $s->batch_name }}">
                        
                        <!-- Nama Batch -->
                        <div class="font-bold text-slate-800 md:col-span-3 md:pr-2">
                            {{ $s->batch_name }}
                        </div>

                        <!-- Program Pelatihan -->
                        <div class="mt-3 text-slate-600 font-medium md:col-span-3 md:mt-0 md:pr-2">
                            <a href="{{ route('courses.show', $s->course->slug) }}" target="_blank" class="hover:text-[#087F78]">
                                {{ $s->course->title }}
                            </a>
                        </div>

                        <!-- Mulai Berjalan -->
                        <div class="mt-3 text-slate-500 font-medium text-xs md:col-span-2 md:mt-0">
                            📅 {{ $s->start_date->format('d M Y') }}
                        </div>

                        <!-- Durasi Otomatis (Dihitung dari hari ini ke start_date) -->
                        <div class="mt-3 text-slate-600 font-bold text-xs md:col-span-2 md:mt-0">
                            <span class="inline-block px-2.5 py-1 rounded-lg bg-slate-100 text-[#10243A]">
                                ⏱ {{ $s->duration }}
                            </span>
                        </div>

                        <!-- Status -->
                        <div class="mt-3 md:col-span-1 md:mt-0">
                            <span class="font-extrabold text-xs {{ $s->status === 'Buka' ? 'text-[#087F78]' : ($s->status === 'Segera' ? 'text-amber-600' : 'text-rose-600') }}">
                                {{ $s->status }}
                            </span>
                        </div>

                        <!-- Hapus jadwal -->
                        <div class="mt-3 flex items-center justify-end gap-2 md:col-span-1 md:mt-0">
                            <form action="{{ route('admin.sessions.destroy', $s->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" data-delete-type="Jadwal / Batch" data-delete-name="{{ $s->batch_name }}" onclick="openAdminDeleteModal(this.form, this.dataset.deleteType, this.dataset.deleteName)" title="Hapus Jadwal" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-slate-100 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>

                        @if($selectedSession?->id === $s->id)
                            <div class="admin-editor-panel mt-6 border-t border-slate-100 pt-6 md:col-span-12" data-editor-panel>
                                <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-start">
                                    <div>
                                        <p class="text-xs font-bold uppercase tracking-wider text-[#087F78]">Pengaturan jadwal / batch</p>
                                        <h3 class="mt-1 text-xl font-extrabold text-[#10243A]">Edit {{ $s->batch_name }}</h3>
                                    </div>
                                    <a href="{{ route('admin.dashboard', ['tab' => 'jadwal']) }}" data-close-editor class="inline-flex w-fit items-center rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-bold text-slate-600 transition-colors hover:border-[#087F78]/40 hover:bg-[#E2F6F2] hover:text-[#087F78]">Tutup</a>
                                </div>

                                <form action="{{ route('admin.sessions.update', $s) }}" method="POST" class="mt-5 space-y-5">
                                    @csrf
                                    @method('PUT')
                                    <div class="grid gap-4 sm:grid-cols-2">
                                        <div class="sm:col-span-2">
                                            <label for="inline_session_course_{{ $s->id }}" class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-700">Program pelatihan *</label>
                                            <select id="inline_session_course_{{ $s->id }}" name="course_id" required class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-[#087F78] focus:outline-none">
                                                @foreach($courses as $course)
                                                    <option value="{{ $course->id }}" @selected($s->course_id === $course->id)>{{ $course->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="sm:col-span-2">
                                            <label for="inline_session_batch_{{ $s->id }}" class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-700">Nama batch / periode *</label>
                                            <input id="inline_session_batch_{{ $s->id }}" name="batch_name" type="text" value="{{ $s->batch_name }}" required maxlength="255" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-[#087F78] focus:outline-none">
                                        </div>
                                        <div>
                                            <label for="inline_session_start_{{ $s->id }}" class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-700">Tanggal mulai *</label>
                                            <input id="inline_session_start_{{ $s->id }}" name="start_date" type="date" value="{{ $s->start_date->format('Y-m-d') }}" required class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-[#087F78] focus:outline-none">
                                        </div>
                                        <div>
                                            <label for="inline_session_end_{{ $s->id }}" class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-700">Tanggal selesai *</label>
                                            <input id="inline_session_end_{{ $s->id }}" name="end_date" type="date" value="{{ $s->end_date->format('Y-m-d') }}" required class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-[#087F78] focus:outline-none">
                                        </div>
                                        <div>
                                            <label for="inline_session_location_{{ $s->id }}" class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-700">Lokasi / tempat</label>
                                            <input id="inline_session_location_{{ $s->id }}" name="location" type="text" value="{{ $s->location }}" maxlength="255" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-[#087F78] focus:outline-none">
                                        </div>
                                        <div>
                                            <label for="inline_session_quota_{{ $s->id }}" class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-700">Kuota peserta *</label>
                                            <input id="inline_session_quota_{{ $s->id }}" name="quota" type="number" value="{{ $s->quota }}" min="1" required class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-[#087F78] focus:outline-none">
                                        </div>
                                        <div>
                                            <label for="inline_session_price_{{ $s->id }}" class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-700">Biaya investasi (Rp)</label>
                                            <input id="inline_session_price_{{ $s->id }}" name="price" type="number" value="{{ (int) $s->price }}" min="0" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-[#087F78] focus:outline-none">
                                        </div>
                                        <div>
                                            <label for="inline_session_status_{{ $s->id }}" class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-700">Status *</label>
                                            <select id="inline_session_status_{{ $s->id }}" name="status" required class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-[#087F78] focus:outline-none">
                                                <option value="Buka" @selected($s->status === 'Buka')>Buka</option>
                                                <option value="Segera" @selected($s->status === 'Segera')>Segera</option>
                                                <option value="Penuh" @selected($s->status === 'Penuh')>Penuh</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="flex justify-end gap-3 border-t border-slate-100 pt-5">
                                        <a href="{{ route('admin.dashboard', ['tab' => 'jadwal']) }}" data-close-editor class="rounded-xl px-5 py-2.5 text-sm font-bold text-slate-600 transition-colors hover:bg-slate-100">Batal</a>
                                        <button type="submit" class="rounded-xl bg-[#087F78] px-6 py-2.5 text-sm font-bold text-white transition-colors hover:bg-[#066761]">Simpan jadwal</button>
                                    </div>
                                </form>
                            </div>
                        @endif

                    </article>
                @empty
                    <div class="p-12 text-center text-slate-400 border border-dashed border-slate-200 rounded-2xl">
                        Belum ada jadwal / batch pelatihan. Klik tombol "+ Tambah jadwal / batch" di atas untuk menambahkan.
                    </div>
                @endforelse
            </div>

            @if($scheduleSessions->hasPages())
                <div class="mt-6 border-t border-slate-100 pt-6">
                    {{ $scheduleSessions->links('pagination.schedule') }}
                </div>
            @endif

            <!-- Tautan Cepat ke Tabel Program -->
            <div class="pt-8 text-center">
                <a href="{{ route('admin.dashboard', ['tab' => 'program']) }}" class="text-xs font-bold text-[#087F78] hover:underline">
                    &rarr; Kelola Data Program Pelatihan
                </a>
            </div>

        @elseif($activeTab === 'pendaftaran')
            <div class="flex flex-col justify-between gap-4 pb-8 sm:flex-row sm:items-end">
                <div>
                    <h1 class="text-3xl font-extrabold tracking-tight text-[#10243A] sm:text-4xl">Data Pendaftaran</h1>
                    <p class="mt-2 text-sm text-slate-500">Daftar peserta yang sudah mengirimkan pendaftaran program pelatihan. Pilih status lalu tekan Simpan untuk memperbarui prosesnya.</p>
                </div>
                <span class="inline-flex w-fit rounded-full bg-[#E2F6F2] px-3.5 py-2 text-xs font-bold text-[#087F78]">
                    {{ number_format($recentRegistrations->count()) }} pendaftar ditampilkan
                </span>
            </div>

            <form method="GET" action="{{ route('admin.dashboard') }}" class="mb-6 flex flex-col gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:flex-row sm:items-end">
                <input type="hidden" name="tab" value="pendaftaran">
                <div class="grid min-w-0 flex-1 grid-cols-1 gap-3 sm:grid-cols-2">
                    <div>
                        <label for="registration_course_filter" class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-600">Filter berdasarkan kelas</label>
                        <select id="registration_course_filter" name="course_id" onchange="filterRegistrationBatches(this.value)" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-[#10243A] focus:border-[#087F78] focus:outline-none focus:ring-2 focus:ring-[#087F78]/20">
                            <option value="">Semua kelas</option>
                            @foreach($courses->sortBy('title') as $course)
                                <option value="{{ $course->id }}" @selected($selectedRegistrationCourseId === $course->id)>{{ $course->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="registration_session_filter" class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-600">Filter berdasarkan batch</label>
                        <select id="registration_session_filter" name="session_id" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-[#10243A] focus:border-[#087F78] focus:outline-none focus:ring-2 focus:ring-[#087F78]/20">
                            <option value="">Semua batch</option>
                            @foreach($sessions->sortBy('start_date') as $session)
                                <option value="{{ $session->id }}" data-course-id="{{ $session->course_id }}" @selected($selectedRegistrationSessionId === $session->id)>{{ $session->batch_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button type="submit" class="rounded-xl bg-[#087F78] px-5 py-2.5 text-sm font-bold text-white transition-colors hover:bg-[#066761]">Tampilkan</button>
                    @if($selectedRegistrationCourseId > 0 || $selectedRegistrationSessionId > 0)
                        <a href="{{ route('admin.dashboard', ['tab' => 'pendaftaran']) }}" class="rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-bold text-slate-600 transition-colors hover:bg-slate-50">Reset</a>
                    @endif
                </div>
            </form>

            @php
                $registrationListQuery = array_filter([
                    'tab' => 'pendaftaran',
                    'course_id' => $selectedRegistrationCourseId,
                    'session_id' => $selectedRegistrationSessionId,
                ]);
            @endphp

            <div class="hidden grid-cols-12 px-8 py-3 text-sm font-bold tracking-tight text-[#10243A] md:grid">
                <div class="col-span-3">Nama peserta</div>
                <div class="col-span-1 -translate-x-8 whitespace-nowrap pr-3">ID registrasi</div>
                <div class="col-span-2 pl-2">Program</div>
                <div class="col-span-2">Jadwal / batch</div>
                <div class="col-span-2">Tanggal daftar</div>
                <div class="col-span-2 text-right">Status</div>
            </div>

            <div class="space-y-4">
                @forelse($recentRegistrations as $registration)
                    @php
                        $isCorporate = $registration->isCorporate();
                        $registrationDetailQuery = array_merge($registrationListQuery, ['registration_id' => $registration->id]);
                    @endphp
                    <article
                        class="cursor-pointer rounded-2xl border bg-white px-5 py-5 shadow-[0_1px_3px_rgba(0,0,0,0.03)] transition-all hover:border-[#087F78]/50 hover:shadow-md md:grid md:grid-cols-12 md:items-center md:px-8 {{ $selectedRegistration?->id === $registration->id ? 'border-[#087F78] ring-2 ring-[#087F78]/10' : 'border-slate-200/90' }}"
                        data-registration-url="{{ route('admin.dashboard', $registrationDetailQuery) }}"
                        role="button"
                        tabindex="0"
                        aria-expanded="{{ $selectedRegistration?->id === $registration->id ? 'true' : 'false' }}"
                        aria-label="Lihat detail pendaftar {{ $registration->name }}"
                    >
                        <div class="min-w-0 md:col-span-3">
                            <h2 class="truncate font-bold text-slate-800">{{ $registration->name }}</h2>
                            <span class="mt-2 inline-flex whitespace-nowrap rounded-full px-2 py-1 text-[9px] font-extrabold uppercase tracking-wider {{ $isCorporate ? 'bg-orange-200 text-orange-900 ring-1 ring-orange-300' : 'bg-sky-100 text-sky-800 ring-1 ring-sky-200' }}">
                                {{ $isCorporate ? 'Permintaan Corporate' : 'Pendaftaran Personal' }}
                            </span>
                            <a href="mailto:{{ $registration->email }}" class="mt-1 block truncate text-xs text-slate-500 hover:text-[#087F78]">{{ $registration->email }}</a>
                            <a href="tel:{{ $registration->phone }}" class="mt-1 block text-xs text-slate-500 hover:text-[#087F78]">{{ $registration->phone }}</a>
                        </div>

                        <div class="mt-4 border-t border-slate-100 pt-4 md:col-span-1 md:mt-0 md:-translate-x-4 md:border-t-0 md:pt-0 md:pr-3">
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-400 md:hidden">ID registrasi</p>
                            <p class="mt-1 text-sm font-bold text-slate-400 md:mt-0">#JCP-{{ str_pad($registration->id, 4, '0', STR_PAD_LEFT) }}</p>
                        </div>

                        <div class="mt-4 min-w-0 border-t border-slate-100 pt-4 md:col-span-2 md:mt-0 md:border-t-0 md:pt-0 md:pl-2">
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-400 md:hidden">Program</p>
                            @if($registration->course)
                                <a href="{{ route('courses.show', $registration->course->slug) }}" target="_blank" class="mt-1 block truncate text-sm font-semibold text-slate-700 hover:text-[#087F78] md:mt-0">
                                    {{ $registration->course->title }}
                                </a>
                            @else
                                <p class="mt-1 text-sm text-slate-400 md:mt-0">Program tidak tersedia</p>
                            @endif
                            @if($registration->organization)
                                <p class="mt-1 truncate text-xs text-slate-500">{{ $registration->organization }}</p>
                            @endif
                        </div>

                        <div class="mt-4 border-t border-slate-100 pt-4 md:col-span-2 md:mt-0 md:border-t-0 md:pt-0">
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-400 md:hidden">Jadwal / batch</p>
                            <p class="mt-1 truncate text-sm text-slate-600 md:mt-0">{{ $registration->courseSession?->batch_name ?? 'Belum memilih batch' }}</p>
                        </div>

                        <div class="mt-4 border-t border-slate-100 pt-4 md:col-span-2 md:mt-0 md:border-t-0 md:pt-0">
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-400 md:hidden">Tanggal daftar</p>
                            <p class="mt-1 text-sm text-slate-600 md:mt-0">{{ $registration->created_at->format('d M Y') }}</p>
                        </div>

                        <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-4 md:col-span-2 md:mt-0 md:justify-end md:border-t-0 md:pt-0">
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-400 md:hidden">Status</p>
                            <form action="{{ route('admin.registrations.update', $registration) }}" method="POST" class="flex items-center gap-2">
                                @csrf
                                @method('PUT')
                                @if($selectedRegistrationCourseId > 0)
                                    <input type="hidden" name="redirect_course_id" value="{{ $selectedRegistrationCourseId }}">
                                @endif
                                @if($selectedRegistrationSessionId > 0)
                                    <input type="hidden" name="redirect_session_id" value="{{ $selectedRegistrationSessionId }}">
                                @endif
                                <label for="registration_status_{{ $registration->id }}" class="sr-only">Status pendaftaran {{ $registration->name }}</label>
                                <select id="registration_status_{{ $registration->id }}" name="status" class="rounded-lg border border-slate-200 bg-white px-2.5 py-2 text-xs font-bold text-[#10243A] focus:border-[#087F78] focus:outline-none focus:ring-2 focus:ring-[#087F78]/20">
                                    <option value="Pending" @selected($registration->status === 'Pending')>Pending</option>
                                    <option value="Follow-up" @selected($registration->status === 'Follow-up')>Follow-up</option>
                                    <option value="Confirmed" @selected($registration->status === 'Confirmed')>Confirmed</option>
                                </select>
                                <button type="submit" class="rounded-lg bg-[#087F78] px-2.5 py-2 text-xs font-bold text-white transition-colors hover:bg-[#066761]">Simpan</button>
                            </form>
                        </div>

                        @if($selectedRegistration?->id === $registration->id)
                            <div class="registration-detail-panel mt-6 border-t border-slate-100 pt-6 md:col-span-12" data-registration-detail aria-labelledby="selected-registration-heading-{{ $registration->id }}">
                                <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-start">
                                    <div>
                                        <p class="text-xs font-bold uppercase tracking-wider text-[#087F78]">Informasi lengkap</p>
                                        <h3 id="selected-registration-heading-{{ $registration->id }}" class="mt-1 text-xl font-extrabold text-[#10243A]">Detail pendaftar</h3>
                                    </div>
                                </div>

                                <dl class="mt-5 grid grid-cols-1 gap-x-8 gap-y-5 text-sm sm:grid-cols-2 lg:grid-cols-3">
                                    <div>
                                        <dt class="text-xs font-bold uppercase tracking-wider text-slate-400">ID registrasi</dt>
                                        <dd class="mt-1 font-semibold text-[#10243A]">#JCP-{{ str_pad($registration->id, 4, '0', STR_PAD_LEFT) }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-xs font-bold uppercase tracking-wider text-slate-400">Jenis pendaftaran</dt>
                                        <dd class="mt-1 font-semibold text-[#10243A]">{{ $isCorporate ? 'Permintaan Corporate' : 'Pendaftaran Personal' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-xs font-bold uppercase tracking-wider text-slate-400">Email</dt>
                                        <dd class="mt-1 break-words font-semibold text-[#10243A]">{{ $registration->email }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-xs font-bold uppercase tracking-wider text-slate-400">No. WhatsApp</dt>
                                        <dd class="mt-1 font-semibold text-[#10243A]">{{ $registration->phone }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-xs font-bold uppercase tracking-wider text-slate-400">Instansi / perusahaan</dt>
                                        <dd class="mt-1 font-semibold text-[#10243A]">{{ $registration->organization ?: 'Tidak diisi' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-xs font-bold uppercase tracking-wider text-slate-400">Program pelatihan</dt>
                                        <dd class="mt-1 font-semibold text-[#10243A]">{{ $registration->course?->title ?? 'Belum memilih program' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-xs font-bold uppercase tracking-wider text-slate-400">Jadwal / batch</dt>
                                        <dd class="mt-1 font-semibold text-[#10243A]">{{ $registration->courseSession?->batch_name ?? 'Belum memilih batch' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-xs font-bold uppercase tracking-wider text-slate-400">Tanggal daftar</dt>
                                        <dd class="mt-1 font-semibold text-[#10243A]">{{ $registration->created_at->format('d M Y, H:i') }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-xs font-bold uppercase tracking-wider text-slate-400">Persetujuan data</dt>
                                        <dd class="mt-1 font-semibold text-[#10243A]">{{ $registration->consent ? 'Disetujui' : 'Belum disetujui' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-xs font-bold uppercase tracking-wider text-slate-400">Status</dt>
                                        <dd class="mt-1 font-semibold text-[#087F78]">{{ $registration->status }}</dd>
                                    </div>
                                    <div class="sm:col-span-2 lg:col-span-3">
                                        <dt class="text-xs font-bold uppercase tracking-wider text-slate-400">Catatan / kebutuhan khusus</dt>
                                        <dd class="mt-2 whitespace-pre-line rounded-xl bg-slate-50 p-4 leading-relaxed text-slate-600">{{ $registration->notes ?: 'Tidak ada catatan tambahan.' }}</dd>
                                    </div>
                                </dl>

                                <div class="mt-6 flex justify-end border-t border-slate-100 pt-5">
                                    <a href="{{ route('admin.dashboard', $registrationListQuery) }}" data-close-registration class="inline-flex items-center rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-bold text-slate-600 transition-all duration-200 hover:border-[#087F78]/40 hover:bg-[#E2F6F2] hover:text-[#087F78]">
                                        Tutup
                                    </a>
                                </div>
                            </div>
                        @endif
                    </article>
                @empty
                    <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center text-sm text-slate-500">
                        Belum ada peserta yang mendaftar.
                    </div>
                @endforelse
            </div>

        @elseif($activeTab === 'artikel')
            <div class="flex flex-col justify-between gap-4 pb-8 sm:flex-row sm:items-end">
                <div>
                    <h1 class="text-3xl font-extrabold tracking-tight text-[#10243A] sm:text-4xl">Kelola Artikel</h1>
                    <p class="mt-2 text-sm text-slate-500">Tulis, jadwalkan, dan publikasikan artikel yang tampil di halaman Artikel pengguna.</p>
                </div>
            </div>

            <section class="mb-8 rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7">
                <div class="mb-6 flex flex-col justify-between gap-3 border-b border-slate-100 pb-5 sm:flex-row sm:items-start">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-[#087F78]">Editor artikel</p>
                        <h2 class="mt-1 text-2xl font-extrabold tracking-tight text-[#10243A]">Tulis artikel baru.</h2>
                        <p class="mt-1 text-sm text-slate-500">Simpan sebagai draft, jadwalkan, atau publikasikan sekarang.</p>
                    </div>
                    <span class="rounded-full bg-[#E2F6F2] px-3 py-1.5 text-xs font-bold text-[#087F78]">Maksimal 1 artikel per hari</span>
                </div>

                <form id="addArticleInlineForm" action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data" onsubmit="return validateRichEditor(this)" class="space-y-5">
                    @csrf
                    <input id="add_article_inline_status" name="status" type="hidden" value="{{ old('status', 'Draft') }}">
                    <div class="grid gap-5 lg:grid-cols-[minmax(0,1.05fr)_minmax(280px,0.95fr)]">
                        <div class="space-y-5">
                            <div>
                                <label for="add_article_title_inline" class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-700">Judul artikel *</label>
                                <input id="add_article_title_inline" name="title" type="text" value="{{ old('title') }}" required maxlength="255" placeholder="Contoh: Tips Memotret dengan Drone" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-[#087F78] focus:outline-none">
                            </div>
                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label for="add_article_author_inline" class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-700">Penulis / tim *</label>
                                    <input id="add_article_author_inline" name="author" type="text" value="{{ old('author') }}" required maxlength="100" placeholder="Contoh: Tim Media JCP" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-[#087F78] focus:outline-none">
                                </div>
                                <div>
                                    <label for="add_article_category_inline" class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-700">Bidang pelatihan *</label>
                                    <select id="add_article_category_inline" name="category" required class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-[#087F78] focus:outline-none">
                                        <option value="">Pilih bidang pelatihan</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->name }}" @selected(old('category') === $category->name)>{{ $category->name }}</option>
                                        @endforeach
                                        @if(old('category') && ! $categories->contains('name', old('category')))
                                            <option value="{{ old('category') }}" selected>{{ old('category') }}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label for="add_article_excerpt_inline" class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-700">Ringkasan singkat *</label>
                                <textarea id="add_article_excerpt_inline" name="excerpt" rows="3" maxlength="500" required placeholder="Tuliskan 2–3 kalimat ringkasan yang muncul pada kartu artikel." class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-[#087F78] focus:outline-none">{{ old('excerpt') }}</textarea>
                            </div>
                        </div>

                        <div class="space-y-5 rounded-2xl border border-dashed border-slate-300 bg-slate-50/70 p-4 sm:p-5">
                            <div>
                                <label for="add_article_image_inline" class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-700">Gambar utama</label>
                                <input id="add_article_image_inline" name="image" type="file" accept="image/jpeg,image/png,image/webp,image/avif" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm file:mr-3 file:rounded-lg file:border-0 file:bg-teal-50 file:px-3 file:py-2 file:font-semibold file:text-[#087F78]">
                                <p class="mt-1 text-xs text-slate-500">Pilih file gambar maksimal 10 MB.</p>
                            </div>
                            <div class="flex items-center gap-3 text-[10px] font-bold uppercase tracking-wider text-slate-400"><span class="h-px flex-1 bg-slate-200"></span>atau<span class="h-px flex-1 bg-slate-200"></span></div>
                            <div>
                                <label for="add_article_image_url_inline" class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-700">URL gambar</label>
                                <input id="add_article_image_url_inline" name="image_url" type="url" value="{{ old('image_url') }}" maxlength="2048" placeholder="https://contoh.com/gambar.jpg" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-[#087F78] focus:outline-none">
                                <p class="mt-1 text-xs text-slate-500">Jika file dan URL diisi, file akan digunakan.</p>
                            </div>
                            <div>
                                <label for="add_article_published_at_inline" class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-700">Tanggal tayang / jadwal</label>
                                <input id="add_article_published_at_inline" name="published_at" type="date" value="{{ old('published_at') }}" min="{{ now()->toDateString() }}" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-[#087F78] focus:outline-none">
                                <p class="mt-1 text-xs text-slate-500">Satu tanggal hanya dapat berisi satu artikel.</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-700">Isi artikel *</label>
                        <div data-rich-editor class="overflow-hidden rounded-xl border border-slate-200 focus-within:border-[#087F78]">
                            <div class="flex flex-wrap items-center gap-1 border-b border-slate-200 bg-slate-50 p-2">
                                <button type="button" data-rich-command="bold" class="rounded-lg px-3 py-1.5 text-sm font-bold text-slate-700 hover:bg-white">B</button>
                                <button type="button" data-rich-command="italic" class="rounded-lg px-3 py-1.5 text-sm italic text-slate-700 hover:bg-white">I</button>
                                <button type="button" data-rich-command="underline" class="rounded-lg px-3 py-1.5 text-sm underline text-slate-700 hover:bg-white">U</button>
                                <button type="button" data-rich-command="insertUnorderedList" class="rounded-lg px-3 py-1.5 text-xs font-bold text-slate-700 hover:bg-white">• List</button>
                                <button type="button" data-rich-command="insertOrderedList" class="rounded-lg px-3 py-1.5 text-xs font-bold text-slate-700 hover:bg-white">1. List</button>
                                <button type="button" data-rich-command="formatBlock" data-rich-value="blockquote" class="rounded-lg px-3 py-1.5 text-xs font-bold text-slate-700 hover:bg-white">Kutipan</button>
                            </div>
                            <div contenteditable="true" data-rich-surface data-placeholder="Tuliskan isi artikel dengan format yang Anda butuhkan..." oninput="this.nextElementSibling.value = this.innerHTML" class="min-h-56 px-4 py-4 text-sm leading-7 outline-none empty:before:text-slate-400 empty:before:content-[attr(data-placeholder)]">{{ old('content') }}</div>
                            <input type="hidden" name="content" data-rich-value value="{{ old('content') }}">
                        </div>
                        <p class="mt-1 text-xs text-slate-500">Gunakan toolbar untuk bold, italic, garis bawah, daftar, dan kutipan.</p>
                    </div>

                    <div class="flex flex-wrap justify-end gap-3 border-t border-slate-100 pt-5">
                        <button type="button" onclick="submitArticleAction('Draft', false)" class="rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-xs font-bold text-slate-700 transition-colors hover:border-[#10243A] hover:bg-slate-50">Simpan draft</button>
                        <button type="button" onclick="submitArticleAction('Draft', true)" class="rounded-xl bg-amber-500 px-5 py-2.5 text-xs font-bold text-white transition-colors hover:bg-amber-600">Jadwalkan</button>
                        <button type="button" onclick="submitArticleAction('Publish', false)" class="rounded-xl bg-[#087F78] px-5 py-2.5 text-xs font-bold text-white transition-colors hover:bg-[#066761]">Publish sekarang</button>
                    </div>
                </form>
            </section>

            @include('pages.partials.article-calendar')

            <section data-article-manager data-article-status-filter="{{ $articleStatusFilter }}" data-article-search="{{ $articleSearch }}" class="mb-8 rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7">
                <div class="flex flex-col justify-between gap-4 border-b border-slate-200 pb-5 lg:flex-row lg:items-center">
                    <div>
                        <h2 class="text-2xl font-extrabold uppercase tracking-tight text-[#10243A]">Daftar kelola artikel</h2>
                        <p class="mt-1 text-sm text-slate-500">Filter dan kelola status artikel yang tersimpan di sistem.</p>
                    </div>
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                        <nav class="flex items-center rounded-xl bg-slate-100 p-1 text-sm font-bold text-slate-600" aria-label="Filter status artikel">
                            @foreach([
                                'all' => 'Semua',
                                'draft' => 'Draft',
                                'scheduled' => 'Terjadwal',
                                'published' => 'Published',
                            ] as $statusValue => $statusLabel)
                                <button type="button" data-article-filter="{{ $statusValue }}" onclick="filterArticleList(this.dataset.articleFilter)" class="rounded-lg px-4 py-2 transition-colors {{ $articleStatusFilter === $statusValue ? 'bg-[#10243A] text-white shadow-sm' : 'hover:bg-white hover:text-[#10243A]' }}">{{ $statusLabel }}</button>
                            @endforeach
                        </nav>
                        <div data-article-search-form class="relative">
                            <label for="article_search" class="sr-only">Cari judul artikel</label>
                            <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-4.35-4.35m1.35-5.65a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <input id="article_search" name="article_search" type="search" value="{{ $articleSearch }}" maxlength="100" placeholder="Cari judul..." oninput="filterArticleList()" class="w-full rounded-xl border border-slate-200 bg-white py-2.5 pl-10 pr-4 text-sm text-[#10243A] outline-none transition-colors focus:border-[#087F78] sm:w-60">
                        </div>
                    </div>
                </div>

            <div class="hidden grid-cols-12 px-8 py-5 text-sm font-bold tracking-tight text-[#10243A] md:grid">
                <div class="col-span-5">Artikel</div>
                <div class="col-span-2">Kategori</div>
                <div class="col-span-2">Tanggal tayang</div>
                <div class="col-span-1">Status</div>
                <div class="col-span-2" aria-hidden="true"></div>
            </div>

            <div data-article-list class="space-y-4">
                @forelse($articles as $article)
                    @php
                        $articleEditorQuery = ['tab' => 'artikel', 'article_id' => $article->id];
                        $articleStatusLabel = $article->status === 'Publish'
                            ? 'Publish'
                            : ($article->published_at !== null ? 'Terjadwalkan' : 'Draft');
                        $articleStatusClasses = match ($articleStatusLabel) {
                            'Publish' => 'bg-emerald-100 text-emerald-800',
                            'Terjadwalkan' => 'bg-amber-100 text-amber-800',
                            default => 'bg-slate-100 text-slate-600',
                        };
                        $articleFilterStatus = match ($articleStatusLabel) {
                            'Publish' => 'published',
                            'Terjadwalkan' => 'scheduled',
                            default => 'draft',
                        };
                    @endphp
                    <article data-article-card data-article-title="{{ $article->title }}" data-article-filter-status="{{ $articleFilterStatus }}" class="admin-editor-card cursor-pointer rounded-2xl border bg-white px-5 py-5 shadow-[0_1px_3px_rgba(0,0,0,0.03)] transition-all hover:border-[#087F78]/50 hover:shadow-md md:grid md:grid-cols-12 md:items-center md:px-8 {{ $selectedArticle?->id === $article->id ? 'border-[#087F78] ring-2 ring-[#087F78]/10' : 'border-slate-200/90' }}" data-editor-url="{{ route('admin.dashboard', $articleEditorQuery) }}" tabindex="0" role="button" aria-expanded="{{ $selectedArticle?->id === $article->id ? 'true' : 'false' }}" aria-label="Edit artikel {{ $article->title }}">
                        <div class="flex min-w-0 items-center gap-3 md:col-span-5 md:pr-4">
                            @if($article->image_path || $article->image_url)
                                <img src="{{ $article->image_url ?: Storage::disk('public')->url($article->image_path) }}" alt="{{ $article->title }}" class="h-14 w-20 shrink-0 rounded-xl object-cover">
                            @else
                                <div class="flex h-14 w-20 shrink-0 items-center justify-center rounded-xl bg-[#10243A] text-[10px] font-bold text-teal-100">JCP</div>
                            @endif
                            <div class="min-w-0">
                                <h2 class="truncate font-bold text-slate-800">{{ $article->title }}</h2>
                                <p class="mt-1 line-clamp-2 text-xs text-slate-500">{{ $article->author ? $article->author.' · ' : '' }}{{ $article->excerpt }}</p>
                            </div>
                        </div>
                        <div class="mt-4 border-t border-slate-100 pt-4 text-sm text-slate-600 md:col-span-2 md:mt-0 md:border-t-0 md:pt-0">{{ $article->category }}</div>
                        <div class="mt-4 border-t border-slate-100 pt-4 text-sm text-slate-600 md:col-span-2 md:mt-0 md:border-t-0 md:pt-0">{{ $article->published_at?->format('d M Y') ?? 'Belum dijadwalkan' }}</div>
                        <div class="mt-4 border-t border-slate-100 pt-4 md:col-span-1 md:mt-0 md:border-t-0 md:pt-0">
                            <span class="rounded-full px-3 py-1.5 text-xs font-extrabold {{ $articleStatusClasses }}">{{ $articleStatusLabel }}</span>
                        </div>
                        <div class="mt-4 flex items-center justify-end gap-2 border-t border-slate-100 pt-4 md:col-span-2 md:mt-0 md:border-t-0 md:pt-0">
                            <form action="{{ route('admin.articles.destroy', $article) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="button" data-delete-type="Artikel" data-delete-name="{{ $article->title }}" onclick="openAdminDeleteModal(this.form, this.dataset.deleteType, this.dataset.deleteName)" class="rounded-lg p-1.5 text-slate-400 transition-colors hover:bg-slate-100 hover:text-rose-600" title="Hapus artikel" aria-label="Hapus artikel">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>

                        @if($selectedArticle?->id === $article->id)
                            <div class="admin-editor-panel mt-6 border-t border-slate-100 pt-6 md:col-span-12" data-editor-panel>
                                <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-start">
                                    <div>
                                        <p class="text-xs font-bold uppercase tracking-wider text-[#087F78]">Pengaturan artikel</p>
                                        <h3 class="mt-1 text-xl font-extrabold text-[#10243A]">Edit artikel</h3>
                                    </div>
                                    <a href="{{ route('admin.dashboard', ['tab' => 'artikel']) }}" data-close-editor class="inline-flex w-fit items-center rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-bold text-slate-600 transition-colors hover:border-[#087F78]/40 hover:bg-[#E2F6F2] hover:text-[#087F78]">Tutup</a>
                                </div>

                                <form action="{{ route('admin.articles.update', $article) }}" method="POST" enctype="multipart/form-data" onsubmit="return validateRichEditor(this)" class="mt-5 space-y-5">
                                    @csrf
                                    @method('PUT')
                                    <div class="grid gap-4 sm:grid-cols-2">
                                        <div class="sm:col-span-2">
                                            <label for="inline_article_title_{{ $article->id }}" class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-700">Judul artikel *</label>
                                            <input id="inline_article_title_{{ $article->id }}" name="title" type="text" value="{{ $article->title }}" required maxlength="255" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-[#087F78] focus:outline-none">
                                        </div>
                                        <div>
                                            <label for="inline_article_author_{{ $article->id }}" class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-700">Penulis / tim</label>
                                            <input id="inline_article_author_{{ $article->id }}" name="author" type="text" value="{{ $article->author }}" maxlength="100" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-[#087F78] focus:outline-none">
                                        </div>
                                        <div>
                                            <label for="inline_article_category_{{ $article->id }}" class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-700">Bidang pelatihan *</label>
                                            <select id="inline_article_category_{{ $article->id }}" name="category" required class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-[#087F78] focus:outline-none">
                                                @if(! $categories->contains('name', $article->category))
                                                    <option value="{{ $article->category }}" selected>{{ $article->category }}</option>
                                                @endif
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->name }}" @selected($article->category === $category->name)>{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label for="inline_article_status_{{ $article->id }}" class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-700">Status *</label>
                                            <select id="inline_article_status_{{ $article->id }}" name="status" required class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-[#087F78] focus:outline-none">
                                                <option value="Publish" @selected($article->status === 'Publish')>Publish</option>
                                                <option value="Draft" @selected($article->status === 'Draft' && $article->published_at === null)>Draft</option>
                                                <option value="Terjadwalkan" @selected($article->status === 'Draft' && $article->published_at !== null)>Terjadwalkan</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label for="inline_article_published_at_{{ $article->id }}" class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-700">Tanggal publish / jadwal</label>
                                            <input id="inline_article_published_at_{{ $article->id }}" name="published_at" type="date" value="{{ $article->published_at?->format('Y-m-d') }}" min="{{ now()->toDateString() }}" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-[#087F78] focus:outline-none">
                                        </div>
                                        <div>
                                            <label for="inline_article_image_{{ $article->id }}" class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-700">Ganti foto utama</label>
                                            <input id="inline_article_image_{{ $article->id }}" name="image" type="file" accept="image/jpeg,image/png,image/webp,image/avif" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm file:mr-3 file:rounded-lg file:border-0 file:bg-teal-50 file:px-3 file:py-2 file:font-semibold file:text-[#087F78]">
                                        </div>
                                        <div>
                                            <label for="inline_article_image_url_{{ $article->id }}" class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-700">URL gambar</label>
                                            <input id="inline_article_image_url_{{ $article->id }}" name="image_url" type="url" value="{{ $article->image_url }}" maxlength="2048" placeholder="https://contoh.com/gambar.jpg" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-[#087F78] focus:outline-none">
                                        </div>
                                    </div>
                                    <div>
                                        <label for="inline_article_excerpt_{{ $article->id }}" class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-700">Ringkasan artikel *</label>
                                        <textarea id="inline_article_excerpt_{{ $article->id }}" name="excerpt" rows="3" maxlength="500" required class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-[#087F78] focus:outline-none">{{ $article->excerpt }}</textarea>
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-700">Isi artikel *</label>
                                        <div data-rich-editor class="overflow-hidden rounded-xl border border-slate-200 focus-within:border-[#087F78]">
                                            <div class="flex flex-wrap items-center gap-1 border-b border-slate-200 bg-slate-50 p-2">
                                                <button type="button" data-rich-command="bold" class="rounded-lg px-3 py-1.5 text-sm font-bold text-slate-700 hover:bg-white">B</button>
                                                <button type="button" data-rich-command="italic" class="rounded-lg px-3 py-1.5 text-sm italic text-slate-700 hover:bg-white">I</button>
                                                <button type="button" data-rich-command="underline" class="rounded-lg px-3 py-1.5 text-sm underline text-slate-700 hover:bg-white">U</button>
                                                <button type="button" data-rich-command="insertUnorderedList" class="rounded-lg px-3 py-1.5 text-xs font-bold text-slate-700 hover:bg-white">• List</button>
                                                <button type="button" data-rich-command="insertOrderedList" class="rounded-lg px-3 py-1.5 text-xs font-bold text-slate-700 hover:bg-white">1. List</button>
                                                <button type="button" data-rich-command="formatBlock" data-rich-value="blockquote" class="rounded-lg px-3 py-1.5 text-xs font-bold text-slate-700 hover:bg-white">Kutipan</button>
                                            </div>
                                            <div contenteditable="true" data-rich-surface oninput="this.nextElementSibling.value = this.innerHTML" class="min-h-56 px-4 py-4 text-sm leading-7 outline-none">{!! $article->content !!}</div>
                                            <input type="hidden" name="content" data-rich-value value="{{ $article->content }}">
                                        </div>
                                    </div>
                                    <div class="flex justify-end gap-3 border-t border-slate-100 pt-5">
                                        <a href="{{ route('admin.dashboard', ['tab' => 'artikel']) }}" data-close-editor class="rounded-xl px-5 py-2.5 text-sm font-bold text-slate-600 transition-colors hover:bg-slate-100">Batal</a>
                                        <button type="button" onclick="publishArticleNow(this)" class="rounded-xl bg-[#087F78] px-5 py-2.5 text-sm font-bold text-white transition-colors hover:bg-[#066761]">Publish sekarang</button>
                                        <button type="submit" class="rounded-xl bg-[#087F78] px-6 py-2.5 text-sm font-bold text-white transition-colors hover:bg-[#066761]">Simpan artikel</button>
                                    </div>
                                </form>
                            </div>
                        @endif
                    </article>
                @empty
                    <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center text-sm text-slate-500">Belum ada artikel. Klik &quot;Tambah artikel&quot; untuk membuat artikel pertama.</div>
                @endforelse
                <div data-article-filter-empty class="hidden rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center text-sm text-slate-500">Tidak ada artikel yang sesuai dengan filter.</div>
            </div>
            </section>

        @elseif($activeTab === 'galeri')
            <div class="flex flex-col justify-between gap-4 pb-8 sm:flex-row sm:items-center">
                <div>
                    <h1 class="text-3xl sm:text-4xl font-extrabold text-[#10243A] tracking-tight">Galeri Kegiatan</h1>
                    <p class="text-sm text-slate-500 mt-2">Kelola folder album dan foto yang tampil di halaman Galeri.</p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <button type="button" onclick="openAddGalleryFolderModal()" class="bg-[#087F78] hover:bg-[#066761] text-white px-6 py-3 rounded-2xl text-sm font-bold transition-all shadow-sm flex items-center gap-2 focus:outline-none cursor-pointer">
                        <span class="text-base font-bold leading-none">+</span>
                        <span>Buat folder</span>
                    </button>
                </div>
            </div>

            @if($selectedGalleryFolder)
                <div class="mb-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex flex-col gap-4 p-5">
                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.dashboard', ['tab' => 'galeri']) }}" class="rounded-xl bg-slate-100 px-3 py-2 text-sm font-bold text-slate-600 hover:bg-slate-200" aria-label="Kembali ke daftar folder">&larr;</a>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Pengaturan folder</p>
                            <p class="mt-1 text-sm text-slate-500">Kelola detail dan foto folder ini.</p>
                        </div>
                        <span class="rounded-lg px-3 py-1.5 text-xs font-bold {{ $selectedGalleryFolder->status === 'Draft' ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800' }}">{{ $selectedGalleryFolder->status === 'Draft' ? 'Draft' : 'Publik' }}</span>
                        <span class="rounded-lg bg-slate-100 px-3 py-1.5 text-xs font-bold text-[#10243A]">{{ $selectedGalleryFolder->photos_count }} foto</span>
                    </div>
                    <div class="flex w-full flex-col gap-3">
                        <form id="galleryFolderSettingsForm" data-folder-id="{{ $selectedGalleryFolder->id }}" action="{{ route('admin.gallery.folders.update', $selectedGalleryFolder) }}" method="POST" class="mx-auto grid w-full gap-3 rounded-xl bg-slate-50 p-3 sm:grid-cols-2 xl:max-w-5xl">
                            @csrf
                            @method('PUT')
                            <input id="gallery_folder_status" type="hidden" name="status" value="Publish">
                            <div>
                                <label for="selected_gallery_folder_name" class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-slate-500">Nama folder</label>
                                <input id="selected_gallery_folder_name" name="name" value="{{ $selectedGalleryFolder->name }}" required maxlength="120" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-3 text-sm font-semibold text-[#10243A] focus:border-[#087F78] focus:outline-none">
                            </div>
                            <div>
                                <label for="selected_gallery_folder_category" class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-slate-500">Bidang galeri</label>
                                <select id="selected_gallery_folder_category" name="category_id" required class="w-full rounded-lg border border-slate-200 bg-white px-2.5 py-3 text-sm font-semibold text-[#10243A] focus:border-[#087F78] focus:outline-none">
                                    @foreach($galleryCategories as $category)
                                        <option value="{{ $category->id }}" @selected($selectedGalleryFolder->category_id == $category->id)>{{ $category->short_name ?: $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="sm:col-span-2">
                                <label for="selected_gallery_folder_description" class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-slate-500">Deskripsi folder</label>
                                <textarea id="selected_gallery_folder_description" name="description" rows="3" maxlength="1000" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-3 text-sm text-[#10243A] focus:border-[#087F78] focus:outline-none">{{ $selectedGalleryFolder->description }}</textarea>
                            </div>
                        </form>
                        <div class="flex flex-wrap items-center justify-end gap-2">
                            <a href="{{ route('gallery.show', $selectedGalleryFolder) }}" target="_blank" rel="noopener" class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-bold text-slate-600 hover:border-[#087F78] hover:text-[#087F78]">Lihat album ↗</a>
                            <button type="button" onclick="openAddGalleryPhotosModal({{ $selectedGalleryFolder->id }})" class="rounded-xl bg-[#087F78] px-4 py-2.5 text-sm font-bold text-white hover:bg-[#066761]">+ Tambah foto</button>
                        </div>
                    </div>
                </div>

                <div class="border-t border-slate-100 p-5">
                @if($selectedGalleryPhotos->count() > 0)
                    <div class="mb-5 flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-slate-200 bg-white p-4">
                        <form id="galleryBulkDeleteForm" action="{{ route('admin.gallery.photos.destroy') }}" method="POST" onsubmit="return submitGalleryBulkAction(event, 'delete')">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="folder_id" value="{{ $selectedGalleryFolder->id }}">
                            <button type="submit" class="rounded-xl border border-rose-200 px-4 py-2.5 text-sm font-bold text-rose-700 hover:bg-rose-50">Hapus terpilih</button>
                        </form>
                        <p class="text-xs font-semibold text-slate-500">Pilih foto yang ingin dihapus, lalu tekan tombol hapus.</p>
                    </div>
                    <label class="mb-4 inline-flex cursor-pointer items-center gap-2 text-sm font-semibold text-slate-600">
                        <input type="checkbox" onchange="toggleGalleryPhotoSelection(this.checked)" class="h-4 w-4 rounded border-slate-300 text-[#087F78] focus:ring-[#087F78]">
                        Pilih semua foto di folder ini
                    </label>

                    <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 xl:grid-cols-4">
                        @foreach($selectedGalleryPhotos as $photo)
                            <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                                <div class="relative aspect-square bg-slate-100">
                                    <img src="{{ $photo->image_url ?: Storage::disk('public')->url($photo->thumbnail_path ?: $photo->path) }}" alt="Foto dari {{ $selectedGalleryFolder->name }}" loading="lazy" decoding="async" width="640" height="640" class="h-full w-full object-cover">
                                    <label class="absolute left-3 top-3 flex h-8 w-8 cursor-pointer items-center justify-center rounded-lg bg-white/95 shadow-sm" aria-label="Pilih foto">
                                        <input type="checkbox" class="gallery-photo-select h-4 w-4 rounded border-slate-300 text-[#087F78] focus:ring-[#087F78]" value="{{ $photo->id }}" aria-label="Pilih foto {{ $photo->id }}">
                                    </label>
                                    <a href="{{ $photo->image_url ?: Storage::disk('public')->url($photo->path) }}" target="_blank" rel="noopener" class="absolute bottom-3 right-3 rounded-lg bg-white/95 px-2.5 py-1.5 text-xs font-bold text-slate-700 shadow-sm hover:text-[#087F78]">Buka foto ↗</a>
                                </div>
                                <div class="space-y-3 p-4">
                                    <form id="galleryPhotoDeleteForm_{{ $photo->id }}" action="{{ route('admin.gallery.photos.destroy') }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="folder_id" value="{{ $selectedGalleryFolder->id }}">
                                        <input type="hidden" name="photo_ids[]" value="{{ $photo->id }}">
                                        <button type="button" onclick="openGalleryPhotoDeleteModal(document.getElementById('galleryPhotoDeleteForm_{{ $photo->id }}'), 1)" class="text-xs font-bold text-rose-700 hover:underline">Hapus foto</button>
                                    </form>
                                    <details>
                                        <summary class="cursor-pointer text-xs font-bold text-[#087F78] hover:underline">Ganti foto</summary>
                                        <form action="{{ route('admin.gallery.photos.update', $photo) }}" method="POST" enctype="multipart/form-data" data-gallery-photo-edit data-folder-id="{{ $selectedGalleryFolder->id }}" class="mt-3 space-y-3">
                                            @csrf
                                            @method('PUT')
                                            <div>
                                                <label for="gallery_caption_{{ $photo->id }}" class="mb-1 block text-xs font-semibold text-slate-600">Keterangan foto</label>
                                                <input id="gallery_caption_{{ $photo->id }}" name="caption" type="text" value="{{ $photo->caption }}" maxlength="255" placeholder="Tambahkan keterangan foto" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-xs focus:border-[#087F78] focus:outline-none">
                                            </div>
                                            <div>
                                                <label for="gallery_file_{{ $photo->id }}" class="mb-1 block text-xs font-semibold text-slate-600">Ganti file (opsional)</label>
                                                <input id="gallery_file_{{ $photo->id }}" name="photo" type="file" accept="image/jpeg,image/png,image/webp,image/avif" class="w-full text-xs file:mr-2 file:rounded-lg file:border-0 file:bg-teal-50 file:px-2 file:py-1.5 file:font-semibold file:text-[#087F78]">
                                            </div>
                                            <div>
                                                <label for="gallery_image_url_{{ $photo->id }}" class="mb-1 block text-xs font-semibold text-slate-600">Atau URL gambar</label>
                                                <input id="gallery_image_url_{{ $photo->id }}" name="image_url" type="url" value="{{ $photo->image_url }}" maxlength="2048" placeholder="https://contoh.com/foto.jpg" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-xs focus:border-[#087F78] focus:outline-none">
                                            </div>
                                            <button type="submit" class="w-full rounded-lg bg-[#087F78] px-3 py-2 text-xs font-bold text-white hover:bg-[#066761]">Simpan perubahan</button>
                                        </form>
                                    </details>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    @if($selectedGalleryPhotos->hasPages())
                        <div class="mt-6">
                            {{ $selectedGalleryPhotos->links() }}
                        </div>
                    @endif
                @else
                    <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center text-sm text-slate-500">Folder ini belum memiliki foto. Tambahkan foto untuk mulai mengelola album.</div>
                @endif

                    <div class="mt-6 flex flex-wrap justify-end gap-2 border-t border-slate-100 pt-5">
                        <button type="button" onclick="openGalleryFolderEditorDeleteModal()" class="rounded-xl border border-rose-200 px-5 py-3 text-sm font-bold text-rose-700 hover:bg-rose-50">Hapus folder</button>
                        <button type="button" onclick="saveGalleryFolderAsDraft()" class="rounded-xl bg-rose-700 px-5 py-3 text-sm font-bold text-white hover:bg-rose-800">Draft</button>
                        <button type="submit" form="galleryFolderSettingsForm" class="rounded-xl bg-[#087F78] px-6 py-3 text-sm font-bold text-white hover:bg-[#066761]">Save &amp; Upload</button>
                    </div>
                </div>
                </div>

                <div id="galleryFolderEditorDeleteModal" class="fixed inset-0 z-[80] hidden items-center justify-center bg-slate-900/50 p-4 backdrop-blur-xs" role="dialog" aria-modal="true" aria-labelledby="galleryFolderEditorDeleteTitle">
                    <div class="w-full max-w-lg rounded-3xl border border-slate-200 bg-white p-6 shadow-2xl sm:p-8">
                        <p class="text-xs font-bold uppercase tracking-wider text-rose-700">Konfirmasi penghapusan</p>
                        <h3 id="galleryFolderEditorDeleteTitle" class="mt-2 text-xl font-extrabold text-[#10243A]">Hapus folder galeri?</h3>
                        <p class="mt-3 text-sm leading-relaxed text-slate-600">Folder dan seluruh foto di dalamnya akan dihapus permanen.</p>
                        <dl class="mt-5 grid gap-3 rounded-2xl bg-slate-50 p-4 text-sm sm:grid-cols-3">
                            <div>
                                <dt class="text-xs font-bold uppercase tracking-wider text-slate-400">Nama folder</dt>
                                <dd class="mt-1 break-words font-bold text-[#10243A]">{{ $selectedGalleryFolder->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-bold uppercase tracking-wider text-slate-400">Bidang</dt>
                                <dd class="mt-1 break-words font-bold text-[#10243A]">{{ $selectedGalleryFolder->category?->short_name ?: $selectedGalleryFolder->category?->name ?: 'Belum dikategorikan' }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs font-bold uppercase tracking-wider text-slate-400">Isi album</dt>
                                <dd class="mt-1 font-bold text-[#10243A]">{{ $selectedGalleryFolder->photos_count }} foto</dd>
                            </div>
                        </dl>
                        <form action="{{ route('admin.gallery.folders.destroy', $selectedGalleryFolder) }}" method="POST" class="mt-6 flex justify-end gap-3 border-t border-slate-100 pt-5">
                            @csrf
                            @method('DELETE')
                            <button type="button" onclick="closeGalleryFolderEditorDeleteModal()" class="rounded-xl px-5 py-2.5 text-sm font-bold text-slate-600 hover:bg-slate-100">Batal</button>
                            <button type="submit" onclick="galleryFolderHasUnsavedChanges = false" class="rounded-xl bg-rose-700 px-5 py-2.5 text-sm font-bold text-white hover:bg-rose-800">Hapus folder</button>
                        </form>
                    </div>
                </div>

                <div id="galleryPhotoDeleteModal" class="fixed inset-0 z-[90] hidden items-center justify-center bg-slate-900/50 p-4 backdrop-blur-xs" role="dialog" aria-modal="true" aria-labelledby="galleryPhotoDeleteTitle">
                    <div class="w-full max-w-md rounded-3xl border border-slate-200 bg-white p-6 shadow-2xl sm:p-8">
                        <h3 id="galleryPhotoDeleteTitle" class="text-xl font-extrabold text-[#10243A]">Hapus foto?</h3>
                        <p id="galleryPhotoDeleteMessage" class="mt-3 text-sm leading-relaxed text-slate-600">Apakah foto ini benar-benar ingin dihapus?</p>
                        <div class="mt-6 flex justify-end gap-3 border-t border-slate-100 pt-5">
                            <button type="button" onclick="closeGalleryPhotoDeleteModal()" class="rounded-xl px-5 py-2.5 text-sm font-bold text-slate-600 hover:bg-slate-100">Batal</button>
                            <button type="button" onclick="confirmGalleryPhotoDelete()" class="rounded-xl bg-rose-700 px-5 py-2.5 text-sm font-bold text-white hover:bg-rose-800">Hapus</button>
                        </div>
                    </div>
                </div>

                <div id="galleryFolderExitModal" class="fixed inset-0 z-[80] hidden items-center justify-center bg-slate-900/50 p-4 backdrop-blur-xs" role="dialog" aria-modal="true" aria-labelledby="galleryFolderExitTitle">
                    <div class="w-full max-w-lg rounded-3xl border border-slate-200 bg-white p-6 shadow-2xl sm:p-8">
                        <h3 id="galleryFolderExitTitle" class="text-xl font-extrabold text-[#10243A]">Perubahan belum disimpan</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600">Folder ini memiliki perubahan pengaturan atau foto yang belum dikonfirmasi. Pilih cara menyimpan perubahan sebelum keluar.</p>
                        <div class="mt-6 flex flex-wrap justify-end gap-3">
                            <button type="button" onclick="closeGalleryFolderExitModal()" class="rounded-xl px-5 py-2.5 text-sm font-bold text-slate-600 hover:bg-slate-100">Batal</button>
                            <button type="button" onclick="saveGalleryFolderAsDraft()" class="rounded-xl bg-rose-700 px-5 py-2.5 text-sm font-bold text-white hover:bg-rose-800">Simpan sebagai draft</button>
                            <button type="button" onclick="saveGalleryFolderAndUpload()" class="rounded-xl bg-[#087F78] px-5 py-2.5 text-sm font-bold text-white hover:bg-[#066761]">Save &amp; Upload</button>
                        </div>
                    </div>
                </div>
            @else
            <div class="hidden grid-cols-12 px-8 py-3 text-sm font-bold text-[#10243A] tracking-tight mb-2 md:grid">
                <div class="col-span-2">Foto</div>
                <div class="col-span-3">Nama folder</div>
                <div class="col-span-5">Deskripsi</div>
                <div class="col-span-1">Isi album</div>
                <div class="col-span-1" aria-hidden="true"></div>
            </div>

            <div class="space-y-4">
                @forelse($galleryFolders as $folder)
                    <div class="flex flex-col gap-4 rounded-2xl border border-slate-200/90 bg-white px-5 py-5 shadow-[0_1px_3px_rgba(0,0,0,0.03)] transition-all hover:border-[#087F78]/50 hover:shadow-md focus-within:ring-2 focus-within:ring-[#087F78]/40 md:grid md:grid-cols-12 md:items-center md:px-8">
                        <a href="{{ route('admin.dashboard', ['tab' => 'galeri', 'folder_id' => $folder->id]) }}" aria-label="Buka pengaturan folder {{ $folder->name }}" class="contents">
                        <div class="md:col-span-2">
                            @if($folder->previewPhoto)
                                <img src="{{ $folder->previewPhoto->image_url ?: Storage::disk('public')->url($folder->previewPhoto->thumbnail_path ?: $folder->previewPhoto->path) }}" alt="Pratinjau {{ $folder->name }}" class="h-16 w-20 rounded-xl object-cover">
                            @else
                                <div class="flex h-16 w-20 items-center justify-center rounded-xl bg-slate-100 text-slate-400">📁</div>
                            @endif
                        </div>
                        <div class="min-w-0 md:col-span-3">
                            <h2 class="font-bold text-slate-800">{{ $folder->name }}</h2>
                            <p class="mt-1 text-xs font-semibold text-[#087F78]">{{ $folder->category?->short_name ?: $folder->category?->name ?: 'Belum dikategorikan' }}</p>
                            <p class="mt-1 text-xs font-semibold {{ $folder->status === 'Draft' ? 'text-amber-700' : 'text-emerald-700' }}">{{ $folder->status === 'Draft' ? 'Draft' : 'Publik' }}</p>
                            <p class="mt-1 text-xs text-slate-500">Dibuat {{ $folder->created_at->format('d M Y') }}</p>
                        </div>
                        <div class="line-clamp-2 text-sm text-slate-500 md:col-span-5">{{ $folder->description ? \Illuminate\Support\Str::limit($folder->description, 120) : 'Tanpa deskripsi' }}</div>
                        <div class="md:col-span-1">
                            <span class="inline-flex rounded-lg bg-slate-100 px-3 py-1.5 text-xs font-bold text-[#10243A]">{{ $folder->photos_count }} foto</span>
                        </div>
                        </a>
                        <div class="flex justify-end md:col-span-1">
                            <button
                                type="button"
                                data-gallery-folder-delete
                                data-delete-url="{{ route('admin.gallery.folders.destroy', $folder) }}"
                                data-folder-name="{{ $folder->name }}"
                                data-folder-category="{{ $folder->category?->short_name ?: $folder->category?->name ?: 'Belum dikategorikan' }}"
                                data-photo-count="{{ $folder->photos_count }}"
                                onclick="openGalleryFolderDeleteModal(this)"
                                aria-label="Hapus folder {{ $folder->name }}"
                                title="Hapus folder"
                                class="rounded-lg p-1.5 text-slate-400 transition-colors hover:bg-slate-100 hover:text-rose-600"
                            >
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                <span class="sr-only">Hapus folder</span>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center text-sm text-slate-500">
                        <p class="font-semibold text-[#10243A]">Belum ada folder galeri.</p>
                        <p class="mt-1">Buat folder terlebih dahulu, lalu unggah foto ke dalamnya.</p>
                    </div>
                @endforelse
            </div>
            <div id="galleryFolderDeleteModal" class="fixed inset-0 z-[80] hidden items-center justify-center bg-slate-900/50 p-4 backdrop-blur-xs" role="dialog" aria-modal="true" aria-labelledby="galleryFolderDeleteTitle">
                <div class="w-full max-w-lg rounded-3xl border border-slate-200 bg-white p-6 shadow-2xl sm:p-8">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-rose-700">Konfirmasi penghapusan</p>
                            <h3 id="galleryFolderDeleteTitle" class="mt-2 text-xl font-extrabold text-[#10243A]">Hapus folder galeri?</h3>
                        </div>
                        <button type="button" onclick="closeGalleryFolderDeleteModal()" aria-label="Tutup" class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-slate-100 text-lg font-bold leading-none text-slate-600 hover:bg-slate-200">&times;</button>
                    </div>
                    <p class="mt-4 text-sm leading-relaxed text-slate-600">Folder yang dihapus tidak dapat dikembalikan. Periksa detail berikut sebelum melanjutkan.</p>
                    <dl class="mt-5 grid gap-3 rounded-2xl bg-slate-50 p-4 text-sm sm:grid-cols-3">
                        <div>
                            <dt class="text-xs font-bold uppercase tracking-wider text-slate-400">Nama folder</dt>
                            <dd id="galleryFolderDeleteName" class="mt-1 break-words font-bold text-[#10243A]"></dd>
                        </div>
                        <div>
                            <dt class="text-xs font-bold uppercase tracking-wider text-slate-400">Bidang</dt>
                            <dd id="galleryFolderDeleteCategory" class="mt-1 break-words font-bold text-[#10243A]"></dd>
                        </div>
                        <div>
                            <dt class="text-xs font-bold uppercase tracking-wider text-slate-400">Isi album</dt>
                            <dd id="galleryFolderDeletePhotoCount" class="mt-1 font-bold text-[#10243A]"></dd>
                        </div>
                    </dl>
                    <p id="galleryFolderDeleteWarning" class="mt-4 rounded-xl border border-rose-100 bg-rose-50 px-4 py-3 text-sm leading-relaxed text-rose-700"></p>
                    <form id="galleryFolderDeleteForm" method="POST" class="mt-6 flex justify-end gap-3 border-t border-slate-100 pt-5">
                        @csrf
                        @method('DELETE')
                        <button type="button" onclick="closeGalleryFolderDeleteModal()" class="rounded-xl px-5 py-2.5 text-sm font-bold text-slate-600 transition-colors hover:bg-slate-100">Batal</button>
                        <button type="submit" class="rounded-xl bg-rose-700 px-5 py-2.5 text-sm font-bold text-white transition-colors hover:bg-rose-800">Hapus folder</button>
                    </form>
                </div>
            </div>
            @endif

        @elseif($activeTab === 'bidang')
            <div class="flex flex-col items-stretch justify-between gap-4 pb-8 sm:flex-row sm:items-center">
                <div>
                    <h1 class="text-3xl font-extrabold tracking-tight text-[#10243A] sm:text-4xl">Bidang Pelatihan</h1>
                    <p class="mt-1 text-sm text-slate-500">Kelola bidang yang digunakan oleh Program, Beranda, dan Galeri.</p>
                </div>

                <button type="button" onclick="openAddCategoryModal()" class="flex w-full cursor-pointer items-center justify-center gap-2 rounded-2xl bg-[#087F78] px-7 py-3 text-sm font-bold text-white shadow-sm transition-all hover:bg-[#066761] focus:outline-none sm:w-auto">
                    <span class="text-base font-bold leading-none">+</span>
                    <span>Tambah bidang</span>
                </button>
            </div>

            <div class="mb-2 hidden grid-cols-12 px-8 py-3 text-sm font-bold tracking-tight text-[#10243A] md:grid">
                <div class="col-span-5">Nama & ilustrasi</div>
                <div class="col-span-2">Nama singkat</div>
                <div class="col-span-2">Program</div>
                <div class="col-span-2">Folder galeri</div>
                <div class="col-span-1" aria-hidden="true"></div>
            </div>

            <div class="space-y-4">
                @forelse($categories as $category)
                    @php
                        $categoryDefaultImage = $categoryDefaultImages[$category->slug] ?? asset('images/hero-bg-alt.jpg');
                        $categoryImage = $category->image_url
                            ?: ($category->image ? Storage::disk('public')->url($category->image) : $categoryDefaultImage);
                    @endphp
                    <article class="rounded-2xl border border-slate-200/90 bg-white px-5 py-5 text-sm shadow-[0_1px_3px_rgba(0,0,0,0.03)] transition-all hover:border-[#087F78]/50 hover:shadow-md md:grid md:grid-cols-12 md:items-center md:px-8">
                        <div class="flex min-w-0 items-center gap-3 md:col-span-5 md:pr-4">
                            <img src="{{ $categoryImage }}" alt="Ilustrasi {{ $category->name }}" class="h-14 w-20 shrink-0 rounded-xl object-cover">
                            <div class="min-w-0">
                                <p class="font-bold text-[#10243A]">{{ $category->name }}</p>
                            @if($category->description)
                                <p class="mt-1 line-clamp-2 text-xs leading-relaxed text-slate-500">{{ $category->description }}</p>
                            @endif
                            </div>
                        </div>

                        <div class="mt-3 text-slate-500 md:col-span-2 md:mt-0">
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-400 md:hidden">Nama singkat</p>
                            <p class="font-semibold">{{ $category->short_name ?: '—' }}</p>
                        </div>

                        <div class="mt-3 text-slate-500 md:col-span-2 md:mt-0">
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-400 md:hidden">Program</p>
                            <p class="font-semibold">{{ $category->courses_count }} program</p>
                        </div>

                        <div class="mt-3 text-slate-500 md:col-span-2 md:mt-0">
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-400 md:hidden">Folder galeri</p>
                            <p class="font-semibold">{{ $category->gallery_folders_count }} folder</p>
                        </div>

                        <div class="mt-3 flex items-center justify-end gap-2 md:col-span-1 md:mt-0">
                            <button type="button"
                                    data-category-id="{{ $category->id }}"
                                    data-category-edit-url="{{ route('admin.categories.update', $category) }}"
                                    data-category-name="{{ $category->name }}"
                                    data-category-short-name="{{ $category->short_name }}"
                                    data-category-description="{{ $category->description }}"
                                    data-category-image-url="{{ $categoryImage }}"
                                    onclick="openEditCategoryModal(this)"
                                    title="Edit bidang pelatihan"
                                    class="rounded-lg p-1.5 text-slate-400 transition-colors hover:bg-[#E2F6F2] hover:text-[#087F78]">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m16.862 4.487 1.651-1.651a2.121 2.121 0 0 1 3 3L8.25 19.099 3 21l1.901-5.25L16.862 4.487ZM15 6l3 3" />
                                </svg>
                            </button>
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="button"
                                        data-category-delete-url="{{ route('admin.categories.destroy', $category) }}"
                                        data-category-delete-name="{{ $category->name }}"
                                        data-category-course-count="{{ $category->courses_count }}"
                                        data-category-folder-count="{{ $category->gallery_folders_count }}"
                                        onclick="openCategoryDeleteModal(this)"
                                        title="Hapus bidang pelatihan"
                                        class="rounded-lg p-1.5 text-slate-400 transition-colors hover:bg-slate-100 hover:text-rose-600">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </article>
                @empty
                    <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center text-sm text-slate-500">Belum ada bidang pelatihan. Klik &quot;Tambah bidang&quot; untuk membuat bidang pertama.</div>
                @endforelse
            </div>

        @else
            <!-- ========================================================
                 TABEL 2: DATA PELATIHAN (PROGRAM)
                 Bisa Tambah, Edit, dan Hapus Program
                 ======================================================== -->
            <div class="flex flex-col items-stretch justify-between gap-4 pb-8 sm:flex-row sm:items-center">
                <div>
                    <h1 class="text-3xl sm:text-4xl font-extrabold text-[#10243A] tracking-tight">
                        Data Pelatihan
                    </h1>
                    <p class="text-xs text-slate-500 mt-1">
                        Kelola informasi dasar program pelatihan.
                    </p>
                </div>

                <div class="flex w-full sm:w-auto">
                    <button type="button" onclick="openAddProgramModal()" class="flex w-full cursor-pointer items-center justify-center gap-2 rounded-2xl bg-[#087F78] px-7 py-3 text-sm font-bold text-white shadow-sm transition-all hover:bg-[#066761] focus:outline-none sm:w-auto">
                        <span class="text-base font-bold leading-none">+</span>
                        <span>Tambah program</span>
                    </button>
                </div>
            </div>

            <!-- Table Header Labels -->
            <div class="grid grid-cols-12 px-8 py-3 text-sm font-bold text-[#10243A] tracking-tight mb-2">
                <div class="col-span-6">Nama program</div>
                <div class="col-span-3">Kategori</div>
                <div class="col-span-2">Status</div>
                <div class="col-span-1" aria-hidden="true"></div>
            </div>

            <!-- List Baris Program Card Rounded -->
            <div class="space-y-4">
                @forelse($courses as $c)
                    @php
                        $courseEditorQuery = ['tab' => 'program', 'course_id' => $c->id];
                    @endphp
                    <article class="admin-editor-card cursor-pointer rounded-2xl border bg-white px-5 py-5 text-sm shadow-[0_1px_3px_rgba(0,0,0,0.03)] transition-all hover:border-[#087F78]/50 hover:shadow-md md:grid md:grid-cols-12 md:items-center md:px-8 {{ $selectedCourse?->id === $c->id ? 'border-[#087F78] ring-2 ring-[#087F78]/10' : 'border-slate-200/90' }}" data-editor-url="{{ route('admin.dashboard', $courseEditorQuery) }}" tabindex="0" role="button" aria-expanded="{{ $selectedCourse?->id === $c->id ? 'true' : 'false' }}" aria-label="Edit program {{ $c->title }}">
                        
                        <!-- Nama program -->
                        <div class="min-w-0 font-bold text-slate-700 transition-colors md:col-span-6 md:pr-4">
                            <a href="{{ route('courses.show', $c->slug) }}" target="_blank">
                                {{ $c->title }}
                            </a>
                        </div>

                        <!-- Kategori -->
                        <div class="mt-3 text-slate-500 font-medium md:col-span-3 md:mt-0">
                            {{ $c->category->short_name ?? $c->category->name }}
                        </div>

                        <!-- Status -->
                        <div class="mt-3 md:col-span-2 md:mt-0">
                            <span class="font-extrabold text-sm {{ $c->status === 'Publish' ? 'text-[#087F78]' : 'text-slate-500' }}">
                                {{ $c->status }}
                            </span>
                        </div>

                        <!-- Hapus program -->
                        <div class="mt-3 flex items-center justify-end gap-2 md:col-span-1 md:mt-0">
                            <form action="{{ route('admin.courses.destroy', $c->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" data-delete-type="Program" data-delete-name="{{ $c->title }}" onclick="openAdminDeleteModal(this.form, this.dataset.deleteType, this.dataset.deleteName)" title="Hapus Program" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-slate-100 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>

                        @if($selectedCourse?->id === $c->id)
                            <div class="admin-editor-panel mt-6 border-t border-slate-100 pt-6 md:col-span-12" data-editor-panel>
                                <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-start">
                                    <div>
                                        <p class="text-xs font-bold uppercase tracking-wider text-[#087F78]">Pengaturan program</p>
                                        <h3 class="mt-1 text-xl font-extrabold text-[#10243A]">Edit {{ $c->title }}</h3>
                                    </div>
                                    <a href="{{ route('admin.dashboard', ['tab' => 'program']) }}" data-close-editor class="inline-flex w-fit items-center rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-bold text-slate-600 transition-colors hover:border-[#087F78]/40 hover:bg-[#E2F6F2] hover:text-[#087F78]">Tutup</a>
                                </div>

                                <form action="{{ route('admin.courses.update', $c) }}" method="POST" enctype="multipart/form-data" class="mt-5 space-y-5">
                                    @csrf
                                    @method('PUT')
                                    <div class="grid gap-4 sm:grid-cols-2">
                                        <div>
                                            <label for="inline_course_title_{{ $c->id }}" class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-700">Nama program *</label>
                                            <input id="inline_course_title_{{ $c->id }}" name="title" type="text" value="{{ $c->title }}" required maxlength="255" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-[#087F78] focus:outline-none">
                                        </div>
                                        <div>
                                            <label for="inline_course_category_{{ $c->id }}" class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-700">Kategori *</label>
                                            <select id="inline_course_category_{{ $c->id }}" name="category_id" required class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-[#087F78] focus:outline-none">
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}" @selected($c->category_id === $category->id)>{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label for="inline_course_level_{{ $c->id }}" class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-700">Level *</label>
                                            <select id="inline_course_level_{{ $c->id }}" name="level" required class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-[#087F78] focus:outline-none">
                                                @foreach(['Basic', 'Basic–Intermediate', 'Intermediate', 'Advanced'] as $level)
                                                    <option value="{{ $level }}" @selected($c->level === $level)>{{ $level }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label for="inline_course_format_{{ $c->id }}" class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-700">Format *</label>
                                            <input id="inline_course_format_{{ $c->id }}" name="format" type="text" value="{{ $c->format }}" required maxlength="100" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-[#087F78] focus:outline-none">
                                        </div>
                                        <div>
                                            <label for="inline_course_status_{{ $c->id }}" class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-700">Status *</label>
                                            <select id="inline_course_status_{{ $c->id }}" name="status" required class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-[#087F78] focus:outline-none">
                                                <option value="Publish" @selected($c->status === 'Publish')>Publish</option>
                                                <option value="Draft" @selected($c->status === 'Draft')>Draft</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label for="inline_course_image_{{ $c->id }}" class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-700">Ganti gambar</label>
                                            <input id="inline_course_image_{{ $c->id }}" name="image" type="file" accept="image/jpeg,image/png,image/webp,image/avif" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm file:mr-3 file:rounded-lg file:border-0 file:bg-teal-50 file:px-3 file:py-2 file:font-semibold file:text-[#087F78]">
                                        </div>
                                    </div>
                                    @if($c->image || $c->image_url)
                                        @php
                                            $legacyImagePositions = [
                                                'center top' => '50% 0%',
                                                'center center' => '50% 50%',
                                                'center bottom' => '50% 100%',
                                                'left center' => '0% 50%',
                                                'right center' => '100% 50%',
                                            ];
                                            $editorImagePosition = $legacyImagePositions[$c->image_position] ?? ($c->image_position ?: '50% 50%');
                                            if (! \App\Models\Course::isValidImagePosition($editorImagePosition)) {
                                                $editorImagePosition = '50% 50%';
                                            }
                                        @endphp
                                        <div class="grid gap-4 sm:grid-cols-[minmax(0,1.25fr)_minmax(220px,0.75fr)]" data-course-image-section>
                                            <div>
                                                <div data-image-position-editor data-position-input="inline_course_image_position_{{ $c->id }}" class="relative aspect-[16/5] w-full touch-none cursor-grab overflow-hidden rounded-xl border border-slate-200 bg-slate-50 active:cursor-grabbing">
                                                    <img src="{{ $c->image_url ?: Storage::disk('public')->url($c->image) }}" alt="Gambar {{ $c->title }} saat ini" class="pointer-events-none absolute inset-0 h-full w-full select-none object-cover" draggable="false" style="object-position: {{ $editorImagePosition }}">
                                                    <span class="pointer-events-none absolute inset-x-0 bottom-0 bg-[#10243A]/75 px-3 py-2 text-center text-xs font-semibold text-white">Seret gambar untuk mengatur posisi</span>
                                                </div>
                                                <p class="mt-2 text-xs text-slate-500">Frame ini mengikuti area banner gambar pada halaman user.</p>
                                            </div>
                                            <div>
                                                <input id="inline_course_image_position_{{ $c->id }}" name="image_position" type="hidden" value="{{ $editorImagePosition }}">
                                                <input id="inline_course_image_url_{{ $c->id }}" name="image_url" type="url" value="{{ $c->image_url }}" maxlength="2048" placeholder="URL gambar baru" class="mb-3 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-[#087F78] focus:outline-none">
                                                <button type="button" data-remove-course-image data-remove-image-url="{{ route('admin.courses.image.destroy', $c) }}" class="inline-flex w-full items-center justify-center rounded-xl border border-rose-200 bg-white px-4 py-3 text-sm font-bold text-rose-700 transition-colors hover:bg-rose-50">
                                                    Hapus gambar
                                                </button>
                                                <p class="mt-2 text-xs leading-relaxed text-slate-500">Gambar akan langsung dihapus dan program kembali memakai gambar bawaan kategori.</p>
                                            </div>
                                        </div>
                                    @endif
                                    @if(! $c->image && ! $c->image_url)
                                        <div>
                                            <label for="inline_course_image_url_{{ $c->id }}" class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-700">URL gambar</label>
                                            <input id="inline_course_image_url_{{ $c->id }}" name="image_url" type="url" maxlength="2048" placeholder="https://contoh.com/gambar.jpg" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-[#087F78] focus:outline-none">
                                        </div>
                                    @endif
                                    <div>
                                        <label for="inline_course_facilities_{{ $c->id }}" class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-700">Fasilitas</label>
                                        <textarea id="inline_course_facilities_{{ $c->id }}" name="facilities" rows="2" maxlength="1000" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-[#087F78] focus:outline-none">{{ $c->facilities }}</textarea>
                                    </div>
                                    <div>
                                        <label for="inline_course_description_{{ $c->id }}" class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-700">Deskripsi singkat *</label>
                                        <textarea id="inline_course_description_{{ $c->id }}" name="description" rows="3" required class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-[#087F78] focus:outline-none">{{ $c->description }}</textarea>
                                    </div>
                                    <div class="grid gap-4 sm:grid-cols-2">
                                        <div>
                                            <label for="inline_course_outcome_{{ $c->id }}" class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-700">Hasil yang dicapai</label>
                                            <textarea id="inline_course_outcome_{{ $c->id }}" name="outcome" rows="4" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-[#087F78] focus:outline-none">{{ $c->outcome }}</textarea>
                                        </div>
                                        <div>
                                            <label for="inline_course_syllabus_{{ $c->id }}" class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-700">Silabus <span class="font-normal normal-case text-slate-400">(satu poin per baris)</span></label>
                                            <textarea id="inline_course_syllabus_{{ $c->id }}" name="syllabus" rows="4" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-[#087F78] focus:outline-none">{{ is_array($c->syllabus) ? implode("\n", $c->syllabus) : '' }}</textarea>
                                        </div>
                                    </div>
                                    <div class="flex justify-end gap-3 border-t border-slate-100 pt-5">
                                        <a href="{{ route('admin.dashboard', ['tab' => 'program']) }}" data-close-editor class="rounded-xl px-5 py-2.5 text-sm font-bold text-slate-600 transition-colors hover:bg-slate-100">Batal</a>
                                        <button type="submit" class="rounded-xl bg-[#087F78] px-6 py-2.5 text-sm font-bold text-white transition-colors hover:bg-[#066761]">Simpan program</button>
                                    </div>
                                </form>
                            </div>
                        @endif
                    </article>
                @empty
                    <div class="p-12 text-center text-slate-400 border border-dashed border-slate-200 rounded-2xl">
                        Belum ada data program pelatihan.
                    </div>
                @endforelse
            </div>
        @endif

    </main>

    <!-- Modal Tambah Bidang Pelatihan -->
    <div id="addCategoryModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 p-4 backdrop-blur-xs flex">
        <div class="w-full max-w-xl overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-2xl">
            <div class="max-h-[90vh] space-y-6 overflow-y-auto p-6 sm:p-8">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <div>
                        <h3 class="text-xl font-extrabold text-[#10243A]">Tambah bidang pelatihan</h3>
                        <p class="mt-1 text-xs text-slate-500">Bidang baru akan tersedia di Program, Beranda, dan Galeri.</p>
                    </div>
                    <button type="button" onclick="closeAddCategoryModal()" aria-label="Tutup" class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 text-lg font-bold leading-none text-slate-600 hover:bg-slate-200">&times;</button>
                </div>

                <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <input type="hidden" name="_form_context" value="training_category">
                    <div>
                        <label for="program_category_name" class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-700">Nama bidang *</label>
                        <input id="program_category_name" name="name" value="{{ old('_form_context') === 'training_category' ? old('name') : '' }}" required maxlength="100" placeholder="Contoh: Dokumentasi Event" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-[#087F78] focus:outline-none">
                    </div>
                    <div>
                        <label for="program_category_short_name" class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-700">Nama singkat</label>
                        <input id="program_category_short_name" name="short_name" value="{{ old('_form_context') === 'training_category' ? old('short_name') : '' }}" maxlength="50" placeholder="Contoh: Event" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-[#087F78] focus:outline-none">
                    </div>
                    <div>
                        <label for="program_category_description" class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-700">Deskripsi bidang</label>
                        <textarea id="program_category_description" name="description" rows="3" maxlength="1000" placeholder="Deskripsi singkat bidang pelatihan ini" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-[#087F78] focus:outline-none">{{ old('_form_context') === 'training_category' ? old('description') : '' }}</textarea>
                    </div>
                    <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50/70 p-4">
                        <div id="addCategoryImagePreview" class="mb-3 hidden overflow-hidden rounded-xl border border-slate-200">
                            <img id="addCategoryImagePreviewImage" src="" alt="Pratinjau ilustrasi bidang" class="h-32 w-full object-cover">
                        </div>
                        <label for="program_category_image" class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-700">Ilustrasi bidang</label>
                        <input id="program_category_image" name="image" type="file" accept="image/jpeg,image/png,image/webp,image/avif" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm file:mr-3 file:rounded-lg file:border-0 file:bg-teal-50 file:px-3 file:py-2 file:font-semibold file:text-[#087F78]">
                        <p class="mt-1 text-xs text-slate-500">Upload gambar maksimal 10 MB. Gambar ini tampil di kartu bidang pada beranda.</p>
                        <input id="program_category_image_url" name="image_url" type="url" value="{{ old('_form_context') === 'training_category' ? old('image_url') : '' }}" maxlength="2048" placeholder="Atau masukkan URL gambar https://..." class="mt-3 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-[#087F78] focus:outline-none">
                    </div>
                    <div class="flex justify-end gap-3 border-t border-slate-100 pt-4">
                        <button type="button" onclick="closeAddCategoryModal()" class="rounded-xl px-5 py-2.5 text-sm font-bold text-slate-600 transition-colors hover:bg-slate-100">Batal</button>
                        <button type="submit" class="rounded-xl bg-[#087F78] px-6 py-2.5 text-sm font-bold text-white transition-colors hover:bg-[#066761]">Tambah bidang</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Bidang Pelatihan -->
    <div id="editCategoryModal" class="fixed inset-0 z-[80] hidden items-center justify-center bg-slate-900/50 p-4 backdrop-blur-xs" role="dialog" aria-modal="true" aria-labelledby="editCategoryTitle">
        <div class="w-full max-w-xl overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-2xl">
            <div class="max-h-[90vh] space-y-6 overflow-y-auto p-6 sm:p-8">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <div>
                        <h3 id="editCategoryTitle" class="text-xl font-extrabold text-[#10243A]">Edit bidang pelatihan</h3>
                        <p class="mt-1 text-xs text-slate-500">Ganti ilustrasi bidang yang tampil di beranda.</p>
                    </div>
                    <button type="button" onclick="closeEditCategoryModal()" aria-label="Tutup" class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 text-lg font-bold leading-none text-slate-600 hover:bg-slate-200">&times;</button>
                </div>

                <form id="editCategoryForm" action="" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="_form_context" value="training_category_edit">
                    <input id="edit_category_id" type="hidden" name="category_id" value="">
                    <div>
                        <label for="edit_category_name" class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-700">Nama bidang *</label>
                        <input id="edit_category_name" name="name" required maxlength="100" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-[#087F78] focus:outline-none">
                    </div>
                    <div>
                        <label for="edit_category_short_name" class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-700">Nama singkat</label>
                        <input id="edit_category_short_name" name="short_name" maxlength="50" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-[#087F78] focus:outline-none">
                    </div>
                    <div>
                        <label for="edit_category_description" class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-700">Deskripsi bidang</label>
                        <textarea id="edit_category_description" name="description" rows="3" maxlength="1000" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-[#087F78] focus:outline-none"></textarea>
                    </div>
                    <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50/70 p-4">
                        <div id="editCategoryImagePreview" class="mb-3 hidden overflow-hidden rounded-xl border border-slate-200">
                            <img id="editCategoryImagePreviewImage" src="" alt="Pratinjau ilustrasi bidang" class="h-32 w-full object-cover">
                        </div>
                        <label for="edit_category_image" class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-700">Ganti ilustrasi</label>
                        <input id="edit_category_image" name="image" type="file" accept="image/jpeg,image/png,image/webp,image/avif" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm file:mr-3 file:rounded-lg file:border-0 file:bg-teal-50 file:px-3 file:py-2 file:font-semibold file:text-[#087F78]">
                        <input id="edit_category_image_url" name="image_url" type="url" maxlength="2048" placeholder="Atau ganti dengan URL gambar https://..." class="mt-3 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-[#087F78] focus:outline-none">
                        <label class="mt-3 flex items-center gap-2 text-xs font-semibold text-rose-700">
                            <input id="edit_category_remove_image" name="remove_image" value="1" type="checkbox" class="rounded border-slate-300 text-[#087F78] focus:ring-[#087F78]">
                            Hapus ilustrasi dan gunakan gambar default beranda
                        </label>
                    </div>
                    <div class="flex justify-end gap-3 border-t border-slate-100 pt-4">
                        <button type="button" onclick="closeEditCategoryModal()" class="rounded-xl px-5 py-2.5 text-sm font-bold text-slate-600 transition-colors hover:bg-slate-100">Batal</button>
                        <button type="submit" class="rounded-xl bg-[#087F78] px-6 py-2.5 text-sm font-bold text-white transition-colors hover:bg-[#066761]">Simpan perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Hapus Bidang Pelatihan -->
    <div id="categoryDeleteModal" class="fixed inset-0 z-[90] hidden items-center justify-center bg-slate-900/50 p-4 backdrop-blur-xs" role="dialog" aria-modal="true" aria-labelledby="categoryDeleteTitle">
        <div class="w-full max-w-lg rounded-3xl border border-slate-200 bg-white p-6 shadow-2xl sm:p-8">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-rose-700">Konfirmasi penghapusan</p>
                    <h3 id="categoryDeleteTitle" class="mt-2 text-xl font-extrabold text-[#10243A]">Hapus bidang pelatihan?</h3>
                </div>
                <button type="button" onclick="closeCategoryDeleteModal()" aria-label="Tutup" class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-slate-100 text-lg font-bold leading-none text-slate-600 hover:bg-slate-200">&times;</button>
            </div>
            <p class="mt-4 text-sm leading-relaxed text-slate-600">Bidang yang dihapus tidak dapat dikembalikan. Periksa data yang masih terhubung sebelum melanjutkan.</p>
            <dl class="mt-5 grid gap-3 rounded-2xl bg-slate-50 p-4 text-sm sm:grid-cols-3">
                <div>
                    <dt class="text-xs font-bold uppercase tracking-wider text-slate-400">Nama bidang</dt>
                    <dd id="categoryDeleteName" class="mt-1 break-words font-bold text-[#10243A]"></dd>
                </div>
                <div>
                    <dt class="text-xs font-bold uppercase tracking-wider text-slate-400">Program</dt>
                    <dd id="categoryDeleteCourseCount" class="mt-1 font-bold text-[#10243A]"></dd>
                </div>
                <div>
                    <dt class="text-xs font-bold uppercase tracking-wider text-slate-400">Folder galeri</dt>
                    <dd id="categoryDeleteFolderCount" class="mt-1 font-bold text-[#10243A]"></dd>
                </div>
            </dl>
            <p id="categoryDeleteWarning" class="mt-4 rounded-xl border border-rose-100 bg-rose-50 px-4 py-3 text-sm leading-relaxed text-rose-700"></p>
            <form id="categoryDeleteForm" method="POST" class="mt-6 flex justify-end gap-3 border-t border-slate-100 pt-5">
                @csrf
                @method('DELETE')
                <button type="button" onclick="closeCategoryDeleteModal()" class="rounded-xl px-5 py-2.5 text-sm font-bold text-slate-600 transition-colors hover:bg-slate-100">Batal</button>
                <button type="submit" class="rounded-xl bg-rose-700 px-5 py-2.5 text-sm font-bold text-white transition-colors hover:bg-rose-800">Hapus bidang</button>
            </form>
        </div>
    </div>

    <!-- Modal Buat Folder Galeri -->
    <div id="addGalleryFolderModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 p-4 backdrop-blur-xs flex">
        <div class="w-full max-w-xl overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-2xl">
            <div class="max-h-[90vh] space-y-6 overflow-y-auto p-6 sm:p-8">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div>
                    <h3 class="text-xl font-extrabold text-[#10243A]">Buat Folder Foto</h3>
                    <p class="mt-1 text-xs text-slate-500">Contoh: Pelatihan Drone November 2026</p>
                </div>
                <button type="button" onclick="closeAddGalleryFolderModal()" aria-label="Tutup" class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 text-lg font-bold leading-none text-slate-600 hover:bg-slate-200">&times;</button>
            </div>

            <form action="{{ route('admin.gallery.folders.store') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="_form_context" value="gallery_folder">
                <div>
                    <label for="gallery_folder_name" class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-700">Nama folder *</label>
                    <input id="gallery_folder_name" name="name" value="{{ old('_form_context') === 'gallery_folder' ? old('name') : '' }}" required maxlength="120" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-[#087F78] focus:outline-none">
                </div>
                <div>
                    <label for="gallery_folder_category" class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-700">Bidang galeri *</label>
                    <select id="gallery_folder_category" name="category_id" required class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-[#087F78] focus:outline-none">
                        <option value="">Pilih bidang</option>
                        @foreach($galleryCategories as $category)
                            <option value="{{ $category->id }}" @selected(old('_form_context') === 'gallery_folder' && old('category_id') == $category->id)>{{ $category->short_name ?: $category->name }}</option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-slate-500">Folder ini akan tampil di bagian bidang yang dipilih pada halaman galeri.</p>
                </div>
                <div>
                    <label for="gallery_folder_description" class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-700">Deskripsi</label>
                    <textarea id="gallery_folder_description" name="description" rows="3" maxlength="1000" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-[#087F78] focus:outline-none">{{ old('_form_context') === 'gallery_folder' ? old('description') : '' }}</textarea>
                </div>
                <div class="flex justify-end gap-3 border-t border-slate-100 pt-4">
                    <button type="button" onclick="closeAddGalleryFolderModal()" class="rounded-xl px-5 py-2.5 text-xs font-bold text-slate-600 transition-all hover:bg-slate-100">Batal</button>
                    <button type="submit" class="rounded-xl bg-[#087F78] px-6 py-2.5 text-xs font-bold text-white shadow-md transition-all hover:bg-[#066761]">Buat folder &rarr;</button>
                </div>
            </form>
            </div>
        </div>
    </div>

    <!-- Modal Unggah Foto -->
    <div id="addGalleryPhotosModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 p-4 backdrop-blur-xs flex">
        <div class="w-full max-w-xl overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-2xl">
            <div class="max-h-[90vh] space-y-6 overflow-y-auto p-6 sm:p-8">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div>
                    <h3 class="text-xl font-extrabold text-[#10243A]">Unggah Foto Galeri</h3>
                    <p class="mt-1 text-xs text-slate-500">Pilih hingga 100 foto, maksimal 25 MB per foto.</p>
                </div>
                <button type="button" onclick="closeAddGalleryPhotosModal()" aria-label="Tutup" class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 text-lg font-bold leading-none text-slate-600 hover:bg-slate-200">&times;</button>
            </div>

            <form id="galleryPhotosForm" action="{{ route('admin.gallery.photos.store') }}" method="POST" enctype="multipart/form-data" onsubmit="uploadGalleryPhotos(event)" class="space-y-4">
                @csrf
                <input type="hidden" name="_form_context" value="gallery_upload">
                <div id="galleryUploadError" class="hidden rounded-xl border border-rose-200 bg-rose-50 p-3 text-sm text-rose-700" role="alert"></div>
                <div>
                    <label for="gallery_folder_id" class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-700">Simpan ke folder *</label>
                    <select id="gallery_folder_id" name="gallery_folder_id" required class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-[#087F78] focus:outline-none">
                        <option value="">Pilih folder</option>
                        @foreach($galleryCategories as $category)
                            @php
                                $categoryFolders = $galleryFolders->where('category_id', $category->id);
                            @endphp
                            @if($categoryFolders->isNotEmpty())
                                <optgroup label="{{ $category->short_name ?: $category->name }}">
                                    @foreach($categoryFolders as $folder)
                                        <option value="{{ $folder->id }}" @selected(old('gallery_folder_id') == $folder->id)>{{ $folder->name }}</option>
                                    @endforeach
                                </optgroup>
                            @endif
                        @endforeach
                        @php
                            $uncategorizedGalleryFolders = $galleryFolders->whereNull('category_id');
                        @endphp
                        @if($uncategorizedGalleryFolders->isNotEmpty())
                            <optgroup label="Belum dikategorikan">
                                @foreach($uncategorizedGalleryFolders as $folder)
                                    <option value="{{ $folder->id }}" @selected(old('gallery_folder_id') == $folder->id)>{{ $folder->name }}</option>
                                @endforeach
                            </optgroup>
                        @endif
                    </select>
                </div>
                <div>
                    <label for="gallery_photos" class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-700">File foto *</label>
                    <div class="space-y-3">
                        <div>
                            <label for="gallery_photos" class="mb-1 block text-sm font-semibold text-slate-700">Pilih foto</label>
                            <input id="gallery_photos" name="photos[]" type="file" accept="image/jpeg,image/png,image/webp,image/avif" multiple class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm file:mr-3 file:rounded-lg file:border-0 file:bg-teal-50 file:px-3 file:py-2 file:font-semibold file:text-[#087F78]">
                        </div>
                        <div>
                            <label for="gallery_photo_folder" class="mb-1 block text-sm font-semibold text-slate-700">Atau pilih folder foto dari PC</label>
                            <input id="gallery_photo_folder" name="photo_folder[]" type="file" webkitdirectory directory multiple accept="image/jpeg,image/png,image/webp,image/avif" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm file:mr-3 file:rounded-lg file:border-0 file:bg-teal-50 file:px-3 file:py-2 file:font-semibold file:text-[#087F78]">
                        </div>
                    </div>
                    <p class="mt-1 text-xs text-slate-500">Format JPG, PNG, WEBP, atau AVIF. Maksimal 100 foto, 25 MB per foto. Jika memilih keduanya, file akan digabung ke album ini.</p>
                </div>
                <div>
                    <label for="gallery_photo_urls" class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-700">URL foto</label>
                    <textarea id="gallery_photo_urls" name="photo_urls" rows="3" placeholder="Satu URL gambar per baris: https://contoh.com/foto.jpg" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-[#087F78] focus:outline-none"></textarea>
                    <p class="mt-1 text-xs text-slate-500">Bisa digunakan sendiri atau digabung dengan file foto.</p>
                </div>
                <div class="flex justify-end gap-3 border-t border-slate-100 pt-4">
                    <button type="button" onclick="closeAddGalleryPhotosModal()" class="rounded-xl px-5 py-2.5 text-xs font-bold text-slate-600 transition-all hover:bg-slate-100">Batal</button>
                    <button type="submit" class="rounded-xl bg-[#087F78] px-6 py-2.5 text-xs font-bold text-white shadow-md transition-all hover:bg-[#066761]">Unggah foto &rarr;</button>
                </div>
            </form>
            </div>
        </div>
    </div>

    <div id="galleryUploadProgress" class="fixed inset-0 z-[70] hidden items-center justify-center bg-slate-900/50 p-4 backdrop-blur-xs flex" role="status" aria-live="polite">
        <div class="w-full max-w-md rounded-3xl border border-slate-200 bg-white p-7 shadow-2xl sm:p-9">
            <div class="mb-6 flex items-center gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-[#E2F6F2] text-2xl text-[#087F78]">↑</div>
                <div>
                    <h2 class="text-xl font-extrabold text-[#10243A]">Mengunggah foto</h2>
                    <p id="galleryUploadProgressMessage" class="mt-1 text-sm text-slate-500">Menyiapkan foto...</p>
                </div>
            </div>
            <div id="galleryUploadProgressTrack" class="h-3 overflow-hidden rounded-full bg-slate-100" role="progressbar" aria-label="Kemajuan unggah foto" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                <div id="galleryUploadProgressBar" class="h-full w-0 rounded-full bg-[#087F78] transition-all duration-200"></div>
            </div>
            <div class="mt-3 flex items-center justify-between text-sm">
                <span class="font-semibold text-slate-500">Progres unggah</span>
                <span id="galleryUploadProgressPercent" class="font-extrabold text-[#087F78]">0%</span>
            </div>
            <p class="mt-6 text-xs text-slate-400">Jangan tutup halaman sampai proses unggah selesai.</p>
        </div>
    </div>

    <!-- ========================================================
         MODAL 1: TAMBAH PROGRAM PELATIHAN
         ======================================================== -->
    <div id="addProgramModal" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs hidden flex items-center justify-center p-4">
        <div class="w-full max-w-xl overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-2xl">
            <div class="max-h-[90vh] space-y-6 overflow-y-auto p-6 sm:p-8">
            
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div>
                    <h3 class="text-xl font-extrabold text-[#10243A]">Tambah Program Pelatihan</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Isi informasi dasar program pelatihan</p>
                </div>
                <button type="button" onclick="closeAddProgramModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center font-bold text-lg leading-none cursor-pointer">
                    &times;
                </button>
            </div>

            <form action="{{ route('admin.courses.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <!-- Nama Program -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Nama Program *
                    </label>
                    <input 
                        type="text" 
                        name="title" 
                        required 
                        placeholder="Contoh: Motion Graphics dengan After Effects" 
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-[#087F78]"
                    >
                </div>

                <!-- Kategori & Status -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Kategori Bidang *
                        </label>
                        <select name="category_id" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-[#087F78]">
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Status Publikasi *
                        </label>
                        <select name="status" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-[#087F78]">
                            <option value="Publish">Publish</option>
                            <option value="Draft">Draft</option>
                        </select>
                    </div>
                </div>

                <!-- Gambar Latar Belakang -->
                <div>
                    <label for="add_course_image" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Gambar Latar Belakang
                    </label>
                    <input
                        type="file"
                        name="image"
                        id="add_course_image"
                        accept="image/jpeg,image/png,image/webp,image/avif"
                        class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm file:mr-3 file:rounded-lg file:border-0 file:bg-teal-50 file:px-3 file:py-2 file:font-semibold file:text-[#087F78]"
                    >
                    <p class="mt-1 text-xs text-slate-500">Opsional, maksimal 10 MB. Gambar ini tampil sebagai latar judul detail kelas.</p>
                    <input type="url" name="image_url" id="add_course_image_url" maxlength="2048" placeholder="Atau masukkan URL gambar: https://contoh.com/gambar.jpg" class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-[#087F78] focus:outline-none">
                </div>

                <!-- Level & Format -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Level *
                        </label>
                        <select name="level" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-[#087F78]">
                            <option value="Basic">Basic</option>
                            <option value="Basic–Intermediate">Basic–Intermediate</option>
                            <option value="Intermediate">Intermediate</option>
                            <option value="Advanced">Advanced</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Format *
                        </label>
                        <select name="format" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-[#087F78]">
                            <option value="Offline / Studio">Offline / Studio</option>
                            <option value="Praktik Lapangan">Praktik Lapangan</option>
                            <option value="Hybrid">Hybrid</option>
                            <option value="Online">Online</option>
                        </select>
                    </div>
                </div>

                <!-- Fasilitas -->
                <div>
                    <label for="add_course_facilities" class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-700">Fasilitas</label>
                    <textarea id="add_course_facilities" name="facilities" rows="2" maxlength="1000" placeholder="Contoh: Modul, Alat Praktik, Sertifikat" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-[#087F78] focus:outline-none"></textarea>
                </div>

                <!-- Deskripsi Ringkas -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Deskripsi Singkat *
                    </label>
                    <textarea 
                        name="description" 
                        rows="2.5" 
                        required 
                        placeholder="Ringkasan penjelasan program pelatihan..."
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-[#087F78]"></textarea>
                </div>

                <!-- Materi & Output -->
                <div>
                    <label for="add_course_syllabus" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Yang Akan Dipelajari
                    </label>
                    <textarea
                        name="syllabus"
                        id="add_course_syllabus"
                        rows="5"
                        placeholder="Satu materi per baris, contoh:&#10;Dasar-dasar komposisi visual&#10;Teknik pengambilan gambar"
                        class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-[#087F78] focus:outline-none"
                    ></textarea>
                    <p class="mt-1 text-xs text-slate-500">Tulis satu poin materi di setiap baris.</p>
                </div>

                <div>
                    <label for="add_course_outcome" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Target Kompetensi & Output
                    </label>
                    <textarea
                        name="outcome"
                        id="add_course_outcome"
                        rows="3"
                        placeholder="Jelaskan kemampuan yang akan dimiliki peserta setelah pelatihan."
                        class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-[#087F78] focus:outline-none"
                    ></textarea>
                </div>

                <!-- Action Buttons -->
                <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                    <button 
                        type="button" 
                        onclick="closeAddProgramModal()"
                        class="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100 transition-all cursor-pointer">
                        Batal
                    </button>
                    <button 
                        type="submit" 
                        class="px-6 py-2.5 rounded-xl text-xs font-bold bg-[#087F78] hover:bg-[#066761] text-white shadow-md transition-all cursor-pointer">
                        Simpan Program &rarr;
                    </button>
                </div>
            </form>

            </div>
        </div>
    </div>

    <!-- ========================================================
         MODAL 2: EDIT PROGRAM PELATIHAN
         ======================================================== -->
    <div id="editProgramModal" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs hidden flex items-center justify-center p-4">
        <div class="w-full max-w-xl overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-2xl">
            <div class="max-h-[90vh] space-y-6 overflow-y-auto p-6 sm:p-8">
            
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div>
                    <h3 class="text-xl font-extrabold text-[#10243A]">Edit Program Pelatihan</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Ubah informasi dan konten detail kelas</p>
                </div>
                <button type="button" onclick="closeEditProgramModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center font-bold text-lg leading-none cursor-pointer">
                    &times;
                </button>
            </div>

            <form id="editProgramForm" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')

                <!-- Nama Program -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Nama Program *
                    </label>
                    <input 
                        type="text" 
                        name="title" 
                        id="edit_course_title"
                        required 
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-[#087F78]"
                    >
                </div>

                <!-- Kategori & Status -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Kategori Bidang *
                        </label>
                        <select name="category_id" id="edit_course_category_id" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-[#087F78]">
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Status Publikasi *
                        </label>
                        <select name="status" id="edit_course_status" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-[#087F78]">
                            <option value="Publish">Publish</option>
                            <option value="Draft">Draft</option>
                        </select>
                    </div>
                </div>

                <!-- Gambar Latar Belakang -->
                <div>
                    <label for="edit_course_image" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Gambar Latar Belakang
                    </label>
                    <div id="edit_course_image_preview" class="mb-3 hidden overflow-hidden rounded-xl border border-slate-200 bg-slate-50">
                        <img id="edit_course_image_preview_image" src="" alt="Gambar latar belakang saat ini" class="h-28 w-full object-cover">
                        <p class="px-3 py-2 text-xs text-slate-500">Gambar saat ini. Unggah file baru untuk menggantinya.</p>
                    </div>
                    <input
                        type="file"
                        name="image"
                        id="edit_course_image"
                        accept="image/jpeg,image/png,image/webp,image/avif"
                        class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm file:mr-3 file:rounded-lg file:border-0 file:bg-teal-50 file:px-3 file:py-2 file:font-semibold file:text-[#087F78]"
                    >
                    <p class="mt-1 text-xs text-slate-500">Opsional, maksimal 10 MB. Kosongkan jika gambar tidak diubah.</p>
                    <input type="url" name="image_url" id="edit_course_image_url" maxlength="2048" placeholder="Atau masukkan URL gambar baru: https://contoh.com/gambar.jpg" class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-[#087F78] focus:outline-none">
                </div>

                <!-- Level & Format -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Level *
                        </label>
                            <select name="level" id="edit_course_level" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-[#087F78]">
                            <option value="Basic">Basic</option>
                            <option value="Basic–Intermediate">Basic–Intermediate</option>
                            <option value="Intermediate">Intermediate</option>
                            <option value="Advanced">Advanced</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Format *
                        </label>
                        <select name="format" id="edit_course_format" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-[#087F78]">
                            <option value="Offline / Studio">Offline / Studio</option>
                            <option value="Praktik Lapangan">Praktik Lapangan</option>
                            <option value="Hybrid">Hybrid</option>
                            <option value="Online">Online</option>
                        </select>
                    </div>
                </div>

                <!-- Fasilitas -->
                <div>
                    <label for="edit_course_facilities" class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-700">Fasilitas</label>
                    <textarea id="edit_course_facilities" name="facilities" rows="2" maxlength="1000" placeholder="Contoh: Modul, Alat Praktik, Sertifikat" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-[#087F78] focus:outline-none"></textarea>
                </div>

                <!-- Deskripsi Ringkas -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Deskripsi Singkat *
                    </label>
                    <textarea 
                        name="description" 
                        id="edit_course_description"
                        rows="2.5" 
                        required 
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-[#087F78]"></textarea>
                </div>

                <!-- Materi & Output -->
                <div>
                    <label for="edit_course_syllabus" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Yang Akan Dipelajari
                    </label>
                    <textarea
                        name="syllabus"
                        id="edit_course_syllabus"
                        rows="5"
                        placeholder="Satu materi per baris"
                        class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-[#087F78] focus:outline-none"
                    ></textarea>
                    <p class="mt-1 text-xs text-slate-500">Tulis satu poin materi di setiap baris.</p>
                </div>

                <div>
                    <label for="edit_course_outcome" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Target Kompetensi & Output
                    </label>
                    <textarea
                        name="outcome"
                        id="edit_course_outcome"
                        rows="3"
                        placeholder="Jelaskan kemampuan yang akan dimiliki peserta setelah pelatihan."
                        class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-[#087F78] focus:outline-none"
                    ></textarea>
                </div>

                <!-- Action Buttons -->
                <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                    <button 
                        type="button" 
                        onclick="closeEditProgramModal()"
                        class="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100 transition-all cursor-pointer">
                        Batal
                    </button>
                    <button 
                        type="submit" 
                        class="px-6 py-2.5 rounded-xl text-xs font-bold bg-[#087F78] hover:bg-[#066761] text-white shadow-md transition-all cursor-pointer">
                        Perbarui Program &rarr;
                    </button>
                </div>
            </form>

            </div>
        </div>
    </div>

    <!-- ========================================================
         MODAL 3: TAMBAH JADWAL / BATCH
         Durasi dihitung otomatis dari tanggal hari ini ke start_date!
         ======================================================== -->
    <div id="addSessionModal" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs hidden flex items-center justify-center p-4">
        <div class="w-full max-w-xl overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-2xl">
            <div class="max-h-[90vh] space-y-6 overflow-y-auto p-6 sm:p-8">
            
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div>
                    <h3 class="text-xl font-extrabold text-[#10243A]">Tambah Jadwal / Batch Baru</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Tentukan kapan batch berjalan, durasi akan otomatis disesuaikan</p>
                </div>
                <button type="button" onclick="closeAddSessionModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center font-bold text-lg leading-none cursor-pointer">
                    &times;
                </button>
            </div>

            <form action="{{ route('admin.sessions.store') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Pilih Program -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Pilih Program Pelatihan *
                    </label>
                    <select name="course_id" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-[#087F78]">
                        @foreach($courses as $c)
                            <option value="{{ $c->id }}">{{ $c->title }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Nama Batch -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Nama Batch / Periode *
                    </label>
                    <input 
                        type="text" 
                        name="batch_name" 
                        required 
                        placeholder="Contoh: Batch 02 - Desember 2026" 
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-[#087F78]"
                    >
                </div>

                <!-- Tanggal Mulai (Kapan Program Berjalan) & Durasi Otomatis -->
                <div class="p-4 rounded-2xl bg-[#E2F6F2]/50 border border-[#087F78]/30 space-y-2">
                    <label class="block text-xs font-bold text-[#087F78] uppercase tracking-wider">
                        📅 Tanggal Kapan Program Berjalan *
                    </label>
                    <input 
                        type="date" 
                        name="start_date" 
                        id="session_start_date"
                        required
                        value="{{ date('Y-m-d', strtotime('+7 days')) }}"
                        onchange="updateSessionDuration('session_start_date', 'session_end_date', 'session_calc_duration_preview')"
                        class="w-full px-4 py-2.5 rounded-xl bg-white border border-slate-200 text-sm focus:outline-none focus:border-[#087F78]"
                    >
                    <label class="block text-xs font-bold text-[#087F78] uppercase tracking-wider mt-3 mb-1">Tanggal Selesai *</label>
                    <input type="date" name="end_date" id="session_end_date" required onchange="updateSessionDuration('session_start_date', 'session_end_date', 'session_calc_duration_preview')" class="w-full px-4 py-2.5 rounded-xl bg-white border border-slate-200 text-sm focus:outline-none focus:border-[#087F78]">
                    <div class="text-xs text-slate-600 flex items-center justify-between pt-1">
                        <span>Hari ini: <strong>{{ date('d M Y') }}</strong></span>
                        <span id="session_calc_duration_preview" class="font-bold text-[#087F78]">Durasi otomatis: 7 hari</span>
                    </div>
                </div>

                <!-- Lokasi & Kuota -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Lokasi / Tempat Pelatihan
                        </label>
                        <input 
                            type="text" 
                            name="location" 
                            value="JCP Learning Studio / Onsite"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-[#087F78]"
                        >
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Kuota Peserta *
                        </label>
                        <input 
                            type="number" 
                            name="quota" 
                            value="15" 
                            min="1" 
                            required 
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-[#087F78]"
                        >
                    </div>
                </div>

                <!-- Harga & Status -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Biaya Investasi (Rp)
                        </label>
                        <input 
                            type="number" 
                            name="price" 
                            value="1500000" 
                            min="0" 
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-[#087F78]"
                        >
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Status Pendaftaran *
                        </label>
                        <select name="status" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-[#087F78]">
                            <option value="Buka">Buka</option>
                            <option value="Segera">Segera</option>
                            <option value="Penuh">Penuh</option>
                        </select>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                    <button 
                        type="button" 
                        onclick="closeAddSessionModal()"
                        class="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100 transition-all cursor-pointer">
                        Batal
                    </button>
                    <button 
                        type="submit" 
                        class="px-6 py-2.5 rounded-xl text-xs font-bold bg-[#087F78] hover:bg-[#066761] text-white shadow-md transition-all cursor-pointer">
                        Simpan Jadwal &rarr;
                    </button>
                </div>
            </form>

            </div>
        </div>
    </div>

    <!-- ========================================================
         MODAL 4: EDIT JADWAL / BATCH
         ======================================================== -->
    <div id="editSessionModal" class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs hidden flex items-center justify-center p-4">
        <div class="w-full max-w-xl overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-2xl">
            <div class="max-h-[90vh] space-y-6 overflow-y-auto p-6 sm:p-8">
            
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div>
                    <h3 class="text-xl font-extrabold text-[#10243A]">Edit Jadwal / Batch</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Perbarui tanggal pelaksanaan atau status kuota</p>
                </div>
                <button type="button" onclick="closeEditSessionModal()" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center font-bold text-lg leading-none cursor-pointer">
                    &times;
                </button>
            </div>

            <form id="editSessionForm" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <!-- Pilih Program -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Pilih Program Pelatihan *
                    </label>
                    <select name="course_id" id="edit_session_course_id" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-[#087F78]">
                        @foreach($courses as $c)
                            <option value="{{ $c->id }}">{{ $c->title }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Nama Batch -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Nama Batch / Periode *
                    </label>
                    <input 
                        type="text" 
                        name="batch_name" 
                        id="edit_session_batch_name"
                        required 
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-[#087F78]"
                    >
                </div>

                <!-- Tanggal Mulai & Durasi Otomatis -->
                <div class="p-4 rounded-2xl bg-[#E2F6F2]/50 border border-[#087F78]/30 space-y-2">
                    <label class="block text-xs font-bold text-[#087F78] uppercase tracking-wider">
                        📅 Tanggal Kapan Program Berjalan *
                    </label>
                    <input 
                        type="date" 
                        name="start_date" 
                        id="edit_session_start_date"
                        required
                        onchange="updateSessionDuration('edit_session_start_date', 'edit_session_end_date', 'edit_session_calc_duration_preview')"
                        class="w-full px-4 py-2.5 rounded-xl bg-white border border-slate-200 text-sm focus:outline-none focus:border-[#087F78]"
                    >
                    <label class="block text-xs font-bold text-[#087F78] uppercase tracking-wider mt-3 mb-1">Tanggal Selesai *</label>
                    <input type="date" name="end_date" id="edit_session_end_date" required onchange="updateSessionDuration('edit_session_start_date', 'edit_session_end_date', 'edit_session_calc_duration_preview')" class="w-full px-4 py-2.5 rounded-xl bg-white border border-slate-200 text-sm focus:outline-none focus:border-[#087F78]">
                    <div class="text-xs text-slate-600 flex items-center justify-between pt-1">
                        <span>Hari ini: <strong>{{ date('d M Y') }}</strong></span>
                        <span id="edit_session_calc_duration_preview" class="font-bold text-[#087F78]">Durasi otomatis: -</span>
                    </div>
                </div>

                <!-- Lokasi & Kuota -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Lokasi / Tempat Pelatihan
                        </label>
                        <input 
                            type="text" 
                            name="location" 
                            id="edit_session_location"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-[#087F78]"
                        >
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Kuota Peserta *
                        </label>
                        <input 
                            type="number" 
                            name="quota" 
                            id="edit_session_quota"
                            min="1" 
                            required 
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-[#087F78]"
                        >
                    </div>
                </div>

                <!-- Harga & Status -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Biaya Investasi (Rp)
                        </label>
                        <input 
                            type="number" 
                            name="price" 
                            id="edit_session_price"
                            min="0" 
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-[#087F78]"
                        >
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                            Status Pendaftaran *
                        </label>
                        <select name="status" id="edit_session_status" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-[#087F78]">
                            <option value="Buka">Buka</option>
                            <option value="Segera">Segera</option>
                            <option value="Penuh">Penuh</option>
                        </select>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                    <button 
                        type="button" 
                        onclick="closeEditSessionModal()"
                        class="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100 transition-all cursor-pointer">
                        Batal
                    </button>
                    <button 
                        type="submit" 
                        class="px-6 py-2.5 rounded-xl text-xs font-bold bg-[#087F78] hover:bg-[#066761] text-white shadow-md transition-all cursor-pointer">
                        Perbarui Jadwal &rarr;
                    </button>
                </div>
            </form>

            </div>
        </div>
    </div>

    <!-- Modal Tambah Artikel -->
    <div id="addArticleModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 p-4 backdrop-blur-xs flex">
        <div class="w-full max-w-2xl overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-2xl">
            <div class="max-h-[90vh] space-y-6 overflow-y-auto p-6 sm:p-8">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div>
                    <h3 class="text-xl font-extrabold text-[#10243A]">Tambah Artikel</h3>
                    <p class="mt-1 text-xs text-slate-500">Artikel berstatus Publish akan tampil di halaman Artikel pengguna.</p>
                </div>
                <button type="button" onclick="closeAddArticleModal()" class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 text-lg font-bold leading-none text-slate-600 hover:bg-slate-200">&times;</button>
            </div>

            <form action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label for="add_article_title" class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-700">Judul artikel *</label>
                    <input id="add_article_title" name="title" type="text" required maxlength="255" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-[#087F78] focus:outline-none">
                </div>
                <div>
                    <label for="add_article_image" class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-700">Foto utama</label>
                    <input id="add_article_image" name="image" type="file" accept="image/jpeg,image/png,image/webp,image/avif" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm file:mr-3 file:rounded-lg file:border-0 file:bg-teal-50 file:px-3 file:py-2 file:font-semibold file:text-[#087F78]">
                    <p class="mt-1 text-xs text-slate-500">Opsional, maksimal 10 MB. Format JPG, PNG, WEBP, atau AVIF.</p>
                    <input id="add_article_image_url" name="image_url" type="url" maxlength="2048" placeholder="Atau masukkan URL gambar" class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-[#087F78] focus:outline-none">
                </div>
                <div>
                    <label for="add_article_category" class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-700">Bidang pelatihan *</label>
                    <select id="add_article_category" name="category" required class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-[#087F78] focus:outline-none">
                        <option value="">Pilih bidang pelatihan</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->name }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="add_article_excerpt" class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-700">Ringkasan artikel *</label>
                    <textarea id="add_article_excerpt" name="excerpt" rows="3" maxlength="500" required class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-[#087F78] focus:outline-none"></textarea>
                </div>
                <div>
                    <label for="add_article_content" class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-700">Isi artikel *</label>
                    <textarea id="add_article_content" name="content" rows="8" required class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-[#087F78] focus:outline-none"></textarea>
                </div>
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                    <div>
                        <label for="add_article_published_at" class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-700">Tanggal tayang</label>
                        <input id="add_article_published_at" name="published_at" type="date" value="{{ date('Y-m-d') }}" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-[#087F78] focus:outline-none">
                    </div>
                    <div>
                        <label for="add_article_status" class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-700">Status *</label>
                        <select id="add_article_status" name="status" required class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-[#087F78] focus:outline-none">
                            <option value="Publish">Publish</option>
                            <option value="Draft">Draft</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end gap-3 border-t border-slate-100 pt-4">
                    <button type="button" onclick="closeAddArticleModal()" class="rounded-xl px-5 py-2.5 text-xs font-bold text-slate-600 transition-all hover:bg-slate-100">Batal</button>
                    <button type="submit" class="rounded-xl bg-[#087F78] px-6 py-2.5 text-xs font-bold text-white shadow-md transition-all hover:bg-[#066761]">Simpan artikel &rarr;</button>
                </div>
            </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Artikel -->
    <div id="editArticleModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 p-4 backdrop-blur-xs flex">
        <div class="w-full max-w-2xl overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-2xl">
            <div class="max-h-[90vh] space-y-6 overflow-y-auto p-6 sm:p-8">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div>
                    <h3 class="text-xl font-extrabold text-[#10243A]">Edit Artikel</h3>
                    <p class="mt-1 text-xs text-slate-500">Perbarui isi artikel dan status tayangnya.</p>
                </div>
                <button type="button" onclick="closeEditArticleModal()" class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 text-lg font-bold leading-none text-slate-600 hover:bg-slate-200">&times;</button>
            </div>

            <form id="editArticleForm" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label for="edit_article_title" class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-700">Judul artikel *</label>
                    <input id="edit_article_title" name="title" type="text" required maxlength="255" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-[#087F78] focus:outline-none">
                </div>
                <div>
                    <label for="edit_article_image" class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-700">Ganti foto utama</label>
                    <input id="edit_article_image" name="image" type="file" accept="image/jpeg,image/png,image/webp,image/avif" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm file:mr-3 file:rounded-lg file:border-0 file:bg-teal-50 file:px-3 file:py-2 file:font-semibold file:text-[#087F78]">
                    <p class="mt-1 text-xs text-slate-500">Kosongkan jika foto lama tetap digunakan. Maksimal 10 MB.</p>
                    <input id="edit_article_image_url" name="image_url" type="url" maxlength="2048" placeholder="Atau masukkan URL gambar baru" class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-[#087F78] focus:outline-none">
                </div>
                <div>
                    <label for="edit_article_category" class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-700">Bidang pelatihan *</label>
                    <select id="edit_article_category" name="category" required class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-[#087F78] focus:outline-none">
                        @foreach($categories as $category)
                            <option value="{{ $category->name }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="edit_article_excerpt" class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-700">Ringkasan artikel *</label>
                    <textarea id="edit_article_excerpt" name="excerpt" rows="3" maxlength="500" required class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-[#087F78] focus:outline-none"></textarea>
                </div>
                <div>
                    <label for="edit_article_content" class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-700">Isi artikel *</label>
                    <textarea id="edit_article_content" name="content" rows="8" required class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-[#087F78] focus:outline-none"></textarea>
                </div>
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                    <div>
                        <label for="edit_article_published_at" class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-700">Tanggal publish / jadwal</label>
                        <input id="edit_article_published_at" name="published_at" type="date" min="{{ now()->toDateString() }}" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-[#087F78] focus:outline-none">
                    </div>
                    <div>
                        <label for="edit_article_status" class="mb-1 block text-xs font-bold uppercase tracking-wider text-slate-700">Status *</label>
                        <select id="edit_article_status" name="status" required class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-[#087F78] focus:outline-none">
                            <option value="Publish">Publish</option>
                            <option value="Draft">Draft</option>
                            <option value="Terjadwalkan">Terjadwalkan</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end gap-3 border-t border-slate-100 pt-4">
                    <button type="button" onclick="closeEditArticleModal()" class="rounded-xl px-5 py-2.5 text-xs font-bold text-slate-600 transition-all hover:bg-slate-100">Batal</button>
                    <button type="submit" class="rounded-xl bg-[#087F78] px-6 py-2.5 text-xs font-bold text-white shadow-md transition-all hover:bg-[#066761]">Simpan perubahan &rarr;</button>
                </div>
            </form>
            </div>
        </div>
    </div>

    <div id="adminDeleteConfirmModal" class="fixed inset-0 z-[90] hidden items-center justify-center bg-slate-900/50 p-4 backdrop-blur-xs" role="dialog" aria-modal="true" aria-labelledby="adminDeleteConfirmTitle">
        <div class="w-full max-w-lg rounded-3xl border border-slate-200 bg-white p-6 shadow-2xl sm:p-8">
            <p class="text-xs font-bold uppercase tracking-wider text-rose-700">Konfirmasi penghapusan</p>
            <h3 id="adminDeleteConfirmTitle" class="mt-2 text-xl font-extrabold text-[#10243A]">Hapus data?</h3>
            <p class="mt-3 text-sm leading-relaxed text-slate-600">Data yang dihapus tidak dapat dikembalikan. Periksa detail berikut sebelum melanjutkan.</p>
            <dl class="mt-5 grid gap-3 rounded-2xl bg-slate-50 p-4 text-sm sm:grid-cols-2">
                <div>
                    <dt class="text-xs font-bold uppercase tracking-wider text-slate-400">Jenis data</dt>
                    <dd id="adminDeleteConfirmType" class="mt-1 font-bold text-[#10243A]"></dd>
                </div>
                <div>
                    <dt class="text-xs font-bold uppercase tracking-wider text-slate-400">Nama</dt>
                    <dd id="adminDeleteConfirmName" class="mt-1 break-words font-bold text-[#10243A]"></dd>
                </div>
            </dl>
            <div class="mt-6 flex justify-end gap-3 border-t border-slate-100 pt-5">
                <button type="button" onclick="closeAdminDeleteModal()" class="rounded-xl px-5 py-2.5 text-sm font-bold text-slate-600 hover:bg-slate-100">Batal</button>
                <button type="button" onclick="confirmAdminDelete()" class="rounded-xl bg-rose-700 px-5 py-2.5 text-sm font-bold text-white hover:bg-rose-800">Hapus</button>
            </div>
        </div>
    </div>

    <!-- Script Interactivity & Penghitungan Durasi Otomatis -->
    <script>
        function setAdminSidebarOpen(isOpen) {
            const sidebar = document.getElementById('admin-sidebar');
            const overlay = document.getElementById('admin-sidebar-overlay');
            const toggle = document.getElementById('admin-sidebar-toggle');

            if (!sidebar || !overlay) {
                return;
            }

            sidebar.classList.toggle('-translate-x-full', !isOpen);
            sidebar.classList.toggle('translate-x-0', isOpen);
            overlay.classList.toggle('hidden', !isOpen);

            if (toggle) {
                toggle.setAttribute('aria-expanded', String(isOpen));
                toggle.setAttribute('aria-label', isOpen ? 'Tutup menu navigasi' : 'Buka menu navigasi');
            }
        }

        function toggleAdminSidebar() {
            const sidebar = document.getElementById('admin-sidebar');

            if (!sidebar) {
                return;
            }

            setAdminSidebarOpen(sidebar.classList.contains('-translate-x-full'));
        }

        document.getElementById('admin-sidebar-toggle')?.addEventListener('click', toggleAdminSidebar);
        document.getElementById('admin-sidebar-overlay')?.addEventListener('click', () => setAdminSidebarOpen(false));
        document.querySelectorAll('#admin-sidebar a').forEach((link) => {
            link.addEventListener('click', () => setAdminSidebarOpen(false));
        });

        let pendingAdminDeleteForm = null;

        function openAdminDeleteModal(form, type, name) {
            const modal = document.getElementById('adminDeleteConfirmModal');
            if (!modal || !form) return;

            pendingAdminDeleteForm = form;
            document.getElementById('adminDeleteConfirmType').textContent = type;
            document.getElementById('adminDeleteConfirmName').textContent = name;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeAdminDeleteModal() {
            const modal = document.getElementById('adminDeleteConfirmModal');
            if (!modal) return;

            pendingAdminDeleteForm = null;
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function confirmAdminDelete() {
            if (!pendingAdminDeleteForm) return;

            const form = pendingAdminDeleteForm;
            closeAdminDeleteModal();
            form.submit();
        }

        // Batasi pilihan batch sesuai kelas yang dipilih sebelum filter dikirim.
        function filterRegistrationBatches(courseId) {
            const batchSelect = document.getElementById('registration_session_filter');
            if (!batchSelect) return;

            const currentBatchId = batchSelect.value;
            let currentBatchIsVisible = !currentBatchId;

            Array.from(batchSelect.options).forEach((option) => {
                const optionCourseId = option.dataset.courseId;
                const isVisible = !optionCourseId || !courseId || optionCourseId === String(courseId);

                option.hidden = !isVisible;
                option.disabled = !isVisible;

                if (option.value === currentBatchId && isVisible) {
                    currentBatchIsVisible = true;
                }
            });

            if (!currentBatchIsVisible) {
                batchSelect.value = '';
            }
        }

        function updateSessionDuration(startId, endId, previewId) {
            const startValue = document.getElementById(startId)?.value;
            const endValue = document.getElementById(endId)?.value;
            const preview = document.getElementById(previewId);
            if (!preview) return;
            if (!startValue || !endValue) {
                preview.innerText = 'Durasi pelatihan: lengkapi tanggal mulai dan selesai';
                return;
            }
            const start = new Date(startValue + 'T00:00:00');
            const end = new Date(endValue + 'T00:00:00');
            const days = Math.round((end - start) / 86400000) + 1;
            preview.innerText = days > 0 ? 'Durasi pelatihan: ' + days + ' hari' : 'Tanggal selesai harus sama atau setelah tanggal mulai';
        }

        // Modal Tambah Program
        function openAddProgramModal() {
            document.getElementById('addProgramModal').classList.remove('hidden');
        }
        function closeAddProgramModal() {
            document.getElementById('addProgramModal').classList.add('hidden');
        }

        // Modal Edit Program
        function openEditProgramModal(course) {
            const form = document.getElementById('editProgramForm');
            form.action = `/admin/courses/${course.id}`;
            document.getElementById('edit_course_title').value = course.title;
            document.getElementById('edit_course_category_id').value = course.category_id;
            document.getElementById('edit_course_status').value = course.status;
            document.getElementById('edit_course_level').value = course.level;
            document.getElementById('edit_course_format').value = course.format;
            document.getElementById('edit_course_facilities').value = course.facilities || '';
            document.getElementById('edit_course_description').value = course.description;
            document.getElementById('edit_course_outcome').value = course.outcome || '';
            document.getElementById('edit_course_syllabus').value = Array.isArray(course.syllabus)
                ? course.syllabus.join('\n')
                : '';
            document.getElementById('edit_course_image_url').value = course.image_url || '';

            const imagePreview = document.getElementById('edit_course_image_preview');
            const imagePreviewImage = document.getElementById('edit_course_image_preview_image');
            if (course.image || course.image_url) {
                imagePreviewImage.src = course.image_url || (@json(rtrim(Storage::disk('public')->url(''), '/').'/') + course.image);
                imagePreview.classList.remove('hidden');
            } else {
                imagePreviewImage.removeAttribute('src');
                imagePreview.classList.add('hidden');
            }

            document.getElementById('editProgramModal').classList.remove('hidden');
        }
        function closeEditProgramModal() {
            document.getElementById('editProgramModal').classList.add('hidden');
        }

        // Modal Tambah & Edit Artikel
        function openAddArticleModal() {
            document.getElementById('addArticleModal').classList.remove('hidden');
        }
        function closeAddArticleModal() {
            document.getElementById('addArticleModal').classList.add('hidden');
        }
        function publishArticleNow(button) {
            const form = button.closest('form');
            const statusInput = form?.querySelector('[name="status"]');
            const publishedAtInput = form?.querySelector('[name="published_at"]');

            if (!form || !statusInput || !publishedAtInput) {
                return;
            }

            const now = new Date();
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const day = String(now.getDate()).padStart(2, '0');
            statusInput.value = 'Publish';
            publishedAtInput.value = `${now.getFullYear()}-${month}-${day}`;
            form.requestSubmit();
        }
        function submitArticleAction(status, requiresSchedule) {
            const form = document.getElementById('addArticleInlineForm');
            const statusInput = document.getElementById('add_article_inline_status');
            const publishedAtInput = document.getElementById('add_article_published_at_inline');

            if (!form || !statusInput || !publishedAtInput) {
                return;
            }

            if (requiresSchedule && !publishedAtInput.value) {
                publishedAtInput.setCustomValidity('Isi tanggal jadwal terlebih dahulu.');
                publishedAtInput.reportValidity();
                publishedAtInput.setCustomValidity('');
                return;
            }

            if (status === 'Publish' && !publishedAtInput.value) {
                const now = new Date();
                const month = String(now.getMonth() + 1).padStart(2, '0');
                const day = String(now.getDate()).padStart(2, '0');
                publishedAtInput.value = `${now.getFullYear()}-${month}-${day}`;
            }

            statusInput.value = status;
            syncRichEditors(form);

            if (form.reportValidity()) {
                form.requestSubmit();
            }
        }
        function syncRichEditors(scope = document) {
            const editors = scope.matches?.('[data-rich-editor]')
                ? [scope]
                : scope.querySelectorAll('[data-rich-editor]');

            editors.forEach((editor) => {
                const surface = editor.querySelector('[data-rich-surface]');
                const value = editor.querySelector('[data-rich-value]');
                if (surface && value) {
                    value.value = surface.innerHTML.trim();
                }
            });
        }
        function validateRichEditor(form) {
            syncRichEditors(form);

            const surface = form.querySelector('[data-rich-surface]');
            const value = form.querySelector('[data-rich-value]');

            if (!surface || !value || value.value.trim() !== '') {
                return true;
            }

            surface.focus();
            surface.classList.add('ring-2', 'ring-rose-300');
            window.alert('Isi artikel wajib diisi sebelum disimpan atau dijadwalkan.');

            return false;
        }
        document.querySelectorAll('[data-rich-editor]').forEach((editor) => {
            const surface = editor.querySelector('[data-rich-surface]');
            const form = editor.closest('form');

            surface?.addEventListener('input', () => syncRichEditors(editor));
            surface?.addEventListener('blur', () => syncRichEditors(editor));

            editor.querySelectorAll('[data-rich-command]').forEach((button) => {
                button.addEventListener('mousedown', (event) => event.preventDefault());
                button.addEventListener('click', () => {
                    surface?.focus();
                    document.execCommand(button.dataset.richCommand, false, button.dataset.richValue || null);
                    syncRichEditors(form || editor);
                });
            });

            form?.addEventListener('submit', () => syncRichEditors(form), true);
        });
        window.initializeArticleCalendar = () => {
            const calendar = document.querySelector('[data-article-calendar]');

            if (!calendar) {
                return;
            }

            calendar.querySelectorAll('[data-article-calendar-date]').forEach((dayButton) => {
                dayButton.addEventListener('click', () => {
                    const dateInput = document.getElementById('add_article_published_at_inline');

                    if (!dateInput) {
                        return;
                    }

                    dateInput.value = dayButton.dataset.articleCalendarDate || '';
                    dateInput.dispatchEvent(new Event('change', { bubbles: true }));
                    dateInput.focus();
                });
            });

            calendar.querySelectorAll('[data-calendar-navigation]').forEach((navigationButton) => {
                navigationButton.addEventListener('click', async () => {
                    const currentMonth = calendar.dataset.calendarMonth;
                    const [year, month] = currentMonth.split('-').map(Number);
                    const targetMonth = new Date(year, month - 1, 1);

                    if (navigationButton.dataset.calendarNavigation === 'previous') {
                        targetMonth.setMonth(targetMonth.getMonth() - 1);
                    } else if (navigationButton.dataset.calendarNavigation === 'next') {
                        targetMonth.setMonth(targetMonth.getMonth() + 1);
                    } else {
                        const now = new Date();
                        targetMonth.setFullYear(now.getFullYear(), now.getMonth(), 1);
                    }

                    const targetMonthValue = targetMonth.getFullYear() + '-' + String(targetMonth.getMonth() + 1).padStart(2, '0');
                    const url = new URL(window.location.href);
                    url.searchParams.set('tab', 'artikel');
                    url.searchParams.set('article_month', targetMonthValue);
                    url.searchParams.set('calendar_only', '1');
                    calendar.classList.add('pointer-events-none', 'opacity-60');

                    try {
                        const response = await fetch(url, {
                            headers: {
                                Accept: 'text/html',
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                        });

                        if (!response.ok) {
                            throw new Error('Kalender gagal dimuat.');
                        }

                        const html = await response.text();
                        const template = document.createElement('template');
                        template.innerHTML = html.trim();
                        const nextCalendar = template.content.firstElementChild;

                        if (!nextCalendar) {
                            throw new Error('Tampilan kalender tidak valid.');
                        }

                        calendar.replaceWith(nextCalendar);
                        url.searchParams.delete('calendar_only');
                        window.history.replaceState({}, '', url);
                        window.initializeArticleCalendar();
                    } catch (error) {
                        calendar.classList.remove('pointer-events-none', 'opacity-60');
                        window.alert(error.message || 'Kalender gagal dimuat. Silakan coba lagi.');
                    }
                });
            });
        };
        window.initializeArticleCalendar();
        function openEditArticleModal(article) {
            const form = document.getElementById('editArticleForm');
            form.action = `/admin/articles/${article.id}`;
            document.getElementById('edit_article_title').value = article.title || '';
            document.getElementById('edit_article_category').value = article.category || '';
            document.getElementById('edit_article_image_url').value = article.image_url || '';
            document.getElementById('edit_article_excerpt').value = article.excerpt || '';
            document.getElementById('edit_article_content').value = article.content || '';
            document.getElementById('edit_article_published_at').value = article.published_at ? String(article.published_at).slice(0, 10) : '';
            document.getElementById('edit_article_status').value = article.status === 'Draft' && article.published_at
                ? 'Terjadwalkan'
                : (article.status || 'Draft');
            document.getElementById('editArticleModal').classList.remove('hidden');
        }
        function closeEditArticleModal() {
            document.getElementById('editArticleModal').classList.add('hidden');
        }

        // Modal Tambah Session / Jadwal
        function openAddSessionModal() {
            document.getElementById('addSessionModal').classList.remove('hidden');
            updateSessionDuration('session_start_date', 'session_end_date', 'session_calc_duration_preview');
        }
        function closeAddSessionModal() {
            document.getElementById('addSessionModal').classList.add('hidden');
        }

        function openAddGalleryFolderModal() {
            document.getElementById('addGalleryFolderModal').classList.remove('hidden');
        }
        function closeAddGalleryFolderModal() {
            document.getElementById('addGalleryFolderModal').classList.add('hidden');
        }
        function showCategoryImagePreview(preview, previewImage, source) {
            if (!preview || !previewImage || !source) {
                return;
            }

            previewImage.src = source;
            preview.classList.remove('hidden');
        }
        function hideCategoryImagePreview(preview, previewImage) {
            if (!preview || !previewImage) {
                return;
            }

            previewImage.removeAttribute('src');
            preview.classList.add('hidden');
        }
        function previewCategoryImageFile(fileInput, preview, previewImage) {
            const file = fileInput?.files?.[0];

            if (!file || !preview || !previewImage) {
                return false;
            }

            const objectUrl = URL.createObjectURL(file);
            previewImage.addEventListener('load', () => URL.revokeObjectURL(objectUrl), { once: true });
            showCategoryImagePreview(preview, previewImage, objectUrl);

            return true;
        }
        const addCategoryImageInput = document.getElementById('program_category_image');
        const addCategoryImageUrlInput = document.getElementById('program_category_image_url');
        const addCategoryImagePreview = document.getElementById('addCategoryImagePreview');
        const addCategoryImagePreviewImage = document.getElementById('addCategoryImagePreviewImage');

        addCategoryImageInput?.addEventListener('change', () => {
            if (previewCategoryImageFile(addCategoryImageInput, addCategoryImagePreview, addCategoryImagePreviewImage)) {
                addCategoryImageUrlInput.value = '';
            } else if (addCategoryImageUrlInput.value.trim()) {
                showCategoryImagePreview(addCategoryImagePreview, addCategoryImagePreviewImage, addCategoryImageUrlInput.value.trim());
            } else {
                hideCategoryImagePreview(addCategoryImagePreview, addCategoryImagePreviewImage);
            }
        });
        addCategoryImageUrlInput?.addEventListener('change', () => {
            const imageUrl = addCategoryImageUrlInput.value.trim();

            if (imageUrl) {
                addCategoryImageInput.value = '';
                showCategoryImagePreview(addCategoryImagePreview, addCategoryImagePreviewImage, imageUrl);
            } else if (!previewCategoryImageFile(addCategoryImageInput, addCategoryImagePreview, addCategoryImagePreviewImage)) {
                hideCategoryImagePreview(addCategoryImagePreview, addCategoryImagePreviewImage);
            }
        });

        const editCategoryImageInput = document.getElementById('edit_category_image');
        const editCategoryImageUrlInput = document.getElementById('edit_category_image_url');
        const editCategoryRemoveImageInput = document.getElementById('edit_category_remove_image');
        const editCategoryImagePreview = document.getElementById('editCategoryImagePreview');
        const editCategoryImagePreviewImage = document.getElementById('editCategoryImagePreviewImage');

        editCategoryImageInput?.addEventListener('change', () => {
            if (previewCategoryImageFile(editCategoryImageInput, editCategoryImagePreview, editCategoryImagePreviewImage)) {
                editCategoryImageUrlInput.value = '';
                editCategoryRemoveImageInput.checked = false;
            } else if (editCategoryImageUrlInput.value.trim()) {
                showCategoryImagePreview(editCategoryImagePreview, editCategoryImagePreviewImage, editCategoryImageUrlInput.value.trim());
            } else if (editCategoryImagePreviewImage.dataset.originalSource) {
                showCategoryImagePreview(editCategoryImagePreview, editCategoryImagePreviewImage, editCategoryImagePreviewImage.dataset.originalSource);
            } else {
                hideCategoryImagePreview(editCategoryImagePreview, editCategoryImagePreviewImage);
            }
        });
        editCategoryImageUrlInput?.addEventListener('change', () => {
            const imageUrl = editCategoryImageUrlInput.value.trim();

            if (imageUrl) {
                editCategoryImageInput.value = '';
                editCategoryRemoveImageInput.checked = false;
                showCategoryImagePreview(editCategoryImagePreview, editCategoryImagePreviewImage, imageUrl);
            } else if (!previewCategoryImageFile(editCategoryImageInput, editCategoryImagePreview, editCategoryImagePreviewImage)) {
                const originalSource = editCategoryImagePreviewImage.dataset.originalSource;

                if (originalSource) {
                    showCategoryImagePreview(editCategoryImagePreview, editCategoryImagePreviewImage, originalSource);
                } else {
                    hideCategoryImagePreview(editCategoryImagePreview, editCategoryImagePreviewImage);
                }
            }
        });
        editCategoryRemoveImageInput?.addEventListener('change', () => {
            if (editCategoryRemoveImageInput.checked) {
                editCategoryImageInput.value = '';
                editCategoryImageUrlInput.value = '';
                hideCategoryImagePreview(editCategoryImagePreview, editCategoryImagePreviewImage);
            } else if (editCategoryImagePreviewImage.dataset.originalSource) {
                showCategoryImagePreview(editCategoryImagePreview, editCategoryImagePreviewImage, editCategoryImagePreviewImage.dataset.originalSource);
            }
        });

        function openAddCategoryModal() {
            document.getElementById('addCategoryModal').classList.remove('hidden');
        }
        function closeAddCategoryModal() {
            document.getElementById('addCategoryModal').classList.add('hidden');
        }
        function openEditCategoryModal(button) {
            const modal = document.getElementById('editCategoryModal');
            const form = document.getElementById('editCategoryForm');
            const preview = document.getElementById('editCategoryImagePreview');
            const previewImage = document.getElementById('editCategoryImagePreviewImage');

            if (!modal || !form || !button) {
                return;
            }

            form.action = button.dataset.categoryEditUrl;
            document.getElementById('edit_category_id').value = button.dataset.categoryId || '';
            document.getElementById('edit_category_name').value = button.dataset.categoryName || '';
            document.getElementById('edit_category_short_name').value = button.dataset.categoryShortName || '';
            document.getElementById('edit_category_description').value = button.dataset.categoryDescription || '';
            editCategoryImageInput.value = '';
            editCategoryImageUrlInput.value = '';
            editCategoryRemoveImageInput.checked = false;
            previewImage.dataset.originalSource = button.dataset.categoryImageUrl || '';

            if (button.dataset.categoryImageUrl) {
                showCategoryImagePreview(preview, previewImage, button.dataset.categoryImageUrl);
            } else {
                hideCategoryImagePreview(preview, previewImage);
            }

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
        function closeEditCategoryModal() {
            const modal = document.getElementById('editCategoryModal');

            if (!modal) {
                return;
            }

            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
        function openCategoryDeleteModal(button) {
            const modal = document.getElementById('categoryDeleteModal');
            const form = document.getElementById('categoryDeleteForm');

            if (!modal || !form || !button) {
                return;
            }

            const courseCount = Number(button.dataset.categoryCourseCount || 0);
            const folderCount = Number(button.dataset.categoryFolderCount || 0);

            form.action = button.dataset.categoryDeleteUrl;
            document.getElementById('categoryDeleteName').textContent = button.dataset.categoryDeleteName;
            document.getElementById('categoryDeleteCourseCount').textContent = `${courseCount} program`;
            document.getElementById('categoryDeleteFolderCount').textContent = `${folderCount} folder`;
            document.getElementById('categoryDeleteWarning').textContent = courseCount > 0 || folderCount > 0
                ? `${courseCount > 0 ? `${courseCount} program akan ikut terhapus. ` : ''}${folderCount > 0 ? `${folderCount} folder galeri akan tetap ada tanpa bidang. ` : ''}Pastikan penghapusan ini sudah sesuai.`
                : 'Bidang ini belum memiliki program atau folder galeri.';
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
        function closeCategoryDeleteModal() {
            const modal = document.getElementById('categoryDeleteModal');

            if (!modal) {
                return;
            }

            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
        function openGalleryFolderDeleteModal(button) {
            const modal = document.getElementById('galleryFolderDeleteModal');
            const form = document.getElementById('galleryFolderDeleteForm');
            const photoCount = Number(button.dataset.photoCount || 0);

            if (!modal || !form) {
                return;
            }

            form.action = button.dataset.deleteUrl;
            document.getElementById('galleryFolderDeleteName').textContent = button.dataset.folderName;
            document.getElementById('galleryFolderDeleteCategory').textContent = button.dataset.folderCategory;
            document.getElementById('galleryFolderDeletePhotoCount').textContent = `${photoCount} foto`;
            document.getElementById('galleryFolderDeleteWarning').textContent = photoCount > 0
                ? `Folder ini berisi ${photoCount} foto. Semua foto di dalam folder ini juga akan dihapus.`
                : 'Folder ini belum memiliki foto.';
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
        function closeGalleryFolderDeleteModal() {
            const modal = document.getElementById('galleryFolderDeleteModal');
            if (!modal) return;

            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function openGalleryFolderEditorDeleteModal() {
            const modal = document.getElementById('galleryFolderEditorDeleteModal');
            if (!modal) return;

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeGalleryFolderEditorDeleteModal() {
            const modal = document.getElementById('galleryFolderEditorDeleteModal');
            if (!modal) return;

            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        let pendingGalleryPhotoDeleteForm = null;

        function openGalleryPhotoDeleteModal(form, photoCount = 1) {
            const modal = document.getElementById('galleryPhotoDeleteModal');
            const message = document.getElementById('galleryPhotoDeleteMessage');
            if (!modal || !form) return;

            pendingGalleryPhotoDeleteForm = form;
            message.textContent = photoCount > 1
                ? `Apakah ${photoCount} foto yang dipilih benar-benar ingin dihapus?`
                : 'Apakah foto ini benar-benar ingin dihapus?';
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeGalleryPhotoDeleteModal() {
            const modal = document.getElementById('galleryPhotoDeleteModal');
            if (!modal) return;

            pendingGalleryPhotoDeleteForm = null;
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function confirmGalleryPhotoDelete() {
            if (!pendingGalleryPhotoDeleteForm) return;

            const folderId = pendingGalleryPhotoDeleteForm.querySelector('input[name="folder_id"]')?.value;
            if (folderId) {
                sessionStorage.setItem('gallery-folder-photo-change', String(folderId));
            }

            const form = pendingGalleryPhotoDeleteForm;
            closeGalleryPhotoDeleteModal();
            form.submit();
        }

        function resetAddGalleryPhotosForm() {
            document.getElementById('galleryPhotosForm').reset();
            document.getElementById('gallery_folder_id').value = '';

            const uploadError = document.getElementById('galleryUploadError');
            uploadError.textContent = '';
            uploadError.classList.add('hidden');
        }
        function openAddGalleryPhotosModal(folderId = null) {
            resetAddGalleryPhotosForm();
            const folderSelect = document.getElementById('gallery_folder_id');
            if (folderId !== null) {
                folderSelect.value = folderId;
            }
            document.getElementById('addGalleryPhotosModal').classList.remove('hidden');
        }
        function closeAddGalleryPhotosModal() {
            resetAddGalleryPhotosForm();
            document.getElementById('addGalleryPhotosModal').classList.add('hidden');
        }

        let galleryFolderHasUnsavedChanges = false;
        let galleryFolderPhotoHasUnsavedChanges = false;
        let galleryFolderInitialSettings = {};

        function closeGalleryFolderExitModal() {
            const modal = document.getElementById('galleryFolderExitModal');
            if (!modal) return;

            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function handleGalleryFolderExit(event) {
            if (!galleryFolderHasUnsavedChanges) return;

            event.preventDefault();

            const modal = document.getElementById('galleryFolderExitModal');
            if (!modal) return;

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function saveGalleryFolderAsDraft() {
            const form = document.getElementById('galleryFolderSettingsForm');
            const status = document.getElementById('gallery_folder_status');
            if (!form || !status) return;

            status.value = 'Draft';
            galleryFolderPhotoHasUnsavedChanges = false;
            galleryFolderHasUnsavedChanges = false;
            form.requestSubmit();
        }

        function saveGalleryFolderAndUpload() {
            const form = document.getElementById('galleryFolderSettingsForm');
            const status = document.getElementById('gallery_folder_status');
            if (!form || !status) return;

            status.value = 'Publish';
            galleryFolderPhotoHasUnsavedChanges = false;
            galleryFolderHasUnsavedChanges = false;
            form.requestSubmit();
        }

        function initializeGalleryFolderExitGuard() {
            const form = document.getElementById('galleryFolderSettingsForm');
            const name = document.getElementById('selected_gallery_folder_name');
            const category = document.getElementById('selected_gallery_folder_category');
            const description = document.getElementById('selected_gallery_folder_description');
            if (!form || !name || !category || !description) return;

            const currentFolderId = form.dataset.folderId;
            const photoChangeMarker = sessionStorage.getItem('gallery-folder-photo-change');
            if (currentFolderId && photoChangeMarker === currentFolderId) {
                galleryFolderPhotoHasUnsavedChanges = true;
                galleryFolderHasUnsavedChanges = true;
                sessionStorage.removeItem('gallery-folder-photo-change');
            }

            galleryFolderInitialSettings = {
                name: name.value,
                category: category.value,
                description: description.value,
            };
            const updateGalleryFolderDirtyState = () => {
                galleryFolderHasUnsavedChanges = name.value !== galleryFolderInitialSettings.name
                    || category.value !== galleryFolderInitialSettings.category
                    || description.value !== galleryFolderInitialSettings.description
                    || galleryFolderPhotoHasUnsavedChanges;
            };
            name.addEventListener('input', updateGalleryFolderDirtyState);
            category.addEventListener('change', updateGalleryFolderDirtyState);
            description.addEventListener('input', updateGalleryFolderDirtyState);
            form.addEventListener('submit', () => {
                galleryFolderHasUnsavedChanges = false;
            });

            document.querySelectorAll('[data-gallery-photo-edit]').forEach((photoForm) => {
                photoForm.addEventListener('submit', () => {
                    sessionStorage.setItem('gallery-folder-photo-change', String(photoForm.dataset.folderId));
                });
            });

            document.querySelectorAll('a[href]').forEach((link) => {
                link.addEventListener('click', (event) => {
                    if (!galleryFolderHasUnsavedChanges || link.closest('#galleryFolderExitModal')) return;
                    handleGalleryFolderExit(event);
                });
            });

        }

        function submitGalleryBulkAction(event, action) {
            event.preventDefault();

            const selectedPhotoIds = Array.from(document.querySelectorAll('.gallery-photo-select:checked'))
                .map((checkbox) => checkbox.value);

            if (selectedPhotoIds.length === 0) {
                window.alert('Pilih setidaknya satu foto terlebih dahulu.');
                return false;
            }

            const form = event.currentTarget;
            form.querySelectorAll('input[name="photo_ids[]"]').forEach((input) => input.remove());
            selectedPhotoIds.forEach((photoId) => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'photo_ids[]';
                input.value = photoId;
                form.append(input);
            });

            if (action === 'delete') {
                openGalleryPhotoDeleteModal(form, selectedPhotoIds.length);

                return false;
            }

            form.submit();

            return false;
        }

        function toggleGalleryPhotoSelection(isSelected) {
            document.querySelectorAll('.gallery-photo-select').forEach((checkbox) => {
                checkbox.checked = isSelected;
            });
        }

        function setGalleryUploadProgress(percent) {
            const progress = Math.max(0, Math.min(100, percent));
            document.getElementById('galleryUploadProgressBar').style.width = `${progress}%`;
            document.getElementById('galleryUploadProgressPercent').textContent = `${progress}%`;
            document.getElementById('galleryUploadProgressTrack').setAttribute('aria-valuenow', String(progress));
        }

        function showGalleryUploadError(message) {
            document.getElementById('galleryUploadProgress').classList.add('hidden');
            document.getElementById('addGalleryPhotosModal').classList.remove('hidden');

            const error = document.getElementById('galleryUploadError');
            error.textContent = message;
            error.classList.remove('hidden');
        }

        function keepGalleryPhotosForRetry(photoInputs, selectedPhotos, failedIndex) {
            const remainingPhotos = selectedPhotos.slice(failedIndex);

            photoInputs.forEach((input) => {
                const transfer = new DataTransfer();
                remainingPhotos
                    .filter((photo) => photo.input === input)
                    .forEach((photo) => transfer.items.add(photo.file));
                input.files = transfer.files;
            });
        }

        function uploadGalleryPhotos(event) {
            event.preventDefault();

            const form = event.currentTarget;
            const error = document.getElementById('galleryUploadError');
            const photoInputs = [
                document.getElementById('gallery_photos'),
                document.getElementById('gallery_photo_folder'),
            ];
            const selectedPhotos = photoInputs.flatMap((input) => Array.from(input.files).map((file) => ({ file, input })));
            const photoUrlsInput = document.getElementById('gallery_photo_urls');
            const photoUrls = (photoUrlsInput?.value || '').split(/\r?\n/).map((url) => url.trim()).filter(Boolean);
            const totalPhotoCount = selectedPhotos.length + photoUrls.length;

            error.classList.add('hidden');
            error.textContent = '';

            if (totalPhotoCount === 0) {
                showGalleryUploadError('Pilih foto, folder foto, atau masukkan minimal satu URL gambar.');
                return;
            }

            if (totalPhotoCount > 100) {
                showGalleryUploadError('Maksimal 100 gambar dapat dipilih dalam satu kali unggah.');
                return;
            }

            if (selectedPhotos.length === 0) {
                form.removeAttribute('onsubmit');
                form.submit();
                return;
            }

            const oversizedPhoto = selectedPhotos.find(({ file }) => file.size > 25 * 1024 * 1024);
            if (oversizedPhoto) {
                showGalleryUploadError(`Ukuran ${oversizedPhoto.file.name} melebihi batas 25 MB.`);
                return;
            }

            const progressModal = document.getElementById('galleryUploadProgress');
            const progressMessage = document.getElementById('galleryUploadProgressMessage');
            const totalBytes = selectedPhotos.reduce((total, { file }) => total + file.size, 0);
            setGalleryUploadProgress(0);
            progressMessage.textContent = `Mengunggah ${totalPhotoCount} gambar...`;
            document.getElementById('addGalleryPhotosModal').classList.add('hidden');
            progressModal.classList.remove('hidden');

            const folderId = document.getElementById('gallery_folder_id').value;
            const csrfToken = form.querySelector('input[name="_token"]').value;

            const uploadNextPhoto = (index, uploadedBytes) => {
                if (index >= selectedPhotos.length) {
                    setGalleryUploadProgress(100);
                    progressMessage.textContent = `${totalPhotoCount} gambar berhasil diunggah. Memuat galeri...`;
                    sessionStorage.setItem('gallery-folder-photo-change', String(folderId));
                    window.setTimeout(() => window.location.assign(`${@json(route('admin.dashboard', ['tab' => 'galeri']))}&folder_id=${encodeURIComponent(folderId)}`), 500);
                    return;
                }

                const { file } = selectedPhotos[index];
                progressMessage.textContent = `Mengunggah foto ${index + 1} dari ${selectedPhotos.length}: ${file.name}`;

                const request = new XMLHttpRequest();
                request.open('POST', form.action);
                request.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                request.setRequestHeader('Accept', 'application/json');

                request.upload.addEventListener('progress', (progressEvent) => {
                    if (!progressEvent.lengthComputable || totalBytes === 0) {
                        return;
                    }

                    const currentFileProgress = Math.min(file.size, (progressEvent.loaded / progressEvent.total) * file.size);
                    const percent = Math.round(((uploadedBytes + currentFileProgress) / totalBytes) * 100);
                    setGalleryUploadProgress(percent);
                });

                request.addEventListener('load', () => {
                    if (request.status >= 200 && request.status < 300) {
                        uploadNextPhoto(index + 1, uploadedBytes + file.size);
                        return;
                    }

                    let detail = `Server merespons HTTP ${request.status}.`;
                    if (request.status === 413) {
                        detail = 'Ukuran file ini melebihi batas upload server (HTTP 413).';
                    } else if (request.status === 419) {
                        detail = 'Sesi admin berakhir. Muat ulang halaman lalu masuk kembali (HTTP 419).';
                    } else {
                        try {
                            const response = JSON.parse(request.responseText);
                            const validationErrors = response.errors ? Object.values(response.errors).flat() : [];
                            detail = validationErrors[0] || response.message || detail;
                        } catch (error) {
                            // Gunakan status HTTP jika server tidak mengembalikan JSON.
                        }
                    }

                    keepGalleryPhotosForRetry(photoInputs, selectedPhotos, index);
                    showGalleryUploadError(`Gagal mengunggah ${file.name} (${index + 1}/${selectedPhotos.length}). ${index} foto sebelumnya berhasil. ${detail}`);
                });

                request.addEventListener('error', () => {
                    keepGalleryPhotosForRetry(photoInputs, selectedPhotos, index);
                    showGalleryUploadError(`Koneksi terputus saat mengunggah ${file.name} (${index + 1}/${selectedPhotos.length}). ${index} foto sebelumnya berhasil. Pilihan file yang belum terunggah dipertahankan.`);
                });

                request.addEventListener('abort', () => {
                    keepGalleryPhotosForRetry(photoInputs, selectedPhotos, index);
                    showGalleryUploadError(`Upload ${file.name} dibatalkan. ${index} foto sebelumnya berhasil; file yang tersisa dipertahankan.`);
                });

                const data = new FormData();
                data.append('_token', csrfToken);
                data.append('gallery_folder_id', folderId);
                if (index === 0 && photoUrls.length > 0) {
                    data.append('photo_urls', photoUrls.join('\n'));
                }
                data.append('photos[]', file, file.name);
                request.send(data);
            };

            uploadNextPhoto(0, 0);
        }

        // Modal Edit Session / Jadwal
        function openEditSessionModal(session) {
            const form = document.getElementById('editSessionForm');
            form.action = `/admin/sessions/${session.id}`;
            document.getElementById('edit_session_course_id').value = session.course_id;
            document.getElementById('edit_session_batch_name').value = session.batch_name;
            
            // Format YYYY-MM-DD
            document.getElementById('edit_session_start_date').value = String(session.start_date).slice(0, 10);
            document.getElementById('edit_session_end_date').value = String(session.end_date || session.start_date).slice(0, 10);
            updateSessionDuration('edit_session_start_date', 'edit_session_end_date', 'edit_session_calc_duration_preview');

            document.getElementById('edit_session_location').value = session.location || '';
            document.getElementById('edit_session_quota').value = session.quota;
            document.getElementById('edit_session_price').value = Math.round(session.price);
            document.getElementById('edit_session_status').value = session.status;
            document.getElementById('editSessionModal').classList.remove('hidden');
        }
        function closeEditSessionModal() {
            document.getElementById('editSessionModal').classList.add('hidden');
        }

        // Tutup modal dengan tombol Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                setAdminSidebarOpen(false);
                closeAddProgramModal();
                closeEditProgramModal();
                closeAddArticleModal();
                closeEditArticleModal();
                closeAddSessionModal();
                closeEditSessionModal();
                closeAddGalleryFolderModal();
                closeAddCategoryModal();
                closeEditCategoryModal();
                closeCategoryDeleteModal();
                closeGalleryFolderDeleteModal();
                closeGalleryFolderEditorDeleteModal();
                closeGalleryPhotoDeleteModal();
                closeAdminDeleteModal();
                closeAddGalleryPhotosModal();
                closeGalleryFolderExitModal();
            }
        });

        @if(old('_form_context') === 'gallery_folder')
            openAddGalleryFolderModal();
        @elseif(old('_form_context') === 'training_category')
            openAddCategoryModal();
        @elseif(old('_form_context') === 'training_category_edit' && old('category_id'))
            const categoryEditorButton = document.querySelector(`[data-category-id="{{ old('category_id') }}"]`);
            if (categoryEditorButton) {
                openEditCategoryModal(categoryEditorButton);
            }
        @elseif(old('_form_context') === 'gallery_upload')
            openAddGalleryPhotosModal(@json(old('gallery_folder_id')));
        @endif

        initializeGalleryFolderExitGuard();
        const adminMainContent = document.getElementById('admin-main-content');
        const adminScrollStorageKey = 'admin-dashboard-scroll-top';

        const saveAdminScrollPosition = () => {
            if (!adminMainContent) {
                return;
            }

            sessionStorage.setItem(adminScrollStorageKey, String(adminMainContent.scrollTop));
        };

        const restoreAdminScrollPosition = () => {
            if (!adminMainContent) {
                return;
            }

            const storedScrollTop = sessionStorage.getItem(adminScrollStorageKey);

            if (storedScrollTop === null) {
                return;
            }

            sessionStorage.removeItem(adminScrollStorageKey);
            requestAnimationFrame(() => {
                requestAnimationFrame(() => {
                    adminMainContent.scrollTop = Number(storedScrollTop);
                });
            });
        };

        restoreAdminScrollPosition();
        document.querySelectorAll('[data-registration-url]').forEach((registrationCard) => {
            const openRegistrationDetails = () => {
                saveAdminScrollPosition();
                window.location.assign(registrationCard.dataset.registrationUrl);
            };

            registrationCard.addEventListener('click', (event) => {
                if (event.target.closest('a, button, form, select, input, textarea, label')) {
                    return;
                }

                openRegistrationDetails();
            });

            registrationCard.addEventListener('keydown', (event) => {
                if (event.target !== registrationCard || !['Enter', ' '].includes(event.key)) {
                    return;
                }

                event.preventDefault();
                openRegistrationDetails();
            });
        });

        document.querySelectorAll('[data-close-registration]').forEach((closeButton) => {
            closeButton.addEventListener('click', (event) => {
                const detailPanel = closeButton.closest('[data-registration-detail]');

                if (!detailPanel) {
                    return;
                }

                event.preventDefault();
                saveAdminScrollPosition();
                detailPanel.classList.add('is-closing');

                window.setTimeout(() => {
                    window.location.assign(closeButton.href);
                }, 180);
            });
        });
        document.querySelectorAll('[data-editor-url]').forEach((editorCard) => {
            const openEditor = () => {
                saveAdminScrollPosition();
                window.location.assign(editorCard.dataset.editorUrl);
            };

            editorCard.addEventListener('click', (event) => {
                if (event.target.closest('a, button, form, select, input, textarea, label')) {
                    return;
                }

                openEditor();
            });

            editorCard.addEventListener('keydown', (event) => {
                if (event.target !== editorCard || !['Enter', ' '].includes(event.key)) {
                    return;
                }

                event.preventDefault();
                openEditor();
            });
        });

        document.querySelectorAll('[data-close-editor]').forEach((closeButton) => {
            closeButton.addEventListener('click', (event) => {
                const editorPanel = closeButton.closest('[data-editor-panel]');

                if (!editorPanel) {
                    return;
                }

                event.preventDefault();
                saveAdminScrollPosition();
                editorPanel.classList.add('is-closing');

                window.setTimeout(() => {
                    window.location.assign(closeButton.href);
                }, 180);
            });
        });

        document.querySelectorAll('[data-image-position-editor]').forEach((editor) => {
            const image = editor.querySelector('img');
            const positionInput = document.getElementById(editor.dataset.positionInput);

            if (!image || !positionInput) {
                return;
            }

            let dragState = null;

            const clampPercentage = (value) => Math.max(0, Math.min(100, Math.round(value)));

            const readPosition = () => {
                const values = positionInput.value.match(/(\d+(?:\.\d+)?)%\s+(\d+(?:\.\d+)?)%/);

                return values ? { x: Number(values[1]), y: Number(values[2]) } : { x: 50, y: 50 };
            };

            const updatePosition = (x, y) => {
                const position = `${clampPercentage(x)}% ${clampPercentage(y)}%`;
                positionInput.value = position;
                image.style.objectPosition = position;
            };

            editor.addEventListener('pointerdown', (event) => {
                if (event.button !== 0) {
                    return;
                }

                const currentPosition = readPosition();
                dragState = {
                    pointerId: event.pointerId,
                    startX: event.clientX,
                    startY: event.clientY,
                    position: currentPosition,
                    width: editor.clientWidth,
                    height: editor.clientHeight,
                };
                editor.setPointerCapture(event.pointerId);
                editor.classList.add('cursor-grabbing');
            });

            editor.addEventListener('pointermove', (event) => {
                if (!dragState || event.pointerId !== dragState.pointerId) {
                    return;
                }

                const deltaX = ((event.clientX - dragState.startX) / dragState.width) * 100;
                const deltaY = ((event.clientY - dragState.startY) / dragState.height) * 100;
                updatePosition(dragState.position.x - deltaX, dragState.position.y - deltaY);
            });

            const stopDragging = (event) => {
                if (!dragState || event.pointerId !== dragState.pointerId) {
                    return;
                }

                dragState = null;
                editor.classList.remove('cursor-grabbing');
            };

            editor.addEventListener('pointerup', stopDragging);
            editor.addEventListener('pointercancel', stopDragging);
            editor.addEventListener('dragstart', (event) => event.preventDefault());
        });

        document.querySelectorAll('[data-remove-course-image]').forEach((removeButton) => {
            removeButton.addEventListener('click', async () => {
                if (!window.confirm('Hapus gambar program ini sekarang?')) {
                    return;
                }

                removeButton.disabled = true;
                removeButton.textContent = 'Menghapus...';

                try {
                    const response = await fetch(removeButton.dataset.removeImageUrl, {
                        method: 'DELETE',
                        credentials: 'same-origin',
                        headers: {
                            Accept: 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        },
                    });

                    if (!response.ok) {
                        throw new Error('Gambar gagal dihapus.');
                    }

                    removeButton.closest('[data-course-image-section]')?.remove();
                } catch (error) {
                    removeButton.disabled = false;
                    removeButton.textContent = 'Hapus gambar';
                    window.alert(error.message || 'Gambar gagal dihapus. Silakan coba lagi.');
                }
            });
        });

        const articleManager = document.querySelector('[data-article-manager]');
        if (articleManager) {
            const articleCards = [...articleManager.querySelectorAll('[data-article-card]')];
            const filterButtons = [...articleManager.querySelectorAll('[data-article-filter]')];
            const searchInput = articleManager.querySelector('#article_search');
            const emptyState = articleManager.querySelector('[data-article-filter-empty]');
            let activeFilter = articleManager.dataset.articleStatusFilter || 'all';

            const applyArticleFilter = () => {
                const searchTerm = searchInput?.value.trim().toLocaleLowerCase() || '';
                let visibleCount = 0;

                articleCards.forEach((card) => {
                    const matchesStatus = activeFilter === 'all' || card.dataset.articleFilterStatus === activeFilter;
                    const matchesSearch = !searchTerm || (card.dataset.articleTitle || '').toLocaleLowerCase().includes(searchTerm);
                    const isVisible = matchesStatus && matchesSearch;

                    card.hidden = !isVisible;
                    visibleCount += isVisible ? 1 : 0;
                });

                filterButtons.forEach((button) => {
                    const isActive = button.dataset.articleFilter === activeFilter;

                    button.classList.toggle('bg-[#10243A]', isActive);
                    button.classList.toggle('text-white', isActive);
                    button.classList.toggle('shadow-sm', isActive);
                    button.classList.toggle('hover:bg-white', !isActive);
                    button.classList.toggle('hover:text-[#10243A]', !isActive);
                });

                emptyState?.classList.toggle('hidden', visibleCount > 0 || articleCards.length === 0);
            };

            window.filterArticleList = (requestedFilter = null) => {
                if (requestedFilter !== null) {
                    activeFilter = requestedFilter;
                }

                applyArticleFilter();
            };

            window.filterArticleList();
        }
        filterRegistrationBatches(document.getElementById('registration_course_filter')?.value || '');
    </script>

</body>
</html>
