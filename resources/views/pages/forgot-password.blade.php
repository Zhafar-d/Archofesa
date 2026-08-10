@extends('layouts.app')

@section('title', 'Lupa Kata Sandi · ARCHOFESA KOST')

@section('content')
<section class="mx-auto flex max-w-6xl items-center justify-center px-4 py-24 sm:px-6 lg:px-8">
    <div class="w-full max-w-xl rounded-[36px] border border-slate-200 bg-white p-8 shadow-sm sm:p-10">
        <p class="text-sm font-semibold uppercase tracking-[0.24em] text-blue-600">Atur Ulang Akses</p>
        <h1 class="mt-4 text-3xl font-semibold text-slate-900">Pulihkan akun Anda</h1>
        <p class="mt-3 text-sm leading-7 text-slate-600">Masukkan email Anda dan kami akan mengirimkan tautan untuk mengatur ulang kata sandi.</p>

        <div class="mt-8 space-y-4">
            <input class="w-full rounded-2xl border border-slate-200 px-4 py-3" placeholder="Alamat Email">
            <button class="w-full rounded-full bg-slate-900 px-5 py-3 text-sm font-semibold text-white">Kirim Tautan Reset</button>
        </div>
    </div>
</section>
@endsection
