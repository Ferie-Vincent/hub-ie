@props([
    'kicker'      => null,
    'kickerColor' => 'orange',
    'description' => null,
])
{{--
    En-tête de page secondaire.
    Drapeau CI en fond (filtré sombre), motif bogolan, texte blanc.
    Slot = contenu du h1 (peut contenir des <em>).
--}}
<section class="relative pt-32 pb-20 bg-noir-profond overflow-hidden flex flex-col justify-center" style="min-height: 500px;">

    {{-- Drapeau CI --}}
    <div class="absolute inset-0" aria-hidden="true">
        <img src="{{ asset('images/flag-ivory-coast.jpg') }}"
             alt=""
             class="w-full h-full object-cover object-center"
             loading="eager">
    </div>

    {{-- Overlay sombre (laisse les couleurs du drapeau légèrement transparaître) --}}
    <div class="absolute inset-0" aria-hidden="true"
         style="background: linear-gradient(135deg,
             hsl(var(--noir-profond) / 0.90) 0%,
             hsl(var(--noir-profond) / 0.80) 50%,
             hsl(var(--noir-profond) / 0.86) 100%);"></div>

    {{-- Filet de couleur en bas (orange → vert, couleurs du drapeau) --}}
    <div class="absolute bottom-0 left-0 right-0 h-[3px]" aria-hidden="true"
         style="background: linear-gradient(to right,
             hsl(var(--orange-ivoire)),
             hsl(var(--blanc-pur) / 0.4) 50%,
             hsl(var(--vert-ivoire)));"></div>

    <div class="relative z-10 max-w-hub mx-auto px-6">
        @if($kicker)
        <div class="kicker-{{ $kickerColor }} rounded-full mb-4 w-fit">{{ $kicker }}</div>
        @endif

        <h1 class="font-serif font-bold text-blanc-pur"
            style="font-size: clamp(2.5rem, 5vw, 4rem); letter-spacing: -0.02em; line-height: 1.05;">
            {!! $slot !!}
        </h1>

        @if($description)
        <p class="mt-4 text-blanc-pur/60 text-lg max-w-2xl leading-relaxed">{{ $description }}</p>
        @endif
    </div>
</section>
