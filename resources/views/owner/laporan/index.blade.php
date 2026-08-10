@extends('layouts.dashboard-app')

@section('title', 'Laporan Keuangan · Pemilik ARCHOFESA')

@section('content')
<div class="px-6 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900">Laporan Keuangan</h1>
        <p class="mt-2 text-slate-600">Laporan hanya-baca untuk semua pembayaran yang sudah lunas.</p>
    </div>

    <div class="rounded-[32px] bg-white p-8 shadow-sm">
        <form method="GET" class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <label class="text-sm font-medium text-[#374151]">Pilih Bulan</label>
                <input type="month" name="month" value="{{ $month }}" class="mt-2 rounded-2xl border border-[#e7e2d8] bg-white px-4 py-3 text-sm text-[#374151]" />
            </div>
            <button type="submit" class="rounded-full bg-[#c9a227] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#b68d1f]">Filter</button>
        </form>

        <div class="mt-8 overflow-hidden rounded-[28px] border border-[#e7e2d8] bg-white">
            <table class="min-w-full divide-y divide-slate-200 text-sm text-left">
                <thead class="bg-[#faf8f5]">
                    <tr>
                        <th class="px-6 py-4 font-semibold text-slate-600">ID</th>
                        <th class="px-6 py-4 font-semibold text-slate-600">Pengguna</th>
                        <th class="px-6 py-4 font-semibold text-slate-600">Booking</th>
                        <th class="px-6 py-4 font-semibold text-slate-600">Jumlah</th>
                        <th class="px-6 py-4 font-semibold text-slate-600">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse ($payments as $payment)
                        <tr>
                            <td class="px-6 py-4 text-slate-700">#{{ $payment->id }}</td>
                            <td class="px-6 py-4 text-slate-700">{{ $payment->booking?->user->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-slate-700">#{{ $payment->booking_id }}</td>
                            <td class="px-6 py-4 text-slate-700">Rp{{ number_format($payment->amount, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-slate-700">{{ optional($payment->paid_at)->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-sm text-slate-500">Tidak ada pembayaran untuk bulan ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">{{ $payments->withQueryString()->links() }}</div>
    </div>
</div>
@endsection
