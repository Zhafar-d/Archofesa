@extends('layouts.admin')

@section('title', 'Ubah Kamar · Admin ARCHOFESA')

@section('content')
<div class="space-y-6 p-6 lg:p-8">

    @if ($errors->any())
        <div class="rounded-2xl border border-red-200 bg-red-50 px-6 py-4 text-sm text-red-700">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="rounded-2xl border border-green-200 bg-green-50 px-6 py-4 text-sm font-semibold text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <div class="rounded-[32px] bg-white p-8 shadow-sm">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.kamar.index') }}" class="text-sm text-[#6b7280] hover:text-[#c9a227]">← Kembali</a>
            <h1 class="text-2xl font-semibold text-[#1f2937]">Ubah Kamar — {{ $room->room_code }}</h1>
        </div>

        <form method="POST" action="{{ route('admin.kamar.update', $room) }}" enctype="multipart/form-data" class="mt-8 space-y-6">
            @csrf
            @method('PUT')

            <div class="grid gap-6 lg:grid-cols-2">

                <label class="block">
                    <span class="text-sm font-medium text-[#374151]">Kode Kamar</span>
                    <input name="room_code"
                           value="{{ old('room_code', $room->room_code) }}"
                           class="mt-2 w-full rounded-2xl border border-[#e7e2d8] bg-white px-4 py-3 text-sm text-[#374151] focus:border-[#c9a227] focus:outline-none"
                           required>
                </label>

                <label class="block">
                    <span class="text-sm font-medium text-[#374151]">Ukuran Kamar</span>
                    <input name="size"
                           value="{{ old('size', $room->size) }}"
                           class="mt-2 w-full rounded-2xl border border-[#e7e2d8] bg-white px-4 py-3 text-sm text-[#374151] focus:border-[#c9a227] focus:outline-none"
                           required>
                </label>

                <label class="block">
                    <span class="text-sm font-medium text-[#374151]">Harga Bulanan (Rp)</span>
                    <input type="number"
                           name="price_monthly"
                           value="{{ old('price_monthly', $room->getRawOriginal('price_monthly')) }}"
                           min="0"
                           step="1000"
                           class="mt-2 w-full rounded-2xl border border-[#e7e2d8] bg-white px-4 py-3 text-sm text-[#374151] focus:border-[#c9a227] focus:outline-none"
                           required>
                </label>

                <label class="block">
                    <span class="text-sm font-medium text-[#374151]">Status</span>
                    <select name="status"
                            class="mt-2 w-full rounded-2xl border border-[#e7e2d8] bg-white px-4 py-3 text-sm text-[#374151] focus:border-[#c9a227] focus:outline-none"
                            required>
                        <option value="available"    {{ old('status', $room->status) === 'available'    ? 'selected' : '' }}>Tersedia</option>
                        <option value="occupied"     {{ old('status', $room->status) === 'occupied'     ? 'selected' : '' }}>Terisi</option>
                        <option value="maintenance"  {{ old('status', $room->status) === 'maintenance'  ? 'selected' : '' }}>Perbaikan</option>
                    </select>
                </label>

                <label class="block lg:col-span-2">
                    <span class="text-sm font-medium text-[#374151]">Deskripsi</span>
                    <textarea name="description" rows="4"
                              class="mt-2 w-full rounded-2xl border border-[#e7e2d8] bg-white px-4 py-3 text-sm text-[#374151] focus:border-[#c9a227] focus:outline-none">{{ old('description', $room->description) }}</textarea>
                </label>

                {{-- URL Gambar (raw, tanpa accessor) --}}
                <label class="block lg:col-span-2">
                    <span class="text-sm font-medium text-[#374151]">URL Gambar Utama</span>
                    <input name="image_url"
                           value="{{ old('image_url', $room->getRawOriginal('image_url')) }}"
                           placeholder="https://... atau kosongkan"
                           class="mt-2 w-full rounded-2xl border border-[#e7e2d8] bg-white px-4 py-3 text-sm text-[#374151] focus:border-[#c9a227] focus:outline-none">
                    <p class="mt-1 text-xs text-[#9ca3af]">Isi URL foto jika tersedia secara online, atau kosongkan dan unggah file di bawah.</p>
                </label>

                {{-- Upload file --}}
                <label class="block lg:col-span-2">
                    <span class="text-sm font-medium text-[#374151]">Unggah Foto Baru (opsional)</span>
                    <input type="file" name="image_files[]" accept="image/*" multiple
                           class="mt-2 w-full rounded-2xl border border-[#e7e2d8] bg-white px-4 py-3 text-sm text-[#374151]">
                    <p class="mt-1 text-xs text-[#9ca3af]">Maks 7 foto (jpg/png/webp, maks 5MB per file). Mengisi ini akan mengganti foto yang lama.</p>
                </label>

            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="rounded-full bg-[#c9a227] px-6 py-3 text-sm font-semibold text-white transition hover:bg-[#b68d1f]">
                    Perbarui Kamar
                </button>
                <a href="{{ route('admin.kamar.index') }}"
                   class="rounded-full border border-[#e7e2d8] px-6 py-3 text-sm font-semibold text-[#374151] transition hover:border-[#c9a227] hover:text-[#c9a227]">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
