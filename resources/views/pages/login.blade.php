@extends('layouts.app')

@section('title', 'Masuk · ARCHOFESA KOST')

@section('content')
<section class="mx-auto flex max-w-6xl items-center justify-center px-4 py-20 sm:px-6 lg:px-8">
    <div class="w-full max-w-xl rounded-[36px] border border-[#e7e2d8] bg-white p-8 shadow-[0_30px_90px_-28px_rgba(15,23,42,0.12)] sm:p-10">
        <div class="text-center sm:text-left">
            <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#c9a227]">Portal Akses Penghuni & Pengelola</p>
            <h1 class="mt-2 text-3xl font-bold text-[#1f2937]">Selamat Datang Kembali</h1>
            <p class="mt-2 text-sm leading-6 text-[#6b7280]">Masuk menggunakan email atau nomor WhatsApp / telepon yang terdaftar.</p>
        </div>

        @if (session('status'))
            <div class="mt-5 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800 flex items-center gap-2">
                <span>✓</span> {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mt-5 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-800 flex items-center gap-2">
                <span>⚠️</span> {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login.store') }}" method="POST" class="mt-6 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-[#4b5563] mb-1.5">Email atau Nomor Telepon / WA</label>
                <div class="relative">
                    <input class="w-full rounded-2xl border border-[#e7e2d8] bg-[#faf8f5] px-4 py-3.5 outline-none ring-0 text-sm text-[#1f2937] placeholder-[#9ca3af] focus:border-[#c9a227] focus:bg-white transition" 
                           type="text" 
                           name="login" 
                           value="{{ old('login') }}" 
                           placeholder="Contoh: 088237299199 atau nama@email.com" 
                           required autofocus>
                </div>
            </div>

            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-[#4b5563]">Kata Sandi</label>
                    <a href="{{ route('forgot-password') }}" class="text-xs font-semibold text-[#c9a227] hover:underline">Lupa kata sandi?</a>
                </div>
                <input class="w-full rounded-2xl border border-[#e7e2d8] bg-[#faf8f5] px-4 py-3.5 outline-none ring-0 text-sm text-[#1f2937] placeholder-[#9ca3af] focus:border-[#c9a227] focus:bg-white transition" 
                       type="password" 
                       name="password" 
                       placeholder="Masukkan kata sandi akun" 
                       required>
            </div>

            <button type="submit" class="w-full rounded-full bg-[#c9a227] py-3.5 text-sm font-bold text-white shadow-md shadow-[#c9a227]/20 transition hover:bg-[#b68d1f] hover:shadow-lg">
                Masuk ke Akun
            </button>
        </form>

        <div class="relative my-6 flex items-center justify-center">
            <div class="w-full border-t border-[#e7e2d8]"></div>
            <span class="absolute bg-white px-3 text-xs uppercase tracking-wider text-[#9ca3af]">atau</span>
        </div>

        <div>
            <button id="firebase-google-login" type="button" class="flex w-full items-center justify-center gap-3 rounded-full border border-[#e7e2d8] bg-white py-3 text-sm font-semibold text-[#374151] transition hover:border-[#c9a227] hover:text-[#c9a227] hover:bg-[#faf8f5]">
                <svg class="h-4 w-4" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                </svg>
                <span>Lanjutkan dengan Google</span>
            </button>
        </div>

        {{-- Section CTA Registrasi yang Jelas --}}
        <div class="mt-8 rounded-2xl border border-[#e7e2d8] bg-[#faf8f5] p-4 text-center">
            <p class="text-xs text-[#6b7280]">Belum memiliki akun di ARCHOFESA KOST?</p>
            <a href="{{ route('register') }}" class="mt-2 inline-flex items-center justify-center gap-1.5 text-sm font-bold text-[#c9a227] hover:text-[#b68d1f] hover:underline">
                <span>Daftar / Buat Akun Baru</span> &rarr;
            </a>
        </div>
    </div>
</section>
@endsection
