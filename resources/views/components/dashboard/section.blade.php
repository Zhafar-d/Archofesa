<div class="rounded-2xl border border-[#e7e2d8] bg-white p-8 shadow-sm">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-[#1f2937]">{{ $title }}</h2>
        @if (isset($subtitle))
            <p class="mt-1 text-sm text-[#6b7280]">{{ $subtitle }}</p>
        @endif
    </div>
    {{ $slot }}
</div>
