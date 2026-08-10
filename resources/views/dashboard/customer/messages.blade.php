@extends('layouts.dashboard-app')

@section('title', 'Pesan · ARCHOFESA KOST')

@section('content')
<div class="flex h-full flex-col">
    <div class="border-b border-slate-200 px-6 py-4">
        <h1 class="text-2xl font-bold text-slate-900">Pesan</h1>
        <p class="text-sm text-slate-600">Chat dengan pemilik dan pengelola properti</p>
    </div>

    <div class="flex flex-1 items-center justify-center p-8 text-center">
        <div class="max-w-md space-y-6">
            <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-blue-50 text-blue-600">
                <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
            </div>
            
            <div>
                <h2 class="text-2xl font-bold text-slate-900">Chat Langsung via Firebase</h2>
                <p class="mt-2 text-slate-600">Fitur pesan menggunakan chat real-time. Anda dapat berkomunikasi langsung dengan admin dan pemilik kos melalui layanan chat khusus kami yang ditenagai oleh Firebase.</p>
            </div>

            <div class="pt-4">
                <a href="#" class="inline-flex items-center justify-center rounded-full bg-[#c9a227] px-8 py-3 text-sm font-semibold text-white transition hover:bg-[#b68d1f]">
                    Buka Aplikasi Chat
                    <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                </a>
                <p class="mt-4 text-xs text-slate-500">*Aplikasi chat mungkin terbuka di tab baru</p>
            </div>
        </div>
    </div>
</div>
@endsection
