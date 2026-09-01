@extends('layouts.owner')

@section('title', 'Ubah Kamar · Pemilik ARCHOFESA')

@section('content')
<div class="px-6 py-8">
    <div class="mb-6">
        <a href="{{ route('owner.kamar.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#c9a227] hover:underline mb-2">
            &larr; Kembali ke Daftar Kamar
        </a>
        <h1 class="text-2xl font-bold text-slate-900">Ubah Data Kamar — {{ $room->room_code }}</h1>
        <p class="mt-1 text-sm text-slate-500">Perbarui spesifikasi, tarif sewa, status fisik, atau foto kamar.</p>
    </div>

    @if ($errors->any())
        <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-800">
            <p class="font-bold">Terjadi kesalahan input:</p>
            <ul class="mt-1.5 list-disc list-inside text-xs space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="rounded-[32px] border border-[#e7e2d8] bg-white p-8 shadow-sm">
        <form method="POST" action="{{ route('owner.kamar.update', $room) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid gap-6 lg:grid-cols-2">
                <div>
                    <label class="block text-sm font-semibold text-slate-700">Kode / Nomor Kamar</label>
                    <input name="room_code"
                           value="{{ old('room_code', $room->room_code) }}"
                           class="mt-2 w-full rounded-2xl border border-[#e7e2d8] bg-[#faf8f5] px-4 py-3.5 text-sm text-slate-900 focus:border-[#c9a227] focus:bg-white focus:outline-none"
                           required>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700">Ukuran Kamar</label>
                    <input name="size"
                           value="{{ old('size', $room->size) }}"
                           class="mt-2 w-full rounded-2xl border border-[#e7e2d8] bg-[#faf8f5] px-4 py-3.5 text-sm text-slate-900 focus:border-[#c9a227] focus:bg-white focus:outline-none"
                           required>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700">Tarif Bulanan (Rp)</label>
                    <input type="number"
                           name="price_monthly"
                           value="{{ old('price_monthly', $room->getRawOriginal('price_monthly')) }}"
                           min="0"
                           step="1000"
                           class="mt-2 w-full rounded-2xl border border-[#e7e2d8] bg-[#faf8f5] px-4 py-3.5 text-sm text-slate-900 focus:border-[#c9a227] focus:bg-white focus:outline-none"
                           required>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700">Status Fisik Kamar</label>
                    <select name="status"
                            class="mt-2 w-full rounded-2xl border border-[#e7e2d8] bg-[#faf8f5] px-4 py-3.5 text-sm text-slate-900 focus:border-[#c9a227] focus:bg-white focus:outline-none"
                            required>
                        <option value="available"    {{ old('status', $room->status) === 'available'    ? 'selected' : '' }}>Tersedia (available)</option>
                        <option value="occupied"     {{ old('status', $room->status) === 'occupied'     ? 'selected' : '' }}>Terisi / Dihuni (occupied)</option>
                        <option value="maintenance"  {{ old('status', $room->status) === 'maintenance'  ? 'selected' : '' }}>Dalam Perawatan (maintenance)</option>
                    </select>
                </div>

                <div class="lg:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700">Deskripsi & Fasilitas</label>
                    <textarea name="description" rows="4"
                              class="mt-2 w-full rounded-2xl border border-[#e7e2d8] bg-[#faf8f5] p-4 text-sm text-slate-900 focus:border-[#c9a227] focus:bg-white focus:outline-none">{{ old('description', $room->description) }}</textarea>
                </div>

                <div class="lg:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700">URL Gambar Utama</label>
                    <input name="image_url"
                           value="{{ old('image_url', $room->getRawOriginal('image_url')) }}"
                           placeholder="https://... atau kosongkan"
                           class="mt-2 w-full rounded-2xl border border-[#e7e2d8] bg-[#faf8f5] px-4 py-3.5 text-sm text-slate-900 focus:border-[#c9a227] focus:bg-white focus:outline-none">
                </div>

                <div class="lg:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700">Unggah Foto Baru (Opsional)</label>
                    <input type="file" name="image_files[]" accept="image/*" multiple
                           class="mt-2 w-full rounded-2xl border border-[#e7e2d8] bg-[#faf8f5] px-4 py-3 text-sm text-slate-700 file:mr-4 file:rounded-full file:border-0 file:bg-[#c9a227] file:px-4 file:py-2 file:text-xs file:font-bold file:text-white hover:file:bg-[#b68d1f]">
                    <p class="mt-1 text-xs text-slate-500">Maks 7 foto (jpg/png/webp). Mengunggah foto baru akan memperbarui galeri foto kamar.</p>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('owner.kamar.index') }}"
                   class="rounded-full border border-slate-200 px-6 py-3 text-sm font-semibold text-slate-600 hover:bg-slate-50">
                    Batal
                </a>
                <button type="submit"
                        class="rounded-full bg-[#c9a227] px-8 py-3 text-sm font-bold text-white shadow-md shadow-[#c9a227]/20 transition hover:bg-[#b68d1f]">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
