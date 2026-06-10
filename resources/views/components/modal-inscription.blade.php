{{--
    Modal pré-inscription Hub 2026.
    Déclenché via : $dispatch('open-inscription') ou @click="$dispatch('open-inscription')"
    Alpine x-data posé sur <body> dans le layout public.
--}}
<div
    x-show="inscriptionOpen"
    x-cloak
    class="fixed inset-0 z-[200] flex items-center justify-center p-4"
    @keydown.escape.window="inscriptionOpen = false"
    aria-modal="true"
    role="dialog"
    aria-labelledby="modal-inscription-title"
>
    {{-- Backdrop --}}
    <div
        class="absolute inset-0 bg-noir-profond/80 backdrop-blur-sm"
        x-show="inscriptionOpen"
        x-transition:enter="transition duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="inscriptionOpen = false"
        aria-hidden="true"
    ></div>

    {{-- Panneau --}}
    <div
        class="relative w-full max-w-lg max-h-[90vh] overflow-y-auto rounded-3xl bg-blanc-pur text-noir-profond shadow-2xl"
        x-show="inscriptionOpen"
        x-transition:enter="transition duration-300"
        x-transition:enter-start="opacity-0 scale-95 translate-y-4"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition duration-200"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-4"
        x-data="{
            step: 1,
            success: false,
            loading: false,
            errors: {},
            form: {
                nom: '', prenom: '', email: '', telephone: '',
                entreprise: '', secteur: '', atelier: ''
            },
            goNext() {
                this.errors = {};
                if (!this.form.nom)    { this.errors.nom = 'Obligatoire'; return; }
                if (!this.form.prenom) { this.errors.prenom = 'Obligatoire'; return; }
                if (!this.form.email || !this.form.email.includes('@')) { this.errors.email = 'E-mail invalide'; return; }
                if (!this.form.entreprise) { this.errors.entreprise = 'Obligatoire'; return; }
                if (!this.form.secteur)    { this.errors.secteur = 'Obligatoire'; return; }
                this.step = 2;
            },
            async submit() {
                if (!this.form.atelier) { this.errors.atelier = 'Choisissez un atelier'; return; }
                this.loading = true;
                this.errors = {};
                try {
                    const res = await fetch('{{ route('pre-inscription.store') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify(this.form)
                    });
                    const data = await res.json();
                    if (res.ok && data.success) {
                        this.success = true;
                    } else if (data.errors) {
                        this.errors = data.errors;
                        if (data.errors.nom || data.errors.prenom || data.errors.email || data.errors.entreprise || data.errors.secteur) {
                            this.step = 1;
                        }
                    }
                } catch(e) {
                    this.errors.global = 'Une erreur est survenue. Réessayez.';
                } finally {
                    this.loading = false;
                }
            }
        }"
    >
        {{-- Filet tricolore --}}
        <div class="h-[3px] rounded-t-3xl" style="background: linear-gradient(to right, hsl(var(--vert-ivoire)), hsl(var(--blanc-pur) / 0.4) 50%, hsl(var(--vert-ivoire)));"></div>

        {{-- ══ ÉTAT SUCCÈS ══ --}}
        <div x-show="success" class="p-10 text-center" x-cloak>
            <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6"
                 style="background: hsl(var(--vert-ivoire) / 0.12);">
                <svg class="w-8 h-8" fill="none" stroke="hsl(var(--vert-ivoire))" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <h2 class="font-serif font-bold text-2xl text-noir-profond mb-3">Pré-inscription enregistrée !</h2>
            <p class="text-noir-profond/60 text-sm leading-relaxed mb-8">
                Votre demande a bien été prise en compte. Vous serez contacté(e) dès l'ouverture officielle des candidatures.
            </p>
            <button @click="inscriptionOpen = false; success = false; step = 1; form = {nom:'',prenom:'',email:'',telephone:'',entreprise:'',secteur:'',atelier:''}"
                    class="btn-fill px-7 py-3 text-sm"><span>Fermer</span></button>
        </div>

        {{-- ══ FORMULAIRE ══ --}}
        <div x-show="!success">

            {{-- En-tête --}}
            <div class="px-8 pt-8 pb-6 border-b border-noir-profond/08">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <div class="kicker-orange rounded-full mb-3 w-fit">Hub Import-Export 2026</div>
                        <h2 id="modal-inscription-title" class="font-serif font-bold text-xl text-noir-profond">
                            Pré-inscription
                        </h2>
                        <p class="text-sm text-noir-profond/50 mt-1">22–25 juin 2026 · Abidjan</p>
                    </div>
                    <button @click="inscriptionOpen = false"
                            class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 mt-1 transition-colors hover:bg-noir-profond/08"
                            aria-label="Fermer">
                        <svg class="w-4 h-4 text-noir-profond/50" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Indicateur d'étapes --}}
                <div class="flex items-center gap-2 mt-5">
                    <div class="h-1 flex-1 rounded-full transition-all duration-300"
                         :style="step >= 1 ? 'background: hsl(var(--vert-ivoire))' : 'background: hsl(var(--noir-profond) / 0.12)'"></div>
                    <div class="h-1 flex-1 rounded-full transition-all duration-300"
                         :style="step >= 2 ? 'background: hsl(var(--vert-ivoire))' : 'background: hsl(var(--noir-profond) / 0.12)'"></div>
                    <span class="text-xs text-noir-profond/40 font-mono ml-1" x-text="'Étape ' + step + '/2'"></span>
                </div>
            </div>

            {{-- Erreur globale --}}
            <div x-show="errors.global" class="mx-8 mt-4 px-4 py-3 rounded-xl text-sm text-red-700 bg-red-50 border border-red-200" x-text="errors.global"></div>

            {{-- ── ÉTAPE 1 : Identité & profil ── --}}
            <div x-show="step === 1" class="px-8 py-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-noir-profond/60 mb-1.5 uppercase tracking-wide">Nom *</label>
                        <input x-model="form.nom" type="text" placeholder="KOUASSI"
                               class="w-full rounded-xl border px-4 py-2.5 text-sm outline-none transition-colors focus:border-vert-ivoire"
                               :class="errors.nom ? 'border-red-400 bg-red-50' : 'border-noir-profond/15 bg-blanc-creme focus:bg-blanc-pur'">
                        <p x-show="errors.nom" class="text-xs text-red-500 mt-1" x-text="errors.nom"></p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-noir-profond/60 mb-1.5 uppercase tracking-wide">Prénom(s) *</label>
                        <input x-model="form.prenom" type="text" placeholder="Amara"
                               class="w-full rounded-xl border px-4 py-2.5 text-sm outline-none transition-colors focus:border-vert-ivoire"
                               :class="errors.prenom ? 'border-red-400 bg-red-50' : 'border-noir-profond/15 bg-blanc-creme focus:bg-blanc-pur'">
                        <p x-show="errors.prenom" class="text-xs text-red-500 mt-1" x-text="errors.prenom"></p>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-noir-profond/60 mb-1.5 uppercase tracking-wide">Adresse e-mail *</label>
                    <input x-model="form.email" type="email" placeholder="amara@entreprise.ci"
                           class="w-full rounded-xl border px-4 py-2.5 text-sm outline-none transition-colors focus:border-vert-ivoire"
                           :class="errors.email ? 'border-red-400 bg-red-50' : 'border-noir-profond/15 bg-blanc-creme focus:bg-blanc-pur'">
                    <p x-show="errors.email" class="text-xs text-red-500 mt-1" x-text="errors.email"></p>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-noir-profond/60 mb-1.5 uppercase tracking-wide">Téléphone</label>
                    <input x-model="form.telephone" type="tel" placeholder="+225 07 00 00 00 00"
                           class="w-full rounded-xl border border-noir-profond/15 bg-blanc-creme px-4 py-2.5 text-sm outline-none transition-colors focus:border-vert-ivoire focus:bg-blanc-pur">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-noir-profond/60 mb-1.5 uppercase tracking-wide">Entreprise *</label>
                    <input x-model="form.entreprise" type="text" placeholder="Nom de votre entreprise"
                           class="w-full rounded-xl border px-4 py-2.5 text-sm outline-none transition-colors focus:border-vert-ivoire"
                           :class="errors.entreprise ? 'border-red-400 bg-red-50' : 'border-noir-profond/15 bg-blanc-creme focus:bg-blanc-pur'">
                    <p x-show="errors.entreprise" class="text-xs text-red-500 mt-1" x-text="errors.entreprise"></p>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-noir-profond/60 mb-1.5 uppercase tracking-wide">Secteur d'activité *</label>
                    <select x-model="form.secteur"
                            class="w-full rounded-xl border px-4 py-2.5 text-sm outline-none transition-colors focus:border-vert-ivoire appearance-none"
                            :class="errors.secteur ? 'border-red-400 bg-red-50' : 'border-noir-profond/15 bg-blanc-creme focus:bg-blanc-pur'">
                        <option value="">Sélectionnez votre secteur</option>
                        <option>Agriculture & agroalimentaire</option>
                        <option>Commerce & distribution</option>
                        <option>Industrie & manufacture</option>
                        <option>Services aux entreprises</option>
                        <option>Logistique & transport</option>
                        <option>Technologies & numérique</option>
                        <option>Finance & assurance</option>
                        <option>Artisanat & textile</option>
                        <option>Autre</option>
                    </select>
                    <p x-show="errors.secteur" class="text-xs text-red-500 mt-1" x-text="errors.secteur"></p>
                </div>

                <div class="pt-2">
                    <button @click="goNext()" class="w-full btn-fill py-3 text-sm"><span>Continuer →</span></button>
                </div>
            </div>

            {{-- ── ÉTAPE 2 : Choix de l'atelier ── --}}
            <div x-show="step === 2" class="px-8 py-6">
                <p class="text-sm font-semibold text-noir-profond mb-1">Choisissez votre atelier</p>
                <p class="text-xs text-noir-profond/50 mb-5">Les ateliers se déroulent en groupes de 60 participants. Sélectionnez celui qui correspond le mieux à votre activité.</p>

                <div class="space-y-3">
                    @foreach([
                        ['zlecaf-cedeao',        '01', 'ZLECAf & CEDEAO',                 'Conquérir les marchés régionaux',   '--vert-ivoire'],
                        ['financement-garanties','02', 'Financement & garanties',          'Sécuriser ses opérations',          '--vert-fonce'],
                        ['commerce-electronique','03', 'Commerce électronique',             'Digitaliser ses échanges',          '--vert-ivoire'],
                        ['conformite-qualite',   '04', 'Conformité & qualité',             'Maîtriser les normes',              '--vert-ivoire'],
                    ] as [$slug, $num, $titre, $tagline, $color])
                    <label class="flex items-start gap-4 p-4 rounded-2xl cursor-pointer border transition-all duration-150"
                           :class="form.atelier === '{{ $slug }}'
                               ? 'border-vert-ivoire bg-vert-soft-bg'
                               : 'border-noir-profond/10 bg-blanc-creme hover:border-noir-profond/25'">
                        <input type="radio" name="atelier" value="{{ $slug }}" x-model="form.atelier" class="sr-only">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center font-mono text-xs font-bold flex-shrink-0 mt-0.5"
                             style="background: hsl(var({{ $color }}) / 0.12); color: hsl(var({{ $color }}));">
                            {{ $num }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-sm text-noir-profond leading-tight">{{ $titre }}</p>
                            <p class="text-xs mt-0.5" style="color: hsl(var({{ $color }}));">{{ $tagline }}</p>
                        </div>
                        <div class="w-4 h-4 rounded-full border-2 flex-shrink-0 mt-1 transition-all duration-150"
                             :class="form.atelier === '{{ $slug }}'
                                 ? 'border-vert-ivoire bg-vert-ivoire'
                                 : 'border-noir-profond/25'"
                             aria-hidden="true"></div>
                    </label>
                    @endforeach
                </div>

                <p x-show="errors.atelier" class="text-xs text-red-500 mt-3" x-text="errors.atelier"></p>

                <div class="flex gap-3 mt-6">
                    <button @click="step = 1; errors = {}" class="flex-1 py-3 rounded-2xl text-sm font-semibold border border-noir-profond/15 text-noir-profond/70 hover:border-noir-profond/30 transition-colors">
                        ← Retour
                    </button>
                    <button @click="submit()" :disabled="loading"
                            class="flex-2 btn-fill py-3 text-sm flex-1"
                            :class="loading ? 'opacity-60 cursor-wait' : ''">
                        <span x-text="loading ? 'Envoi…' : 'Confirmer ma pré-inscription'"></span>
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>
