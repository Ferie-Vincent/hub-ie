@php
    $app = $user->applications()
        ->whereNotIn('status', ['withdrawn', 'rejected'])
        ->with('workshops')
        ->latest()
        ->first();
    $statusLabel = match($app?->status?->value) {
        'accepted'   => ['Inscrit(e)', 'text-emerald-700', 'bg-emerald-50 border-emerald-200'],
        'waitlisted' => ['Liste d\'attente', 'text-amber-700', 'bg-amber-50 border-amber-200'],
        'received'   => ['Dossier reçu', 'text-blue-700', 'bg-blue-50 border-blue-200'],
        default      => ['En cours', 'text-gris-500', 'bg-gray-50 border-gray-200'],
    };
    $workshop = $app?->workshops?->first();
    $inputClass = 'w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2.5 text-sm text-slate-800 focus:border-vert-ivoire focus:bg-white focus:outline-none focus:ring-2 focus:ring-vert-soft-bg transition-colors';
    $labelClass = 'block text-[10px] font-semibold uppercase tracking-widest text-gris-500 mb-1';
@endphp

<div class="space-y-4 max-w-5xl">

    {{-- Flash --}}
    @if(session('profile_saved'))
    <div class="flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-2.5" role="alert">
        <svg class="w-4 h-4 shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        <p class="text-sm font-medium text-emerald-700">{{ session('profile_saved') }}</p>
    </div>
    @endif
    @if(session('password_saved'))
    <div class="flex items-center gap-3 rounded-2xl border border-blue-200 bg-blue-50 px-4 py-2.5" role="alert">
        <svg class="w-4 h-4 shrink-0 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
        <p class="text-sm font-medium text-blue-700">{{ session('password_saved') }}</p>
    </div>
    @endif

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- FORMULAIRE (pleine largeur)                                --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
        <div class="rounded-3xl bg-white border border-gray-100 shadow-sm overflow-hidden">

            {{-- En-tête : avatar + identité --}}
            <div class="flex items-center gap-4 p-5 border-b border-gray-100">

                {{-- Avatar --}}
                <div class="relative shrink-0">
                    @if($photo)
                        <img src="{{ $photo->temporaryUrl() }}" alt="Aperçu"
                             class="h-14 w-14 rounded-2xl object-cover ring-2 ring-blanc-pur shadow">
                        <span class="absolute -top-1 -right-1 rounded-full bg-vert-ivoire px-1 py-px text-[8px] font-bold text-white">NEW</span>
                    @elseif($user->photo_path)
                        <img src="{{ Storage::url($user->photo_path) }}" alt="Photo de profil"
                             class="h-14 w-14 rounded-2xl object-cover ring-2 ring-blanc-pur shadow">
                    @else
                        <div class="h-14 w-14 rounded-2xl flex items-center justify-center text-lg font-black text-blanc-pur shadow"
                             style="background: linear-gradient(135deg, hsl(var(--vert-ivoire)), hsl(var(--vert-fonce)));">
                            {{ mb_strtoupper(mb_substr($user->first_name, 0, 1) . mb_substr($user->last_name, 0, 1)) }}
                        </div>
                    @endif
                    <label class="absolute -bottom-1.5 -right-1.5 h-6 w-6 rounded-lg flex items-center justify-center cursor-pointer bg-white border border-gray-200 shadow-sm hover:bg-gray-50 transition-colors" title="Changer la photo">
                        <svg class="w-3 h-3 text-gris-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <input type="file" wire:model="photo" accept="image/*" class="sr-only">
                    </label>
                </div>

                {{-- Nom + email + actions photo --}}
                <div class="flex-1 min-w-0">
                    <h1 class="font-serif font-bold text-xl text-noir-profond leading-tight truncate">
                        {{ $user->first_name }} {{ $user->last_name }}
                    </h1>
                    <p class="text-xs text-gris-500 truncate mt-0.5">{{ $user->email }}</p>
                    <div class="flex items-center gap-3 mt-2">
                        @error('photo')<p class="text-xs text-red-500">{{ $message }}</p>@enderror
                        <div wire:loading wire:target="photo" class="text-xs text-gris-500">Chargement…</div>
                        @if($user->photo_path && !$photo)
                        <button type="button" wire:click="removePhoto"
                                class="text-xs text-red-400 hover:text-red-600 transition-colors cursor-pointer">
                            Supprimer la photo
                        </button>
                        @endif
                    </div>
                </div>

                {{-- Badge statut --}}
                @if($app)
                <span class="inline-flex items-center gap-1.5 rounded-full border px-3 py-1 text-xs font-semibold shrink-0 {{ $statusLabel[1] }} {{ $statusLabel[2] }}">
                    @if($app->status->value === 'accepted')
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    @endif
                    {{ $statusLabel[0] }}
                </span>
                @endif
            </div>

            {{-- Champs --}}
            <form wire:submit="saveProfile" class="p-5">
                <div class="grid grid-cols-2 gap-3">

                    <div>
                        <label for="firstName" class="{{ $labelClass }}">Prénom <span class="text-red-400">*</span></label>
                        <input id="firstName" type="text" wire:model="firstName" autocomplete="given-name"
                               class="{{ $inputClass }} @error('firstName') border-red-300 bg-red-50 @enderror">
                        @error('firstName')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="lastName" class="{{ $labelClass }}">Nom <span class="text-red-400">*</span></label>
                        <input id="lastName" type="text" wire:model="lastName" autocomplete="family-name"
                               class="{{ $inputClass }} @error('lastName') border-red-300 bg-red-50 @enderror">
                        @error('lastName')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>

                    <div class="col-span-2">
                        <label for="email" class="{{ $labelClass }}">Email <span class="text-red-400">*</span></label>
                        <input id="email" type="email" wire:model="email" autocomplete="email"
                               class="{{ $inputClass }} @error('email') border-red-300 bg-red-50 @enderror">
                        @error('email')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="phone" class="{{ $labelClass }}">Téléphone</label>
                        <input id="phone" type="tel" wire:model="phone" placeholder="+225 07 00 00 00 00"
                               autocomplete="tel" class="{{ $inputClass }}">
                        @error('phone')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="birthDate" class="{{ $labelClass }}">Date de naissance</label>
                        <input id="birthDate" type="date" wire:model="birthDate" autocomplete="bday"
                               class="{{ $inputClass }}">
                        @error('birthDate')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="gender" class="{{ $labelClass }}">Genre</label>
                        <select id="gender" wire:model="gender" class="{{ $inputClass }} cursor-pointer">
                            <option value="">— Sélectionner —</option>
                            <option value="M">Masculin</option>
                            <option value="F">Féminin</option>
                            <option value="X">Préfère ne pas préciser</option>
                        </select>
                        @error('gender')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="city" class="{{ $labelClass }}">Ville</label>
                        <input id="city" type="text" wire:model="city" autocomplete="address-level2"
                               class="{{ $inputClass }}">
                        @error('city')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>

                    <div class="col-span-2">
                        <label for="nationality" class="{{ $labelClass }}">Nationalité</label>
                        <input id="nationality" type="text" wire:model="nationality" class="{{ $inputClass }}">
                        @error('nationality')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>

                </div>

                <div class="flex justify-end mt-4 pt-4 border-t border-gray-100">
                    <button type="submit" wire:loading.attr="disabled"
                            class="inline-flex items-center gap-2 btn-fill rounded-xl px-5 py-2.5 text-sm font-semibold cursor-pointer disabled:opacity-70 transition-opacity">
                        <span wire:loading.remove wire:target="saveProfile" class="inline-flex items-center gap-2">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Enregistrer
                        </span>
                        <span wire:loading wire:target="saveProfile" class="inline-flex items-center gap-2">
                            <svg class="animate-spin h-3.5 w-3.5" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                            </svg>
                            Enregistrement…
                        </span>
                    </button>
                </div>
            </form>
        </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- INSCRIPTION + ACCÈS RAPIDES (ligne pleine largeur)         --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="flex gap-4 items-start">

            {{-- Inscription --}}
            @if($app)
            <div class="w-2/3 min-w-0 rounded-3xl bg-white border border-gray-100 shadow-sm overflow-hidden">
                <div class="flex items-center gap-2.5 border-b border-gray-100 px-5 py-3.5">
                    <div class="h-6 w-6 rounded-lg flex items-center justify-center" style="background:hsl(var(--vert-soft-bg));">
                        <svg class="w-3 h-3" style="color:hsl(var(--vert-ivoire));" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                        </svg>
                    </div>
                    <h2 class="font-serif font-bold text-sm text-noir-profond">Mon inscription</h2>
                </div>

                <div class="p-5 grid grid-cols-2 gap-x-4 gap-y-4">

                    <div>
                        <p class="text-[10px] font-semibold uppercase tracking-widest text-gris-500 mb-1">Statut</p>
                        <span class="inline-flex items-center gap-1 rounded-full border px-2.5 py-0.5 text-xs font-semibold {{ $statusLabel[1] }} {{ $statusLabel[2] }}">
                            @if($app->status->value === 'accepted')
                            <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            @endif
                            {{ $statusLabel[0] }}
                        </span>
                    </div>

                    <div>
                        <p class="text-[10px] font-semibold uppercase tracking-widest text-gris-500 mb-1">Référence</p>
                        <p class="font-mono text-xs text-noir-profond font-medium">{{ $app->reference_code }}</p>
                    </div>

                    @if($workshop)
                    <div class="col-span-2">
                        <p class="text-[10px] font-semibold uppercase tracking-widest text-gris-500 mb-1">Atelier</p>
                        <p class="text-xs text-noir-profond leading-snug">{{ $workshop->title }}</p>
                    </div>
                    @endif

                    @if($app->group_label || $app->check_in_code)
                    <div class="{{ $app->check_in_code ? '' : 'col-span-2' }}">
                        @if($app->group_label)
                        <p class="text-[10px] font-semibold uppercase tracking-widest text-gris-500 mb-1">Groupe</p>
                        <span class="inline-flex items-center justify-center h-8 w-8 rounded-xl text-sm font-black text-blanc-pur" style="background:hsl(var(--vert-ivoire));">
                            {{ $app->group_label }}
                        </span>
                        @endif
                    </div>

                    @if($app->check_in_code)
                    <div>
                        <p class="text-[10px] font-semibold uppercase tracking-widest text-gris-500 mb-1">Code entrée</p>
                        <p class="font-mono text-2xl font-black tracking-widest text-noir-profond leading-none">{{ $app->check_in_code }}</p>
                    </div>
                    @endif
                    @endif

                    @if($app->accepted_at)
                    <div class="col-span-2 pt-3 border-t border-gray-100">
                        <p class="text-[10px] text-gris-500">Inscrit(e) le {{ $app->accepted_at->format('d/m/Y') }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            {{-- Accès rapides --}}
            <div class="{{ $app ? 'w-1/3' : 'w-full' }} min-w-0 rounded-3xl bg-white border border-gray-100 shadow-sm overflow-hidden">
                <div class="flex items-center gap-2.5 border-b border-gray-100 px-5 py-3.5">
                    <div class="h-6 w-6 rounded-lg flex items-center justify-center bg-gray-100">
                        <svg class="w-3 h-3 text-gris-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <h2 class="font-serif font-bold text-sm text-noir-profond">Accès rapides</h2>
                </div>
                <div class="p-2">
                    @if($app?->status?->value === 'accepted')
                    <a href="{{ route('participant.badge') }}" class="flex items-center gap-3 rounded-xl px-3 py-2 text-sm font-medium text-gris-500 hover:text-noir-profond hover:bg-gray-50 transition-colors group">
                        <svg class="w-4 h-4 shrink-0 group-hover:text-vert-ivoire transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                        Mon badge
                    </a>
                    <a href="{{ route('participant.downloads') }}" class="flex items-center gap-3 rounded-xl px-3 py-2 text-sm font-medium text-gris-500 hover:text-noir-profond hover:bg-gray-50 transition-colors group">
                        <svg class="w-4 h-4 shrink-0 group-hover:text-vert-ivoire transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Documents
                    </a>
                    <a href="{{ route('participant.messages') }}" class="flex items-center gap-3 rounded-xl px-3 py-2 text-sm font-medium text-gris-500 hover:text-noir-profond hover:bg-gray-50 transition-colors group">
                        <svg class="w-4 h-4 shrink-0 group-hover:text-vert-ivoire transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        Messages
                    </a>
                    @endif
                    <a href="{{ route('candidate.dashboard') }}" class="flex items-center gap-3 rounded-xl px-3 py-2 text-sm font-medium text-gris-500 hover:text-noir-profond hover:bg-gray-50 transition-colors group">
                        <svg class="w-4 h-4 shrink-0 group-hover:text-vert-ivoire transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        Tableau de bord
                    </a>
                </div>
            </div>

    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- SÉCURITÉ                                                   --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div class="rounded-3xl bg-white border border-gray-100 shadow-sm overflow-hidden">
        <div class="flex items-center gap-2.5 border-b border-gray-100 px-5 py-3.5">
            <div class="h-6 w-6 rounded-lg flex items-center justify-center bg-slate-100">
                <svg class="w-3 h-3 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            <h2 class="font-serif font-bold text-sm text-noir-profond">Sécurité — Mot de passe</h2>
        </div>

        <form wire:submit="changePassword" class="p-5">
            @php $pwdClass = 'w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2.5 text-sm text-slate-800 focus:border-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-200 transition-colors'; @endphp
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div>
                    <label for="currentPassword" class="{{ $labelClass }}">Mot de passe actuel</label>
                    <input id="currentPassword" type="password" wire:model="currentPassword" autocomplete="current-password"
                           class="{{ $pwdClass }} @error('currentPassword') border-red-300 bg-red-50 @enderror">
                    @error('currentPassword')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="newPassword" class="{{ $labelClass }}">Nouveau mot de passe</label>
                    <input id="newPassword" type="password" wire:model="newPassword" autocomplete="new-password"
                           class="{{ $pwdClass }} @error('newPassword') border-red-300 bg-red-50 @enderror">
                    @error('newPassword')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="confirmPassword" class="{{ $labelClass }}">Confirmer</label>
                    <input id="confirmPassword" type="password" wire:model="confirmPassword" autocomplete="new-password"
                           class="{{ $pwdClass }} @error('confirmPassword') border-red-300 bg-red-50 @enderror">
                    @error('confirmPassword')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-100">
                <p class="text-xs text-gris-500">8 caractères min · Majuscules, minuscules et chiffres.</p>
                <button type="submit" wire:loading.attr="disabled"
                        class="inline-flex items-center gap-2 rounded-xl px-5 py-2.5 text-sm font-semibold bg-slate-800 text-white hover:bg-slate-700 transition-colors cursor-pointer disabled:opacity-70 shrink-0 ml-4">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    Mettre à jour
                </button>
            </div>
        </form>
    </div>

</div>
