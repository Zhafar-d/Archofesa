@extends('layouts.app')

@section('title', 'Daftar Akun Baru · ARCHOFESA KOST')

@section('content')
<section class="mx-auto flex max-w-6xl items-center justify-center px-4 py-20 sm:px-6 lg:px-8">
    <div class="w-full max-w-xl rounded-[36px] border border-[#e7e2d8] bg-white p-8 shadow-[0_30px_90px_-28px_rgba(15,23,42,0.12)] sm:p-10">
        <div class="text-center sm:text-left">
            <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#c9a227]">Registrasi Akun Baru</p>
            <h1 class="mt-2 text-3xl font-bold text-[#1f2937]">Bergabung dengan ARCHOFESA</h1>
            <p class="mt-2 text-sm leading-6 text-[#6b7280]">Daftar untuk memilih kamar idaman, mengelola hunian, dan menerima notifikasi perpanjangan otomatis via WhatsApp.</p>
        </div>

        @if ($errors->any())
            <div class="mt-5 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-800 flex items-center gap-2">
                <span>⚠️</span> {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('register.store') }}" method="POST" class="mt-6 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-[#4b5563] mb-1.5">Nama Lengkap</label>
                <input class="w-full rounded-2xl border border-[#e7e2d8] bg-[#faf8f5] px-4 py-3.5 outline-none ring-0 text-sm text-[#1f2937] placeholder-[#9ca3af] focus:border-[#c9a227] focus:bg-white transition" 
                       type="text" 
                       name="name" 
                       value="{{ old('name') }}"
                       placeholder="Contoh: Ahmad Fauzi" 
                       required>
            </div>
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-[#4b5563] mb-1.5">Alamat Email</label>
                <input class="w-full rounded-2xl border border-[#e7e2d8] bg-[#faf8f5] px-4 py-3.5 outline-none ring-0 text-sm text-[#1f2937] placeholder-[#9ca3af] focus:border-[#c9a227] focus:bg-white transition" 
                       type="email" 
                       name="email" 
                       value="{{ old('email') }}"
                       placeholder="Contoh: nama@email.com" 
                       required>
            </div>
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-[#4b5563] mb-1.5">Nomor WhatsApp / Telepon</label>
                <input class="w-full rounded-2xl border border-[#e7e2d8] bg-[#faf8f5] px-4 py-3.5 outline-none ring-0 text-sm text-[#1f2937] placeholder-[#9ca3af] focus:border-[#c9a227] focus:bg-white transition" 
                       type="tel" 
                       name="phone" 
                       value="{{ old('phone') }}"
                       placeholder="Contoh: 088237299199" 
                       required>
            </div>
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-[#4b5563] mb-1.5">Kata Sandi</label>
                <input class="w-full rounded-2xl border border-[#e7e2d8] bg-[#faf8f5] px-4 py-3.5 outline-none ring-0 text-sm text-[#1f2937] placeholder-[#9ca3af] focus:border-[#c9a227] focus:bg-white transition" 
                       type="password" 
                       name="password" 
                       placeholder="Buat kata sandi minimal 8 karakter" 
                       required>
            </div>
            <button type="submit" class="w-full rounded-full bg-[#c9a227] py-3.5 text-sm font-bold text-white shadow-md shadow-[#c9a227]/20 transition hover:bg-[#b68d1f] hover:shadow-lg">
                Daftar Akun Sekarang
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
                <span>Daftar dengan Google</span>
            </button>
        </div>

        {{-- Section CTA Login --}}
        <div class="mt-8 rounded-2xl border border-[#e7e2d8] bg-[#faf8f5] p-4 text-center">
            <p class="text-xs text-[#6b7280]">Sudah memiliki akun terdaftar?</p>
            <a href="{{ route('login') }}" class="mt-2 inline-flex items-center justify-center gap-1.5 text-sm font-bold text-[#c9a227] hover:text-[#b68d1f] hover:underline">
                <span>Masuk ke Akun Anda</span> &rarr;
            </a>
        </div>
    </div>
</section>
@endsection
