@extends('layouts.dashboard')

@section('title', 'Dashboard Preview · Kost The Archofesa Pedurungan Semarang')

@section('content')
<div class="grid gap-6 xl:grid-cols-[1.1fr_0.9fr]">
    <div class="rounded-[32px] border border-slate-200 bg-white p-6 shadow-sm">
        <p class="text-sm font-semibold uppercase tracking-[0.24em] text-blue-600">Customer dashboard</p>
        <h2 class="mt-3 text-2xl font-semibold text-slate-900">A premium overview for residents and future bookings.</h2>
        <p class="mt-4 text-sm leading-7 text-slate-600">This layout prepares customer, owner, and administrator experiences with a premium structure and room for future widgets.</p>

        <div class="mt-8 grid gap-4 md:grid-cols-2">
            <div class="rounded-[24px] bg-slate-50 p-5">
                <p class="text-sm text-slate-500">Current stay</p>
                <p class="mt-2 text-2xl font-semibold text-slate-900">Kos mahasiswa</p>
            </div>
            <div class="rounded-[24px] bg-slate-50 p-5">
                <p class="text-sm text-slate-500">Next payment</p>
                <p class="mt-2 text-2xl font-semibold text-slate-900">July 15</p>
            </div>
        </div>
    </div>

    <div class="rounded-[32px] border border-slate-200 bg-gradient-to-br from-blue-600 to-cyan-500 p-6 text-white shadow-sm">
        <p class="text-sm font-semibold uppercase tracking-[0.24em] text-blue-100">Owner & admin</p>
        <h2 class="mt-3 text-2xl font-semibold">Operations view ready for future modules.</h2>
        <p class="mt-4 text-sm leading-7 text-blue-50">The foundation supports inventory tracking, occupancy management, and role-based dashboards.</p>
    </div>
</div>
@endsection
