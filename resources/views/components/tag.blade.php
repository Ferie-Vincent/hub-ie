@props(['color' => 'orange'])
@php
$styles = [
    'orange' => 'background:hsl(var(--orange-soft-bg));color:hsl(var(--orange-brule))',
    'vert'   => 'background:hsl(var(--vert-soft-bg));color:hsl(141 100% 22%)',
    'sable'  => 'background:hsl(36 36% 88%);color:hsl(24 5% 33%)',
    'violet' => 'background:hsl(258 52% 93%);color:hsl(258 48% 32%)',
    'blue'   => 'background:hsl(210 86% 93%);color:hsl(210 70% 28%)',
];
$sty = $styles[$color] ?? $styles['orange'];
@endphp
<span {{ $attributes->merge(['class' => 'prog-tag']) }} style="{{ $sty }}">{{ $slot }}</span>
