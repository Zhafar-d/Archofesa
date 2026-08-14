@props(['href' => null, 'text' => 'Kembali'])

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => 'inline-flex items-center gap-2 text-sm font-medium text-gray-600 hover:text-[#c9a227] transition']) }}>
        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        {{ $text }}
    </a>
@else
    <button onclick="window.history.back()" {{ $attributes->merge(['class' => 'inline-flex items-center gap-2 text-sm font-medium text-gray-600 hover:text-[#c9a227] transition']) }}>
        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        {{ $text }}
    </button>
@endif
