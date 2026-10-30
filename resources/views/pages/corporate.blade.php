@extends('layouts.app')

@section('title', 'Corporate & In-House Training • JCP Training')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-12">

    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-xs font-semibold text-slate-400">
        <a href="{{ route('home') }}" class="hover:text-[#087F78]">Beranda</a>
        <span>/</span>
        <span class="text-[#087F78]">Corporate & In-House Training</span>
    </div>

    <!-- Header (Slide 12: 11 / LAYANAN B2B) -->
    <div>
        <span class="text-xs font-bold uppercase tracking-wider text-[#087F78] block mb-1">11 / Layanan B2B</span>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-[#10243A] tracking-tight">
            Halaman Corporate & In-House Training
        </h1>
        <p class="text-slate-600 text-sm mt-1">
            Jalur khusus instansi pemerintah, BUMN, dan korporat swasta agar mendapatkan pelatihan yang disesuaikan dengan SOP internal.
        </p>
    </div>

    <!-- Main 2-Column Section (Slide 12 Layout) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Left Box (Slide 12: Navy Box) -->
        <div class="lg:col-span-6 bg-[#10243A] text-white rounded-3xl p-8 sm:p-12 space-y-8 shadow-xl relative overflow-hidden">
            <div class="absolute -right-16 -top-16 w-64 h-64 bg-[#087F78]/30 rounded-full blur-2xl"></div>

            <div class="relative z-10 space-y-6">
                <span class="inline-block px-3 py-1 rounded-md text-xs font-bold tracking-wider uppercase bg-[#087F78] text-teal-100">
                    CORPORATE TRAINING
                </span>

                <h2 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight leading-tight">
                    Pelatihan sesuai kebutuhan tim Anda.
                </h2>

                <p class="text-slate-300 text-sm sm:text-base leading-relaxed">
                    Kami mendesain silabus dan modul pelatihan yang terkalibrasi khusus sesuai alur kerja, tools, dan target bisnis organisasi Anda.
                </p>

                <!-- Value Bullets (Slide 12: Custom materi • onsite / hybrid • penjadwalan fleksibel • penawaran resmi) -->
                <div class="pt-2 space-y-3 text-sm text-slate-200">
                    <div class="flex items-center gap-2.5">
                        <span class="text-teal-400 font-bold">✓</span>
                        <span><strong>Custom materi:</strong> Kurikulum disesuaikan dengan studi kasus nyata tim</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <span class="text-teal-400 font-bold">✓</span>
                        <span><strong>Onsite / Hybrid:</strong> Pelatihan dapat dilaksanakan di kantor Anda atau studio JCP</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <span class="text-teal-400 font-bold">✓</span>
                        <span><strong>Penjadwalan fleksibel:</strong> Waktu pelaksanaan menyesuaikan agenda instansi</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <span class="text-teal-400 font-bold">✓</span>
                        <span><strong>Penawaran resmi:</strong> Dokumen proposal, TOR, invoice resmi, dan kelengkapan pajak</span>
                    </div>
                </div>

                <div class="pt-4">
                    @if(config('services.whatsapp.admin_number'))
                        <a href="https://wa.me/{{ preg_replace('/\D+/', '', config('services.whatsapp.admin_number')) }}?text={{ rawurlencode('Halo Admin JCP, kami ingin konsultasi Corporate Training') }}" target="_blank" rel="noopener" class="btn-teal px-7 py-3.5 rounded-xl font-bold text-sm inline-flex items-center gap-2 shadow-lg">
                            <span>Konsultasi Kebutuhan via WhatsApp</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Box (Slide 12: FORM PERMINTAAN) -->
        <div class="lg:col-span-6 bg-white rounded-3xl p-8 sm:p-10 border border-slate-200 shadow-xs space-y-6">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-[#087F78] block mb-1">Form Permintaan</span>
                <h3 class="text-2xl font-extrabold text-[#10243A] tracking-tight">
                    Minta Penawaran Resmi (Proposal / Quotation)
                </h3>
                <p class="text-xs text-slate-500 mt-1">
                    Isi form singkat di bawah, tim konsultan program JCP akan mengirimkan proposal penawaran maksimal 24 jam.
                </p>
            </div>

            <form action="{{ route('register.store') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="submission_type" value="corporate">
                <input type="hidden" name="consent" value="1">

                <!-- Nama PIC & Instansi (Slide 12: Nama PIC & instansi) -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Nama PIC
                    </label>
                    <input type="text" name="name" required placeholder="Contoh: Rina" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-[#087F78]">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Nama Instansi / Perusahaan
                    </label>
                    <input type="text" name="organization" required placeholder="Contoh: PT Sinar Abadi" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-[#087F78]">
                </div>

                <!-- Email & WhatsApp (Slide 12: Email & WhatsApp) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Email</label>
                        <input type="email" name="email" required placeholder="pic@perusahaan.com" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-[#087F78]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">WhatsApp</label>
                        <input type="text" name="phone" required placeholder="081234567890" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-[#087F78]">
                    </div>
                </div>

                <!-- Bidang pelatihan & Jumlah peserta (Slide 12: Bidang pelatihan • Jumlah peserta) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Bidang pelatihan</label>
                        <select name="training_area" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-[#087F78]">
                            <option value="Artificial Intelligence">Artificial Intelligence</option>
                            <option value="Drone Technology">Drone Technology</option>
                            <option value="Photo & Video Production">Photo & Video Production</option>
                            <option value="Digital Marketing">Digital Marketing</option>
                            <option value="Information Technology">Information Technology</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Jumlah peserta</label>
                        <input type="text" name="quota_est" placeholder="Contoh: 15 orang" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-[#087F78]">
                    </div>
                </div>

                <!-- Lokasi & waktu preferensi (Slide 12) -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Lokasi & waktu preferensi</label>
                    <input type="text" name="location_pref" placeholder="Contoh: Onsite kantor Jakarta, November 2026" class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-[#087F78]">
                </div>

                <!-- Catatan kebutuhan (Slide 12) -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Catatan kebutuhan</label>
                    <textarea name="notes" rows="3" placeholder="Sebutkan sasaran kompetensi atau studi kasus khusus yang ingin dipelajari tim Anda." class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-[#087F78]"></textarea>
                </div>

                <!-- Tombol Submit (Slide 12: Minta penawaran) -->
                <button type="submit" class="bg-[#087F78] hover:bg-[#066761] text-white w-full py-3.5 rounded-xl font-bold text-sm tracking-wide shadow-md transition-all">
                    Minta penawaran
                </button>
            </form>
        </div>

    </div>

</div>
@endsection

