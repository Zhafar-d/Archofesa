@extends('layouts.app')

@section('title', 'Kontak · Kost The Archofesa Pedurungan Semarang')

@section('content')
<section class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
    <div class="grid gap-8 lg:grid-cols-[0.95fr_1.05fr]">
        <div class="rounded-[36px] border border-slate-200 bg-white p-8 shadow-sm">
            <p class="text-sm font-semibold uppercase tracking-[0.24em] text-blue-600">Kontak</p>
            <h1 class="mt-4 text-4xl font-semibold tracking-tight text-slate-900">Kami siap melayani pertanyaan dan informasi ketersediaan kamar.</h1>
            <p class="mt-6 text-lg leading-8 text-slate-600">Hubungi kami untuk reservasi, pertanyaan properti, atau untuk menjadwalkan kunjungan pribadi.</p>
            <div class="mt-8 space-y-4 text-sm text-slate-600">
                <p><span class="font-semibold text-slate-900">Alamat:</span> Pedurungan, Semarang, Jawa Tengah</p>
                <p><span class="font-semibold text-slate-900">Telepon:</span> +62 812 3456 7890</p>
                <p><span class="font-semibold text-slate-900">Email:</span> hello@archofesakost.com</p>
            </div>

            {{-- Peta Lokasi --}}
            <div class="mt-8">
                <p class="mb-3 text-sm font-semibold text-slate-900">Lokasi di Peta</p>
                <x-leaflet-map height="224px" />
            </div>
        </div>

        <div class="rounded-[36px] border border-slate-200 bg-slate-50 p-8 shadow-sm">
            <div class="rounded-[24px] border border-dashed border-slate-300 bg-white p-6">
                <p class="text-sm font-semibold uppercase tracking-[0.24em] text-blue-600">Formulir Pertanyaan</p>
                <p class="mt-3 text-sm leading-7 text-slate-600">Formulir ini siap digunakan untuk integrasi validasi dan pengiriman email di masa mendatang.</p>
                <div class="mt-6 space-y-4">
                    <input class="w-full rounded-2xl border border-slate-200 px-4 py-3" placeholder="Nama Anda">
                    <input class="w-full rounded-2xl border border-slate-200 px-4 py-3" placeholder="Alamat Email">
                    <textarea class="min-h-32 w-full rounded-2xl border border-slate-200 px-4 py-3" placeholder="Ceritakan kebutuhan Anda"></textarea>
                    <button class="rounded-full bg-slate-900 px-5 py-3 text-sm font-semibold text-white">Kirim Pertanyaan</button>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
