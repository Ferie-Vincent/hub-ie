<x-layouts.candidate title="Mon espace">

@php
    $user = auth()->user();
    $app  = $user->applications()
        ->whereNotIn('status', ['withdrawn', 'rejected'])
        ->with('documents')
        ->latest()
        ->first();
@endphp

{{-- Flash --}}
@if(session('status_flash'))
<div class="mb-6 rounded-xl bg-vert-soft-bg border border-vert-ivoire/30 px-5 py-3 flex items-center gap-3"
     role="alert">
    <svg class="w-5 h-5 flex-shrink-0" style="color:hsl(var(--vert-ivoire))" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
    </svg>
    <p class="text-sm font-medium text-vert-ivoire">{{ session('status_flash') }}</p>
</div>
@endif

<div class="space-y-6">

    {{-- ── Titre --}}
    <div>
        <p class="text-sm text-gris-500 mb-1">Bienvenue,</p>
        <h1 class="font-serif font-bold text-3xl text-noir-profond">
            {{ $user->first_name }} {{ $user->last_name }}
        </h1>
    </div>

    @if(! $app)
    {{-- ── Pas encore de candidature --}}
    <div class="rounded-2xl bg-white border-2 border-dashed border-sable p-8 text-center shadow-sm">
        <div class="w-14 h-14 mx-auto rounded-full flex items-center justify-center mb-4"
             style="background:hsl(var(--orange-soft-bg))">
            <svg class="w-7 h-7" style="color:hsl(var(--orange-ivoire))" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/>
            </svg>
        </div>
        <h2 class="font-serif font-bold text-xl text-noir-profond mb-2">Aucune candidature</h2>
        <p class="text-sm mb-6" style="color:hsl(var(--gris-700))">
            Vous n'avez pas encore soumis de candidature au Hub Import-Export 2026.
        </p>
        <a href="{{ route('candidature.index') }}" class="btn-fill px-7 py-2.5 text-sm inline-flex items-center gap-2">
            Déposer ma candidature
        </a>
    </div>

    @else
    {{-- ── Zone 1 : Statut --}}
    <div class="rounded-2xl bg-white shadow-card p-6">
        <div class="flex items-start justify-between gap-4 flex-wrap">
            <div>
                <p class="text-xs font-medium text-gris-500 uppercase tracking-widest mb-2">Statut du dossier</p>
                @php
                    $statusColors = [
                        'draft'        => ['bg' => 'bg-gray-100', 'text' => 'text-gray-600', 'dot' => 'bg-gray-400'],
                        'received'     => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'dot' => 'bg-blue-500'],
                        'incomplete'   => ['bg' => 'bg-amber-100', 'text' => 'text-amber-700', 'dot' => 'bg-amber-500'],
                        'eligible'     => ['bg' => 'bg-cyan-100', 'text' => 'text-cyan-700', 'dot' => 'bg-cyan-500'],
                        'under_review' => ['bg' => 'bg-indigo-100', 'text' => 'text-indigo-700', 'dot' => 'bg-indigo-500'],
                        'shortlisted'  => ['bg' => 'bg-purple-100', 'text' => 'text-purple-700', 'dot' => 'bg-purple-500'],
                        'accepted'     => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'dot' => 'bg-green-500'],
                        'waitlisted'   => ['bg' => 'bg-orange-100', 'text' => 'text-orange-700', 'dot' => 'bg-orange-500'],
                        'rejected'     => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'dot' => 'bg-red-500'],
                        'withdrawn'    => ['bg' => 'bg-gray-100', 'text' => 'text-gray-600', 'dot' => 'bg-gray-400'],
                    ];
                    $sc = $statusColors[$app->status->value] ?? $statusColors['draft'];
                @endphp
                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold {{ $sc['bg'] }} {{ $sc['text'] }}">
                    <span class="w-2 h-2 rounded-full {{ $sc['dot'] }}"></span>
                    {{ $app->status->label() }}
                </span>
            </div>
            <div class="text-right">
                <p class="text-xs text-gris-500">Référence</p>
                <p class="font-mono font-bold text-lg text-noir-profond tracking-widest">{{ $app->reference_code }}</p>
            </div>
        </div>

        @php
            $statusMessages = [
                'draft'        => 'Votre brouillon n\'a pas encore été soumis.',
                'received'     => 'Votre dossier est en cours d\'instruction administrative.',
                'incomplete'   => 'Votre dossier est incomplet. Veuillez le compléter avant la date limite.',
                'eligible'     => 'Votre dossier est recevable — en cours d\'évaluation par le comité de sélection.',
                'under_review' => 'Votre dossier est en cours d\'évaluation par le comité.',
                'shortlisted'  => 'Félicitations ! Vous êtes présélectionné(e). La décision finale sera communiquée sous 7 jours.',
                'accepted'     => 'Félicitations ! Vous avez été retenu(e) comme auditeur au Hub Import-Export 2026.',
                'waitlisted'   => 'Vous êtes sur liste d\'attente. Vous serez informé(e) en cas de place disponible.',
            ];
        @endphp
        @if(isset($statusMessages[$app->status->value]))
        <p class="mt-3 text-sm leading-relaxed" style="color:hsl(var(--gris-700))">
            {{ $statusMessages[$app->status->value] }}
        </p>
        @endif

        <p class="text-xs mt-3 text-gris-500">
            Dernière mise à jour : {{ $app->updated_at->diffForHumans() }}
        </p>

        {{-- CTA selon statut --}}
        <div class="mt-4 flex flex-wrap gap-3">
            @if($app->status->value === 'draft')
            <a href="{{ route('candidature.index') }}" class="btn-fill px-6 py-2 text-sm">
                Continuer ma candidature
            </a>
            @elseif($app->status->value === 'incomplete')
            <a href="{{ route('candidature.index') }}" class="btn-fill px-6 py-2 text-sm">
                Compléter mon dossier
            </a>
            @endif

            @if($app->status->canWithdraw())
            <form method="POST" action="{{ route('application.withdraw') }}"
                  onsubmit="return confirm('Êtes-vous sûr(e) de vouloir retirer votre candidature ? Cette action est irréversible.')">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="px-5 py-2 rounded-xl border border-red-200 text-red-600 text-sm font-medium hover:bg-red-50 transition-colors">
                    Retirer ma candidature
                </button>
            </form>
            @endif
        </div>
    </div>

    {{-- ── Zone 1b : Timeline progression --}}
    @php
        $timelineSteps = [
            ['key' => ['received'],               'label' => 'Reçue'],
            ['key' => ['eligible', 'incomplete'], 'label' => 'Instruction'],
            ['key' => ['under_review', 'shortlisted'], 'label' => 'Comité'],
            ['key' => ['accepted', 'waitlisted', 'rejected'], 'label' => 'Décision'],
        ];
        $currentStep = 0;
        foreach ($timelineSteps as $i => $ts) {
            if (in_array($app->status->value, $ts['key'])) { $currentStep = $i; break; }
        }
        $isTerminalPositive = in_array($app->status->value, ['accepted']);
        $isTerminalNegative = in_array($app->status->value, ['rejected', 'withdrawn']);
        $isWaitlisted       = $app->status->value === 'waitlisted';
    @endphp
    @if(! in_array($app->status->value, ['draft', 'withdrawn']))
    <div class="rounded-2xl bg-white shadow-card px-6 py-5">
        <p class="text-xs font-medium text-gris-500 uppercase tracking-widest mb-4">Avancement du dossier</p>
        <div class="flex items-center gap-0">
            @foreach($timelineSteps as $i => $ts)
            @php
                $done    = $i < $currentStep || ($i === $currentStep && $isTerminalPositive);
                $active  = $i === $currentStep && ! $isTerminalNegative;
                $isLast  = $i === count($timelineSteps) - 1;
                $neg     = $i === $currentStep && $isTerminalNegative;
                $wait    = $i === $currentStep && $isWaitlisted;
            @endphp
            <div class="flex items-center {{ $isLast ? '' : 'flex-1' }}">
                <div class="flex flex-col items-center gap-1.5">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 text-xs font-bold transition-all
                        @if($done) bg-vert-ivoire text-blanc-pur
                        @elseif($neg) bg-red-500 text-white
                        @elseif($wait) bg-orange-400 text-white
                        @elseif($active) bg-orange-ivoire text-blanc-pur ring-4 ring-orange-ivoire/20
                        @else bg-sable-doux text-gris-500
                        @endif">
                        @if($done)
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                        @elseif($neg)
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        @else
                        {{ $i + 1 }}
                        @endif
                    </div>
                    <span class="text-xs font-medium whitespace-nowrap
                        @if($done || $active) text-noir-profond
                        @else text-gris-500
                        @endif">{{ $ts['label'] }}</span>
                </div>
                @if(! $isLast)
                <div class="flex-1 h-0.5 mx-2 mt-[-1rem]
                    {{ $i < $currentStep ? 'bg-vert-ivoire' : 'bg-sable-doux' }}"></div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ── Zone 2 : Badge (si accepted) --}}
    @if($app->status->value === 'accepted')
    <div class="rounded-2xl shadow-card overflow-hidden"
         style="background:linear-gradient(135deg,hsl(var(--noir-profond)),hsl(24 9% 22%))">
        <div class="p-6 sm:p-8">
            <div class="flex items-center gap-2 mb-5">
                <span class="w-2 h-2 rounded-full bg-vert-ivoire animate-pulse-dot"></span>
                <p class="text-xs text-blanc-pur/60 uppercase tracking-widest font-medium">Votre badge d'entrée</p>
            </div>
            <div class="flex flex-col sm:flex-row items-center gap-8">
                {{-- QR code placeholder --}}
                <div class="w-40 h-40 bg-blanc-pur rounded-xl flex items-center justify-center flex-shrink-0 p-2">
                    @if($app->qr_token)
                    <img src="{{ route('application.qr', $app) }}"
                         alt="QR code d'accès — {{ $app->reference_code }}"
                         class="w-full h-full">
                    @else
                    <div class="w-full h-full bg-sable-doux/30 rounded-lg flex items-center justify-center">
                        <span class="text-xs text-gris-500 text-center px-2">QR code en cours de génération</span>
                    </div>
                    @endif
                </div>
                <div class="text-center sm:text-left">
                    @if($app->check_in_code)
                    <p class="text-xs text-blanc-pur/50 uppercase tracking-widest mb-2">Code d'accès numérique</p>
                    <p class="font-mono font-bold text-blanc-pur leading-none"
                       style="font-size:3rem;letter-spacing:0.4em">{{ $app->check_in_code }}</p>
                    @endif
                    @if($app->group_label)
                    <div class="mt-3 inline-block px-3 py-1 rounded-full text-sm font-bold"
                         style="background:hsl(var(--orange-soft-bg));color:hsl(var(--orange-brule))">
                        {{ $app->group_label }}
                    </div>
                    @endif
                    <p class="text-xs text-blanc-pur/40 mt-3">À présenter à l'entrée chaque jour</p>
                </div>
            </div>
            <div class="flex flex-wrap gap-3 mt-6">
                @if($app->badge_path)
                <a href="{{ route('application.badge.download', $app) }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-blanc-pur border border-blanc-pur/20 hover:bg-blanc-pur/10 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Télécharger mon badge
                </a>
                <a href="{{ route('application.convocation.download', $app) }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-blanc-pur border border-blanc-pur/20 hover:bg-blanc-pur/10 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Télécharger ma convocation
                </a>
                @else
                <p class="text-xs text-blanc-pur/40 italic">Votre badge sera disponible au téléchargement sous 24h.</p>
                @endif
            </div>
        </div>
    </div>
    @endif

    {{-- ── Zone 3 : Récapitulatif --}}
    <div class="rounded-2xl bg-white shadow-card p-6">
        <div class="flex items-center justify-between mb-5">
            <h2 class="font-serif font-bold text-lg text-noir-profond">Récapitulatif du dossier</h2>
            @if($app->status->value === 'draft')
            <a href="{{ route('candidature.index') }}"
               class="text-sm text-orange-ivoire link-underline font-medium">
                Modifier
            </a>
            @endif
        </div>
        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-4 text-sm">
            <div>
                <dt class="text-xs font-medium text-gris-500 uppercase tracking-wide mb-0.5">Nom complet</dt>
                <dd class="font-medium text-noir-profond">{{ $user->first_name }} {{ $user->last_name }}</dd>
            </div>
            <div>
                <dt class="text-xs font-medium text-gris-500 uppercase tracking-wide mb-0.5">Email</dt>
                <dd class="font-medium text-noir-profond">{{ $user->email }}</dd>
            </div>
            @if($user->phone)
            <div>
                <dt class="text-xs font-medium text-gris-500 uppercase tracking-wide mb-0.5">Téléphone</dt>
                <dd class="font-medium text-noir-profond">{{ $user->phone }}</dd>
            </div>
            @endif
            @if($user->city)
            <div>
                <dt class="text-xs font-medium text-gris-500 uppercase tracking-wide mb-0.5">Ville</dt>
                <dd class="font-medium text-noir-profond">{{ $user->city }}, {{ $user->country }}</dd>
            </div>
            @endif
            @if($app->category)
            <div>
                <dt class="text-xs font-medium text-gris-500 uppercase tracking-wide mb-0.5">Catégorie</dt>
                <dd class="font-medium text-noir-profond">{{ $app->category->label() }}</dd>
            </div>
            @endif
            @if($app->organization_name)
            <div>
                <dt class="text-xs font-medium text-gris-500 uppercase tracking-wide mb-0.5">Organisation</dt>
                <dd class="font-medium text-noir-profond">{{ $app->organization_name }}</dd>
            </div>
            @endif
            @if($app->position)
            <div>
                <dt class="text-xs font-medium text-gris-500 uppercase tracking-wide mb-0.5">Fonction</dt>
                <dd class="font-medium text-noir-profond">{{ $app->position }}</dd>
            </div>
            @endif
            @if($app->documents->isNotEmpty())
            <div class="sm:col-span-2">
                <dt class="text-xs font-medium text-gris-500 uppercase tracking-wide mb-1">Pièces jointes</dt>
                <dd class="flex flex-wrap gap-2">
                    @foreach($app->documents as $doc)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium"
                          style="background:hsl(var(--vert-soft-bg));color:hsl(var(--vert-ivoire))">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        {{ $doc->type->label() }}
                    </span>
                    @endforeach
                </dd>
            </div>
            @endif
        </dl>
    </div>

    @endif {{-- /app exists --}}
</div>

</x-layouts.candidate>
