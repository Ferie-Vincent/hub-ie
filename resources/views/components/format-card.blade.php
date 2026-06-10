@props([
    'num'         => '01',
    'kicker'      => '',
    'title'       => '',
    'description' => '',
    'iconPath'    => '',
    'accent'      => '--vert-ivoire',
])
<div class="v10-format-card group relative flex flex-col">
    <div class="v10-corner"></div>

    <div class="kicker-orange mb-4 rounded-full w-fit">{{ $kicker }}</div>

    <div class="v10-format-icon w-14 h-14 rounded-2xl flex items-center justify-center mb-5"
         style="background: hsl(var({{ $accent }}) / 0.12); border: 1px solid hsl(var({{ $accent }}) / 0.2);">
        <svg class="w-7 h-7" fill="none" stroke="hsl(var({{ $accent }}))"
             viewBox="0 0 24 24" stroke-width="1.5" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $iconPath }}"/>
        </svg>
    </div>

    <h3 class="font-serif font-bold text-noir-profond text-lg leading-tight mb-3">{{ $title }}</h3>
    <p class="text-sm text-gris-500 leading-relaxed">{{ $description }}</p>
</div>
