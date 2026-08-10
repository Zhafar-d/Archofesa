<div class="rounded-2xl border border-[#e7e2d8] bg-white p-6 shadow-sm">
    <p class="text-sm font-medium text-[#6b7280]">{{ $title }}</p>
    <p class="mt-2 text-4xl font-bold text-[#1f2937]">{{ $value }}</p>
    @if (isset($detail))
        <p class="mt-2 text-sm text-[#6b7280]">{{ $detail }}</p>
    @endif
</div>
