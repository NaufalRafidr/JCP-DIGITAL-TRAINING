@extends('layouts.guest')

@section('title', 'Login Admin')

@section('content')
<section class="w-full max-w-md mx-auto">
    <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
        <p class="text-xs font-bold uppercase tracking-widest text-[#087F78]">JCP Admin</p>
        <h1 class="mt-2 text-2xl font-extrabold text-[#10243A]">Login Admin</h1>
        <p class="mt-2 text-sm text-slate-500">Masukkan password untuk mengakses dashboard admin.</p>

        @if(session('success'))
            <div class="mt-5 rounded-xl border border-emerald-200 bg-emerald-50 p-3 text-sm text-emerald-800">{{ session('success') }}</div>
        @endif

        @if($retryAfter > 0)
            <div data-admin-login-lockout data-retry-after="{{ $retryAfter }}" role="alert" class="mt-5 rounded-xl border border-rose-200 bg-rose-50 p-3 text-sm text-rose-700">
                Terlalu banyak percobaan login. Silakan coba lagi dalam <span data-admin-login-countdown>{{ $retryAfter }}</span> detik.
            </div>
        @elseif($errors->any())
            <div class="mt-5 rounded-xl border border-rose-200 bg-rose-50 p-3 text-sm text-rose-700">{{ $errors->first() }}</div>
        @endif

        <form action="{{ route('login.store') }}" method="POST" class="mt-6 space-y-4">
            @csrf
            <div>
                <label for="password" class="mb-1 block text-sm font-semibold text-slate-700">Password</label>
                <div class="relative">
                    <input id="password" name="password" type="password" autocomplete="current-password" required autofocus @disabled($retryAfter > 0) class="w-full rounded-xl border border-slate-300 px-4 py-3 pr-12 text-sm focus:border-[#087F78] focus:outline-none disabled:cursor-not-allowed disabled:bg-slate-100 disabled:text-slate-400">
                    <button type="button" data-password-toggle="password" aria-controls="password" aria-label="Tampilkan password" aria-pressed="false" @disabled($retryAfter > 0) class="absolute right-2 top-1/2 inline-flex h-10 w-10 -translate-y-1/2 items-center justify-center rounded-lg text-slate-400 transition-colors hover:bg-slate-100 hover:text-[#087F78] focus:outline-none focus:ring-2 focus:ring-[#087F78]/30 disabled:cursor-not-allowed disabled:opacity-50">
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
            <button type="submit" @disabled($retryAfter > 0) class="w-full rounded-xl bg-[#087F78] px-4 py-3 font-bold text-white hover:bg-[#066761] disabled:cursor-not-allowed disabled:bg-slate-400">
                @if($retryAfter > 0)
                    Tunggu <span data-admin-login-countdown>{{ $retryAfter }}</span> detik
                @else
                    Masuk Admin
                @endif
            </button>
        </form>
    </div>
</section>
@endsection
