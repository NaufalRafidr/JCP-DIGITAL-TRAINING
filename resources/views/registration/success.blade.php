@extends('layouts.guest')

@section('title', 'Pendaftaran Berhasil - JCP Training')

@section('content')
@php
    $isCorporate = $registration->isCorporate();
    $whatsappAdminNumber = config('services.whatsapp.admin_number');
    $courseTitle = $registration->course?->title ?? 'Pelatihan JCP';
    $batchName = $registration->courseSession?->batch_name ?? 'yang tersedia';
    $registrationCode = '#JCP-'.str_pad($registration->id, 4, '0', STR_PAD_LEFT);
    $whatsappMessage = $isCorporate
        ? "Halo Admin JCP,\nPerkenalkan, saya {$registration->name}.\nID registrasi saya {$registrationCode}.\nNama instansi {$registration->organization} sudah melakukan permintaan kegiatan corporate training.\nPIC: {$registration->name}. Mohon ditindaklanjuti. Terima kasih."
        : "Halo Admin JCP,\nPerkenalkan, saya {$registration->name}.\nID registrasi saya {$registrationCode}.\nSaya sudah mendaftar kelas {$courseTitle} pada batch {$batchName}. Mohon konfirmasi pendaftaran saya. Terima kasih.";
@endphp

<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center space-y-8">

    <div class="w-20 h-20 rounded-full bg-[#E2F6F2] text-3xl font-extrabold text-[#087F78] shadow-lg flex items-center justify-center mx-auto">
        &#10003;
    </div>

    <div class="space-y-3">
        <span class="rounded-full bg-emerald-100 px-3.5 py-1 text-xs font-bold uppercase tracking-wider text-emerald-800">
            {{ $isCorporate ? 'Permintaan Corporate Berhasil Diterima' : 'Konfirmasi Berhasil Diterima' }}
        </span>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-[#10243A] tracking-tight">
            {{ $isCorporate ? 'Permintaan Anda sudah tercatat!' : 'Terima kasih, '.$registration->name.'!' }}
        </h1>
        <p class="text-base text-slate-600 max-w-lg mx-auto leading-relaxed">
            @if($isCorporate)
                Permintaan kegiatan dari instansi <strong class="text-[#087F78]">{{ $registration->organization }}</strong> telah tercatat di sistem kami.
            @else
                Data pendaftaran Anda untuk program <strong class="text-[#087F78]">{{ $registration->course->title ?? 'Pelatihan JCP' }}</strong> telah tercatat di sistem kami.
            @endif
        </p>
    </div>

    <div class="bg-[#F6F9FC] rounded-2xl p-6 border border-slate-200 text-left space-y-3 text-sm max-w-md mx-auto">
        <div class="flex justify-between border-b border-slate-200 pb-2">
            <span class="text-slate-500">ID Registrasi:</span>
            <span class="font-bold text-[#10243A]">{{ $registrationCode }}</span>
        </div>
        @if($isCorporate)
            <div class="flex justify-between border-b border-slate-200 pb-2">
                <span class="text-slate-500">Instansi:</span>
                <span class="font-bold text-[#10243A]">{{ $registration->organization }}</span>
            </div>
            <div class="flex justify-between border-b border-slate-200 pb-2">
                <span class="text-slate-500">PIC:</span>
                <span class="font-bold text-[#10243A]">{{ $registration->name }}</span>
            </div>
        @else
            <div class="flex justify-between border-b border-slate-200 pb-2">
                <span class="text-slate-500">Program:</span>
                <span class="font-bold text-[#10243A]">{{ $registration->course->title ?? '-' }}</span>
            </div>
            @if($registration->courseSession)
                <div class="flex justify-between border-b border-slate-200 pb-2">
                    <span class="text-slate-500">Batch:</span>
                    <span class="font-bold text-[#10243A]">{{ $registration->courseSession->batch_name }}</span>
                </div>
                <div class="flex justify-between border-b border-slate-200 pb-2">
                    <span class="text-slate-500">Tanggal Mulai:</span>
                    <span class="font-bold text-[#087F78]">{{ $registration->courseSession->start_date->format('d M Y') }}</span>
                </div>
            @endif
        @endif
        <div class="flex justify-between">
            <span class="text-slate-500">WhatsApp Peserta:</span>
            <span class="font-bold text-[#10243A]">{{ $registration->phone }}</span>
        </div>
    </div>

    <div class="max-w-lg mx-auto space-y-3 rounded-2xl bg-[#10243A] p-6 text-white">
        <div class="text-xs font-bold uppercase tracking-wider text-teal-400">Langkah Berikutnya: Hubungkan Admin</div>
        <p class="text-xs text-slate-300 leading-relaxed">
            Tekan tombol di bawah untuk membuka WhatsApp admin dengan template pesan yang sudah terisi. Pesan belum terkirim sampai Anda menekan tombol Kirim di WhatsApp.
        </p>
        <div class="pt-2">
            @if($whatsappAdminNumber)
                <a href="https://wa.me/{{ preg_replace('/\D+/', '', $whatsappAdminNumber) }}?text={{ rawurlencode($whatsappMessage) }}" target="_blank" rel="noopener" class="btn-teal px-6 py-2.5 rounded-xl text-xs font-bold inline-flex items-center gap-2">
                    <span>Hubungkan Admin via WhatsApp</span>
                    <span>&rarr;</span>
                </a>
            @else
                <p class="rounded-xl bg-white/10 px-4 py-3 text-xs text-teal-100">Nomor WhatsApp admin belum dikonfigurasi.</p>
            @endif
        </div>
    </div>

    <div class="pt-4">
        <a href="{{ route('home') }}" class="text-xs font-bold text-slate-500 hover:text-[#087F78] underline">
            &larr; Kembali ke Beranda JCP Training
        </a>
    </div>

</div>
@endsection
