@props([
    'value'   => '',
    'label'   => '',
    'caption' => '',
    'color'   => 'orange',
])
<div {{ $attributes->merge(['class' => 'rounded-2xl bg-blanc-creme px-6 py-5 shadow-card hub-stat-lift']) }}>
    <p class="font-serif font-bold leading-none mb-2"
       style="font-size: 2.5rem; color: hsl(var(--{{ $color === 'vert' ? 'vert-ivoire' : 'vert-ivoire' }}));">
        {{ $value }}
    </p>
    <p class="font-semibold text-noir-profond text-sm leading-snug">{{ $label }}</p>
    @if($caption)
    <p class="text-xs mt-1" style="color:hsl(var(--gris-700))">{{ $caption }}</p>
    @endif
</div>
