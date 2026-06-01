<x-layouts.public
    :title="'Portfolio — Éditions précédentes du Hub Import-Export'"
    :description="'Retour sur les éditions passées du Hub Import-Export : participants, ateliers, thèmes et résultats clés.'"
>

<x-page-hero kicker="Portfolio">
    Nos <em class="font-fraunces italic text-orange-soft" style="font-variation-settings: 'opsz' 144, 'SOFT' 100;">éditions</em> passées.
</x-page-hero>

<div class="bg-blanc-creme pb-24">
    <div class="max-w-hub mx-auto px-6 pt-14">

        {{-- ── Édition en cours ──────────────────────────────────────────────── --}}
        @if($currentEdition)
        <div class="mb-16 rounded-2xl overflow-hidden"
             style="background: linear-gradient(135deg, hsl(var(--noir-profond)) 0%, hsl(var(--noir-profond)/0.92) 100%); border: 1px solid hsl(var(--orange-ivoire)/0.3);">
            <div class="px-8 py-10 flex flex-col md:flex-row md:items-center gap-6">
                <div class="flex-1 space-y-3">
                    <span class="inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-widest text-orange-ivoire">
                        <span class="relative flex h-2 w-2">
                            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-orange-ivoire opacity-60"></span>
                            <span class="relative inline-flex h-2 w-2 rounded-full bg-orange-ivoire"></span>
                        </span>
                        Édition en cours
                    </span>
                    <h2 class="text-2xl font-bold text-blanc-pur font-fraunces">{{ $currentEdition->title }}</h2>
                    @if($currentEdition->theme)
                        <p class="text-blanc-pur/60 text-sm italic">« {{ $currentEdition->theme }} »</p>
                    @endif
                    @if($currentEdition->event_starts_at)
                    <p class="text-blanc-pur/50 text-sm flex items-center gap-2">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        {{ $currentEdition->event_starts_at->locale('fr')->isoFormat('D MMMM') }}
                        @if($currentEdition->event_ends_at)
                            – {{ $currentEdition->event_ends_at->locale('fr')->isoFormat('D MMMM YYYY') }}
                        @endif
                        · {{ $currentEdition->location }}
                    </p>
                    @endif
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('inscription') }}" class="btn-fill whitespace-nowrap text-sm px-6 py-3">
                        Candidater
                    </a>
                    <a href="{{ route('programme') }}" class="glass whitespace-nowrap text-sm px-6 py-3 rounded-xl text-blanc-pur/80 hover:text-blanc-pur transition-colors">
                        Programme
                    </a>
                </div>
            </div>
        </div>
        @endif

        {{-- ── Éditions archivées ──────────────────────────────────────────── --}}
        @if($pastEditions->isEmpty())

            {{-- État vide — première édition, pas encore d'historique --}}
            <div class="text-center py-20">
                <div class="mx-auto mb-6 w-20 h-20 rounded-full flex items-center justify-center"
                     style="background: hsl(var(--noir-profond)/0.06);">
                    <svg class="w-9 h-9" style="color: hsl(var(--noir-profond)/0.25);" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-noir-profond mb-2">Cette édition sera la première !</h3>
                <p class="text-gris-500 max-w-md mx-auto text-sm leading-relaxed">
                    Le portfolio des éditions passées sera disponible ici après la clôture de l'édition {{ $currentEdition?->year ?? '' }}.
                    Revenez nous rendre visite.
                </p>
            </div>

        @else

            <div class="mb-8">
                <h2 class="text-xl font-bold text-noir-profond mb-1">Éditions précédentes</h2>
                <p class="text-sm text-gris-500">{{ $pastEditions->count() }} édition{{ $pastEditions->count() > 1 ? 's' : '' }} archivée{{ $pastEditions->count() > 1 ? 's' : '' }}</p>
            </div>

            {{-- Timeline / grille éditions --}}
            <div class="relative">
                {{-- Ligne verticale timeline --}}
                <div class="absolute left-0 top-0 bottom-0 w-px ml-4 hidden md:block"
                     style="background: linear-gradient(to bottom, hsl(var(--orange-ivoire)/0.4), transparent);" aria-hidden="true"></div>

                <div class="space-y-8 md:pl-14">
                    @foreach($pastEditions as $edition)
                    <div class="relative group">

                        {{-- Dot timeline --}}
                        <div class="absolute -left-14 top-6 hidden md:flex items-center justify-center w-8 h-8 rounded-full border-2 transition-colors"
                             style="background: hsl(var(--blanc-creme)); border-color: hsl(var(--orange-ivoire)/0.4);"
                             aria-hidden="true">
                            <span class="text-[10px] font-bold" style="color: hsl(var(--orange-ivoire));">
                                {{ substr($edition->year, 2) }}
                            </span>
                        </div>

                        {{-- Card édition --}}
                        <div class="rounded-2xl overflow-hidden bg-blanc-pur transition-shadow duration-300 group-hover:shadow-card"
                             style="border: 1px solid hsl(var(--noir-profond)/0.08);">

                            <div class="flex flex-col md:flex-row">

                                {{-- Bande gauche colorée --}}
                                <div class="md:w-2 shrink-0"
                                     style="background: hsl(var(--orange-ivoire));"></div>

                                <div class="flex-1 p-6 md:p-8">
                                    <div class="flex flex-col md:flex-row md:items-start gap-6">

                                        {{-- Infos principales --}}
                                        <div class="flex-1 space-y-3">
                                            <div class="flex items-center gap-3">
                                                <span class="text-xs font-bold uppercase tracking-widest px-3 py-1 rounded-full"
                                                      style="background: hsl(var(--orange-ivoire)/0.12); color: hsl(var(--orange-brule));">
                                                    {{ $edition->year }}
                                                </span>
                                                <span class="text-xs text-gris-500">
                                                    Archivée
                                                </span>
                                            </div>

                                            <h3 class="text-lg font-bold text-noir-profond font-fraunces">
                                                {{ $edition->title }}
                                            </h3>

                                            @if($edition->theme)
                                            <p class="text-sm text-gris-500 italic">
                                                « {{ $edition->theme }} »
                                            </p>
                                            @endif

                                            <div class="flex flex-wrap gap-4 text-xs text-gris-500 pt-1">
                                                @if($edition->event_starts_at)
                                                <span class="flex items-center gap-1.5">
                                                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                    {{ $edition->event_starts_at->locale('fr')->isoFormat('D MMMM') }}
                                                    @if($edition->event_ends_at)
                                                        – {{ $edition->event_ends_at->locale('fr')->isoFormat('D MMMM YYYY') }}
                                                    @endif
                                                </span>
                                                @endif

                                                <span class="flex items-center gap-1.5">
                                                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    </svg>
                                                    {{ $edition->location }}
                                                </span>
                                            </div>
                                        </div>

                                        {{-- Stats clés --}}
                                        <div class="grid grid-cols-2 gap-4 md:gap-6 md:text-right shrink-0">
                                            <div>
                                                <p class="text-2xl font-bold text-noir-profond tabular-nums">
                                                    {{ number_format($edition->accepted_count) }}
                                                </p>
                                                <p class="text-xs text-gris-500 mt-0.5">participants</p>
                                            </div>
                                            <div>
                                                <p class="text-2xl font-bold text-noir-profond tabular-nums">
                                                    {{ number_format($edition->applications_count) }}
                                                </p>
                                                <p class="text-xs text-gris-500 mt-0.5">candidatures</p>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                    @endforeach
                </div>
            </div>

        @endif

    </div>
</div>

</x-layouts.public>
