@extends('layouts.dashboard-app')

@section('title', 'Notifikasi · ARCHOFESA KOST')

@section('content')
<div class="px-6 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900">Notifikasi</h1>
        <p class="mt-2 text-slate-600">Tetap terupdate dengan informasi penting</p>
    </div>

    <div class="mb-6 flex gap-3">
        <input type="text" placeholder="Cari notifikasi..." class="flex-1 rounded-lg border border-slate-200 bg-slate-50 px-4 py-2">
        <select class="rounded-lg border border-slate-200 bg-white px-4 py-2">
            <option>Semua</option>
            <option>Belum Dibaca</option>
            <option>Pembayaran</option>
            <option>Booking</option>
            <option>Pesan</option>
        </select>
    </div>

    <div class="space-y-4">
        @forelse ($notifications as $notif)
            @php
                $icon = 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z';
                $color = 'text-blue-600';
                $bgColor = 'bg-blue-100';
                
                if (isset($notif->data['type'])) {
                    if ($notif->data['type'] === 'payment') {
                        $icon = 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z';
                        $color = 'text-green-600';
                        $bgColor = 'bg-green-100';
                    } elseif ($notif->data['type'] === 'booking') {
                        $icon = 'M8 7V3m8 4V3m-9 8h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z';
                        $color = 'text-yellow-600';
                        $bgColor = 'bg-yellow-100';
                    }
                }
            @endphp
            <div class="rounded-2xl border {{ is_null($notif->read_at) ? 'border-blue-200 bg-blue-50' : 'border-slate-200 bg-white' }} p-6 transition hover:shadow-sm">
                <div class="flex gap-4">
                    <div class="mt-1 rounded-full {{ $bgColor }} p-3 flex-shrink-0">
                        <svg class="h-6 w-6 {{ $color }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}" />
                        </svg>
                    </div>

                    <div class="flex-1">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="font-semibold text-slate-900">{{ $notif->data['title'] ?? 'Notifikasi Baru' }}</p>
                                <p class="mt-1 text-sm text-slate-600">{{ $notif->data['message'] ?? '' }}</p>
                            </div>
                            @if (is_null($notif->read_at))
                                <span class="ml-2 h-2 w-2 rounded-full bg-blue-600 flex-shrink-0"></span>
                            @endif
                        </div>
                        <p class="mt-2 text-xs text-slate-500">{{ $notif->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>
        @empty
            <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-12 text-center">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                </div>
                <h3 class="mt-4 text-lg font-semibold text-slate-900">Belum ada notifikasi</h3>
                <p class="mt-2 text-slate-600">Pemberitahuan penting tentang tagihan dan status kamar akan muncul di sini.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
