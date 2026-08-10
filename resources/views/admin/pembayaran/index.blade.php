@extends('layouts.admin')

@section('title', 'Manajemen Keuangan · Admin ARCHOFESA')

@section('content')
<div class="space-y-6 p-6 lg:p-8">
        <div class="rounded-[32px] bg-white p-8 shadow-sm">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-2xl font-semibold text-[#1f2937]">Manajemen Keuangan</h1>
                    <p class="mt-1 text-sm text-[#6b7280]">Lihat transaksi dan tandai pembayaran COD sebagai sudah dibayar.</p>
                </div>
            </div>

            <div class="mt-8 grid gap-6 lg:grid-cols-2">
                <x-dashboard.stat-card title="Transaksi Berhasil" value="{{ $summary['success_count'] }}" detail="Jumlah transaksi berstatus lunas" />
                <x-dashboard.stat-card title="Nominal Masuk" value="Rp{{ number_format($summary['success_amount'], 0, ',', '.') }}" detail="Total pendapatan masuk" />
            </div>

            <div class="mt-8 overflow-hidden rounded-[28px] border border-[#e7e2d8] bg-white">
                <table class="min-w-full divide-y divide-slate-200 text-sm text-left">
                    <thead class="bg-[#faf8f5]">
                        <tr>
                            <th class="px-6 py-4 font-semibold text-slate-600">ID</th>
                            <th class="px-6 py-4 font-semibold text-slate-600">Pengguna</th>
                            <th class="px-6 py-4 font-semibold text-slate-600">Booking</th>
                            <th class="px-6 py-4 font-semibold text-slate-600">Metode</th>
                            <th class="px-6 py-4 font-semibold text-slate-600">Jumlah</th>
                            <th class="px-6 py-4 font-semibold text-slate-600">Status</th>
                            <th class="px-6 py-4 font-semibold text-slate-600">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @forelse ($payments as $payment)
                            <tr>
                                <td class="px-6 py-4 text-slate-700">#{{ $payment->id }}</td>
                                <td class="px-6 py-4 text-slate-700">{{ $payment->booking?->user->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-slate-700">#{{ $payment->booking_id }}</td>
                                <td class="px-6 py-4 text-slate-700">{{ strtoupper($payment->payment_method) }}</td>
                                <td class="px-6 py-4 text-slate-700">Rp{{ number_format($payment->amount, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-slate-700">{{ ucfirst($payment->status) }}</td>
                                <td class="px-6 py-4">
                                    @if ($payment->payment_method === 'cod' && $payment->status !== 'paid')
                                        <form method="POST" action="{{ route('admin.pembayaran.mark-paid', $payment) }}">
                                            @csrf
                                            <button type="submit" class="rounded-full bg-[#16a34a] px-4 py-2 text-xs font-semibold text-white transition hover:bg-[#15803d]">Tandai Sudah Dibayar</button>
                                        </form>
                                    @else
                                        <span class="text-xs text-slate-500">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-sm text-slate-500">Belum ada transaksi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">{{ $payments->withQueryString()->links() }}</div>
        </div>
    </div>
</div>
@endsection
