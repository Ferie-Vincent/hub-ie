@props([
    'href'     => '#',
    'size'     => 'md',
    'external' => false,
    'as'       => 'a',
])
@php
$padding = match($size) {
    'sm'  => 'px-5 py-2.5 text-sm',
    'lg'  => 'px-8 py-4 text-base',
    default => 'px-6 py-3 text-sm',
};
@endphp
@if($as === 'button')
<button {{ $attributes->merge(['class' => "btn-fill $padding"]) }}>
    <span>{{ $slot }}</span>
</button>
@else
<a
    href="{{ $href }}"
    @if($external) target="_blank" rel="noopener noreferrer" @endif
    {{ $attributes->merge(['class' => "btn-fill $padding"]) }}
>
    <span>{{ $slot }}</span>
</a>
@endif
