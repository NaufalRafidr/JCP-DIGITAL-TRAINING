<!DOCTYPE html>
<html lang="id" class="h-full scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo-jcp.png') }}">
    <meta name="description" content="JCP Training - Platform Pelatihan Profesional dari PT Jogja Creative Production. Upgrade skill, buka peluang karir & bisnis masa depan.">
    <title>@yield('title', 'JCP TRAINING') - PT Jogja Creative Production</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Styles & Scripts via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background-color: #FAF8F1;
            color: #10243A;
        }
    </style>
</head>
<body class="flex flex-col min-h-screen bg-brand-ivory text-[#10243A] antialiased">

    <!-- ========================================================
         NAVBAR: Persis sesuai gambar referensi (Slide 4 Desktop 1440px)
         ======================================================== -->
    <header class="sticky top-0 z-50 bg-brand-ivory/95 backdrop-blur-md pt-4 sm:pt-6 pb-2 transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Navbar Card Container (Rounded Card dengan border halus) -->
            <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200/90 shadow-sm px-6 sm:px-8 py-3.5 sm:py-4 flex items-center justify-between relative">
                
                <!-- Logo: JCP TRAINING -->
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-2.5 font-extrabold text-xl tracking-tight text-[#10243A] transition-opacity hover:opacity-90 sm:gap-3 sm:text-2xl">
                        <img src="{{ asset('images/logo-jcp.png') }}" alt="" aria-hidden="true" class="h-8 w-8 shrink-0 object-contain sm:h-10 sm:w-10">
                        <span>JCP <span class="tracking-normal">DIGITAL <span class="tracking-normal">TRAINING</span></span></span>
                    </a>
                </div>

                <!-- Desktop Nav Items: Beranda, Pelatihan⌄, Jadwal, Corporate, Artikel, Tentang -->
                <nav class="hidden lg:flex items-center gap-7 lg:gap-9 text-[15px] font-semibold text-slate-700">
                    <a href="{{ route('home') }}" class="hover:text-[#087F78] transition-colors {{ request()->routeIs('home') ? 'text-slate-800' : 'text-slate-600' }}">
                        Beranda
                    </a>

                    <!-- Dropdown Pelatihan (Tepat sesuai Slide 4) -->
                    <div class="relative group" id="pelatihan-menu-container">
                        <button 
                            type="button" 
                            id="pelatihan-btn"
                            class="flex items-center gap-1.5 text-slate-600 hover:text-[#087F78] transition-colors py-2 focus:outline-none">
                            <span>Pelatihan</span>
                            <svg class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <!-- Box Dropdown Pelatihan (2 Kolom sesuai gambar) -->
                        <div id="pelatihan-dropdown" class="absolute left-1/2 -translate-x-1/3 top-full mt-3 w-[540px] bg-white rounded-2xl sm:rounded-3xl border border-slate-200/90 shadow-2xl p-6 sm:p-7 hidden transition-all duration-150 z-50">
                            <!-- Judul Dropdown -->
                            <div class="text-xs font-extrabold uppercase tracking-wider text-[#087F78] mb-4">
                                DROPDOWN PELATIHAN
                            </div>

                            <!-- 2 Kolom Pelatihan -->
                            <div class="grid grid-cols-2 gap-x-6 gap-y-3.5 text-sm text-slate-700">
                                <!-- Kolom Kiri -->
                                <div class="space-y-3.5">
                                    <a href="{{ route('courses.index', ['kategori' => 'photo-video']) }}" class="flex items-center gap-2.5 hover:text-[#087F78] font-medium transition-colors group/item">
                                        <span class="w-2 h-2 rounded-full bg-[#087F78] shrink-0"></span>
                                        <span class="group-hover/item:translate-x-0.5 transition-transform">Photography & Videography</span>
                                    </a>
                                    <a href="{{ route('courses.index', ['kategori' => 'artificial-intelligence']) }}" class="flex items-center gap-2.5 hover:text-[#087F78] font-medium transition-colors group/item">
                                        <span class="w-2 h-2 rounded-full bg-[#087F78] shrink-0"></span>
                                        <span class="group-hover/item:translate-x-0.5 transition-transform">Artificial Intelligence</span>
                                    </a>
                                    <a href="{{ route('courses.index', ['kategori' => 'information-technology']) }}" class="flex items-center gap-2.5 hover:text-[#087F78] font-medium transition-colors group/item">
                                        <span class="w-2 h-2 rounded-full bg-[#087F78] shrink-0"></span>
                                        <span class="group-hover/item:translate-x-0.5 transition-transform">Information Technology</span>
                                    </a>
                                </div>

                                <!-- Kolom Kanan -->
                                <div class="space-y-3.5">
                                    <a href="{{ route('courses.index', ['kategori' => 'digital-marketing']) }}" class="flex items-center gap-2.5 hover:text-[#087F78] font-medium transition-colors group/item">
                                        <span class="w-2 h-2 rounded-full bg-[#087F78] shrink-0"></span>
                                        <span class="group-hover/item:translate-x-0.5 transition-transform">Digital Marketing</span>
                                    </a>
                                    <a href="{{ route('courses.index', ['kategori' => 'drone-technology']) }}" class="flex items-center gap-2.5 hover:text-[#087F78] font-medium transition-colors group/item">
                                        <span class="w-2 h-2 rounded-full bg-[#087F78] shrink-0"></span>
                                        <span class="group-hover/item:translate-x-0.5 transition-transform">Drone Technology</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('courses.schedule') }}" class="hover:text-[#087F78] transition-colors {{ request()->routeIs('courses.schedule') ? 'text-[#087F78]' : 'text-slate-600' }}">
                        Jadwal
                    </a>
                    <a href="{{ route('corporate') }}" class="hover:text-[#087F78] transition-colors {{ request()->routeIs('corporate') ? 'text-[#087F78]' : 'text-slate-600' }}">
                        Corporate
                    </a>
                    <a href="{{ route('gallery.index') }}" class="hover:text-[#087F78] transition-colors {{ request()->routeIs('gallery.*') ? 'text-[#087F78]' : 'text-slate-600' }}">
                        Galeri
                    </a>
                    <a href="{{ route('artikel') }}" class="hover:text-[#087F78] transition-colors {{ request()->routeIs('artikel') ? 'text-[#087F78]' : 'text-slate-600' }}">
                        Artikel
                    </a>
                    <a href="{{ route('tentang') }}" class="hover:text-[#087F78] transition-colors {{ request()->routeIs('tentang') ? 'text-[#087F78]' : 'text-slate-600' }}">
                        Tentang
                    </a>
                </nav>

                <!-- Tombol CTA: Daftar sekarang -->
                <div class="flex items-center gap-2 sm:gap-3">
                    <a href="{{ route('register') }}" class="inline-flex h-12 shrink-0 items-center justify-center whitespace-nowrap rounded-2xl bg-[#087F78] px-5 text-sm font-bold text-white shadow-sm transition-all hover:bg-[#066761] sm:px-6">
                        Daftar sekarang
                    </a>
                    
                    <!-- Mobile Hamburger (Slide 4 Mobile / 390px) -->
                    <button type="button" id="mobile-menu-btn" class="lg:hidden p-2 text-slate-700 hover:text-[#10243A] rounded-lg min-h-[44px] min-w-[44px] flex items-center justify-center" aria-label="Buka Menu">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>

            </div>

            <!-- Mobile Drawer Menu (Slide 4 Mobile) -->
            <div id="mobile-menu" class="hidden lg:hidden mt-2 bg-white rounded-2xl border border-slate-200 shadow-xl p-5 space-y-3">
                <a href="{{ route('home') }}" class="block text-sm font-semibold text-slate-800 py-1">Beranda</a>
                <div class="pt-2 border-t border-slate-100">
                    <span class="block text-xs font-bold text-[#087F78] uppercase mb-2">Pelatihan:</span>
                    <div class="space-y-2 pl-2 text-xs text-slate-600">
                        <a href="{{ route('courses.index', ['kategori' => 'photo-video']) }}" class="block">• Photography & Videography</a>
                        <a href="{{ route('courses.index', ['kategori' => 'digital-marketing']) }}" class="block">• Digital Marketing</a>
                        <a href="{{ route('courses.index', ['kategori' => 'artificial-intelligence']) }}" class="block">• Artificial Intelligence</a>
                        <a href="{{ route('courses.index', ['kategori' => 'drone-technology']) }}" class="block">• Drone Technology</a>
                        <a href="{{ route('courses.index', ['kategori' => 'information-technology']) }}" class="block">• Information Technology</a>
                    </div>
                </div>
                <div class="pt-2 border-t border-slate-100 space-y-2">
                    <a href="{{ route('courses.schedule') }}" class="block text-sm font-semibold text-slate-800">Jadwal</a>
                    <a href="{{ route('corporate') }}" class="block text-sm font-semibold text-slate-800">Corporate</a>
                    <a href="{{ route('gallery.index') }}" class="block text-sm font-semibold text-slate-800">Galeri</a>
                    <a href="{{ route('artikel') }}" class="block text-sm font-semibold text-slate-800">Artikel</a>
                    <a href="{{ route('tentang') }}" class="block text-sm font-semibold text-slate-800">Tentang</a>
                </div>
            </div>

        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-grow">
        @if(session('success'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl flex items-center justify-between">
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    @if(config('services.whatsapp.admin_number'))
        <a
            href="https://wa.me/{{ preg_replace('/\D+/', '', config('services.whatsapp.admin_number')) }}?text={{ rawurlencode('Halo Admin JCP Training, saya ingin bertanya tentang layanan yang tersedia.') }}"
            target="_blank"
            rel="noopener noreferrer"
            aria-label="Hubungi Admin melalui WhatsApp"
            title="Chat Admin via WhatsApp"
            class="fixed bottom-7 right-7 z-40 inline-flex h-16 w-16 items-center justify-center rounded-full border border-[#7DE8A4] bg-[#25D366] text-white shadow-[0_10px_30px_rgba(16,36,58,0.25)] transition-transform duration-200 hover:scale-105 hover:bg-[#20BD5A] focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-[#25D366]/60 sm:bottom-8 sm:right-8 sm:h-[70px] sm:w-[70px]"
        >
            <svg class="h-8 w-8" viewBox="0 0 32 32" fill="none" aria-hidden="true">
                <path fill="currentColor" d="M16 3.5a12.5 12.5 0 0 0-10.8 18.8L3.5 28.5l6.4-1.7A12.5 12.5 0 1 0 16 3.5Zm0 22.7c-2 0-3.9-.6-5.5-1.7l-.4-.3-3.8 1 1-3.7-.3-.4A10.2 10.2 0 1 1 16 26.2Zm5.6-7.7c-.3-.2-1.8-.9-2.1-1-.3-.1-.5-.2-.7.2-.2.3-.8 1-1 1.2-.2.2-.4.2-.7.1-.3-.2-1.3-.5-2.5-1.6-.9-.8-1.6-1.8-1.8-2.1-.2-.3 0-.5.1-.7.1-.1.3-.4.5-.6.2-.2.2-.3.3-.5.1-.2 0-.4 0-.6 0-.2-.7-1.7-1-2.3-.3-.6-.5-.5-.7-.5h-.6c-.2 0-.6.1-.9.4-.3.3-1.1 1.1-1.1 2.6s1.1 3 1.2 3.2c.2.2 2.1 3.2 5.1 4.5.7.3 1.3.5 1.7.6.7.2 1.3.2 1.8.1.5-.1 1.8-.7 2.1-1.4.3-.7.3-1.3.2-1.4-.1-.1-.3-.2-.6-.3Z"/>
            </svg>
        </a>
    @endif

    <!-- Footer: PT Jogja Creative Production -->
    <footer class="bg-[#10243A] text-white border-t border-slate-800 pt-16 pb-12 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 pb-12 border-b border-slate-800 text-sm">
                <div class="space-y-3 md:col-span-1">
                    <span class="font-extrabold text-xl text-white">JCP TRAINING</span>
                    <p class="text-slate-400 text-xs leading-relaxed">
                        PT Jogja Creative Production • Platform Pelatihan Praktis Terstandar Industri.
                    </p>
                </div>
                <div>
                    <h4 class="font-bold text-white mb-3 text-xs uppercase tracking-wider">Pelatihan</h4>
                    <ul class="space-y-2 text-xs text-slate-400">
                        <li><a href="{{ route('courses.index', ['kategori' => 'photo-video']) }}" class="hover:text-white">Foto & Video</a></li>
                        <li><a href="{{ route('courses.index', ['kategori' => 'digital-marketing']) }}" class="hover:text-white">Digital Marketing</a></li>
                        <li><a href="{{ route('courses.index', ['kategori' => 'artificial-intelligence']) }}" class="hover:text-white">Artificial Intelligence</a></li>
                        <li><a href="{{ route('courses.index', ['kategori' => 'drone-technology']) }}" class="hover:text-white">Drone Technology</a></li>
                        <li><a href="{{ route('courses.index', ['kategori' => 'information-technology']) }}" class="hover:text-white">Information Technology</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-white mb-3 text-xs uppercase tracking-wider">Halaman</h4>
                    <ul class="space-y-2 text-xs text-slate-400">
                        <li><a href="{{ route('courses.schedule') }}" class="hover:text-white">Jadwal Cohort</a></li>
                        <li><a href="{{ route('corporate') }}" class="hover:text-white">Corporate Training</a></li>
                        <li><a href="{{ route('artikel') }}" class="hover:text-white">Artikel</a></li>
                        <li><a href="{{ route('tentang') }}" class="hover:text-white">Tentang Kami</a></li>
                        <li><a href="{{ route('kontak') }}" class="hover:text-white">Kontak</a></li>
                        <li><a href="{{ route('admin.dashboard') }}" class="text-[#087F78] hover:text-teal-300 font-semibold">Dashboard Admin</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-white mb-3 text-xs uppercase tracking-wider">Bantuan</h4>
                    <ul class="space-y-2 text-xs text-slate-400">
                        <li><a href="{{ route('privacy') }}" class="hover:text-white">Kebijakan Privasi</a></li>
                        <li><a href="{{ route('terms') }}" class="hover:text-white">Syarat & Ketentuan</a></li>
                        @if(config('services.whatsapp.admin_number'))
                            <li><a href="https://wa.me/{{ preg_replace('/\D+/', '', config('services.whatsapp.admin_number')) }}" target="_blank" rel="noopener" class="text-emerald-400 hover:text-emerald-300">WhatsApp Admin</a></li>
                        @endif
                    </ul>
                </div>
            </div>
            <div class="pt-8 text-xs text-slate-500 text-center">
                &copy; 2026 PT Jogja Creative Production • JCP Training Website Blueprint. All rights reserved.
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const mobileBtn = document.getElementById('mobile-menu-btn');
            const mobileMenu = document.getElementById('mobile-menu');
            if (mobileBtn && mobileMenu) {
                mobileBtn.addEventListener('click', () => {
                    mobileMenu.classList.toggle('hidden');
                });
            }

            // Pelatihan dropdown toggle
            const pelatihanBtn = document.getElementById('pelatihan-btn');
            const pelatihanDropdown = document.getElementById('pelatihan-dropdown');
            if (pelatihanBtn && pelatihanDropdown) {
                pelatihanBtn.addEventListener('click', (e) => {
                    pelatihanDropdown.classList.toggle('hidden');
                    const isOpen = !pelatihanDropdown.classList.contains('hidden');
                    // Toggle text color
                    pelatihanBtn.classList.toggle('text-[#087F78]', isOpen);
                    // Rotate arrow
                    const arrow = pelatihanBtn.querySelector('svg');
                    if (arrow) {
                        arrow.classList.toggle('rotate-180', isOpen);
                    }
                });
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
