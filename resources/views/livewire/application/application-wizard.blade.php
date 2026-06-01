<div class="min-h-screen bg-blanc-creme py-10">
    <div class="max-w-3xl mx-auto px-4 sm:px-6">

        {{-- Hors fenêtre d'inscription --}}
        @if(! $windowOpen)
        <div class="rounded-2xl bg-sable-doux/40 border border-sable-doux p-8 text-center shadow-card">
            <div class="w-14 h-14 mx-auto rounded-full flex items-center justify-center mb-4"
                 style="background:hsl(var(--orange-soft-bg))">
                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                     style="color:hsl(var(--orange-ivoire))" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                </svg>
            </div>
            <h2 class="font-serif font-bold text-2xl text-noir-profond mb-2">Dépôt de candidatures fermé</h2>
            <p style="color:hsl(var(--gris-700))" class="text-base max-w-md mx-auto">
                La période d'inscription au Hub Import-Export 2026 est actuellement fermée.
                Restez informé(e) en vous inscrivant à notre newsletter.
            </p>
        </div>
        @else

        {{-- En-tête wizard --}}
        <div class="mb-8 text-center">
            <x-kicker color="orange">Candidature 2026</x-kicker>
            <h1 class="font-serif font-bold text-3xl text-noir-profond mt-3">
                Formulaire de candidature
            </h1>
            <p class="text-sm mt-2" style="color:hsl(var(--gris-700))">
                Complétez les 4 étapes. Votre brouillon est sauvegardé automatiquement à chaque étape.
            </p>
        </div>

        {{-- Barre de progression --}}
        <div class="mb-8" role="progressbar" aria-valuemin="1" aria-valuemax="4" aria-valuenow="{{ $step }}">
            <div class="flex items-center gap-0">
                @foreach([1 => 'Identité', 2 => 'Profil', 3 => 'Motivation', 4 => 'Pièces'] as $i => $label)
                <button
                    wire:click="goToStep({{ $i }})"
                    @if($i >= $step) disabled @endif
                    class="flex-1 flex flex-col items-center group focus:outline-none"
                    aria-label="Étape {{ $i }} — {{ $label }}"
                >
                    <div class="relative flex items-center w-full">
                        @if($i > 1)
                        <div class="flex-1 h-0.5 {{ $i <= $step ? 'bg-orange-ivoire' : 'bg-sable-doux' }} transition-colors duration-300"></div>
                        @endif
                        <div class="w-9 h-9 rounded-full flex items-center justify-center flex-shrink-0 transition-all duration-300 font-bold text-sm
                            {{ $i < $step ? 'bg-vert-ivoire text-blanc-pur' : ($i === $step ? 'bg-orange-ivoire text-blanc-pur shadow-md scale-110' : 'bg-white border-2 border-sable-doux text-gris-500') }}">
                            @if($i < $step)
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            @else
                            {{ $i }}
                            @endif
                        </div>
                        @if($i < 4)
                        <div class="flex-1 h-0.5 {{ $i < $step ? 'bg-orange-ivoire' : 'bg-sable-doux' }} transition-colors duration-300"></div>
                        @endif
                    </div>
                    <span class="mt-2 text-xs font-medium {{ $i === $step ? 'text-orange-brule' : 'text-gris-500' }} hidden sm:block">{{ $label }}</span>
                </button>
                @endforeach
            </div>
        </div>

        {{-- Indicateur brouillon repris --}}
        @if($draftResumedAt)
        <div class="mb-5 rounded-xl border border-sable-doux px-4 py-2.5 flex items-center gap-2.5 text-sm"
             style="background:hsl(var(--sable-doux)/0.35)">
            <svg class="w-4 h-4 flex-shrink-0" style="color:hsl(var(--vert-ivoire))" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span style="color:hsl(var(--gris-700))">
                Brouillon du <strong class="text-noir-profond">{{ $draftResumedAt }}</strong> repris — étape {{ $step }}/4
            </span>
        </div>
        @endif

        {{-- Flash erreur générale --}}
        @if($errors->any())
        <div class="mb-6 rounded-xl bg-red-50 border border-red-200 p-4" role="alert">
            <p class="font-semibold text-red-700 text-sm mb-1">Merci de corriger les erreurs suivantes :</p>
            <ul class="list-disc list-inside text-red-600 text-sm space-y-0.5">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- Carte de l'étape --}}
        <div class="bg-white rounded-2xl shadow-card p-6 sm:p-8">

            {{-- ─── Étape 1 — Identité ─────────────────────────────── --}}
            @if($step === 1)
            <h2 class="font-serif font-bold text-xl text-noir-profond mb-6">Étape 1 — Identité personnelle</h2>
            <form wire:submit.prevent="nextStep" novalidate>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                    <div>
                        <label class="form-label" for="firstName">Prénom <span class="text-red-500">*</span></label>
                        <input id="firstName" wire:model="firstName" type="text" autocomplete="given-name"
                               class="form-input @error('firstName') border-red-400 @enderror" placeholder="ex : Kouamé">
                        @error('firstName')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="form-label" for="lastName">Nom <span class="text-red-500">*</span></label>
                        <input id="lastName" wire:model="lastName" type="text" autocomplete="family-name"
                               class="form-input @error('lastName') border-red-400 @enderror" placeholder="ex : Konan">
                        @error('lastName')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="form-label">Civilité <span class="text-red-500">*</span></label>
                        <div class="flex gap-3 mt-1">
                            @foreach(['F' => 'Femme', 'M' => 'Homme', 'X' => 'Préfère ne pas préciser'] as $val => $lbl)
                            <label class="flex items-center gap-2 cursor-pointer text-sm">
                                <input wire:model="gender" type="radio" value="{{ $val }}"
                                       class="accent-orange-ivoire w-4 h-4">
                                {{ $lbl }}
                            </label>
                            @endforeach
                        </div>
                        @error('gender')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="form-label" for="birthDate">Date de naissance <span class="text-red-500">*</span></label>
                        <input id="birthDate" wire:model="birthDate" type="date"
                               class="form-input @error('birthDate') border-red-400 @enderror"
                               max="{{ now()->subYears(18)->format('Y-m-d') }}">
                        @error('birthDate')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="form-label" for="nationality">Nationalité <span class="text-red-500">*</span></label>
                        <input id="nationality" wire:model="nationality" type="text"
                               class="form-input @error('nationality') border-red-400 @enderror">
                        @error('nationality')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="form-label" for="phone">
                            Téléphone <span class="text-red-500">*</span>
                            <span class="text-xs font-normal text-gris-500 ml-1">(format +225…)</span>
                        </label>
                        <input id="phone" wire:model="phone" type="tel" autocomplete="tel"
                               class="form-input @error('phone') border-red-400 @enderror"
                               placeholder="+2250700000000">
                        @error('phone')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="form-label" for="city">Ville de résidence <span class="text-red-500">*</span></label>
                        <input id="city" wire:model="city" type="text" autocomplete="address-level2"
                               class="form-input @error('city') border-red-400 @enderror" placeholder="ex : Abidjan">
                        @error('city')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="form-label" for="country">Pays de résidence <span class="text-red-500">*</span></label>
                        <input id="country" wire:model="country" type="text" autocomplete="country-name"
                               class="form-input @error('country') border-red-400 @enderror">
                        @error('country')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="form-label" for="email">Email</label>
                        <input id="email" type="email" value="{{ auth()->user()->email }}" disabled
                               class="form-input opacity-60 cursor-not-allowed bg-sable-doux/30">
                        <p class="text-xs mt-1" style="color:hsl(var(--gris-700))">Repris de votre compte, non modifiable ici.</p>
                    </div>

                    <div>
                        <label class="form-label" for="photo">Photo d'identité <span class="text-xs font-normal text-gris-500">(facultatif, JPG/PNG max 3 Mo)</span></label>
                        <input id="photo" wire:model="photo" type="file" accept="image/jpeg,image/png"
                               class="form-input @error('photo') border-red-400 @enderror file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-orange-soft-bg file:text-orange-brule">
                        @error('photo')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                </div>
                <div class="flex justify-end mt-8">
                    <button type="submit" class="btn-fill px-8 py-3 text-base">
                        Continuer
                        <svg class="w-4 h-4 ml-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>
            </form>

            {{-- ─── Étape 2 — Profil professionnel ────────────────── --}}
            @elseif($step === 2)
            <h2 class="font-serif font-bold text-xl text-noir-profond mb-6">Étape 2 — Profil professionnel</h2>
            <form wire:submit.prevent="nextStep" novalidate>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                    <div class="sm:col-span-2">
                        <label class="form-label" for="category">Catégorie professionnelle <span class="text-red-500">*</span></label>
                        <select id="category" wire:model.live="category"
                                class="form-input @error('category') border-red-400 @enderror">
                            <option value="">— Sélectionnez —</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->value }}">{{ $cat->label() }}</option>
                            @endforeach
                        </select>
                        @error('category')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="form-label" for="organizationName">Nom de l'organisation <span class="text-red-500">*</span></label>
                        <input id="organizationName" wire:model="organizationName" type="text"
                               class="form-input @error('organizationName') border-red-400 @enderror">
                        @error('organizationName')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="form-label" for="organizationType">Type d'organisation <span class="text-red-500">*</span></label>
                        <select id="organizationType" wire:model="organizationType"
                                class="form-input @error('organizationType') border-red-400 @enderror">
                            <option value="">— Sélectionnez —</option>
                            @foreach(['Société commerciale','GIE','Coopérative','Banque','Assureur','Administration publique','ONG','Association','Université / École','Autre'] as $t)
                            <option value="{{ $t }}">{{ $t }}</option>
                            @endforeach
                        </select>
                        @error('organizationType')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="form-label" for="position">Poste / Fonction <span class="text-red-500">*</span></label>
                        <input id="position" wire:model="position" type="text"
                               class="form-input @error('position') border-red-400 @enderror">
                        @error('position')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="form-label" for="sector">Secteur d'activité <span class="text-red-500">*</span></label>
                        <input id="sector" wire:model="sector" type="text" list="sectors-list"
                               class="form-input @error('sector') border-red-400 @enderror">
                        <datalist id="sectors-list">
                            @foreach(['Agroalimentaire','Textile / Confection','BTP / Construction','Transport logistique','Commerce international','Services financiers','Numérique / Tech','Santé / Pharmaceutique','Éducation','Énergie','Tourisme / Hôtellerie','Autre'] as $s)
                            <option value="{{ $s }}">
                            @endforeach
                        </datalist>
                        @error('sector')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="form-label" for="experienceYears">Années d'expérience <span class="text-red-500">*</span></label>
                        <input id="experienceYears" wire:model="experienceYears" type="number" min="0" max="60"
                               class="form-input @error('experienceYears') border-red-400 @enderror">
                        @error('experienceYears')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    @if($requiresRccm)
                    <div>
                        <label class="form-label" for="rccmNumber">Numéro RCCM <span class="text-red-500">*</span></label>
                        <input id="rccmNumber" wire:model="rccmNumber" type="text"
                               class="form-input @error('rccmNumber') border-red-400 @enderror"
                               placeholder="ex : CI-ABJ-2020-B-123456">
                        @error('rccmNumber')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    @endif

                    <div>
                        <label class="form-label" for="website">Site web <span class="text-xs font-normal text-gris-500">(facultatif)</span></label>
                        <input id="website" wire:model="website" type="url"
                               class="form-input" placeholder="https://…">
                    </div>

                    <div>
                        <label class="form-label" for="professionalEmail">Email professionnel <span class="text-xs font-normal text-gris-500">(facultatif)</span></label>
                        <input id="professionalEmail" wire:model="professionalEmail" type="email"
                               class="form-input">
                    </div>

                    <div>
                        <label class="form-label" for="professionalPhone">Téléphone professionnel <span class="text-xs font-normal text-gris-500">(facultatif)</span></label>
                        <input id="professionalPhone" wire:model="professionalPhone" type="tel"
                               class="form-input" placeholder="+225…">
                    </div>

                </div>
                <div class="flex justify-between mt-8">
                    <button type="button" wire:click="previousStep"
                            class="px-6 py-3 rounded-xl border-2 border-sable-doux font-semibold text-noir-profond hover:bg-sable-doux/30 transition-colors text-base">
                        Retour
                    </button>
                    <button type="submit" class="btn-fill px-8 py-3 text-base">
                        Continuer
                        <svg class="w-4 h-4 ml-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>
            </form>

            {{-- ─── Étape 3 — Motivation et ateliers ──────────────── --}}
            @elseif($step === 3)
            <h2 class="font-serif font-bold text-xl text-noir-profond mb-6">Étape 3 — Motivation et ateliers</h2>
            <form wire:submit.prevent="nextStep" novalidate>
                <div class="space-y-6">

                    <div>
                        <div class="flex justify-between items-baseline mb-1">
                            <label class="form-label mb-0" for="motivation">
                                Lettre de motivation <span class="text-red-500">*</span>
                            </label>
                            <span class="text-xs font-mono {{ strlen($motivation) < 500 ? 'text-red-500' : (strlen($motivation) > 1500 ? 'text-red-500' : 'text-vert-ivoire') }}"
                                  x-text="'{{ strlen($motivation) }} / 1500'"
                                  wire:key="char-counter">
                                {{ strlen($motivation) }} / 1500
                            </span>
                        </div>
                        <textarea id="motivation" wire:model="motivation" rows="8"
                                  class="form-input resize-none @error('motivation') border-red-400 @enderror"
                                  placeholder="Décrivez en 500 à 1500 caractères votre activité, vos objectifs et les raisons de votre candidature au Hub Import-Export 2026…"></textarea>
                        @error('motivation')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <p class="form-label">Atelier souhaité <span class="text-red-500">*</span>
                            <span class="text-xs font-normal text-gris-500 ml-1">(1 seul atelier par candidat)</span>
                        </p>
                        @error('chosenWorkshop')<p class="form-error mb-2">{{ $message }}</p>@enderror
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach($workshops as $ws)
                            <label class="flex items-start gap-3 p-4 rounded-xl border-2 cursor-pointer transition-all duration-200
                                {{ $chosenWorkshop == $ws->id ? 'border-orange-ivoire bg-orange-soft-bg/40' : 'border-sable-doux hover:border-sable-doux/80 bg-white' }}">
                                <input type="radio" wire:model.live="chosenWorkshop" value="{{ $ws->id }}"
                                       name="chosenWorkshop"
                                       class="mt-0.5 accent-orange-ivoire w-4 h-4 flex-shrink-0">
                                <div>
                                    <p class="font-semibold text-sm text-noir-profond leading-tight">{{ $ws->title }}</p>
                                    @if($ws->short_description)
                                    <p class="text-xs mt-0.5" style="color:hsl(var(--gris-700))">{{ $ws->short_description }}</p>
                                    @endif
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <label class="form-label" for="referralSource">Comment avez-vous connu le Hub ? <span class="text-red-500">*</span></label>
                        <select id="referralSource" wire:model="referralSource"
                                class="form-input @error('referralSource') border-red-400 @enderror">
                            <option value="">— Sélectionnez —</option>
                            @foreach(['Web' => 'Site web', 'social' => 'Réseaux sociaux', 'word_of_mouth' => 'Bouche-à-oreille', 'partner' => 'Partenaire', 'press' => 'Presse', 'other' => 'Autre'] as $val => $lbl)
                            <option value="{{ $val }}">{{ $lbl }}</option>
                            @endforeach
                        </select>
                        @error('referralSource')<p class="form-error">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <p class="form-label">Première participation au Hub ? <span class="text-red-500">*</span></p>
                        <div class="flex gap-5 mt-1">
                            <label class="flex items-center gap-2 cursor-pointer text-sm">
                                <input wire:model="isFirstParticipation" type="radio" :value="true"
                                       class="accent-orange-ivoire w-4 h-4"> Oui
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer text-sm">
                                <input wire:model="isFirstParticipation" type="radio" :value="false"
                                       class="accent-orange-ivoire w-4 h-4"> Non
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="form-label" for="expectations">
                            Attentes spécifiques
                            <span class="text-xs font-normal text-gris-500">(facultatif, max 600 caractères)</span>
                        </label>
                        <textarea id="expectations" wire:model="expectations" rows="3" maxlength="600"
                                  class="form-input resize-none" placeholder="Quels résultats concrets espérez-vous tirer de cet événement ?"></textarea>
                    </div>

                </div>
                <div class="flex justify-between mt-8">
                    <button type="button" wire:click="previousStep"
                            class="px-6 py-3 rounded-xl border-2 border-sable-doux font-semibold text-noir-profond hover:bg-sable-doux/30 transition-colors text-base">
                        Retour
                    </button>
                    <button type="submit" class="btn-fill px-8 py-3 text-base">
                        Continuer
                        <svg class="w-4 h-4 ml-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>
            </form>

            {{-- ─── Étape 4 — Pièces et confirmation ──────────────── --}}
            @elseif($step === 4)
            <h2 class="font-serif font-bold text-xl text-noir-profond mb-6">Étape 4 — Pièces et confirmation</h2>
            <form wire:submit.prevent="submit" novalidate>
                <div class="space-y-6">

                    {{-- Pièces jointes --}}
                    <div class="space-y-4">
                        <p class="font-semibold text-noir-profond text-sm">Documents requis</p>

                        <div>
                            <label class="form-label" for="cvFile">
                                CV <span class="text-red-500">*</span>
                                <span class="text-xs font-normal text-gris-500">(PDF, JPG ou PNG, max 5 Mo)</span>
                            </label>
                            <input id="cvFile" wire:model="cvFile" type="file"
                                   accept=".pdf,.jpg,.jpeg,.png"
                                   class="form-input @error('cvFile') border-red-400 @enderror file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-orange-soft-bg file:text-orange-brule">
                            @error('cvFile')<p class="form-error">{{ $message }}</p>@enderror
                        </div>

                        @if($requiresRccm)
                        <div>
                            <label class="form-label" for="rccmFile">
                                Attestation RCCM <span class="text-red-500">*</span>
                                <span class="text-xs font-normal text-gris-500">(obligatoire pour votre catégorie)</span>
                            </label>
                            <input id="rccmFile" wire:model="rccmFile" type="file"
                                   accept=".pdf,.jpg,.jpeg,.png"
                                   class="form-input @error('rccmFile') border-red-400 @enderror file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-orange-soft-bg file:text-orange-brule">
                            @error('rccmFile')<p class="form-error">{{ $message }}</p>@enderror
                        </div>
                        @endif

                        <div>
                            <label class="form-label" for="idCardFile">
                                Pièce d'identité
                                <span class="text-xs font-normal text-gris-500">(facultatif)</span>
                            </label>
                            <input id="idCardFile" wire:model="idCardFile" type="file"
                                   accept=".pdf,.jpg,.jpeg,.png"
                                   class="form-input file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-orange-soft-bg file:text-orange-brule">
                        </div>
                    </div>

                    {{-- Récapitulatif --}}
                    <div class="rounded-xl bg-sable-doux/20 border border-sable-doux p-5">
                        <p class="font-semibold text-noir-profond text-sm mb-3">Récapitulatif de votre dossier</p>
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-2 text-sm">
                            <div><dt class="text-gris-500">Nom complet</dt><dd class="font-medium text-noir-profond">{{ $firstName }} {{ $lastName }}</dd></div>
                            <div><dt class="text-gris-500">Email</dt><dd class="font-medium text-noir-profond">{{ auth()->user()->email }}</dd></div>
                            <div><dt class="text-gris-500">Téléphone</dt><dd class="font-medium text-noir-profond">{{ $phone ?: '—' }}</dd></div>
                            <div><dt class="text-gris-500">Ville</dt><dd class="font-medium text-noir-profond">{{ $city }}, {{ $country }}</dd></div>
                            @if($category)
                            <div class="sm:col-span-2"><dt class="text-gris-500">Catégorie</dt><dd class="font-medium text-noir-profond">{{ \App\Enums\ApplicationCategory::tryFrom($category)?->label() }}</dd></div>
                            @endif
                            @if($organizationName)
                            <div class="sm:col-span-2"><dt class="text-gris-500">Organisation</dt><dd class="font-medium text-noir-profond">{{ $organizationName }} — {{ $position }}</dd></div>
                            @endif
                            @if(count($chosenWorkshops))
                            <div class="sm:col-span-2">
                                <dt class="text-gris-500">Ateliers choisis</dt>
                                <dd class="font-medium text-noir-profond">
                                    @foreach($workshops->whereIn('id', $chosenWorkshops) as $ws)
                                    {{ $loop->first ? '' : ' · ' }}{{ $ws->title }}
                                    @endforeach
                                </dd>
                            </div>
                            @endif
                        </dl>
                        <p class="text-xs mt-3" style="color:hsl(var(--gris-700))">
                            Besoin de modifier ? Cliquez sur une étape précédente dans la barre de progression.
                        </p>
                    </div>

                    {{-- Consentements --}}
                    <div class="space-y-3">
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input wire:model="rgpdConsent" type="checkbox" id="rgpdConsent"
                                   class="mt-0.5 accent-orange-ivoire w-4 h-4 flex-shrink-0">
                            <span class="text-sm leading-relaxed" style="color:hsl(var(--gris-700))">
                                <span class="font-semibold text-noir-profond">Obligatoire — </span>
                                Je consens au traitement de mes données personnelles aux fins de l'organisation du Hub Import-Export 2026,
                                conformément à la loi n°2013-450 sur la protection des données personnelles
                                (<a href="{{ route('politique-confidentialite') }}" target="_blank" class="link-underline">Politique de confidentialité</a>).
                            </span>
                        </label>
                        @error('rgpdConsent')<p class="form-error">{{ $message }}</p>@enderror

                        <label class="flex items-start gap-3 cursor-pointer">
                            <input wire:model="communicationConsent" type="checkbox" id="commConsent"
                                   class="mt-0.5 accent-orange-ivoire w-4 h-4 flex-shrink-0">
                            <span class="text-sm leading-relaxed" style="color:hsl(var(--gris-700))">
                                J'accepte de recevoir les communications officielles relatives au Hub Import-Export 2026.
                            </span>
                        </label>
                    </div>

                </div>

                <div class="flex justify-between mt-8">
                    <button type="button" wire:click="previousStep"
                            class="px-6 py-3 rounded-xl border-2 border-sable-doux font-semibold text-noir-profond hover:bg-sable-doux/30 transition-colors text-base">
                        Retour
                    </button>
                    <button type="submit" wire:loading.attr="disabled" wire:target="submit"
                            class="btn-fill px-8 py-3 text-base flex items-center gap-2">
                        <span wire:loading.remove wire:target="submit">Soumettre ma candidature</span>
                        <span wire:loading wire:target="submit" class="flex items-center gap-2">
                            <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                            </svg>
                            Envoi en cours…
                        </span>
                    </button>
                </div>
            </form>
            @endif

        </div>
        @endif
    </div>
</div>
