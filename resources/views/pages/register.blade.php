@extends('layouts.app')

@section('title', 'Daftar · ARCHOFESA KOST')

@section('content')
<section class="mx-auto flex max-w-6xl items-center justify-center px-4 py-24 sm:px-6 lg:px-8">
    <div class="w-full max-w-xl rounded-[36px] border border-[#e7e2d8] bg-white p-8 shadow-[0_30px_90px_-28px_rgba(15,23,42,0.12)] sm:p-10">
        <p class="text-sm font-semibold uppercase tracking-[0.24em] text-[#c9a227]">Buat Akun</p>
        <h1 class="mt-4 text-3xl font-semibold text-[#1f2937]">Bergabung dengan ARCHOFESA KOST</h1>
        <p class="mt-3 text-sm leading-7 text-[#4b5563]">Buat akun pribadi untuk menjelajahi kamar yang tersedia, mengelola masa tinggal Anda, dan mengakses dukungan premium.</p>

        <form action="{{ route('register.store') }}" method="POST" class="mt-8 space-y-4">
            @csrf
            <input class="w-full rounded-2xl border border-[#e7e2d8] bg-[#faf8f5] px-4 py-3 outline-none ring-0" type="text" name="name" placeholder="Nama Lengkap" required>
            <input class="w-full rounded-2xl border border-[#e7e2d8] bg-[#faf8f5] px-4 py-3 outline-none ring-0" type="email" name="email" placeholder="Alamat Email" required>
            <input class="w-full rounded-2xl border border-[#e7e2d8] bg-[#faf8f5] px-4 py-3 outline-none ring-0" type="password" name="password" placeholder="Buat Kata Sandi" required>
            <button class="w-full rounded-full bg-[#c9a227] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#b68d1f]">Buat Akun</button>
        </form>

        <div class="mt-6">
            <button id="firebase-google-login" type="button" class="flex w-full items-center justify-center gap-3 rounded-full border border-[#e7e2d8] bg-white px-5 py-3 text-sm font-semibold text-[#374151] transition hover:border-[#c9a227] hover:text-[#c9a227]">
                <span>Daftar dengan Google</span>
            </button>
        </div>
    </div>
</section>
@endsection
