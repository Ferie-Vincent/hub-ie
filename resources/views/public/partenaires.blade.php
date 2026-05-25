<x-layouts.public :title="'Partenaires — Hub Import-Export 2026'" :darkHero="true">

<x-page-hero kicker="Partenaires" kickerColor="vert">
    Ils nous <em class="font-fraunces italic text-vert-soft" style="font-variation-settings: 'opsz' 144, 'SOFT' 100;">accompagnent.</em>
</x-page-hero>

@php
    $strategicPartners = [
        ['sigle' => 'TMA', 'name' => 'TradeMark Africa', 'role' => 'Partenaire stratégique', 'accent' => '--vert-ivoire'],
        ['sigle' => 'GIZ', 'name' => 'GIZ', 'role' => 'Partenaire stratégique', 'accent' => '--vert-ivoire'],
    ];

    $supportAgencies = [
        ['sigle' => 'ACIEx', 'name' => 'Agence Ivoirienne pour la Compétitivité à l\'Export', 'accent' => '--orange-ivoire'],
        ['sigle' => 'CNE', 'name' => 'Conseil National des Exportateurs', 'accent' => '--orange-ivoire'],
        ['sigle' => 'GUCE-CI', 'name' => 'Guichet Unique du Commerce Extérieur', 'accent' => '--orange-brule'],
        ['sigle' => 'CODINORM', 'name' => 'Côte d\'Ivoire Normalisation', 'accent' => '--orange-brule'],
        ['sigle' => 'CI-PME', 'name' => 'Agence CI-PME', 'accent' => '--vert-ivoire'],
    ];
@endphp

<div class="bg-blanc-creme text-noir-profond">
    <section class="max-w-hub mx-auto px-6 py-16 md:py-20">
        <div class="grid gap-8 lg:grid-cols-[0.85fr_1.15fr] lg:items-end mb-12 md:mb-16">
            <div>
                <span class="kicker-orange rounded-full">Écosystème</span>
                <h2 class="mt-5 font-serif font-bold leading-[1.08] tracking-[-0.02em]" style="font-size: clamp(2rem, 3.4vw, 3rem);">
                    Le rendez-vous stratégique des acteurs du commerce extérieur ivoirien.
                </h2>
            </div>

            <div class="lg:pl-8">
                <p class="max-w-2xl text-base md:text-lg leading-relaxed" style="color: hsl(var(--noir-profond) / 0.62);">
                    Sous le haut patronage de Monsieur le Ministre du Commerce, de l'Industrie et de l'Artisanat de la République de Côte d'Ivoire.
                </p>
            </div>
        </div>

        <div class="relative overflow-hidden rounded-3xl bg-noir-profond text-blanc-pur shadow-card">
            <div class="h-[3px]" style="background: linear-gradient(to right, hsl(var(--orange-ivoire)), hsl(var(--blanc-pur) / 0.45) 50%, hsl(var(--vert-ivoire)));"></div>

            <div class="relative grid lg:grid-cols-[0.9fr_1.1fr]">
                <div class="p-7 sm:p-10 lg:p-12 border-b lg:border-b-0 lg:border-r border-blanc-pur/10">
                    <p class="text-xs font-mono font-bold uppercase tracking-[0.22em] mb-7" style="color: hsl(var(--orange-soft));">
                        Organisateur
                    </p>

                    <div class="flex items-start gap-5">
                        <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl font-mono text-sm font-bold" style="background: hsl(var(--orange-ivoire));">
                            MCIA
                        </div>
                        <div>
                            <h3 class="font-serif text-2xl font-bold leading-tight">
                                Ministère du Commerce,<br>de l'Industrie et de l'Artisanat
                            </h3>
                            <span class="mt-5 inline-flex rounded-full px-3 py-1.5 text-xs font-semibold" style="background: hsl(var(--orange-ivoire) / 0.12); color: hsl(var(--orange-soft)); border: 1px solid hsl(var(--orange-ivoire) / 0.24);">
                                République de Côte d'Ivoire
                            </span>
                        </div>
                    </div>
                </div>

                <div class="p-7 sm:p-10 lg:p-12">
                    <p class="text-xs font-mono font-bold uppercase tracking-[0.22em] mb-7" style="color: hsl(var(--vert-soft));">
                        Maîtrise d'ouvrage opérationnelle
                    </p>

                    <div class="flex items-start gap-5">
                        <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl font-mono text-sm font-bold" style="background: hsl(var(--vert-ivoire));">
                            DGCE
                        </div>
                        <div>
                            <h3 class="font-serif text-2xl font-bold leading-tight">
                                Direction Générale du<br>Commerce Extérieur
                            </h3>
                            <span class="mt-5 inline-flex rounded-full px-3 py-1.5 text-xs font-semibold" style="background: hsl(var(--vert-ivoire) / 0.12); color: hsl(var(--vert-soft)); border: 1px solid hsl(var(--vert-ivoire) / 0.24);">
                                Hub Import-Export 2026
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="max-w-hub mx-auto px-6 pb-16 md:pb-20">
        <div class="grid gap-10 lg:grid-cols-[280px_1fr]">
            <aside class="hidden lg:block">
                <div class="sticky top-28 border-l pl-5" style="border-color: hsl(var(--noir-profond) / 0.12);">
                    <p class="text-xs font-mono font-bold uppercase tracking-[0.22em]" style="color: hsl(var(--gris-500));">Catégories</p>
                    <div class="mt-6 space-y-4 text-sm font-semibold">
                        <a href="#strategiques" class="block link-underline w-fit" style="color: hsl(var(--noir-profond) / 0.72);">Partenaires stratégiques</a>
                        <a href="#agences" class="block link-underline w-fit" style="color: hsl(var(--noir-profond) / 0.72);">Agences d'appui nationales</a>
                        <a href="#medias" class="block link-underline w-fit" style="color: hsl(var(--noir-profond) / 0.72);">Médias partenaires</a>
                    </div>
                </div>
            </aside>

            <div class="space-y-14">
                <div id="strategiques" class="scroll-mt-28">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between mb-6">
                        <div>
                            <span class="kicker-vert rounded-full">Stratégique</span>
                            <h2 class="mt-3 font-serif text-3xl font-bold tracking-[-0.02em]">Partenaires stratégiques</h2>
                        </div>
                        <div class="hidden sm:block h-px flex-1 max-w-xs" style="background: linear-gradient(to right, hsl(var(--vert-ivoire) / 0.45), transparent);"></div>
                    </div>

                    <div class="grid gap-5 md:grid-cols-2">
                        @foreach($strategicPartners as $partner)
                            <article class="group relative overflow-hidden rounded-2xl bg-blanc-pur p-7 shadow-card" style="border: 1px solid hsl(var(--noir-profond) / 0.08);">
                                <div class="absolute inset-x-0 top-0 h-1" style="background: hsl(var({{ $partner['accent'] }}));"></div>
                                <div class="flex items-start justify-between gap-5">
                                    <div class="flex items-start gap-4">
                                        <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl font-mono text-sm font-bold transition-transform duration-300 group-hover:-translate-y-1" style="background: hsl(var({{ $partner['accent'] }}) / 0.10); color: hsl(var({{ $partner['accent'] }})); border: 1px solid hsl(var({{ $partner['accent'] }}) / 0.22);">
                                            {{ $partner['sigle'] }}
                                        </div>
                                        <div>
                                            <p class="text-xs font-semibold uppercase tracking-[0.16em]" style="color: hsl(var({{ $partner['accent'] }}));">{{ $partner['role'] }}</p>
                                            <h3 class="mt-2 font-serif text-2xl font-bold leading-tight">{{ $partner['name'] }}</h3>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>

                <div id="agences" class="scroll-mt-28">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between mb-6">
                        <div>
                            <span class="kicker-orange rounded-full">National</span>
                            <h2 class="mt-3 font-serif text-3xl font-bold tracking-[-0.02em]">Agences d'appui nationales</h2>
                        </div>
                        <div class="hidden sm:block h-px flex-1 max-w-xs" style="background: linear-gradient(to right, hsl(var(--orange-ivoire) / 0.45), transparent);"></div>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                        @foreach($supportAgencies as $agency)
                            <article class="group relative min-h-[150px] rounded-2xl bg-blanc-pur p-5 overflow-hidden transition duration-300 hover:-translate-y-1 hover:shadow-card" style="border: 1px solid hsl(var(--noir-profond) / 0.08);">
                                {{-- Barre latérale — effet programme --}}
                                <div class="absolute left-0 top-1/2 -translate-y-1/2 w-[3px] rounded-r-full transition-all duration-300 opacity-0 group-hover:opacity-100"
                                     style="height: 55%; background: hsl(var({{ $agency['accent'] }}));"></div>
                                <div class="mb-5 flex items-center justify-between gap-4">
                                    <div class="flex h-11 w-11 items-center justify-center rounded-xl font-mono text-[11px] font-bold transition-transform duration-300 group-hover:-translate-y-0.5" style="background: hsl(var({{ $agency['accent'] }}) / 0.10); color: hsl(var({{ $agency['accent'] }})); border: 1px solid hsl(var({{ $agency['accent'] }}) / 0.20);">
                                        {{ Str::limit($agency['sigle'], 4, '') }}
                                    </div>
                                    <span class="font-mono text-xs font-bold" style="color: hsl(var({{ $agency['accent'] }}));">{{ $agency['sigle'] }}</span>
                                </div>

                                <h3 class="max-w-[18rem] text-sm font-semibold leading-snug">{{ $agency['name'] }}</h3>
                            </article>
                        @endforeach
                    </div>
                </div>

                <div id="medias" class="scroll-mt-28 rounded-2xl bg-blanc-pur p-6 sm:p-7" style="border: 1px dashed hsl(var(--noir-profond) / 0.16);">
                    <div class="flex items-center gap-4">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl" style="background: hsl(var(--noir-profond) / 0.06); color: hsl(var(--gris-500));">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h11l5 5v8a2 2 0 0 1-2 2Z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20V9h-5V5M7 10h3M7 14h8M7 17h8"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-mono font-bold uppercase tracking-[0.22em]" style="color: hsl(var(--gris-500));">Médias partenaires</p>
                            <h2 class="mt-1 font-serif text-2xl font-bold">Espace presse</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

</x-layouts.public>
