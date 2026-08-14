@extends('layouts.app')

@section('title', 'Booking · ARCHOFESA')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
    <div class="rounded-[36px] border border-[#e7e2d8] bg-white p-8 shadow-[0_24px_70px_-32px_rgba(15,23,42,0.16)] sm:p-10">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.24em] text-[#c9a227]">Booking Kamar</p>
                <h1 class="mt-3 text-3xl font-semibold text-[#1f2937]">Pilih Kamar Anda</h1>
                <p class="mt-3 max-w-2xl text-sm leading-7 text-[#4b5563]">Pilih kamar yang tersedia dan tentukan durasi sewa Anda. Semua kamar ditampilkan secara real-time dari sistem kami.</p>
            </div>
        </div>

        @if (session('success'))
            <div class="mt-8 rounded-2xl border border-green-200 bg-green-50 px-6 py-4 text-sm font-semibold text-green-800 shadow-sm">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mt-8 rounded-2xl border border-red-200 bg-red-50 px-6 py-4 text-sm font-semibold text-red-800 shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('booking.store') }}" class="mt-10 space-y-10">
            @csrf
            
            <section>
                <h2 class="text-xl font-semibold text-[#1f2937] mb-6">1. Pilih Kamar</h2>
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($rooms as $room)
                        @php
                            $isAvailable = $room->is_available;
                            
                            $badgeColor = $isAvailable 
                                ? 'bg-green-100 text-green-700 border-green-200' 
                                : 'bg-red-100 text-red-700 border-red-200';
                            
                            $statusLabel = $isAvailable ? 'Tersedia' : 'Terisi';
                        @endphp
                        <label class="relative flex cursor-pointer flex-col rounded-[24px] border-2 {{ $isAvailable ? 'border-[#e7e2d8] hover:border-[#c9a227] bg-white' : 'border-slate-100 bg-slate-50 opacity-60' }} p-5 shadow-sm transition focus-within:ring-2 focus-within:ring-[#c9a227] focus-within:ring-offset-2">
                            <input type="radio" name="room_id" value="{{ $room->id }}" class="peer sr-only" {{ $isAvailable ? 'required' : 'disabled' }}>
                            <div class="absolute -inset-0.5 rounded-[24px] border-2 border-[#c9a227] opacity-0 transition peer-checked:opacity-100"></div>
                            
                            <div class="relative z-10 flex flex-1 flex-col">
                                <div class="flex items-center justify-between gap-3">
                                    <h3 class="font-semibold text-[#1f2937]">Kamar {{ $room->room_code }}</h3>
                                    <span class="rounded-full border px-3 py-1 text-xs font-semibold uppercase tracking-wider {{ $badgeColor }}">
                                        {{ $statusLabel }}
                                    </span>
                                </div>
                                <p class="mt-2 text-sm text-[#6b7280]">{{ $room->price_monthly >= 1400000 ? 'Family Room' : 'Student Room' }}</p>
                                <div class="mt-auto pt-4">
                                    <p class="text-lg font-bold text-[#1f2937]">Rp{{ number_format($room->price_monthly, 0, ',', '.') }}<span class="text-sm font-normal text-[#6b7280]">/bulan</span></p>
                                    <button type="button" class="mt-4 inline-flex items-center justify-center rounded-full px-4 py-2 text-sm font-semibold transition {{ $isAvailable ? 'bg-[#c9a227] text-white hover:bg-[#b68d1f]' : 'bg-slate-200 text-slate-500 cursor-not-allowed' }}" {{ $isAvailable ? '' : 'disabled' }}>
                                        Pilih Kamar
                                    </button>
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>
            </section>

            <section class="rounded-[28px] border border-[#e7e2d8] bg-[#faf8f5] p-6 lg:p-8">
                <h2 class="text-xl font-semibold text-[#1f2937] mb-6">2. Tentukan Tanggal</h2>
                <div class="grid gap-6 md:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-[#374151] mb-2" for="move_in_date">Tanggal Masuk</label>
                        <input id="move_in_date" type="date" name="move_in_date" value="{{ old('move_in_date', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}" class="w-full rounded-2xl border border-[#e7e2d8] bg-white px-4 py-3 text-[#1f2937] focus:border-[#c9a227] focus:outline-none focus:ring-1 focus:ring-[#c9a227]" required>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-sm font-medium text-[#374151]" for="move_out_date">Tanggal Keluar</label>
                            <span class="inline-flex items-center rounded-full bg-[#c9a227]/10 px-2.5 py-0.5 text-xs font-semibold text-[#c9a227]">
                                Otomatis 1 Bulan
                            </span>
                        </div>
                        <input id="move_out_date" type="date" name="move_out_date" value="{{ old('move_out_date', \Carbon\Carbon::now()->addMonth()->format('Y-m-d')) }}" class="w-full rounded-2xl border border-[#e7e2d8] bg-slate-100 px-4 py-3 text-slate-500 cursor-not-allowed focus:outline-none" readonly required>
                        <p class="mt-2 text-xs text-[#6b7280]">Tanggal keluar terkunci secara otomatis 1 bulan dari tanggal masuk.</p>
                    </div>
                </div>
                <div class="mt-8 flex justify-end">
                    <button type="submit" class="rounded-full bg-[#c9a227] px-8 py-3 text-sm font-semibold text-white shadow-lg shadow-[#c9a227]/20 transition hover:bg-[#b68d1f]">
                        Ajukan Booking
                    </button>
                </div>
            </section>
        </form>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const moveInInput = document.getElementById('move_in_date');
                const moveOutInput = document.getElementById('move_out_date');

                function updateMoveOutDate() {
                    if (!moveInInput.value) return;

                    const parts = moveInInput.value.split('-');
                    if (parts.length !== 3) return;

                    const year = parseInt(parts[0], 10);
                    const month = parseInt(parts[1], 10) - 1; // 0-indexed month
                    const day = parseInt(parts[2], 10);

                    const targetDate = new Date(year, month + 1, day);

                    // Check for month overflow (e.g., Jan 31 -> Mar 3)
                    const expectedMonth = (month + 1) % 12;
                    if (targetDate.getMonth() !== expectedMonth) {
                        targetDate.setDate(0); // set to last day of previous month
                    }

                    const yyyy = targetDate.getFullYear();
                    const mm = String(targetDate.getMonth() + 1).padStart(2, '0');
                    const dd = String(targetDate.getDate()).padStart(2, '0');

                    moveOutInput.value = `${yyyy}-${mm}-${dd}`;
                }

                moveInInput.addEventListener('change', updateMoveOutDate);
                moveInInput.addEventListener('input', updateMoveOutDate);

                if (moveInInput.value) {
                    updateMoveOutDate();
                }

                // Handle room selection - prevent form submission on "Pilih Kamar" button click
                const roomButtons = document.querySelectorAll('button[type="button"]');
                roomButtons.forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        
                        // Find the radio input in the same label
                        const label = this.closest('label');
                        if (label) {
                            const radio = label.querySelector('input[type="radio"]');
                            if (radio && !radio.disabled) {
                                radio.checked = true;
                                // Trigger change event to update styling
                                radio.dispatchEvent(new Event('change', { bubbles: true }));
                            }
                        }
                    });
                });

                // Preserve room selection on form validation error
                const roomInputs = document.querySelectorAll('input[name="room_id"]');
                roomInputs.forEach(radio => {
                    radio.addEventListener('change', function() {
                        // Store selected room in sessionStorage
                        if (this.checked) {
                            sessionStorage.setItem('selectedRoomId', this.value);
                        }
                    });
                });

                // Restore selection from sessionStorage if exists
                const savedRoomId = sessionStorage.getItem('selectedRoomId');
                if (savedRoomId) {
                    const savedRadio = document.querySelector(`input[name="room_id"][value="${savedRoomId}"]`);
                    if (savedRadio && !savedRadio.disabled) {
                        savedRadio.checked = true;
                    }
                }

                // Clear sessionStorage on successful submission
                const form = document.querySelector('form[action*="booking.store"]');
                if (form) {
                    form.addEventListener('submit', function(e) {
                        // Check if a room is selected
                        const selectedRoom = document.querySelector('input[name="room_id"]:checked');
                        if (!selectedRoom) {
                            e.preventDefault();
                            alert('Silakan pilih kamar terlebih dahulu!');
                            return false;
                        }
                        
                        // Form is valid, clear storage after a short delay
                        setTimeout(() => {
                            sessionStorage.removeItem('selectedRoomId');
                        }, 100);
                    });
                }
            });
        </script>

        <section class="mt-16 border-t border-[#e7e2d8] pt-12">
            <h2 class="text-2xl font-semibold text-[#1f2937]">Riwayat Booking Anda</h2>
            <div class="mt-6 space-y-4">
                @forelse ($bookings as $booking)
                    <div class="flex flex-col gap-4 rounded-[24px] border border-[#e7e2d8] bg-white p-6 shadow-sm md:flex-row md:items-center md:justify-between">
                        <div>
                            <p class="font-semibold text-[#1f2937]">Kamar {{ $booking->room_code ?? ($booking->room->room_code ?? 'TBD') }}</p>
                            <p class="mt-1 text-sm text-[#4b5563]">{{ optional($booking->move_in_date)->format('d M Y') }} → {{ optional($booking->move_out_date)->format('d M Y') }}</p>
                        </div>
                        <div class="flex flex-col items-end gap-2 text-right">
                            <span class="rounded-full border border-[#e7e2d8] bg-[#faf8f5] px-4 py-1.5 text-xs font-semibold uppercase tracking-[0.2em] text-[#374151]">{{ ucfirst($booking->status) }}</span>
                            <p class="text-sm font-medium text-[#1f2937]">Rp{{ number_format($booking->monthly_rate, 0, ',', '.') }} / bln</p>
                        </div>
                    </div>
                @empty
                    <div class="rounded-[24px] border border-dashed border-[#e7e2d8] bg-[#faf8f5] p-10 text-center">
                        <p class="text-[#4b5563]">Belum ada riwayat booking.</p>
                    </div>
                @endforelse
            </div>
        </section>
    </div>
</div>
@endsection
