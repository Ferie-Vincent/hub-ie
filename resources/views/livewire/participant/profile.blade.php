<div class="space-y-8 max-w-3xl">

    {{-- Flash messages --}}
    @if(session('profile_saved'))
    <div class="flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-3" role="alert">
        <svg class="w-5 h-5 shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        <p class="text-sm font-medium text-emerald-700">{{ session('profile_saved') }}</p>
    </div>
    @endif
    @if(session('password_saved'))
    <div class="flex items-center gap-3 rounded-2xl border border-blue-200 bg-blue-50 px-5 py-3" role="alert">
        <svg class="w-5 h-5 shrink-0 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
        </svg>
        <p class="text-sm font-medium text-blue-700">{{ session('password_saved') }}</p>
    </div>
    @endif

    {{-- ═══ CARTE PHOTO + IDENTITÉ ══════════════════════════════ --}}
    <div class="rounded-3xl bg-white border border-gray-100 shadow-sm overflow-hidden">

        {{-- Header --}}
        <div class="flex items-center gap-3 border-b border-gray-100 px-6 py-4">
            <div class="h-8 w-8 rounded-xl flex items-center justify-center"
                 style="background:hsl(var(--orange-soft-bg));">
                <svg class="w-4 h-4" style="color:hsl(var(--vert-ivoire));" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <h2 class="font-serif font-bold text-lg text-noir-profond">Informations personnelles</h2>
        </div>

        <form wire:submit="saveProfile" class="p-6 space-y-8">

            {{-- Photo avatar --}}
            <div class="flex flex-col sm:flex-row items-center gap-6">

                {{-- Aperçu avatar --}}
                <div class="relative shrink-0">
                    @if($photo)
                        <img src="{{ $photo->temporaryUrl() }}"
                             alt="Aperçu de votre photo"
                             class="h-24 w-24 rounded-2xl object-cover shadow-md ring-2 ring-orange-200">
                        <span class="absolute -top-1.5 -right-1.5 rounded-full bg-vert-soft-bg0 px-2 py-0.5 text-[10px] font-bold text-white">
                            Nouveau
                        </span>
                    @elseif($user->photo_path)
                        <img src="{{ Storage::url($user->photo_path) }}"
                             alt="Votre photo de profil"
                             class="h-24 w-24 rounded-2xl object-cover shadow-md">
                    @else
                        <div class="h-24 w-24 rounded-2xl flex items-center justify-center text-2xl font-black text-blanc-pur shadow-md"
                             style="background:hsl(var(--vert-ivoire));">
                            {{ mb_strtoupper(mb_substr($user->first_name, 0, 1) . mb_substr($user->last_name, 0, 1)) }}
                        </div>
                    @endif
                </div>

                {{-- Upload + actions --}}
                <div class="flex-1 space-y-3 text-center sm:text-left">
                    <div>
                        <p class="text-sm font-semibold text-noir-profond mb-1">Photo de profil</p>
                        <p class="text-xs text-gris-500">JPG, PNG ou WebP · 2 Mo maximum · Recommandé : 400×400 px</p>
                    </div>
                    <div class="flex flex-wrap justify-center sm:justify-start gap-3">
                        <label class="inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-semibold cursor-pointer transition-all hover:scale-105"
                               style="background:hsl(var(--orange-soft-bg));color:hsl(var(--vert-fonce));">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Choisir une photo
                            <input type="file" wire:model="photo" accept="image/*" class="sr-only">
                        </label>
                        @if($user->photo_path && !$photo)
                        <button type="button" wire:click="removePhoto"
                                class="inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-medium border border-red-200 text-red-600 hover:bg-red-50 transition-colors cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Supprimer
                        </button>
                        @endif
                    </div>
                    @error('photo')
                    <p class="text-xs text-red-500" role="alert">{{ $message }}</p>
                    @enderror
                    <div wire:loading wire:target="photo" class="text-xs text-gris-500">Chargement…</div>
                </div>
            </div>

            <hr class="border-gray-100">

            {{-- Champs identité --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                {{-- Prénom --}}
                <div>
                    <label for="firstName" class="block text-xs font-semibold uppercase tracking-wide text-gris-500 mb-1.5">
                        Prénom <span class="text-red-400">*</span>
                    </label>
                    <input id="firstName" type="text" wire:model="firstName"
                           class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-slate-800 focus:border-vert-ivoire focus:bg-white focus:outline-none focus:ring-2 focus:ring-vert-soft-bg transition-colors
                                  @error('firstName') border-red-300 bg-red-50 @enderror"
                           autocomplete="given-name">
                    @error('firstName')<p class="mt-1 text-xs text-red-500" role="alert">{{ $message }}</p>@enderror
                </div>

                {{-- Nom --}}
                <div>
                    <label for="lastName" class="block text-xs font-semibold uppercase tracking-wide text-gris-500 mb-1.5">
                        Nom <span class="text-red-400">*</span>
                    </label>
                    <input id="lastName" type="text" wire:model="lastName"
                           class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-slate-800 focus:border-vert-ivoire focus:bg-white focus:outline-none focus:ring-2 focus:ring-vert-soft-bg transition-colors
                                  @error('lastName') border-red-300 bg-red-50 @enderror"
                           autocomplete="family-name">
                    @error('lastName')<p class="mt-1 text-xs text-red-500" role="alert">{{ $message }}</p>@enderror
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-xs font-semibold uppercase tracking-wide text-gris-500 mb-1.5">
                        Email <span class="text-red-400">*</span>
                    </label>
                    <input id="email" type="email" wire:model="email"
                           class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-slate-800 focus:border-vert-ivoire focus:bg-white focus:outline-none focus:ring-2 focus:ring-vert-soft-bg transition-colors
                                  @error('email') border-red-300 bg-red-50 @enderror"
                           autocomplete="email">
                    @error('email')<p class="mt-1 text-xs text-red-500" role="alert">{{ $message }}</p>@enderror
                </div>

                {{-- Téléphone --}}
                <div>
                    <label for="phone" class="block text-xs font-semibold uppercase tracking-wide text-gris-500 mb-1.5">
                        Téléphone
                    </label>
                    <input id="phone" type="tel" wire:model="phone"
                           placeholder="+225 07 00 00 00 00"
                           class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-slate-800 focus:border-vert-ivoire focus:bg-white focus:outline-none focus:ring-2 focus:ring-vert-soft-bg transition-colors"
                           autocomplete="tel">
                    @error('phone')<p class="mt-1 text-xs text-red-500" role="alert">{{ $message }}</p>@enderror
                </div>

                {{-- Date de naissance --}}
                <div>
                    <label for="birthDate" class="block text-xs font-semibold uppercase tracking-wide text-gris-500 mb-1.5">
                        Date de naissance
                    </label>
                    <input id="birthDate" type="date" wire:model="birthDate"
                           class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-slate-800 focus:border-vert-ivoire focus:bg-white focus:outline-none focus:ring-2 focus:ring-vert-soft-bg transition-colors"
                           autocomplete="bday">
                    @error('birthDate')<p class="mt-1 text-xs text-red-500" role="alert">{{ $message }}</p>@enderror
                </div>

                {{-- Genre --}}
                <div>
                    <label for="gender" class="block text-xs font-semibold uppercase tracking-wide text-gris-500 mb-1.5">
                        Genre
                    </label>
                    <select id="gender" wire:model="gender"
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-slate-800 focus:border-vert-ivoire focus:bg-white focus:outline-none focus:ring-2 focus:ring-vert-soft-bg transition-colors cursor-pointer">
                        <option value="">— Sélectionner —</option>
                        <option value="M">Masculin</option>
                        <option value="F">Féminin</option>
                        <option value="X">Préfère ne pas préciser</option>
                    </select>
                    @error('gender')<p class="mt-1 text-xs text-red-500" role="alert">{{ $message }}</p>@enderror
                </div>

                {{-- Ville --}}
                <div>
                    <label for="city" class="block text-xs font-semibold uppercase tracking-wide text-gris-500 mb-1.5">
                        Ville
                    </label>
                    <input id="city" type="text" wire:model="city"
                           class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-slate-800 focus:border-vert-ivoire focus:bg-white focus:outline-none focus:ring-2 focus:ring-vert-soft-bg transition-colors"
                           autocomplete="address-level2">
                    @error('city')<p class="mt-1 text-xs text-red-500" role="alert">{{ $message }}</p>@enderror
                </div>

                {{-- Nationalité --}}
                <div>
                    <label for="nationality" class="block text-xs font-semibold uppercase tracking-wide text-gris-500 mb-1.5">
                        Nationalité
                    </label>
                    <input id="nationality" type="text" wire:model="nationality"
                           class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-slate-800 focus:border-vert-ivoire focus:bg-white focus:outline-none focus:ring-2 focus:ring-vert-soft-bg transition-colors">
                    @error('nationality')<p class="mt-1 text-xs text-red-500" role="alert">{{ $message }}</p>@enderror
                </div>

            </div>

            {{-- Bouton save --}}
            <div class="flex justify-end pt-2">
                <button type="submit"
                        wire:loading.attr="disabled"
                        class="inline-flex items-center gap-2 btn-fill rounded-xl px-8 py-3 text-sm font-semibold min-h-[44px] cursor-pointer disabled:opacity-70 transition-opacity">
                    <span wire:loading.remove wire:target="saveProfile">
                        <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Enregistrer
                    </span>
                    <span wire:loading wire:target="saveProfile" class="inline-flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                        </svg>
                        Enregistrement…
                    </span>
                </button>
            </div>

        </form>
    </div>

    {{-- ═══ CARTE MOT DE PASSE ═══════════════════════════════════ --}}
    <div class="rounded-3xl bg-white border border-gray-100 shadow-sm overflow-hidden">

        <div class="flex items-center gap-3 border-b border-gray-100 px-6 py-4">
            <div class="h-8 w-8 rounded-xl flex items-center justify-center bg-blue-50">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            <h2 class="font-serif font-bold text-lg text-noir-profond">Changer le mot de passe</h2>
        </div>

        <form wire:submit="changePassword" class="p-6">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div>
                    <label for="currentPassword" class="block text-xs font-semibold uppercase tracking-wide text-gris-500 mb-1.5">
                        Mot de passe actuel
                    </label>
                    <input id="currentPassword" type="password" wire:model="currentPassword"
                           class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-slate-800 focus:border-blue-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-200 transition-colors
                                  @error('currentPassword') border-red-300 bg-red-50 @enderror"
                           autocomplete="current-password">
                    @error('currentPassword')<p class="mt-1 text-xs text-red-500" role="alert">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="newPassword" class="block text-xs font-semibold uppercase tracking-wide text-gris-500 mb-1.5">
                        Nouveau mot de passe
                    </label>
                    <input id="newPassword" type="password" wire:model="newPassword"
                           class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-slate-800 focus:border-blue-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-200 transition-colors
                                  @error('newPassword') border-red-300 bg-red-50 @enderror"
                           autocomplete="new-password">
                    @error('newPassword')<p class="mt-1 text-xs text-red-500" role="alert">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="confirmPassword" class="block text-xs font-semibold uppercase tracking-wide text-gris-500 mb-1.5">
                        Confirmer
                    </label>
                    <input id="confirmPassword" type="password" wire:model="confirmPassword"
                           class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-slate-800 focus:border-blue-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-200 transition-colors
                                  @error('confirmPassword') border-red-300 bg-red-50 @enderror"
                           autocomplete="new-password">
                    @error('confirmPassword')<p class="mt-1 text-xs text-red-500" role="alert">{{ $message }}</p>@enderror
                </div>
            </div>
            <p class="mt-3 text-xs text-gris-500">8 caractères minimum · Majuscules, minuscules et chiffres requis.</p>
            <div class="flex justify-end mt-5">
                <button type="submit"
                        wire:loading.attr="disabled"
                        class="inline-flex items-center gap-2 rounded-xl px-6 py-3 text-sm font-semibold bg-blue-600 text-white hover:bg-blue-700 transition-colors min-h-[44px] cursor-pointer disabled:opacity-70">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    Mettre à jour
                </button>
            </div>
        </form>
    </div>

</div>
