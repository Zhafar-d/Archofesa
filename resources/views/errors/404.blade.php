@extends('layouts.app')

@section('title', 'Halaman Tidak Ditemukan · ARCHOFESA KOST')

@section('content')
<section class="mx-auto flex min-h-[70vh] max-w-5xl items-center justify-center px-4 py-24 sm:px-6 lg:px-8">
    <div class="max-w-2xl text-center">
        <p class="text-sm font-semibold uppercase tracking-[0.24em] text-[#c9a227]">404</p>
        <h1 class="mt-4 text-4xl font-semibold tracking-tight text-[#1f2937] sm:text-5xl">Halaman tidak ditemukan.</h1>
        <p class="mt-6 text-lg leading-8 text-[#4b5563]">Halaman yang Anda cari tidak ada atau telah dipindahkan.</p>
        <a href="{{ route('home') }}" class="mt-8 inline-flex rounded-full bg-[#c9a227] px-6 py-3 text-sm font-semibold text-white shadow-lg transition hover:bg-[#b68d1f]">Kembali ke Beranda</a>
    </div>
</section>
@endsection
