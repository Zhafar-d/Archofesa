@extends('layouts.app')

@section('title', 'Masuk · ARCHOFESA KOST')

@section('content')
<section class="mx-auto flex max-w-6xl items-center justify-center px-4 py-24 sm:px-6 lg:px-8">
    <div class="w-full max-w-xl rounded-[36px] border border-[#e7e2d8] bg-white p-8 shadow-[0_30px_90px_-28px_rgba(15,23,42,0.12)] sm:p-10">
        <p class="text-sm font-semibold uppercase tracking-[0.24em] text-[#c9a227]">Portal Akses</p>
        <h1 class="mt-4 text-3xl font-semibold text-[#1f2937]">Selamat Datang Kembali</h1>
        <p class="mt-3 text-sm leading-7 text-[#4b5563]">Masuk dengan email Anda atau lanjutkan dengan Google untuk pengalaman properti yang lebih mudah.</p>

        <form action="{{ route('login.store') }}" method="POST" class="mt-8 space-y-4">
            @csrf
            <input class="w-full rounded-2xl border border-[#e7e2d8] bg-[#faf8f5] px-4 py-3 outline-none ring-0" type="email" name="email" placeholder="Alamat Email" required>
            <input class="w-full rounded-2xl border border-[#e7e2d8] bg-[#faf8f5] px-4 py-3 outline-none ring-0" type="password" name="password" placeholder="Kata Sandi" required>
            <button class="w-full rounded-full bg-[#c9a227] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#b68d1f]">Lanjutkan</button>
        </form>

        <div class="mt-6">
            <button id="firebase-google-login" type="button" class="flex w-full items-center justify-center gap-3 rounded-full border border-[#e7e2d8] bg-white px-5 py-3 text-sm font-semibold text-[#374151] transition hover:border-[#c9a227] hover:text-[#c9a227]">
                <span>Lanjutkan dengan Google</span>
            </button>
        </div>

        <div class="mt-6 flex items-center justify-between text-sm text-[#6b7280]">
            <a href="{{ route('forgot-password') }}" class="font-medium text-[#c9a227]">Lupa kata sandi?</a>
            <a href="{{ route('register') }}" class="font-medium text-[#374151]">Buat akun</a>
        </div>
    </div>
</section>
@endsection
