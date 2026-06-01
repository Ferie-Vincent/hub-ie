<x-layouts.candidate title="Mon espace">

@php
    $user = auth()->user();
    $app  = $user->applications()
        ->whereNotIn('status', ['withdrawn', 'rejected'])
        ->with(['documents', 'workshops', 'edition'])
        ->latest()
        ->first();

    $isAccepted = $app && $app->status->value === 'accepted';

    // Accès rapide stats (accepted uniquement)
    $unreadCount = 0;
    $docCount    = 0;
    $newDocCount = 0;
    $groupMembers = collect();

    if ($isAccepted) {
        $workshopIds = $app->workshops->pluck('id')->toArray();

        $convIds = \App\Models\Conversation::where('application_id', $app->id)->pluck('id');
        $unreadCount = \App\Models\ConversationMessage::whereIn('conversation_id', $convIds)
            ->where('sender_id', '!=', auth()->id())
            ->whereNull('read_at')
            ->count();

        $docCount = \App\Models\WorkshopCourseFile::where('is_published', true)
            ->whereIn('workshop_id', $workshopIds)->count();

        $newDocCount = \App\Models\WorkshopCourseFile::where('is_published', true)
            ->whereIn('workshop_id', $workshopIds)
            ->where('created_at', '>=', now()->subDays(7))->count();

        if ($app->group_label) {
            $groupMembers = \App\Models\User::select('first_name', 'last_name')
                ->join('applications', 'applications.user_id', '=', 'users.id')
                ->where('applications.group_label', $app->group_label)
                ->where('applications.edition_id', $app->edition_id)
                ->where('applications.status', 'accepted')
                ->where('users.id', '!=', auth()->id())
                ->orderBy('users.last_name')->orderBy('users.first_name')
                ->get();
        }
    }

    $statusColors = [
        'draft'        => ['pill' => 'bg-gray-100 text-gray-600',       'dot' => 'bg-gray-400'],
        'received'     => ['pill' => 'bg-blue-100 text-blue-700',        'dot' => 'bg-blue-500'],
        'incomplete'   => ['pill' => 'bg-amber-100 text-amber-700',      'dot' => 'bg-amber-500'],
        'eligible'     => ['pill' => 'bg-cyan-100 text-cyan-700',        'dot' => 'bg-cyan-500'],
        'under_review' => ['pill' => 'bg-indigo-100 text-indigo-700',    'dot' => 'bg-indigo-500'],
        'shortlisted'  => ['pill' => 'bg-purple-100 text-purple-700',    'dot' => 'bg-purple-500'],
        'accepted'     => ['pill' => 'bg-emerald-100 text-emerald-700',  'dot' => 'bg-emerald-500'],
        'waitlisted'   => ['pill' => 'bg-orange-100 text-orange-700',    'dot' => 'bg-orange-500'],
        'rejected'     => ['pill' => 'bg-red-100 text-red-700',          'dot' => 'bg-red-500'],
        'withdrawn'    => ['pill' => 'bg-gray-100 text-gray-600',        'dot' => 'bg-gray-400'],
    ];
    $sc = $app ? ($statusColors[$app->status->value] ?? $statusColors['draft']) : null;

    $statusMsg = $app ? [
        'draft'        => 'Votre brouillon n\'est pas encore soumis.',
        'received'     => 'Dossier en cours d\'instruction administrative.',
        'incomplete'   => 'Dossier incomplet — veuillez le compléter avant la date limite.',
        'eligible'     => 'Dossier recevable — en cours d\'évaluation par le comité.',
        'under_review' => 'En cours d\'évaluation par le comité de sélection.',
        'shortlisted'  => 'Présélectionné(e) — décision finale sous 7 jours.',
        'accepted'     => 'Vous êtes retenu(e) comme auditeur au Hub Import-Export 2026 !',
        'waitlisted'   => 'Sur liste d\'attente — vous serez notifié(e) en cas de place.',
    ][$app->status->value] ?? '' : '';
@endphp

<div class="space-y-8">

{{-- Flash --}}
@if(session('status_flash'))
<div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-3 flex items-center gap-3" role="alert">
    <svg class="w-5 h-5 shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
    </svg>
    <p class="text-sm font-medium text-emerald-700">{{ session('status_flash') }}</p>
</div>
@endif

{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- HERO BANNER                                                 --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
<div class="relative overflow-hidden rounded-3xl px-8 py-10
    {{ $isAccepted
        ? 'text-white'
        : 'bg-white border border-gray-100 shadow-sm' }}"
     style="{{ $isAccepted
        ? 'background: linear-gradient(135deg, hsl(var(--noir-profond)) 0%, hsl(24 30% 18%) 60%, hsl(var(--noir-profond)) 100%);'
        : '' }}">

    @if($isAccepted)
    {{-- Motif bogolan décoratif --}}
    <div class="absolute inset-0 opacity-[0.04]" aria-hidden="true">
        <svg width="100%" height="100%"><rect width="100%" height="100%" fill="url(#bogolan-pattern)"/></svg>
    </div>
    {{-- Accent radial orange --}}
    <div class="absolute -top-16 -right-16 h-56 w-56 rounded-full opacity-20 blur-3xl pointer-events-none"
         style="background: hsl(var(--orange-ivoire));" aria-hidden="true"></div>
    <div class="absolute -bottom-10 -left-10 h-40 w-40 rounded-full opacity-10 blur-2xl pointer-events-none"
         style="background: hsl(var(--vert-ivoire));" aria-hidden="true"></div>
    @endif

    <div class="relative flex flex-col sm:flex-row sm:items-center gap-5">
        {{-- Avatar initiales --}}
        <div class="h-16 w-16 shrink-0 rounded-2xl flex items-center justify-center text-xl font-black shadow-lg"
             style="{{ $isAccepted
                 ? 'background: hsl(var(--orange-ivoire)); color: white;'
                 : 'background: hsl(var(--orange-soft-bg)); color: hsl(var(--orange-brule));' }}">
            {{ mb_strtoupper(mb_substr($user->first_name, 0, 1) . mb_substr($user->last_name, 0, 1)) }}
        </div>

        <div class="flex-1 min-w-0">
            <p class="text-xs font-semibold uppercase tracking-widest mb-1
                {{ $isAccepted ? 'text-white/50' : 'text-gris-500' }}">
                Bienvenue dans votre espace
            </p>
            <h1 class="font-serif font-bold text-2xl sm:text-3xl truncate
                {{ $isAccepted ? 'text-white' : 'text-noir-profond' }}">
                {{ $user->first_name }} {{ $user->last_name }}
            </h1>
            @if($app)
            <div class="flex flex-wrap items-center gap-3 mt-2">
                <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-semibold {{ $sc['pill'] }}">
                    <span class="h-1.5 w-1.5 rounded-full {{ $sc['dot'] }}
                        {{ $isAccepted ? 'animate-pulse' : '' }}"></span>
                    {{ $app->status->label() }}
                </span>
                <span class="font-mono text-xs {{ $isAccepted ? 'text-white/60' : 'text-gris-500' }}">
                    {{ $app->reference_code }}
                </span>
                @if($isAccepted && $app->group_label)
                <span class="rounded-full px-3 py-1 text-xs font-bold"
                      style="background: hsl(var(--orange-ivoire)/0.2); color: hsl(var(--orange-ivoire));">
                    Groupe {{ $app->group_label }}
                </span>
                @endif
            </div>
            @endif
        </div>

        @if($isAccepted)
        {{-- Badge mini statut --}}
        <div class="shrink-0 text-center">
            <div class="rounded-2xl px-5 py-3 text-center"
                 style="background: hsl(var(--orange-ivoire)/0.15); border: 1px solid hsl(var(--orange-ivoire)/0.3);">
                <p class="font-mono font-black text-2xl tracking-[0.3em] text-white">{{ $app->check_in_code }}</p>
                <p class="text-[10px] text-white/40 uppercase tracking-widest mt-0.5">Code d'accès</p>
            </div>
        </div>
        @endif
    </div>

    @if($statusMsg)
    <p class="relative mt-4 text-sm {{ $isAccepted ? 'text-white/70' : 'text-gris-500' }}">
        {{ $statusMsg }}
    </p>
    @endif

    @if(!$isAccepted && $app)
    <div class="mt-4 flex flex-wrap gap-3">
        @if(in_array($app->status->value, ['draft', 'incomplete']))
        <a href="{{ route('candidature.index') }}" class="btn-fill px-5 py-2 text-sm inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            {{ $app->status->value === 'draft' ? 'Continuer ma candidature' : 'Compléter mon dossier' }}
        </a>
        @endif
        @if($app->status->canWithdraw())
        <form method="POST" action="{{ route('application.withdraw') }}"
              onsubmit="return confirm('Êtes-vous sûr(e) ? Cette action est irréversible.')">
            @csrf @method('DELETE')
            <button type="submit" class="px-5 py-2 rounded-xl border border-red-200 text-red-600 text-sm font-medium hover:bg-red-50 transition-colors cursor-pointer">
                Retirer ma candidature
            </button>
        </form>
        @endif
    </div>
    @endif
</div>

@if(!$app)
{{-- Pas de candidature --}}
<div class="rounded-3xl bg-white border-2 border-dashed border-gray-200 p-12 text-center shadow-sm">
    <div class="w-16 h-16 mx-auto rounded-2xl flex items-center justify-center mb-5"
         style="background: hsl(var(--orange-soft-bg));">
        <svg class="w-8 h-8" style="color: hsl(var(--orange-ivoire));" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
    </div>
    <h2 class="font-serif font-bold text-xl text-noir-profond mb-2">Déposez votre candidature</h2>
    <p class="text-sm text-gris-500 mb-6 max-w-sm mx-auto">Rejoignez les acteurs du commerce extérieur ivoirien au Hub Import-Export 2026.</p>
    <a href="{{ route('candidature.index') }}" class="btn-fill px-8 py-3 text-sm inline-flex items-center gap-2">
        Déposer ma candidature
    </a>
</div>
@else

{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- PROGRESSION (hors draft/withdrawn)                          --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
@if(!in_array($app->status->value, ['draft', 'withdrawn']))
@php
    $steps = [
        ['label' => 'Reçue',      'keys' => ['received']],
        ['label' => 'Instruction','keys' => ['eligible','incomplete']],
        ['label' => 'Comité',     'keys' => ['under_review','shortlisted']],
        ['label' => 'Décision',   'keys' => ['accepted','waitlisted','rejected']],
    ];
    $curStep = 0;
    foreach ($steps as $i => $s) {
        if (in_array($app->status->value, $s['keys'])) { $curStep = $i; break; }
    }
    $isPos = $app->status->value === 'accepted';
    $isNeg = in_array($app->status->value, ['rejected','withdrawn']);
@endphp
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm px-6 py-5">
    <p class="text-xs font-semibold uppercase tracking-widest text-gris-500 mb-4">Avancement du dossier</p>
    <div class="flex items-center">
        @foreach($steps as $i => $s)
        @php
            $done   = $i < $curStep || ($i === $curStep && $isPos);
            $active = $i === $curStep && !$isNeg;
            $neg    = $i === $curStep && $isNeg;
            $isLast = $i === count($steps) - 1;
        @endphp
        <div class="flex items-center {{ $isLast ? '' : 'flex-1' }}">
            <div class="flex flex-col items-center gap-1.5">
                <div class="w-9 h-9 rounded-full flex items-center justify-center text-xs font-bold transition-all duration-300
                    @if($done) text-white @elseif($neg) bg-red-500 text-white @elseif($active) text-white ring-4 @else bg-gray-100 text-gray-400 @endif"
                     style="@if($done) background:hsl(var(--vert-ivoire)); @elseif($active) background:hsl(var(--orange-ivoire)); ring-color:hsl(var(--orange-ivoire)/0.2); @endif">
                    @if($done)
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    @elseif($neg)
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    @else {{ $i + 1 }}
                    @endif
                </div>
                <span class="text-xs font-medium whitespace-nowrap {{ $done || $active ? 'text-noir-profond' : 'text-gray-400' }}">
                    {{ $s['label'] }}
                </span>
            </div>
            @if(!$isLast)
            <div class="flex-1 h-0.5 mx-3 mt-[-1.25rem] rounded-full transition-all duration-500
                {{ $i < $curStep ? '' : 'bg-gray-200' }}"
                 style="{{ $i < $curStep ? 'background:hsl(var(--vert-ivoire));' : '' }}"></div>
            @endif
        </div>
        @endforeach
    </div>
    <p class="text-xs text-right text-gris-500 mt-3">Dernière maj : {{ $app->updated_at->diffForHumans() }}</p>
</div>
@endif

{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- BENTO GRID — contenu principal                             --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

    @if($isAccepted)

    {{-- ── BADGE QR (2 cols) ─────────────────────────────────── --}}
    <div class="sm:col-span-2 lg:row-span-2 rounded-3xl overflow-hidden shadow-lg"
         style="background: linear-gradient(135deg, hsl(var(--noir-profond)) 0%, hsl(24 20% 16%) 100%);">
        <div class="px-8 py-8">
            <div class="flex items-start gap-2 mb-6">
                <span class="inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-widest"
                      style="color: hsl(var(--orange-ivoire));">
                    <span class="h-2 w-2 rounded-full animate-pulse" style="background:hsl(var(--orange-ivoire));"></span>
                    Badge d'entrée
                </span>
            </div>
            <div class="flex flex-col sm:flex-row items-center gap-8">
                {{-- QR --}}
                <div class="bg-white rounded-2xl p-3 shadow-2xl shrink-0">
                    @if($app->qr_token)
                    <img src="{{ route('application.qr', $app) }}" alt="QR code — {{ $app->reference_code }}"
                         class="w-36 h-36 sm:w-40 sm:h-40">
                    @else
                    <div class="w-36 h-36 sm:w-40 sm:h-40 flex items-center justify-center rounded-xl"
                         style="background:hsl(var(--sable-doux)/0.3);">
                        <span class="text-xs text-gris-500 text-center px-3">QR code en génération…</span>
                    </div>
                    @endif
                </div>
                {{-- Infos badge --}}
                <div class="text-center sm:text-left space-y-4">
                    <div>
                        <p class="text-xs uppercase tracking-widest mb-1" style="color:hsl(var(--blanc-pur)/0.4);">Code numérique</p>
                        <p class="font-mono font-black text-blanc-pur tracking-[0.4em]" style="font-size: 2.5rem; line-height:1;">
                            {{ $app->check_in_code }}
                        </p>
                    </div>
                    @if($app->group_label)
                    <div class="inline-block rounded-2xl px-6 py-2 font-bold text-sm"
                         style="background:hsl(var(--orange-ivoire)/0.15); color:hsl(var(--orange-ivoire)); border:1px solid hsl(var(--orange-ivoire)/0.3);">
                        Groupe {{ $app->group_label }}
                    </div>
                    @endif
                    <p class="text-xs" style="color:hsl(var(--blanc-pur)/0.35);">À présenter à l'entrée chaque jour</p>
                    @if($app->badge_path)
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('application.badge.download', $app) }}"
                           class="inline-flex items-center gap-2 rounded-xl px-4 py-2 text-xs font-semibold text-blanc-pur border transition-colors hover:bg-blanc-pur/10 cursor-pointer"
                           style="border-color:hsl(var(--blanc-pur)/0.2);">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Mon badge
                        </a>
                        <a href="{{ route('application.convocation.download', $app) }}"
                           class="inline-flex items-center gap-2 rounded-xl px-4 py-2 text-xs font-semibold text-blanc-pur border transition-colors hover:bg-blanc-pur/10 cursor-pointer"
                           style="border-color:hsl(var(--blanc-pur)/0.2);">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Convocation
                        </a>
                    </div>
                    @else
                    <p class="text-xs italic" style="color:hsl(var(--blanc-pur)/0.3);">Badge disponible sous 24h</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ── CARD MON ATELIER ──────────────────────────────────── --}}
    @php $enrolledWorkshop = $app->workshops->first(); @endphp
    @if($enrolledWorkshop)
    <div class="rounded-3xl bg-white border border-gray-100 shadow-sm p-6 flex flex-col gap-4">
        <div class="flex items-start justify-between">
            <div class="h-12 w-12 rounded-2xl flex items-center justify-center"
                 style="background: hsl(var(--orange-soft-bg));">
                <svg class="w-6 h-6" style="color:hsl(var(--orange-ivoire));" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <span class="rounded-full px-2.5 py-0.5 text-[11px] font-bold"
                  style="background:hsl(var(--orange-ivoire)/0.12);color:hsl(var(--orange-brule));">
                Mon atelier
            </span>
        </div>
        <div>
            <p class="text-base font-bold text-noir-profond leading-snug">{{ $enrolledWorkshop->title }}</p>
            @if($enrolledWorkshop->short_description)
            <p class="text-xs text-gris-500 mt-1 leading-relaxed line-clamp-2">{{ $enrolledWorkshop->short_description }}</p>
            @endif
        </div>
        <a href="{{ route('participant.downloads') }}"
           class="inline-flex items-center gap-1 text-xs font-semibold mt-auto cursor-pointer"
           style="color:hsl(var(--orange-ivoire));">
            Voir les documents
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>
    @endif

    {{-- Card Documents supprimée — accès via "Mon atelier" → Voir les documents --}}

    {{-- ── CARD MESSAGES ─────────────────────────────────────── --}}
    <a href="{{ route('participant.messages') }}"
       class="group relative flex flex-col justify-between rounded-3xl bg-white border border-gray-100 p-6 shadow-sm
              transition-all duration-200 hover:scale-[1.02] hover:shadow-lg cursor-pointer">
        <div class="flex items-start justify-between mb-4">
            <div class="h-12 w-12 rounded-2xl flex items-center justify-center relative"
                 style="background: hsl(var(--vert-soft-bg));">
                <svg class="w-6 h-6" style="color:hsl(var(--vert-ivoire));" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                </svg>
                @if($unreadCount > 0)
                <span class="absolute -top-1 -right-1 h-5 w-5 rounded-full flex items-center justify-center text-[10px] font-bold text-blanc-pur"
                      style="background:hsl(var(--orange-ivoire));">{{ min($unreadCount, 9) }}</span>
                @endif
            </div>
        </div>
        <div>
            <p class="text-lg font-bold text-noir-profond mb-1">Messages</p>
            <p class="text-sm {{ $unreadCount > 0 ? 'font-semibold' : 'text-gris-500' }}"
               style="{{ $unreadCount > 0 ? 'color:hsl(var(--orange-brule));' : '' }}">
                @if($unreadCount > 0)
                    {{ $unreadCount }} message{{ $unreadCount > 1 ? 's' : '' }} non lu{{ $unreadCount > 1 ? 's' : '' }}
                @else
                    Discuter avec votre formateur
                @endif
            </p>
        </div>
        <div class="flex items-center gap-1 mt-4 text-xs font-semibold" style="color:hsl(var(--vert-ivoire));">
            Ouvrir
            <svg class="w-4 h-4 transition-transform duration-200 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </div>
    </a>

    {{-- ── MON GROUPE (3 cols) ───────────────────────────────── --}}
    @if($app->group_label)
    <div class="sm:col-span-2 lg:col-span-3 rounded-3xl bg-white border border-gray-100 shadow-sm p-6">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h2 class="font-serif font-bold text-lg text-noir-profond">
                    Mon groupe · <span style="color:hsl(var(--orange-ivoire));">{{ $app->group_label }}</span>
                </h2>
                <p class="text-sm text-gris-500 mt-0.5">{{ $groupMembers->count() + 1 }} participant{{ $groupMembers->count() + 1 > 1 ? 's' : '' }}</p>
            </div>
            <a href="{{ route('participant.messages') }}"
               class="inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-semibold transition-all hover:scale-105 cursor-pointer"
               style="background:hsl(var(--orange-soft-bg));color:hsl(var(--orange-brule));">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                </svg>
                Message au formateur
            </a>
        </div>

        {{-- Avatar grid --}}
        <div class="flex flex-wrap gap-3">
            {{-- Moi --}}
            <div class="flex flex-col items-center gap-1.5 cursor-default" title="{{ $user->first_name }} {{ $user->last_name }} (Vous)">
                <div class="relative h-12 w-12 rounded-2xl flex items-center justify-center text-sm font-bold text-blanc-pur shadow-sm"
                     style="background:hsl(var(--orange-ivoire));">
                    {{ mb_strtoupper(mb_substr($user->first_name, 0, 1) . mb_substr($user->last_name, 0, 1)) }}
                    <span class="absolute -bottom-0.5 -right-0.5 h-3.5 w-3.5 rounded-full bg-emerald-400 border-2 border-white"></span>
                </div>
                <span class="text-[10px] text-gris-500 text-center max-w-[60px] truncate">Vous</span>
            </div>

            {{-- Autres membres --}}
            @foreach($groupMembers as $member)
            @php
                $colors = ['#6366f1','#8b5cf6','#ec4899','#14b8a6','#f59e0b','#3b82f6','#10b981','#f97316'];
                $color  = $colors[($loop->index) % count($colors)];
                $initials = mb_strtoupper(mb_substr($member->first_name, 0, 1) . mb_substr($member->last_name, 0, 1));
            @endphp
            <div class="flex flex-col items-center gap-1.5 cursor-default"
                 title="{{ $member->first_name }} {{ $member->last_name }}">
                <div class="h-12 w-12 rounded-2xl flex items-center justify-center text-sm font-bold text-white shadow-sm"
                     style="background: {{ $color }};">
                    {{ $initials }}
                </div>
                <span class="text-[10px] text-gris-500 text-center max-w-[60px] truncate">
                    {{ $member->first_name }}
                </span>
            </div>
            @endforeach
        </div>

        <p class="mt-5 pt-4 border-t border-gray-100 text-xs italic text-gris-500">
            Seuls les prénoms et noms sont visibles entre participants pour des raisons de confidentialité.
        </p>
    </div>
    @endif

    @endif {{-- /isAccepted --}}

    {{-- ── RÉCAPITULATIF (toujours visible) ─────────────────── --}}
    <div x-data="{ open: false }"
         class="sm:col-span-2 lg:col-span-{{ $isAccepted ? '3' : '2' }} rounded-3xl bg-white border border-gray-100 shadow-sm overflow-hidden">

        <button @click="open = !open"
                class="w-full flex items-center justify-between px-6 py-5 cursor-pointer hover:bg-gray-50 transition-colors"
                :aria-expanded="open">
            <h2 class="font-serif font-bold text-lg text-noir-profond">Récapitulatif du dossier</h2>
            <div class="h-8 w-8 rounded-full flex items-center justify-center transition-colors"
                 :class="open ? 'bg-noir-profond' : 'bg-gray-100'">
                <svg class="w-4 h-4 transition-transform duration-200"
                     :class="open ? 'rotate-180 text-blanc-pur' : 'text-gris-500'"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>
        </button>

        <div x-show="open" x-collapse x-cloak class="divide-y divide-gray-50">

            {{-- ── Groupe Identité ──────────────────────────────── --}}
            <div class="px-6 py-5">
                <p class="flex items-center gap-2 text-[11px] font-bold uppercase tracking-widest mb-4"
                   style="color:hsl(var(--orange-ivoire));">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Identité
                </p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    {{-- Nom complet --}}
                    <div class="flex items-center gap-3 rounded-2xl bg-gray-50 px-4 py-3">
                        <div class="h-9 w-9 shrink-0 rounded-xl flex items-center justify-center bg-white shadow-sm">
                            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[10px] font-semibold uppercase tracking-wide text-slate-400">Nom complet</p>
                            <p class="text-sm font-semibold text-slate-800 truncate">{{ $user->first_name }} {{ $user->last_name }}</p>
                        </div>
                    </div>
                    {{-- Email --}}
                    <div class="flex items-center gap-3 rounded-2xl bg-gray-50 px-4 py-3">
                        <div class="h-9 w-9 shrink-0 rounded-xl flex items-center justify-center bg-white shadow-sm">
                            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[10px] font-semibold uppercase tracking-wide text-slate-400">Email</p>
                            <p class="text-sm font-semibold text-slate-800 truncate">{{ $user->email }}</p>
                        </div>
                    </div>
                    @if($user->phone)
                    <div class="flex items-center gap-3 rounded-2xl bg-gray-50 px-4 py-3">
                        <div class="h-9 w-9 shrink-0 rounded-xl flex items-center justify-center bg-white shadow-sm">
                            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[10px] font-semibold uppercase tracking-wide text-slate-400">Téléphone</p>
                            <p class="text-sm font-semibold text-slate-800">{{ $user->phone }}</p>
                        </div>
                    </div>
                    @endif
                    @if($user->city)
                    <div class="flex items-center gap-3 rounded-2xl bg-gray-50 px-4 py-3">
                        <div class="h-9 w-9 shrink-0 rounded-xl flex items-center justify-center bg-white shadow-sm">
                            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[10px] font-semibold uppercase tracking-wide text-slate-400">Ville</p>
                            <p class="text-sm font-semibold text-slate-800">{{ $user->city }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- ── Groupe Profil professionnel ──────────────────── --}}
            @if($app->category || $app->organization_name || $app->position)
            <div class="px-6 py-5">
                <p class="flex items-center gap-2 text-[11px] font-bold uppercase tracking-widest mb-4"
                   style="color:hsl(var(--vert-ivoire));">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Profil professionnel
                </p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @if($app->category)
                    <div class="flex items-center gap-3 rounded-2xl px-4 py-3"
                         style="background:hsl(var(--vert-soft-bg));">
                        <div class="h-9 w-9 shrink-0 rounded-xl flex items-center justify-center bg-white shadow-sm">
                            <svg class="w-4 h-4" style="color:hsl(var(--vert-ivoire));" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[10px] font-semibold uppercase tracking-wide" style="color:hsl(var(--vert-ivoire)/0.7);">Catégorie</p>
                            <p class="text-sm font-semibold text-slate-800 truncate">{{ $app->category->label() }}</p>
                        </div>
                    </div>
                    @endif
                    @if($app->organization_name)
                    <div class="flex items-center gap-3 rounded-2xl px-4 py-3"
                         style="background:hsl(var(--vert-soft-bg));">
                        <div class="h-9 w-9 shrink-0 rounded-xl flex items-center justify-center bg-white shadow-sm">
                            <svg class="w-4 h-4" style="color:hsl(var(--vert-ivoire));" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[10px] font-semibold uppercase tracking-wide" style="color:hsl(var(--vert-ivoire)/0.7);">Organisation</p>
                            <p class="text-sm font-semibold text-slate-800 truncate">{{ $app->organization_name }}</p>
                        </div>
                    </div>
                    @endif
                    @if($app->position)
                    <div class="flex items-center gap-3 rounded-2xl px-4 py-3"
                         style="background:hsl(var(--vert-soft-bg));">
                        <div class="h-9 w-9 shrink-0 rounded-xl flex items-center justify-center bg-white shadow-sm">
                            <svg class="w-4 h-4" style="color:hsl(var(--vert-ivoire));" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[10px] font-semibold uppercase tracking-wide" style="color:hsl(var(--vert-ivoire)/0.7);">Fonction</p>
                            <p class="text-sm font-semibold text-slate-800 truncate">{{ $app->position }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            {{-- ── Pièces jointes ───────────────────────────────── --}}
            @if($app->documents->isNotEmpty())
            <div class="px-6 py-5">
                <p class="flex items-center gap-2 text-[11px] font-bold uppercase tracking-widest mb-4 text-blue-500">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                    </svg>
                    Pièces jointes
                </p>
                <div class="flex flex-wrap gap-2">
                    @foreach($app->documents as $doc)
                    <span class="inline-flex items-center gap-2 rounded-xl px-4 py-2.5 text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100">
                        <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        {{ $doc->type->label() }}
                    </span>
                    @endforeach
                </div>
            </div>
            @endif

        </div>
    </div>

</div> {{-- /bento grid --}}
@endif {{-- /app --}}

</div> {{-- /space-y-8 --}}

</x-layouts.candidate>
