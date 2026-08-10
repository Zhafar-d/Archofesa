@extends('layouts.app')

@section('title', 'Fasilitas · ARCHOFESA KOST')

@section('content')
<section class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
    <div class="max-w-3xl">
        <p class="text-sm font-semibold uppercase tracking-[0.24em] text-blue-600">Fasilitas</p>
        <h1 class="mt-4 text-4xl font-semibold tracking-tight text-slate-900 sm:text-5xl">Kenyamanan, privasi, dan ruang bersama tanpa kompromi.</h1>
        <p class="mt-6 text-lg leading-8 text-slate-600">Setiap fasilitas dipilih untuk mendukung rutinitas bersih, belajar produktif, dan kehidupan modern.</p>
    </div>

    <div class="mt-12 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
        @php
            $items = [
                ['title' => 'Air Conditioner', 'description' => 'Suhu kamar yang nyaman sepanjang hari.'],
                ['title' => 'Kasur', 'description' => 'Tempat tidur yang nyaman untuk menginap lebih lama.'],
                ['title' => 'Lemari', 'description' => 'Penyimpanan yang membuat setiap kamar tetap rapi.'],
                ['title' => 'Meja Belajar', 'description' => 'Tempat yang fokus untuk belajar, membaca, atau merencanakan.'],
                ['title' => 'Dapur Bersama', 'description' => 'Area bersama yang terang untuk makan sehari-hari.'],
                ['title' => 'Rooftop Lounge', 'description' => 'Ruang atap yang tenang untuk bersantai.'],
                ['title' => 'Parkir Motor', 'description' => 'Akses kendaraan yang mudah dan terpercaya.'],
                ['title' => 'Layanan Kebersihan', 'description' => 'Standar perawatan rutin untuk setiap kamar.'],
                ['title' => 'Kamar Mandi Dalam', 'description' => 'Fasilitas premium yang membuat rutinitas harian menjadi mudah.'],
            ];
        @endphp

        @foreach ($items as $item)
            <div class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-xl font-semibold text-slate-900">{{ $item['title'] }}</h3>
                <p class="mt-3 text-sm leading-7 text-slate-600">{{ $item['description'] }}</p>
            </div>
        @endforeach
    </div>
</section>
@endsection
