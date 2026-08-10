@extends('layouts.dashboard-app')

@section('title', 'Pembayaran · ARCHOFESA KOST')

@section('content')
<div class="px-6 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900">Pembayaran</h1>
        <p class="mt-2 text-slate-600">Kelola tagihan dan riwayat pembayaran Anda</p>
    </div>

    @if(request('status') === 'success')
        <div class="mb-6 rounded-2xl border border-green-200 bg-green-50 px-6 py-4 text-sm font-semibold text-green-800">
            ✓ Pembayaran berhasil diproses. Terima kasih!
        </div>
    @elseif(request('status') === 'pending')
        <div class="mb-6 rounded-2xl border border-yellow-200 bg-yellow-50 px-6 py-4 text-sm font-semibold text-yellow-800">
            ⏳ Pembayaran sedang diproses. Kami akan konfirmasi setelah dana diterima.
        </div>
    @endif

    <div class="grid gap-6 lg:grid-cols-3">
        <x-dashboard.section title="Tagihan Saat Ini" subtitle="{{ $currentPayment ? 'Jatuh tempo pada ' . $currentPayment->created_at->addDays(5)->format('d M Y') : 'Tidak ada tagihan menunggu' }}">
            <div class="space-y-4">
                @if ($currentPayment)
                    <div class="rounded-lg bg-gradient-to-br from-blue-50 to-cyan-50 p-6">
                        <p class="text-sm text-slate-600">Sewa Bulanan</p>
                        <p class="mt-2 text-4xl font-bold text-slate-900">Rp{{ number_format($currentPayment->amount, 0, ',', '.') }}</p>
                        <p class="mt-2 text-sm text-slate-600">
                            Kamar {{ $currentPayment->booking ? $currentPayment->booking->room_code : '-' }} · {{ $currentPayment->booking && $currentPayment->booking->room && $currentPayment->booking->room->price_monthly >= 1400000 ? 'Family Room' : 'Student Room' }}
                        </p>
                    </div>
                    <a href="{{ route('payment.pay', $currentPayment) }}"
                       class="block w-full rounded-lg bg-[#c9a227] px-4 py-2 text-center font-semibold text-white hover:bg-[#b68d1f]">
                        Bayar Sekarang
                    </a>
                @else
                    <div class="rounded-lg border border-dashed border-slate-200 bg-slate-50 p-6 text-center">
                        <p class="text-sm text-slate-600">Semua tagihan sudah lunas!</p>
                    </div>
                @endif
            </div>
        </x-dashboard.section>

        <x-dashboard.section title="Statistik Pembayaran">
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-slate-600">Total Dibayar</p>
                    <p class="mt-1 text-2xl font-bold text-slate-900">Rp{{ number_format($totalPaid, 0, ',', '.') }}</p>
                </div>
                <div class="border-t border-slate-100 pt-4">
                    <p class="text-sm text-slate-600">Status Pembayaran</p>
                    <p class="mt-1 inline-flex rounded-full {{ $hasOverdue ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }} px-3 py-1 text-sm font-semibold">{{ $hasOverdue ? 'Terlambat' : 'Tepat Waktu' }}</p>
                </div>
            </div>
        </x-dashboard.section>

        <x-dashboard.section title="Tagihan Mendatang">
            <div class="space-y-3">
                @forelse ($upcomingPayments as $upcoming)
                    <div class="rounded-lg border border-slate-100 p-3">
                        <p class="text-sm font-medium text-slate-900">{{ $upcoming->created_at->format('F Y') }}</p>
                        <p class="text-sm text-slate-600">Rp{{ number_format($upcoming->amount, 0, ',', '.') }} · Jatuh tempo {{ $upcoming->created_at->addDays(5)->format('d M') }}</p>
                    </div>
                @empty
                    <div class="rounded-lg border border-dashed border-slate-100 p-4 text-center">
                        <p class="text-sm text-slate-600">Tidak ada tagihan mendatang.</p>
                    </div>
                @endforelse
            </div>
        </x-dashboard.section>
    </div>

    <div class="mt-8">
        <x-dashboard.section title="Riwayat Pembayaran" subtitle="Semua transaksi Anda">
            <div class="space-y-3">
                @forelse ($payments as $payment)
                    <div class="flex flex-col gap-3 rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="font-semibold text-slate-900">{{ $payment->booking ? 'Booking #' . $payment->booking->id : 'Pembayaran #' . $payment->id }}</p>
                            <p class="mt-1 text-sm text-slate-600">{{ optional($payment->booking)->room_code ?: 'Tagihan' }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-semibold text-slate-900">Rp{{ number_format($payment->amount, 0, ',', '.') }}</p>
                            <p class="text-sm text-slate-500">{{ strtoupper($payment->payment_method) }} · {{ ucfirst($payment->status) }}</p>
                        </div>
                        @if ($payment->status === 'pending' && $payment->payment_method === 'midtrans')
                            <a href="{{ route('payment.pay', $payment) }}" class="inline-flex items-center justify-center rounded-full bg-blue-600 px-5 py-2 text-sm font-semibold text-white hover:bg-blue-700">Bayar Sekarang</a>
                        @endif
                    </div>
                @empty
                    <div class="rounded-3xl border border-slate-200 bg-white p-6 text-center text-sm text-slate-600">
                        Tidak ada riwayat pembayaran.
                    </div>
                @endforelse
            </div>
        </x-dashboard.section>
    </div>
</div>
@endsection
