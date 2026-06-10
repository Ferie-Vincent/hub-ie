<x-layouts.public :title="'Dev — Composants Hub Import-Export 2026'">

{{-- ══════════════════════════════════════════════════════════════════════════ --}}
{{-- PAGE DE DÉMONSTRATION DES COMPOSANTS — LOCAL UNIQUEMENT (Phase 3)        --}}
{{-- Accessibilité WCAG 2.1 AA, Lighthouse Accessibility ≥ 95                 --}}
{{-- ══════════════════════════════════════════════════════════════════════════ --}}

<x-page-hero kicker="Dev">
    Composants <em class="font-fraunces italic text-orange-soft" style="font-variation-settings: 'opsz' 144, 'SOFT' 100;">design system.</em>
</x-page-hero>

<div class="bg-blanc-creme pb-24">
    <div class="max-w-hub mx-auto px-6 pt-14 space-y-20">

        {{-- ── SECTION : Kickers ───────────────────────────────────────────── --}}
        <section aria-labelledby="demo-kickers">
            <h2 id="demo-kickers" class="font-mono text-xs font-bold uppercase tracking-widest mb-6 border-b pb-3" style="color:#595147;border-color:rgba(89,81,71,0.2)">
                &lt;x-kicker&gt; — Badges de section
            </h2>
            <div class="flex flex-wrap gap-4">
                <x-kicker color="orange">Orange</x-kicker>
                <x-kicker color="vert">Vert</x-kicker>
                <x-kicker color="sable">Sable</x-kicker>
                <x-kicker color="violet">Violet</x-kicker>
            </div>
        </section>

        {{-- ── SECTION : Section Titles ────────────────────────────────────── --}}
        <section aria-labelledby="demo-titles">
            <h2 id="demo-titles" class="font-mono text-xs font-bold uppercase tracking-widest mb-6 border-b pb-3" style="color:#595147;border-color:rgba(89,81,71,0.2)">
                &lt;x-section-title&gt; — Titres de section
            </h2>
            <div class="space-y-10">
                <x-section-title kicker="Kicker orange" kickerColor="orange" sub="Sous-titre descriptif de la section">
                    Titre <em class="font-fraunces italic text-vert-ivoire" style="font-variation-settings: 'opsz' 144, 'SOFT' 100;">principal</em>
                </x-section-title>
                <x-section-title kicker="Kicker vert" kickerColor="vert" sub="Un autre sous-titre">
                    Variante <em class="font-fraunces italic text-vert-ivoire" style="font-variation-settings: 'opsz' 144, 'SOFT' 100;">verte</em>
                </x-section-title>
                <div class="bg-noir-profond p-10 rounded-2xl">
                    <x-section-title kicker="Fond sombre" kickerColor="orange" sub="Sous-titre sur fond sombre" :dark="true">
                        Titre sur <em class="font-fraunces italic text-orange-soft" style="font-variation-settings: 'opsz' 144, 'SOFT' 100;">fond noir</em>
                    </x-section-title>
                </div>
            </div>
        </section>

        {{-- ── SECTION : Stat Cards ────────────────────────────────────────── --}}
        <section aria-labelledby="demo-stats">
            <h2 id="demo-stats" class="font-mono text-xs font-bold uppercase tracking-widest mb-6 border-b pb-3" style="color:#595147;border-color:rgba(89,81,71,0.2)">
                &lt;x-stat-card&gt; — Cartes de statistiques
            </h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <x-stat-card value="180" label="Auditeurs" caption="Participants attendus" color="orange" />
                <x-stat-card value="17 016 Mds" label="FCFA d'exports CI" caption="Commerce extérieur 2023" color="vert" />
                <x-stat-card value="4" label="Ateliers thématiques" caption="Sessions parallèles" color="orange" />
                <x-stat-card value="+165%" label="Croissance exports" caption="Progression décennale CI" color="vert" />
            </div>
        </section>

        {{-- ── SECTION : Glass Panels ──────────────────────────────────────── --}}
        <section aria-labelledby="demo-glass">
            <h2 id="demo-glass" class="font-mono text-xs font-bold uppercase tracking-widest mb-6 border-b pb-3" style="color:#595147;border-color:rgba(89,81,71,0.2)">
                &lt;x-glass-panel&gt; — Panneaux glassmorphiques
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-noir-profond rounded-2xl p-2">
                    <x-glass-panel variant="glass" :rounded="true" padding="p-6">
                        <p class="text-blanc-pur font-medium mb-2">Glass (fond sombre)</p>
                        <p class="text-sm" style="color:rgba(250,250,248,.7)">Pour les sections sur fond noir/sombre.</p>
                    </x-glass-panel>
                </div>
                <x-glass-panel variant="glass-light" :rounded="true" padding="p-6">
                    <p class="text-noir-profond font-medium mb-2">Glass Light (fond clair)</p>
                    <p class="text-sm" style="color:hsl(var(--gris-700))">Pour les sections sur fond clair.</p>
                </x-glass-panel>
                <div class="bg-noir-profond rounded-2xl p-2">
                    <x-glass-panel variant="glass-dark" :rounded="true" padding="p-6">
                        <p class="text-blanc-pur font-medium mb-2">Glass Dark</p>
                        <p class="text-sm" style="color:rgba(250,250,248,.7)">Variante plus sombre.</p>
                    </x-glass-panel>
                </div>
            </div>
        </section>

        {{-- ── SECTION : CTA Buttons ───────────────────────────────────────── --}}
        <section aria-labelledby="demo-cta">
            <h2 id="demo-cta" class="font-mono text-xs font-bold uppercase tracking-widest mb-6 border-b pb-3" style="color:#595147;border-color:rgba(89,81,71,0.2)">
                &lt;x-cta-button&gt; — Boutons CTA
            </h2>
            <div class="flex flex-wrap gap-4 items-center">
                <x-cta-button href="#" size="sm">Petit</x-cta-button>
                <x-cta-button href="#" size="md">Moyen (défaut)</x-cta-button>
                <x-cta-button href="#" size="lg">Grand</x-cta-button>
                <x-cta-button href="#" size="md" :external="true">Lien externe</x-cta-button>
                <x-cta-button size="md" as="button">Bouton natif</x-cta-button>
            </div>
        </section>

        {{-- ── SECTION : Tags ──────────────────────────────────────────────── --}}
        <section aria-labelledby="demo-tags">
            <h2 id="demo-tags" class="font-mono text-xs font-bold uppercase tracking-widest mb-6 border-b pb-3" style="color:#595147;border-color:rgba(89,81,71,0.2)">
                &lt;x-tag&gt; — Tags catégorie programme
            </h2>
            <div class="flex flex-wrap gap-3">
                <x-tag color="orange">Plénière</x-tag>
                <x-tag color="vert">Atelier</x-tag>
                <x-tag color="sable">Networking</x-tag>
                <x-tag color="violet">Panel</x-tag>
                <x-tag color="blue">Forum B2B</x-tag>
            </div>
        </section>

        {{-- ── SECTION : Program Rows ──────────────────────────────────────── --}}
        <section aria-labelledby="demo-prog">
            <h2 id="demo-prog" class="font-mono text-xs font-bold uppercase tracking-widest mb-6 border-b pb-3" style="color:#595147;border-color:rgba(89,81,71,0.2)">
                &lt;x-program-row&gt; — Lignes de programme
            </h2>
            <div class="bg-blanc-pur rounded-2xl overflow-hidden divide-y divide-gris-500/10">
                <x-program-row num="01" tag="Plénière" tagColor="orange">
                    Cérémonie d'ouverture officielle sous le haut patronage du Ministre du Commerce
                </x-program-row>
                <x-program-row num="02" tag="Atelier" tagColor="vert">
                    ZLECAf et intégration régionale CEDEAO — enjeux pour les PME ivoiriennes
                </x-program-row>
                <x-program-row num="03" tag="Panel" tagColor="violet">
                    Financement des exportations et mécanismes de garantie
                </x-program-row>
                <x-program-row num="04" tag="Networking" tagColor="sable">
                    Forum B2B — sessions matchmaking entreprises locales / partenaires internationaux
                </x-program-row>
            </div>
        </section>

        {{-- ── SECTION : Workshop Cards ────────────────────────────────────── --}}
        <section aria-labelledby="demo-workshops">
            <h2 id="demo-workshops" class="font-mono text-xs font-bold uppercase tracking-widest mb-6 border-b pb-3" style="color:#595147;border-color:rgba(89,81,71,0.2)">
                &lt;x-workshop-card&gt; — Cards ateliers (hover 5 effets)
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-workshop-card
                    num="01"
                    kicker="Commerce régional"
                    title="ZLECAf & CEDEAO"
                    description="Comprendre les accords de libre-échange continental et leurs opportunités pour les exportateurs ivoiriens."
                    accent="orange"
                    href="#"
                    iconPath="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"
                />
                <x-workshop-card
                    num="02"
                    kicker="Financement"
                    title="Mécanismes de garantie"
                    description="Instruments financiers, garanties à l'export et fonds d'appui aux PME exportatrices."
                    accent="vert"
                    href="#"
                    iconPath="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z"
                />
            </div>
        </section>

        {{-- ── SECTION : Format Cards ───────────────────────────────────────── --}}
        <section aria-labelledby="demo-formats">
            <h2 id="demo-formats" class="font-mono text-xs font-bold uppercase tracking-widest mb-6 border-b pb-3" style="color:#595147;border-color:rgba(89,81,71,0.2)">
                &lt;x-format-card&gt; — Cards formats d'échange
            </h2>
            <div class="bg-noir-profond p-8 rounded-2xl grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-format-card
                    num="01"
                    kicker="Jour 1"
                    title="Cérémonie d'ouverture"
                    description="Ouverture officielle sous le haut patronage du Ministre, avec allocutions institutionnelles et cadrage stratégique."
                    accent="orange"
                    iconPath="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.038 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.038-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0 1 12 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 0 1 3 12c0-1.605.42-3.113 1.157-4.418"
                />
                <x-format-card
                    num="02"
                    kicker="Jours 2–4"
                    title="Ateliers thématiques"
                    description="Sessions parallèles par thématique, animées par des experts sectoriels et des représentants institutionnels."
                    accent="vert"
                    iconPath="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z"
                />
            </div>
        </section>

        {{-- ── SECTION : Countdown ─────────────────────────────────────────── --}}
        <section aria-labelledby="demo-countdown">
            <h2 id="demo-countdown" class="font-mono text-xs font-bold uppercase tracking-widest mb-6 border-b pb-3" style="color:#595147;border-color:rgba(89,81,71,0.2)">
                &lt;x-countdown&gt; — Compteur live jusqu'au 22 juin 2026
            </h2>
            <div class="bg-noir-profond rounded-2xl p-8">
                <x-countdown />
            </div>
        </section>

        {{-- ── SECTION : Animations ─────────────────────────────────────────── --}}
        <section aria-labelledby="demo-animations">
            <h2 id="demo-animations" class="font-mono text-xs font-bold uppercase tracking-widest mb-6 border-b pb-3" style="color:#595147;border-color:rgba(89,81,71,0.2)">
                Keyframes — 13 animations définies
            </h2>
            <div class="bg-blanc-pur rounded-2xl p-8">
                <ul class="grid grid-cols-2 md:grid-cols-3 gap-3 text-sm">
                    @foreach([
                        ['draw',            'Tracé SVG logo (au mount)'],
                        ['fade-up',         'Apparition scroll reveal'],
                        ['v10-fade-in',     'Apparition tab content'],
                        ['marquee',         'Défilement sponsors'],
                        ['pulse',           'Pulsation opacité dot'],
                        ['v10-pulse',       'Halo orange pulsant'],
                        ['v10-green-pulse', 'Halo vert pulsant'],
                        ['v10-accent-rule', 'Ligne d\'accent mot'],
                        ['v10-float',       'Flottement icône hover'],
                        ['v10-draw',        'Redessinage SVG hover'],
                        ['hub-float-subtle','Flottement ambiant portrait'],
                        ['v10-bar-in',      'Barre verticale prog-row'],
                        ['v10-line-in',     'Ligne bord bas format-card'],
                    ] as [$name, $desc])
                    <li class="flex items-start gap-2">
                        <span class="mt-0.5 flex-shrink-0 w-2 h-2 rounded-full bg-vert-ivoire"></span>
                        <span>
                            <code class="text-xs font-mono text-vert-fonce">{{ $name }}</code>
                            <span class="block text-xs" style="color:hsl(var(--gris-700))">{{ $desc }}</span>
                        </span>
                    </li>
                    @endforeach
                </ul>
            </div>
        </section>

        {{-- ── SECTION : Typography ─────────────────────────────────────────── --}}
        <section aria-labelledby="demo-typo">
            <h2 id="demo-typo" class="font-mono text-xs font-bold uppercase tracking-widest mb-6 border-b pb-3" style="color:#595147;border-color:rgba(89,81,71,0.2)">
                Typographie — 5 familles de polices
            </h2>
            <div class="bg-blanc-pur rounded-2xl p-8 space-y-6">
                <div>
                    <p class="text-xs font-mono mb-1" style="color:hsl(var(--gris-700))">font-sans (Inter)</p>
                    <p class="font-sans text-2xl font-bold text-noir-profond">Commerce extérieur ivoirien — 400 500 600 700</p>
                </div>
                <div>
                    <p class="text-xs font-mono mb-1" style="color:hsl(var(--gris-700))">font-serif (Playfair Display)</p>
                    <p class="font-serif text-2xl font-bold text-noir-profond">Hub Import-Export 2026 — Abidjan</p>
                </div>
                <div>
                    <p class="text-xs font-mono mb-1" style="color:hsl(var(--gris-700))">font-fraunces (Fraunces)</p>
                    <p class="font-fraunces text-2xl italic text-vert-ivoire" style="font-variation-settings: 'opsz' 144, 'SOFT' 100;">Quatre <em>ateliers,</em> une stratégie.</p>
                </div>
                <div>
                    <p class="text-xs font-mono mb-1" style="color:hsl(var(--gris-700))">font-manrope (Manrope)</p>
                    <p class="font-manrope text-2xl font-bold text-noir-profond">DGCE — Ministère du Commerce</p>
                </div>
                <div>
                    <p class="text-xs font-mono mb-1" style="color:hsl(var(--gris-700))">font-mono (JetBrains Mono)</p>
                    <p class="font-mono text-xl text-vert-fonce">22–25 juin 2026 · Abidjan · Côte d'Ivoire</p>
                </div>
            </div>
        </section>

        {{-- ── SECTION : Colors ─────────────────────────────────────────────── --}}
        <section aria-labelledby="demo-colors">
            <h2 id="demo-colors" class="font-mono text-xs font-bold uppercase tracking-widest mb-6 border-b pb-3" style="color:#595147;border-color:rgba(89,81,71,0.2)">
                Palette — 13 tokens HSL
            </h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-3">
                @foreach([
                    ['bg-noir-profond', 'text-blanc-pur',    'noir-profond',   'Noir profond',   '#0D0F0D'],
                    ['bg-noir-doux',    'text-blanc-pur',    'noir-doux',      'Noir doux',      '#1A1D1A'],
                    ['bg-blanc-pur',    'text-noir-profond', 'blanc-pur',      'Blanc pur',      '#FAFAF8'],
                    ['bg-blanc-creme',  'text-noir-profond', 'blanc-creme',    'Blanc crème',    '#F5F2ED'],
                    ['bg-vert-ivoire','text-blanc-pur',    'orange-ivoire',  'Orange ivoire',  '#E8741C'],
                    ['bg-orange-brule', 'text-blanc-pur',    'orange-brule',   'Orange brûlé',   '#C45A0A'],
                    ['bg-orange-soft',  'text-blanc-pur',    'orange-soft',    'Orange soft',    '#F09E62'],
                    ['bg-vert-ivoire',  'text-blanc-pur',    'vert-ivoire',    'Vert ivoire',    '#009A44'],
                    ['bg-vert-soft',    'text-blanc-pur',    'vert-soft',      'Vert soft',      '#4CAF7A'],
                    ['bg-sable',        'text-blanc-pur',    'sable',          'Sable',          '#C5A96A'],
                    ['bg-gris-500',     'text-blanc-pur',    'gris-500',       'Gris 500',       '#6B7280'],
                ] as [$bg, $text, $token, $name, $hex])
                <div class="rounded-xl overflow-hidden shadow-card">
                    <div class="{{ $bg }} h-14 flex items-end px-3 pb-2">
                        <span class="{{ $text }} text-xs font-mono opacity-80">{{ $hex }}</span>
                    </div>
                    <div class="bg-blanc-pur px-3 py-2">
                        <p class="text-xs font-bold text-noir-profond">{{ $name }}</p>
                        <p class="text-xs font-mono text-gris-500">{{ $token }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </section>

        {{-- ── SECTION : Links ──────────────────────────────────────────────── --}}
        <section aria-labelledby="demo-links">
            <h2 id="demo-links" class="font-mono text-xs font-bold uppercase tracking-widest mb-6 border-b pb-3" style="color:#595147;border-color:rgba(89,81,71,0.2)">
                Liens &amp; navigation — .link-underline, .hub-tab-hover
            </h2>
            <div class="flex flex-wrap gap-8 items-center bg-blanc-pur rounded-2xl p-8">
                <a href="#" class="link-underline text-noir-profond font-medium">Lien avec soulignement animé</a>
                <a href="#" class="link-underline text-vert-ivoire font-medium">Lien orange</a>
                <a href="#" class="hub-tab-hover px-4 py-2 text-sm text-noir-profond font-medium rounded-lg relative">Tab nav</a>
                <a href="#" class="hub-footer-link text-sm text-blanc-pur/60 bg-noir-profond px-3 py-1 rounded">Footer link</a>
            </div>
        </section>

        {{-- ── SECTION : Reveal ─────────────────────────────────────────────── --}}
        <section aria-labelledby="demo-reveal" class="pb-4">
            <h2 id="demo-reveal" class="font-mono text-xs font-bold uppercase tracking-widest mb-6 border-b pb-3" style="color:#595147;border-color:rgba(89,81,71,0.2)">
                Intersection Observer — .reveal / .is-visible
            </h2>
            <div class="bg-blanc-pur rounded-2xl p-8 space-y-4">
                <div class="reveal bg-vert-soft-bg rounded-xl p-6">
                    <p class="text-sm text-noir-profond font-medium">Élément 1 — apparaît au scroll (fade-up 0.8s)</p>
                </div>
                <div class="reveal bg-vert-soft-bg rounded-xl p-6" style="transition-delay: 0.1s">
                    <p class="text-sm text-noir-profond font-medium">Élément 2 — décalé de 100ms</p>
                </div>
                <div class="reveal bg-blanc-creme rounded-xl p-6" style="transition-delay: 0.2s">
                    <p class="text-sm text-noir-profond font-medium">Élément 3 — décalé de 200ms</p>
                </div>
            </div>
        </section>

    </div>
</div>

</x-layouts.public>
