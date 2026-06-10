<div class="space-y-6">

    {{-- Header --}}
    <div>
        <h1 class="font-serif font-bold text-2xl text-noir-profond">Mon badge</h1>
        <p class="mt-1 text-sm text-gris-500">Votre badge d'accès pour l'événement Hub Import-Export 2026</p>
    </div>

    @if(session('error'))
    <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-600" role="alert">
        {{ session('error') }}
    </div>
    @endif

    {{-- Pas d'inscription --}}
    @if(! $enrollment)
    <div class="rounded-2xl bg-white shadow-card p-10 text-center">
        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl"
             style="background: hsl(var(--orange-soft-bg));">
            <svg class="h-7 w-7" style="color: hsl(var(--vert-ivoire));" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
            </svg>
        </div>
        <h2 class="mb-2 font-semibold text-noir-profond">Aucune inscription trouvée</h2>
        <p class="mb-6 text-sm text-gris-500">Vous n'avez pas encore de dossier d'inscription actif.</p>
        <a href="{{ route('candidature.index') }}" wire:navigate
           class="inline-flex items-center gap-2 rounded-xl px-5 py-2.5 text-sm font-semibold btn-fill transition">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            M'inscrire
        </a>
    </div>

    {{-- En liste d'attente --}}
    @elseif($isWaitlisted)
    <div class="rounded-2xl bg-white shadow-card overflow-hidden">
        <div class="flex items-center gap-3 border-b border-sable px-6 py-4">
            <div class="flex h-8 w-8 items-center justify-center rounded-lg"
                 style="background: hsl(var(--orange-soft-bg));">
                <svg class="h-4 w-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h2 class="font-semibold text-noir-profond text-sm">Inscription en attente</h2>
        </div>
        <div class="p-8 text-center">
            <span class="inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-sm font-semibold mb-6"
                  style="background: hsl(42 100% 94%); color: hsl(32 95% 44%);">
                <span class="relative flex h-2 w-2">
                    <span class="absolute inline-flex h-full w-full animate-ping rounded-full opacity-75 bg-amber-400"></span>
                    <span class="relative inline-flex h-2 w-2 rounded-full bg-amber-500"></span>
                </span>
                Liste d'attente
            </span>
            <p class="text-sm text-gris-500 max-w-sm mx-auto">
                Vous êtes en liste d'attente — votre place sera confirmée par l'administration.
                Vous recevrez un e-mail dès que votre inscription est validée.
            </p>
            @if($enrollment->workshop)
            <div class="mt-6 rounded-xl border border-sable bg-blanc-creme/60 px-5 py-4 text-sm text-left max-w-xs mx-auto">
                <p class="text-xs font-semibold uppercase tracking-wide text-gris-500 mb-1">Atelier demandé</p>
                <p class="font-medium text-noir-profond">{{ $enrollment->workshop->title }}</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Inscrit --}}
    @elseif($isEnrolled)
    <div class="grid gap-6 lg:grid-cols-2">

        {{-- Carte badge --}}
        <div class="rounded-2xl bg-white shadow-card overflow-hidden">
            <div class="flex items-center gap-3 border-b border-sable px-6 py-4">
                <div class="flex h-8 w-8 items-center justify-center rounded-lg"
                     style="background: hsl(var(--vert-soft-bg));">
                    <svg class="h-4 w-4" style="color: hsl(var(--vert-ivoire));" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002 2V7a2 2 0 00-2-2H5z"/>
                    </svg>
                </div>
                <h2 class="font-semibold text-noir-profond text-sm">Votre badge</h2>
                <span class="ml-auto inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-xs font-semibold"
                      style="background: hsl(var(--vert-soft-bg)); color: hsl(var(--vert-ivoire));">
                    <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    Confirmé
                </span>
            </div>

            <div class="p-6 space-y-5">
                {{-- Atelier --}}
                @if($enrollment->workshop)
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-gris-500 mb-1">Atelier</p>
                    <p class="font-medium text-noir-profond">{{ $enrollment->workshop->title }}</p>
                </div>
                @endif

                {{-- Date d'inscription --}}
                @if($enrollment->enrolled_at)
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-gris-500 mb-1">Inscrit le</p>
                    <p class="font-medium text-noir-profond">
                        {{ $enrollment->enrolled_at->translatedFormat('d F Y') }}
                    </p>
                </div>
                @endif

                {{-- Téléchargement badge PDF --}}
                <div class="pt-2 border-t border-sable">
                    @if($enrollment->hasBadge() && $enrollment->isBadgeValid())
                    <a href="{{ route('participant.badge.download') }}"
                       class="inline-flex w-full items-center justify-center gap-2 rounded-xl px-5 py-3 text-sm font-semibold btn-fill transition">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Télécharger mon badge PDF
                    </a>
                    @else
                    <div class="rounded-xl border border-sable bg-blanc-creme/60 px-4 py-3 text-sm text-gris-500 text-center">
                        <svg class="mx-auto mb-2 h-5 w-5 text-gris-500/50" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Votre badge est en cours de génération.<br>
                        <span class="text-xs">Il sera disponible prochainement.</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Carte code d'entrée --}}
        <div class="rounded-2xl bg-white shadow-card overflow-hidden">
            <div class="flex items-center gap-3 border-b border-sable px-6 py-4">
                <div class="flex h-8 w-8 items-center justify-center rounded-lg"
                     style="background: hsl(var(--orange-soft-bg));">
                    <svg class="h-4 w-4" style="color: hsl(var(--vert-ivoire));" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <h2 class="font-semibold text-noir-profond text-sm">Code d'entrée</h2>
            </div>

            <div class="p-6 flex flex-col items-center justify-center gap-4 min-h-[180px]">
                @if($enrollment->check_in_code)
                <p class="text-xs font-semibold uppercase tracking-wide text-gris-500">
                    Présentez ce code le jour J à l'accueil
                </p>
                <div class="rounded-2xl border-2 px-8 py-5 text-center"
                     style="border-color: hsl(var(--vert-ivoire)/30%); background: hsl(var(--vert-soft-bg));">
                    <span class="font-mono text-4xl font-bold tracking-[0.35em] select-all"
                          style="color: hsl(var(--vert-ivoire));">
                        {{ $enrollment->check_in_code }}
                    </span>
                </div>
                <p class="text-xs text-gris-500 text-center max-w-[220px]">
                    Ce code à 6 chiffres vous sera demandé en cas de difficulté avec le QR code.
                </p>
                @else
                <div class="text-center text-sm text-gris-500">
                    <svg class="mx-auto mb-2 h-8 w-8 text-gris-500/30" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Code en cours d'attribution.
                </div>
                @endif
            </div>
        </div>

    </div>
    @endif

</div>
