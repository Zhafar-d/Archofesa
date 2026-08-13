@extends('layouts.admin')

@section('title', 'Tambah Kamar · Admin ARCHOFESA')

@section('content')
<div class="space-y-6 p-6 lg:p-8">
        <div class="rounded-[32px] bg-white p-8 shadow-sm">
            <h1 class="text-2xl font-semibold text-[#1f2937]">Tambah Kamar</h1>
            <form method="POST" action="{{ route('admin.kamar.store') }}" enctype="multipart/form-data" class="mt-8 space-y-6">
                @csrf
                <div class="grid gap-6 lg:grid-cols-2">
                    <label class="block">
                        <span class="text-sm font-medium text-[#374151]">Kode Kamar</span>
                        <input name="room_code" value="{{ old('room_code') }}" class="mt-2 w-full rounded-2xl border border-[#e7e2d8] bg-white px-4 py-3 text-sm text-[#374151]" required>
                    </label>
                    <label class="block">
                        <span class="text-sm font-medium text-[#374151]">Ukuran</span>
                        <input name="size" value="{{ old('size') }}" class="mt-2 w-full rounded-2xl border border-[#e7e2d8] bg-white px-4 py-3 text-sm text-[#374151]" required>
                    </label>
                    <label class="block">
                        <span class="text-sm font-medium text-[#374151]">Harga Bulanan</span>
                        <input type="number" step="0.01" name="price_monthly" value="{{ old('price_monthly') }}" class="mt-2 w-full rounded-2xl border border-[#e7e2d8] bg-white px-4 py-3 text-sm text-[#374151]" required>
                    </label>
                    <label class="block">
                        <span class="text-sm font-medium text-[#374151]">Status</span>
                        <select name="status" class="mt-2 w-full rounded-2xl border border-[#e7e2d8] bg-white px-4 py-3 text-sm text-[#374151]" required>
                            <option value="available">available</option>
                            <option value="occupied">occupied</option>
                            <option value="maintenance">maintenance</option>
                        </select>
                    </label>
                    <label class="block lg:col-span-2">
                        <span class="text-sm font-medium text-[#374151]">Deskripsi</span>
                        <textarea name="description" rows="4" class="mt-2 w-full rounded-2xl border border-[#e7e2d8] bg-white px-4 py-3 text-sm text-[#374151]"></textarea>
                    </label>
                    <label class="block lg:col-span-2">
                        <span class="text-sm font-medium text-[#374151]">Unggah Foto Kamar</span>
                        <input type="file" name="image_files[]" accept="image/*" multiple class="mt-2 w-full rounded-2xl border border-[#e7e2d8] bg-white px-4 py-3 text-sm text-[#374151]">
                        <p class="mt-2 text-xs text-slate-500">Unggah hingga 7 foto potret sekaligus atau isi URL gambar jika perlu.</p>
                    </label>
                    <label class="block lg:col-span-2">
                        <span class="text-sm font-medium text-[#374151]">URL Gambar</span>
                        <input name="image_url" value="{{ old('image_url') }}" class="mt-2 w-full rounded-2xl border border-[#e7e2d8] bg-white px-4 py-3 text-sm text-[#374151]">
                    </label>
                </div>
                <button class="rounded-full bg-[#c9a227] px-6 py-3 text-sm font-semibold text-white transition hover:bg-[#b68d1f]">Simpan Kamar</button>
            </form>
        </div>
</div>
@endsection
