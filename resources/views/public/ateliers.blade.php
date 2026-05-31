<x-layouts.public :title="'Ateliers — Hub Import-Export 2026'">

<x-page-hero
    kicker="Ateliers thématiques"
    description="Des sessions pratiques en groupes de 60, animées par des experts du commerce extérieur."
>
    Quatre <em class="font-fraunces italic text-orange-soft" style="font-variation-settings: 'opsz' 144, 'SOFT' 100;">ateliers</em> pour s'outiller.
</x-page-hero>

{{-- Section principale : fond clair --}}
<section class="relative overflow-hidden py-24 bg-blanc-creme">

    <div class="max-w-hub mx-auto px-6">

        {{-- Chapeau de section --}}
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-6 mb-14">
            <div>
                <div class="kicker-orange rounded-full mb-4 w-fit">22–25 juin 2026 · Abidjan</div>
                <h2 class="font-serif font-bold text-noir-profond" style="font-size: clamp(1.8rem, 3.5vw, 2.5rem); line-height: 1.1;">
                    Choisissez votre<br>domaine d'expertise
                </h2>
            </div>
            <p class="text-noir-profond/50 text-sm max-w-xs leading-relaxed sm:text-right">
                Chaque atelier se déroule en groupe restreint de 60 participants pour maximiser les échanges avec les experts.
            </p>
        </div>

        {{-- Grille des ateliers --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            @foreach($workshops as $atelier)
            <a href="{{ route('ateliers.show', $atelier['slug']) }}"
               class="group relative overflow-hidden rounded-2xl block transition-all duration-300 hover:-translate-y-1 bg-blanc-pur"
               style="border: 1px solid hsl(var(--noir-profond) / 0.08); box-shadow: 0 2px 12px hsl(var(--noir-profond) / 0.06);">

                {{-- Numéro fantôme --}}
                <div class="absolute -top-4 -right-2 font-serif font-bold leading-none select-none pointer-events-none"
                     style="font-size: 9rem; color: hsl(var(--noir-profond) / 0.04); letter-spacing: -0.04em; line-height: 1;">
                    {{ $atelier['num'] }}
                </div>

                {{-- Filet coloré en haut --}}
                <div class="absolute top-0 left-0 right-0 h-[3px] rounded-t-2xl transition-opacity duration-300"
                     style="background: linear-gradient(to right, hsl(var({{ $atelier['accent'] }})), transparent); opacity: 0.7;"></div>

                {{-- Contenu --}}
                <div class="relative z-10 p-8">

                    {{-- Icône + numéro --}}
                    <div class="flex items-start justify-between mb-6">
                        <div class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0"
                             style="background: {{ $atelier['accentBg'] }}; border: 1px solid {{ $atelier['accentBorder'] }};">
                            <svg class="w-5 h-5" fill="none" stroke="hsl(var({{ $atelier['accent'] }}))" viewBox="0 0 24 24" aria-hidden="true">
                                {!! $atelier['icon'] !!}
                            </svg>
                        </div>
                        <span class="text-xs font-mono font-bold tracking-widest" style="color: hsl(var({{ $atelier['accent'] }}) / 0.60);">
                            ATELIER {{ $atelier['num'] }}
                        </span>
                    </div>

                    {{-- Titre + tagline --}}
                    <h2 class="font-serif font-bold text-noir-profond text-xl mb-1 leading-tight">
                        {{ $atelier['titre'] }}
                    </h2>
                    <p class="text-sm font-semibold mb-4" style="color: hsl(var({{ $atelier['accent'] }}));">
                        {{ $atelier['tagline'] }}
                    </p>

                    {{-- Description --}}
                    <p class="text-noir-profond/60 text-sm leading-relaxed mb-6">
                        {{ $atelier['desc'] }}
                    </p>

                    {{-- Thèmes pills --}}
                    <div class="flex flex-wrap gap-2 mb-6">
                        @foreach($atelier['themes'] as $theme)
                        <span class="text-xs px-2.5 py-1 rounded-full font-medium"
                              style="background: {{ $atelier['accentBg'] }}; color: hsl(var({{ $atelier['accent'] }}) / 0.85); border: 1px solid {{ $atelier['accentBorder'] }};">
                            {{ $theme }}
                        </span>
                        @endforeach
                    </div>

                    {{-- CTA --}}
                    <span class="inline-flex items-center gap-2 text-sm font-semibold transition-colors duration-200"
                          style="color: hsl(var({{ $atelier['accent'] }}));">
                        Découvrir l'atelier
                        <svg class="w-4 h-4 transition-transform duration-200 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </span>
                </div>

                {{-- Hover glow overlay --}}
                <div class="absolute inset-0 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"
                     style="background: radial-gradient(ellipse at 30% 50%, hsl(var({{ $atelier['accent'] }}) / 0.05) 0%, transparent 65%);"></div>

            </a>
            @endforeach
        </div>

        {{-- CTA bas de section --}}
        <div class="mt-14 text-center">
            <p class="text-noir-profond/40 text-sm mb-4">Accès conditionné à la sélection de votre candidature</p>
            <a href="{{ route('inscription') }}" class="btn-fill px-7 py-3"><span>S'inscrire au Hub 2026</span></a>
        </div>

    </div>
</section>

</x-layouts.public>
