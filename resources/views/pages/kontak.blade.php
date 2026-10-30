@extends('layouts.app')

@section('title', 'Kontak & Lokasi • JCP Training')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-10">
    
    <div>
        <div class="flex items-center gap-2 text-xs font-semibold text-slate-400 mb-2">
            <a href="{{ route('home') }}" class="hover:text-[#087F78]">Beranda</a>
            <span>/</span>
            <span class="text-[#087F78]">Kontak Kami</span>
        </div>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-[#10243A] tracking-tight">
            Hubungi Tim JCP Training
        </h1>
        <p class="text-slate-600 text-sm mt-1">
            Konsultasikan kebutuhan pelatihan pribadi maupun instansi bersama konsultan program kami.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
        <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-xs space-y-6">
            <h2 class="text-xl font-bold text-[#10243A]">Kantor & Studio Pelatihan</h2>
            <div class="space-y-4 text-sm text-slate-600">
                <div class="flex items-start gap-3">
                    <span class="text-xl">📍</span>
                    <div>
                        <strong>PT Jogja Creative Production (JCP Training)</strong><br>
                        Jl. Malioboro / Ring Road Utara, Sleman, D.I. Yogyakarta 55281
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-xl">💬</span>
                    <div>
                        <strong>WhatsApp Official:</strong> +62 812-3456-7890 (Fast Response)
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-xl">✉️</span>
                    <div>
                        <strong>Email:</strong> info@jcptraining.id / training@jogjacreative.com
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-xl">🕒</span>
                    <div>
                        <strong>Jam Operasional:</strong> Senin - Sabtu, 08.30 - 17.00 WIB
                    </div>
                </div>
            </div>

            <div class="pt-4">
                @if(config('services.whatsapp.admin_number'))
                    <a href="https://wa.me/{{ preg_replace('/\D+/', '', config('services.whatsapp.admin_number')) }}?text={{ rawurlencode('Halo Admin JCP Training') }}" target="_blank" rel="noopener" class="btn-teal px-6 py-3 rounded-xl font-bold text-sm inline-flex items-center gap-2">
                        <span>Chat WhatsApp Sekarang</span>
                        <span>&rarr;</span>
                    </a>
                @endif
            </div>
        </div>

        <div class="bg-[#F6F9FC] rounded-3xl p-8 border border-slate-200 space-y-4">
            <h3 class="text-lg font-bold text-[#10243A]">Kirim Pesan Cepat</h3>
            <form action="{{ route('register.store') }}" method="POST" class="space-y-3 text-sm">
                @csrf
                <input type="hidden" name="course_id" value="1">
                <input type="hidden" name="consent" value="1">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama</label>
                    <input type="text" name="name" required placeholder="Nama Anda" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-white">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Email</label>
                    <input type="email" name="email" required placeholder="email@anda.com" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-white">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">No. WhatsApp</label>
                    <input type="text" name="phone" required placeholder="08..." class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-white">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Pesan / Pertanyaan</label>
                    <textarea name="notes" rows="3" placeholder="Tuliskan pertanyaan Anda..." class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-white"></textarea>
                </div>
                <button type="submit" class="btn-teal w-full py-3 rounded-xl font-bold text-sm">
                    Kirim Pesan
                </button>
            </form>
        </div>
    </div>

</div>
@endsection

