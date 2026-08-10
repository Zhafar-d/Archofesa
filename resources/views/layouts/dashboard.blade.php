<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard · Kost The Archofesa Pedurungan Semarang')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full bg-slate-100 text-slate-900 antialiased">
    <div class="min-h-screen bg-slate-100">
        <div class="mx-auto flex min-h-screen max-w-7xl flex-col lg:flex-row">
            <aside class="w-full border-b border-slate-200 bg-white/80 p-6 backdrop-blur lg:w-72 lg:border-b-0 lg:border-r">
                <div class="mb-8">
                    <p class="text-sm font-semibold uppercase tracking-[0.28em] text-blue-600">ARCHOFESA KOST</p>
                    <h2 class="mt-2 text-2xl font-semibold text-slate-900">Operations Hub</h2>
                    <p class="mt-2 text-sm text-slate-500">Prepared for customer, owner, and administrator experiences.</p>
                </div>

                <nav class="space-y-2 text-sm font-medium text-slate-600">
                    <a href="{{ route('dashboard') }}" class="flex items-center rounded-2xl bg-blue-50 px-4 py-3 text-blue-700">Overview</a>
                    <a href="{{ route('rooms') }}" class="flex items-center rounded-2xl px-4 py-3 transition hover:bg-slate-100">Rooms</a>
                    <a href="{{ route('contact') }}" class="flex items-center rounded-2xl px-4 py-3 transition hover:bg-slate-100">Support</a>
                    <a href="{{ route('home') }}" class="flex items-center rounded-2xl px-4 py-3 transition hover:bg-slate-100">Public Site</a>
                </nav>
            </aside>

            <div class="flex-1 p-4 sm:p-6 lg:p-8">
                <header class="mb-6 flex flex-col gap-4 rounded-[28px] border border-slate-200 bg-white px-6 py-5 shadow-sm sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-slate-500">Welcome back</p>
                        <h1 class="text-2xl font-semibold text-slate-900">Dashboard Preview</h1>
                    </div>
                    <a href="{{ route('home') }}" class="inline-flex items-center rounded-full border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 transition hover:border-blue-400 hover:text-blue-600">View property</a>
                </header>

                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>
