@extends('layouts.app')

@section('title', 'Galeri · ARCHOFESA KOST')

@section('content')
<section class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
    <div class="max-w-3xl">
        <p class="text-sm font-semibold uppercase tracking-[0.24em] text-blue-600">Galeri</p>
        <h1 class="mt-4 text-4xl font-semibold tracking-tight text-slate-900 sm:text-5xl">Pratinjau visual ruang-ruang yang dinikmati oleh penghuni.</h1>
    </div>

    <div class="mt-12 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
        @forelse ($images as $image)
            <div class="overflow-hidden rounded-[28px] border border-slate-200 bg-white shadow-sm">
                <img src="{{ $image }}" alt="Interior ARCHOFESA KOST" class="h-64 w-full object-cover">
            </div>
        @empty
            <div class="col-span-full rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-12 text-center">
                <p class="text-slate-600">Belum ada foto tersedia.</p>
            </div>
        @endforelse
    </div>
</section>
@endsection
