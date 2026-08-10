@extends('layouts.admin')

@section('title', 'Booking Masuk · Admin ARCHOFESA')

@section('content')
<div class="space-y-6 p-6 lg:p-8">
        <div class="rounded-[32px] bg-white p-8 shadow-sm">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-2xl font-semibold text-[#1f2937]">Booking Masuk</h1>
                    <p class="mt-1 text-sm text-[#6b7280]">Kelola semua booking dan cari berdasarkan nama penyewa.</p>
                </div>
                <form method="GET" class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <select name="status" class="rounded-2xl border border-[#e7e2d8] bg-white px-4 py-3 text-sm text-[#374151]">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu</option>
                        <option value="menunggu_pembayaran" {{ request('status') === 'menunggu_pembayaran' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                        <option value="dibayar" {{ request('status') === 'dibayar' ? 'selected' : '' }}>Sudah Dibayar</option>
                        <option value="menunggu_konfirmasi_owner" {{ request('status') === 'menunggu_konfirmasi_owner' ? 'selected' : '' }}>Menunggu Konfirmasi Pemilik</option>
                        <option value="dihuni" {{ request('status') === 'dihuni' ? 'selected' : '' }}>Dihuni</option>
                    </select>
                    <input type="search" name="search" placeholder="Cari nama user" value="{{ request('search') }}" class="rounded-2xl border border-[#e7e2d8] bg-white px-4 py-3 text-sm text-[#374151]" />
                    <button type="submit" class="rounded-full bg-[#c9a227] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#b68d1f]">Cari</button>
                </form>
            </div>

            <div class="mt-8 overflow-hidden rounded-[28px] border border-[#e7e2d8] bg-white">
                <table class="min-w-full divide-y divide-slate-200 text-sm text-left">
                    <thead class="bg-[#faf8f5]">
                        <tr>
                            <th class="px-6 py-4 font-semibold text-slate-600">ID</th>
                            <th class="px-6 py-4 font-semibold text-slate-600">Nama</th>
                            <th class="px-6 py-4 font-semibold text-slate-600">Kamar</th>
                            <th class="px-6 py-4 font-semibold text-slate-600">Status</th>
                            <th class="px-6 py-4 font-semibold text-slate-600">Pembayaran</th>
                            <th class="px-6 py-4 font-semibold text-slate-600">Tanggal</th>
                            <th class="px-6 py-4 font-semibold text-slate-600">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @forelse ($bookings as $booking)
                            <tr>
                                <td class="px-6 py-4 text-slate-700">#{{ $booking->id }}</td>
                                <td class="px-6 py-4 text-slate-700">{{ $booking->user->name }}</td>
                                <td class="px-6 py-4 text-slate-700">{{ $booking->room_code ?? ($booking->room->room_code ?? 'N/A') }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-[0.24em] {{ $booking->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : ($booking->status === 'dibayar' ? 'bg-blue-100 text-blue-700' : ($booking->status === 'dihuni' ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-700')) }}">{{ str()->headline($booking->status) }}</span>
                                </td>
                                <td class="px-6 py-4 text-slate-700">{{ ucfirst($booking->payment_status) }}</td>
                                <td class="px-6 py-4 text-slate-700">{{ optional($booking->created_at)->format('d M Y') }}</td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('admin.bookings.show', $booking) }}" class="rounded-full border border-[#e7e2d8] bg-white px-3 py-2 text-xs font-semibold text-slate-700 transition hover:bg-[#faf8f5]">Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-sm text-slate-500">Tidak ada booking yang ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">{{ $bookings->withQueryString()->links() }}</div>
        </div>
    </div>
</div>
@endsection
