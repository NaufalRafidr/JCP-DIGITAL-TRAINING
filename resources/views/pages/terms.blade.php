@extends('layouts.app')

@section('title', 'Syarat & Ketentuan • JCP Training')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-8">
    <div>
        <h1 class="text-3xl font-extrabold text-[#10243A]">Syarat & Ketentuan</h1>
        <p class="text-sm text-slate-500 mt-1">Ketentuan operasional dan pendaftaran pelatihan di JCP Training</p>
    </div>

    <div class="bg-white rounded-3xl p-8 border border-slate-200 text-sm text-slate-700 space-y-6 leading-relaxed">
        <section class="space-y-2">
            <h2 class="text-lg font-bold text-[#10243A]">1. Kepastian Kuota & Pendaftaran</h2>
            <p>Pendaftaran dianggap terkonfirmasi penuh setelah peserta menerima verifikasi dari admin dan menyelesaikan administrasi pembayaran sebelum batas waktu yang ditentukan.</p>
        </section>

        <section class="space-y-2">
            <h2 class="text-lg font-bold text-[#10243A]">2. Kehadiran & Sertifikasi</h2>
            <p>Sertifikat resmi kelulusan diterbitkan bagi peserta yang memenuhi batas minimum kehadiran 80% dan mengumpulkan tugas evaluasi akhir/studi kasus yang ditentukan mentor.</p>
        </section>

        <section class="space-y-2">
            <h2 class="text-lg font-bold text-[#10243A]">3. Penjadwalan Ulang (Reschedule)</h2>
            <p>Peserta berhak mengajukan permohonan reschedule ke batch berikutnya maksimal 3 hari kerja sebelum tanggal pelaksanaan dimulai dengan konfirmasi tertulis kepada admin.</p>
        </section>
    </div>
</div>
@endsection

