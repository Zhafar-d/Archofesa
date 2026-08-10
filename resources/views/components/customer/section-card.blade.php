@props(['title' => null, 'subtitle' => null, 'class' => ''])

<div {{ $attributes->merge(['class' => 'rounded-[32px] border border-[#e7e2d8] bg-white p-8 shadow-[0_20px_60px_-30px_rgba(15,23,42,0.16)] ' . $class]) }}>
    @if($title || $subtitle)
        <div class="mb-6">
            @if($title)
                <p class="text-sm font-semibold uppercase tracking-[0.24em] text-[#c9a227]">{{ $title }}</p>
            @endif
            @if($subtitle)
                <p class="mt-2 text-sm leading-7 text-[#4b5563]">{{ $subtitle }}</p>
            @endif
        </div>
    @endif

    {{ $slot }}
</div>
