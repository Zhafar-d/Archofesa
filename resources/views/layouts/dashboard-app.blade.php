<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard · ARCHOFESA KOST')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="min-h-full bg-[#faf8f5] text-[#1f2937] antialiased">
    <div class="min-h-screen bg-[radial-gradient(circle_at_top_left,_rgba(201,162,39,0.12),_transparent_28%),linear-gradient(135deg,_#faf8f5_0%,_#fffdf9_100%)]">
        <x-dashboard.navbar />

        <main class="pb-10">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
