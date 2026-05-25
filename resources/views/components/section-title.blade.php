@props([
    'id'          => null,
    'kicker'      => null,
    'kickerColor' => 'orange',
    'sub'         => null,
    'dark'        => false,
    'level'       => 2,
])
<div class="space-y-3">
    @if($kicker)
    <x-kicker :color="$kickerColor">{{ $kicker }}</x-kicker>
    @endif
    @if($level === 1)
    <h1 @if($id) id="{{ $id }}" @endif
        class="font-serif font-bold {{ $dark ? 'text-blanc-pur' : 'text-noir-profond' }} leading-tight"
        style="font-size: clamp(2.5rem, 5vw, 4.5rem); letter-spacing: -0.02em; line-height: 1.05;">
        {!! $slot !!}
    </h1>
    @else
    <h2 @if($id) id="{{ $id }}" @endif
        class="font-serif font-bold {{ $dark ? 'text-blanc-pur' : 'text-noir-profond' }} leading-tight"
        style="font-size: clamp(2rem, 4vw, 3.5rem); letter-spacing: -0.02em; line-height: 1.1;">
        {!! $slot !!}
    </h2>
    @endif
    @if($sub)
    <p class="text-base leading-relaxed max-w-xl" style="{{ $dark ? 'color:rgba(250,250,248,.6)' : 'color:hsl(var(--gris-700))' }}">{{ $sub }}</p>
    @endif
</div>
