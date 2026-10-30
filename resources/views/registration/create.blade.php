@extends('layouts.app')

@section('title', 'Form Pendaftaran Peserta • JCP Training')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-10">

    <!-- Stepper Indicator (Slide 11 / 10 CONVERSION FLOW) -->
    <div class="bg-[#F6F9FC] p-4 sm:p-6 rounded-2xl border border-slate-200">
        <div class="flex items-center justify-between max-w-4xl mx-auto overflow-x-auto gap-2 text-xs font-bold text-slate-400">
            <div class="flex items-center gap-1.5 text-[#087F78] shrink-0">
                <span class="w-6 h-6 rounded-full bg-[#087F78] text-white flex items-center justify-center text-[11px]">1</span>
                <span>Pilih kelas</span>
            </div>
            <span class="text-slate-300">&rarr;</span>
            <div class="flex items-center gap-1.5 text-[#087F78] shrink-0">
                <span class="w-6 h-6 rounded-full bg-[#087F78] text-white flex items-center justify-center text-[11px]">2</span>
                <span>Lihat jadwal</span>
            </div>
            <span class="text-slate-300">&rarr;</span>
            <div class="flex items-center gap-1.5 text-[#087F78] bg-[#E2F6F2] px-3 py-1.5 rounded-full shrink-0">
                <span class="w-6 h-6 rounded-full bg-[#087F78] text-white flex items-center justify-center text-[11px]">3</span>
                <span>Isi form</span>
            </div>
            <span class="text-slate-300">&rarr;</span>
            <div class="flex items-center gap-1.5 shrink-0">
                <span class="w-6 h-6 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center text-[11px]">4</span>
                <span>Konfirmasi</span>
            </div>
            <span class="text-slate-300">&rarr;</span>
            <div class="flex items-center gap-1.5 shrink-0">
                <span class="w-6 h-6 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center text-[11px]">5</span>
                <span>Follow-up admin</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Main Form (Slide 11: FORM PESERTA) -->
        <div class="lg:col-span-8 bg-white rounded-3xl p-6 sm:p-10 border border-slate-200 shadow-xs space-y-6">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-[#087F78] block mb-1">Form Peserta</span>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-[#10243A] tracking-tight">
                    Pendaftaran Pelatihan JCP
                </h1>
                <p class="text-sm text-slate-500 mt-1">
                    Silakan isi data diri Anda dengan benar. Tim admin akan memverifikasi ketersediaan kuota segera.
                </p>
            </div>

            @if($errors->any())
                <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-sm">
                    <div class="font-bold mb-1">Mohon perbaiki data berikut:</div>
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Nama Lengkap* -->
                    <div>
                        <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Nama Lengkap <span class="text-rose-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="name" 
                            id="name" 
                            value="{{ old('name') }}"
                            required 
                            placeholder="Contoh: Budi Santoso"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-[#087F78] focus:ring-1 focus:ring-[#087F78]"
                        >
                    </div>

                    <!-- Email* -->
                    <div>
                        <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Email Aktif <span class="text-rose-500">*</span>
                        </label>
                        <input 
                            type="email" 
                            name="email" 
                            id="email" 
                            value="{{ old('email') }}"
                            required 
                            placeholder="budi@email.com"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-[#087F78] focus:ring-1 focus:ring-[#087F78]"
                        >
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- No WhatsApp* -->
                    <div>
                        <label for="phone" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            No. WhatsApp Aktif <span class="text-rose-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="phone" 
                            id="phone" 
                            value="{{ old('phone') }}"
                            required 
                            placeholder="081234567890"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-[#087F78] focus:ring-1 focus:ring-[#087F78]"
                        >
                    </div>

                    <!-- Instansi / Perusahaan -->
                    <div>
                        <label for="organization" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Instansi / Perusahaan / UMKM
                        </label>
                        <input 
                            type="text" 
                            name="organization" 
                            id="organization" 
                            value="{{ old('organization') }}"
                            placeholder="Nama usaha / kantor (opsional)"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-[#087F78] focus:ring-1 focus:ring-[#087F78]"
                        >
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Pilih Program* -->
                    <div>
                        <label for="course_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Pilih Program Pelatihan <span class="text-rose-500">*</span>
                        </label>
                        <select 
                            name="course_id" 
                            id="course_id" 
                            required 
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-[#087F78] focus:ring-1 focus:ring-[#087F78]">
                            <option value="">-- Pilih Program --</option>
                            @foreach($courses as $c)
                                <option value="{{ $c->id }}" {{ (old('course_id', $selectedCourseId ?? '') == $c->id) ? 'selected' : '' }}>
                                    {{ $c->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Pilih Jadwal / Batch* -->
                    <div>
                        <label for="course_session_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Pilih Jadwal / Batch <span class="text-rose-500">*</span>
                        </label>
                        <select 
                            name="course_session_id" 
                            id="course_session_id" 
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-[#087F78] focus:ring-1 focus:ring-[#087F78]">
                            <option id="course_session_placeholder" value="">-- Pilih program terlebih dahulu --</option>
                            @foreach($sessions as $s)
                                <option value="{{ $s->id }}" data-course-id="{{ $s->course_id }}" {{ (old('course_session_id', $selectedSessionId ?? '') == $s->id) ? 'selected' : '' }}>
                                    {{ $s->course->title }} - {{ $s->batch_name }} ({{ $s->start_date->format('d M Y') }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Catatan Kebutuhan -->
                <div>
                    <label for="notes" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Catatan / Ekspektasi Khusus
                    </label>
                    <textarea 
                        name="notes" 
                        id="notes" 
                        rows="3" 
                        placeholder="Contoh: Saya ingin fokus pada pemetaan perkebunan, atau butuh penawaran resmi instansi."
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-[#087F78] focus:ring-1 focus:ring-[#087F78]">{{ old('notes') }}</textarea>
                </div>

                <!-- Checkbox Persetujuan (Slide 11 Data & Privasi: Checkbox persetujuan pemrosesan data) -->
                <div class="p-4 rounded-xl bg-[#F6F9FC] border border-slate-200 flex items-start gap-3">
                    <input 
                        type="checkbox" 
                        name="consent" 
                        id="consent" 
                        value="1" 
                        checked
                        required
                        class="mt-1 w-4 h-4 text-[#087F78] rounded border-slate-300 focus:ring-[#087F78]"
                    >
                    <label for="consent" class="text-xs text-slate-600 leading-relaxed cursor-pointer">
                        Saya menyetujui data formulir ini diproses oleh tim JCP Training untuk keperluan verifikasi pendaftaran, komunikasi jadwal, dan penawaran program resmi selaras dengan UU PDP No. 27/2022.
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="bg-[#087F78] hover:bg-[#066761] text-white w-full py-4 rounded-xl font-extrabold text-base tracking-wide shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2">
                    <span>Kirim Data Pendaftaran</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </button>
            </form>

        </div>

        <!-- Sidebar Info (Slide 11: DATA & PRIVASI) -->
        <div class="lg:col-span-4 bg-[#10243A] text-white rounded-3xl p-6 sm:p-8 space-y-6 shadow-xl">
            <div>
                <span class="text-xs uppercase tracking-wider font-bold text-teal-400">DATA & PRIVASI</span>
                <h3 class="text-xl font-bold text-white mt-1">Keamanan Data Terjamin</h3>
                <p class="text-xs text-slate-300 mt-2 leading-relaxed">
                    Checkbox persetujuan pemrosesan data. Validasi, anti-spam, pesan sukses, dan notifikasi admin otomatis.
                </p>
            </div>

            <div class="space-y-4 pt-4 border-t border-slate-800 text-xs text-slate-300">
                <div class="flex items-start gap-2.5">
                    <span class="text-teal-400 font-bold text-sm">✓</span>
                    <span><strong>Respons Cepat:</strong> Admin akan menghubungi WhatsApp Anda dalam maksimal 1x24 jam kerja.</span>
                </div>
                <div class="flex items-start gap-2.5">
                    <span class="text-teal-400 font-bold text-sm">✓</span>
                    <span><strong>Garansi Kuota:</strong> Data Anda mengunci prioritas antrean batch pelatihan pilihan.</span>
                </div>
                <div class="flex items-start gap-2.5">
                    <span class="text-teal-400 font-bold text-sm">✓</span>
                    <span><strong>Faktur & Penawaran:</strong> Kebutuhan invoice kantor dapat langsung diterbitkan resmi.</span>
                </div>
            </div>

            <!-- Follow-up Channel Banner (Slide 11: WhatsApp = kanal follow-up) -->
            <div class="p-4 rounded-2xl bg-[#087F78]/30 border border-[#087F78] text-center space-y-2">
                <div class="text-xs font-bold text-teal-300 uppercase tracking-wider">
                    WhatsApp = Kanal Follow-up
                </div>
                <p class="text-xs text-slate-200">
                    Pastikan nomor WhatsApp Anda aktif untuk menerima link konfirmasi dan panduan lokasi.
                </p>
            </div>
        </div>

    </div>

</div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const courseSelect = document.getElementById('course_id');
            const sessionSelect = document.getElementById('course_session_id');
            const sessionPlaceholder = document.getElementById('course_session_placeholder');

            if (!courseSelect || !sessionSelect || !sessionPlaceholder) {
                return;
            }

            const filterSessionsByCourse = () => {
                const selectedCourseId = courseSelect.value;
                let hasAvailableSession = false;
                let selectedSessionIsVisible = false;

                Array.from(sessionSelect.options).forEach((option) => {
                    if (!option.value) {
                        return;
                    }

                    const belongsToSelectedCourse = option.dataset.courseId === selectedCourseId;
                    option.hidden = !belongsToSelectedCourse;
                    option.disabled = !belongsToSelectedCourse;

                    if (belongsToSelectedCourse) {
                        hasAvailableSession = true;
                        selectedSessionIsVisible ||= option.selected;
                    }
                });

                if (!selectedSessionIsVisible) {
                    sessionSelect.value = '';
                }

                sessionSelect.disabled = !selectedCourseId || !hasAvailableSession;
                sessionPlaceholder.textContent = !selectedCourseId
                    ? '-- Pilih program terlebih dahulu --'
                    : hasAvailableSession
                        ? '-- Pilih Jadwal / Batch --'
                        : '-- Belum ada batch tersedia untuk program ini --';
            };

            courseSelect.addEventListener('change', filterSessionsByCourse);
            filterSessionsByCourse();
        });
    </script>
@endpush

