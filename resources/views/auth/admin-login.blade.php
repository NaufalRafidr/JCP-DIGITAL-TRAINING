@extends('layouts.guest')

@section('title', 'Login Admin')

@section('content')
<section class="w-full max-w-md mx-auto">
    <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
        <p class="text-xs font-bold uppercase tracking-widest text-[#087F78]">JCP Admin</p>
        <h1 class="mt-2 text-2xl font-extrabold text-[#10243A]">Masuk ke dashboard</h1>
        <p class="mt-2 text-sm text-slate-500">Gunakan akun yang telah diberi akses admin.</p>

        @if(session('success'))
            <div class="mt-5 rounded-xl border border-emerald-200 bg-emerald-50 p-3 text-sm text-emerald-800">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="mt-5 rounded-xl border border-rose-200 bg-rose-50 p-3 text-sm text-rose-700">{{ $errors->first() }}</div>
        @endif

        <form action="{{ route('login.store') }}" method="POST" class="mt-6 space-y-4">
            @csrf
            <div>
                <label for="username" class="mb-1 block text-sm font-semibold text-slate-700">Username</label>
                <input id="username" name="username" type="text" value="{{ old('username') }}" autocomplete="username" maxlength="40" required autofocus class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-[#087F78] focus:outline-none">
            </div>
            <div>
                <label for="password" class="mb-1 block text-sm font-semibold text-slate-700">Kata sandi</label>
                <input id="password" name="password" type="password" autocomplete="current-password" required class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-[#087F78] focus:outline-none">
            </div>
            <label class="flex items-center gap-2 text-sm text-slate-600"><input type="checkbox" name="remember" value="1" class="rounded border-slate-300 text-[#087F78]"> Ingat saya</label>
            <button type="submit" class="w-full rounded-xl bg-[#087F78] px-4 py-3 font-bold text-white hover:bg-[#066761]">Masuk</button>
        </form>
    </div>
</section>
@endsection
