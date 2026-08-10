@extends('layouts.admin')

@section('title', 'Manajemen Kamar · Admin ARCHOFESA')

@section('content')
<div class="space-y-6 p-6 lg:p-8">
        <div class="rounded-[32px] bg-white p-8 shadow-sm">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-2xl font-semibold text-[#1f2937]">Manajemen Kamar</h1>
                    <p class="mt-1 text-sm text-[#6b7280]">Kelola kamar dan ubah status saat maintenance atau nonaktif.</p>
                </div>
                <a href="{{ route('admin.kamar.create') }}" class="rounded-full bg-[#c9a227] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#b68d1f]">Tambah Kamar</a>
            </div>

            <div class="mt-8 overflow-hidden rounded-[28px] border border-[#e7e2d8] bg-white">
                <table class="min-w-full divide-y divide-slate-200 text-sm text-left">
                    <thead class="bg-[#faf8f5]">
                        <tr>
                            <th class="px-6 py-4 font-semibold text-slate-600">Kode</th>
                            <th class="px-6 py-4 font-semibold text-slate-600">Ukuran</th>
                            <th class="px-6 py-4 font-semibold text-slate-600">Harga</th>
                            <th class="px-6 py-4 font-semibold text-slate-600">Status</th>
                            <th class="px-6 py-4 font-semibold text-slate-600">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @forelse ($rooms as $room)
                            <tr>
                                <td class="px-6 py-4 text-slate-700">{{ $room->room_code }}</td>
                                <td class="px-6 py-4 text-slate-700">{{ $room->size }}</td>
                                <td class="px-6 py-4 text-slate-700">Rp{{ number_format($room->price_monthly, 0, ',', '.') }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-[0.24em] {{ $room->status === 'available' ? 'bg-green-100 text-green-700' : ($room->status === 'occupied' ? 'bg-blue-100 text-blue-700' : 'bg-yellow-100 text-yellow-700') }}">{{ ucfirst($room->status) }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('admin.kamar.edit', $room) }}" class="rounded-full border border-[#e7e2d8] bg-white px-3 py-2 text-xs font-semibold text-slate-700 transition hover:bg-[#faf8f5]">Ubah</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-sm text-slate-500">Belum ada kamar tersedia.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">{{ $rooms->links() }}</div>
        </div>
    </div>
</div>
@endsection
