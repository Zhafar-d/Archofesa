@extends('layouts.admin')

@section('title', 'Manajemen Penghuni · Admin ARCHOFESA')

@section('content')
<div class="space-y-6 p-6 lg:p-8">
        <div class="rounded-[32px] bg-white p-8 shadow-sm">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-2xl font-semibold text-[#1f2937]">Manajemen Penghuni</h1>
                    <p class="mt-1 text-sm text-[#6b7280]">Kelola penghuni yang sedang menempati dan perbarui tanggal tagihan.</p>
                </div>
            </div>

            <div class="mt-8 overflow-hidden rounded-[28px] border border-[#e7e2d8] bg-white">
                <table class="min-w-full divide-y divide-slate-200 text-sm text-left">
                    <thead class="bg-[#faf8f5]">
                        <tr>
                            <th class="px-6 py-4 font-semibold text-slate-600">Nama</th>
                            <th class="px-6 py-4 font-semibold text-slate-600">Kamar</th>
                            <th class="px-6 py-4 font-semibold text-slate-600">Mulai Huni</th>
                            <th class="px-6 py-4 font-semibold text-slate-600">Tanggal Jatuh Tempo</th>
                            <th class="px-6 py-4 font-semibold text-slate-600">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @forelse ($tenants as $booking)
                            <tr>
                                <td class="px-6 py-4 text-slate-700">{{ $booking->user->name }}</td>
                                <td class="px-6 py-4 text-slate-700">{{ $booking->room_code ?? ($booking->room->room_code ?? 'N/A') }}</td>
                                <td class="px-6 py-4 text-slate-700">{{ optional($booking->move_in_date)->format('d M Y') }}</td>
                                <td class="px-6 py-4 text-slate-700">{{ optional($booking->move_out_date)->format('d M Y') }}</td>
                                <td class="px-6 py-4">
                                    <button data-bs-target="#extendModal{{ $booking->id }}" class="rounded-full border border-[#e7e2d8] bg-white px-3 py-2 text-xs font-semibold text-slate-700 transition hover:bg-[#faf8f5]">Tambah Waktu</button>
                                </td>
                            </tr>

                            <div id="extendModal{{ $booking->id }}" class="hidden">
                                <form method="POST" action="{{ route('admin.penghuni.update-due-date', $booking) }}" class="space-y-4">
                                    @csrf
                                    @method('PUT')
                                    <label class="block">
                                        <span class="text-sm font-medium text-[#374151]">Tanggal Jatuh Tempo Baru</span>
                                        <input type="date" name="move_out_date" value="{{ old('move_out_date', $booking->move_out_date?->format('Y-m-d')) }}" class="mt-2 w-full rounded-2xl border border-[#e7e2d8] bg-white px-4 py-3 text-sm text-[#374151]" required>
                                    </label>
                                    <button type="submit" class="rounded-full bg-[#c9a227] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#b68d1f]">Simpan Perpanjangan</button>
                                </form>
                            </div>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-sm text-slate-500">Belum ada penghuni aktif.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">{{ $tenants->links() }}</div>
        </div>
    </div>
</div>
@endsection
