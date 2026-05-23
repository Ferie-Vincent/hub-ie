# Hub Import-Export 2026 — Brief technique

> Spécification technique consolidée. Les exigences **DOIT** sont contraignantes ; **DEVRAIT** est fortement recommandé. Pour le contenu rédactionnel (textes officiels, emails, mentions légales) : voir `docs/CONTENT.md`. Pour le séquencement d'implémentation : voir `docs/IMPLEMENTATION-PLAN.md`.

---

# PLAN I — PRODUIT

## I.1 Identité du projet

| | |
|---|---|
| Nom | Hub Import-Export 2026 |
| URL | hubimportexport.ci |
| Commanditaire | Ministère du Commerce, de l'Industrie et de l'Artisanat (MCIA) — Côte d'Ivoire |
| Maîtrise d'ouvrage opérationnelle | Direction Générale du Commerce Extérieur (DGCE) |
| Partenaires techniques et financiers | TradeMark Africa, GIZ |
| Thème | « Résilience et compétitivité du commerce extérieur ivoirien : s'outiller pour conquérir les marchés stratégiques dans un monde en pleine crise » |
| Dates | Du lundi 22 au jeudi 25 juin 2026 |
| Lieux | CGECI · CCI-CI · SEEN Hôtel Abidjan-Plateau |
| Auditeurs visés | 180 sélectionnés, répartis en 3 groupes (G1, G2, G3) |
| Langue V1 | Français exclusivement |

## I.2 Référence visuelle obligatoire

La plateforme **DOIT** reproduire fidèlement l'expérience visuelle et interactive de `https://diasporaforgrowth.ci/`. C'est lui-même un événement officiel ivoirien (Forum Diaspora for Growth, Paris 2026). L'intention est de **prolonger une cohérence chromatique et typographique d'État** entre événements institutionnels ivoiriens.

**Repris à l'identique** : palette HSL, typographies, keyframes d'animation, pattern de titres (Inter bold + Fraunces italic coloré), comportement glassmorphique du hero, marquee partenaires, countdown, hover multi-effets des cards, ligne d'accent animée sous mot, dots avec halo pulsant, transitions sticky du header.

**Ce qui change** : contenus, photos, logo, module candidature avec validation comité, QR code + code 6 chiffres + scan d'entrée, dashboard de pilotage.

## I.3 Les trois fonctions de la plateforme

**1. Vitrine institutionnelle.** Présentation officielle : programme, ateliers, partenaires, mot du Ministre, formats d'échange, cadre stratégique, FAQ, mentions légales. Ce que voient les visiteurs non connectés.

**2. Portail de candidature.** Les acteurs économiques ivoiriens candidatent en ligne via un compte personnel. Le dossier est instruit administrativement, puis évalué par un comité. Les retenus reçoivent par email une convocation officielle avec **QR code unique** et **code numérique à 6 chiffres** servant de pointage à l'entrée.

**3. Outil de pilotage et de pointage.** Back-office Filament : tri des dossiers, évaluation comité, décision, suivi quotas, scan d'entrée en présentiel (caméra QR ou saisie code 6 chiffres), KPIs temps réel, exports Excel.

## I.4 Personae

| Persona | Description | Cas d'usage prioritaire |
|---|---|---|
| **Le candidat** | Acteur économique ivoirien (agro-transformateur, PME exportatrice, transitaire, banquier, journaliste, universitaire, agent public). Souvent sur mobile. Maîtrise variable du numérique. | Découvrir le Hub, candidater en 4 étapes, suivre son statut, télécharger son badge si retenu. |
| **L'agent DGCE** | Administratif chargé de la vérification de complétude. | Filtrer les nouveaux dossiers, vérifier les pièces, marquer recevable ou incomplet. |
| **Le membre du comité** | Expert (DGCE, ACIEx, CNE, TradeMark Africa, GIZ, secteur privé). | Lire un dossier, attribuer un score sur 5 critères, commenter. |
| **Le président du comité** | DGCE haut niveau ou délégué du Ministre. | Consulter scores, vérifier quotas, décider retenu / liste d'attente / non retenu, exporter. |
| **L'agent d'accueil** | Personnel mobilisé sur site. | Scanner les badges (QR) ou saisir les codes à 6 chiffres. |
| **Le responsable communication** | Communication MCIA / DGCE. | Publier actualités, ajuster les partenaires visibles, alimenter FAQ. |
| **Le journaliste** | Presse accréditée. | Accéder à l'espace presse. |

## I.5 Objectifs de l'événement (TDR officiel)

Renforcer la compétitivité et l'intégration commerciale des entreprises ivoiriennes sur les marchés régionaux et internationaux, par un accompagnement stratégique, réglementaire et opérationnel :

- Sensibiliser sur les opportunités CEDEAO, ZLECAf, UE et Chine.
- Accompagner la mise en conformité (normes qualité, certification, douane).
- Encourager les outils numériques et la digitalisation des procédures.
- Promouvoir l'accès au financement et aux garanties.
- Favoriser l'accès à l'information stratégique sur les marchés mondiaux.

## I.6 Principes directeurs non-négociables

- **Sobriété institutionnelle, expérience premium.** Sérieux d'un ministère + modernité d'un événement international. Aucun élément ludique ou amateur. Aucune iconographie générique de banque d'images.
- **Glassmorphisme assumé.** `backdrop-filter: blur(20px)` + fond translucide + bordure subtile, **obligatoire** sur le hero (carte dates clés) et en seconde intention sur certaines cards + header au scroll. C'est une signature visuelle.
- **Animations comme grammaire, pas comme bruit.** Toutes les animations du § II.5 à reproduire, aucune supplémentaire à inventer.
- **Mobile-first.** 60 % du trafic candidat sera mobile. Le wizard doit fonctionner parfaitement sur smartphone.
- **Performance perçue.** Lighthouse Performance ≥ 90 sur `/`. Aucune animation au-delà de 60 fps. Aucune image > 200 ko après optimisation. Lazy-loading des images sous le fold.
- **Accessibilité.** WCAG 2.1 AA. Tous les boutons et liens accessibles au clavier. Contrastes conformes. SVG décoratifs en `aria-hidden`, SVG informatifs avec `aria-label`.
- **Souveraineté des données.** Aucun service tiers ne collecte les données candidats. Hébergement en Côte d'Ivoire ou Europe. Conformité **Loi n°2013-450** sur la protection des données à caractère personnel (ARTCI).

---

# PLAN II — IDENTITÉ VISUELLE ET ANIMATIONS

## II.1 Palette de couleurs

Variables CSS HSL **sans alpha**, pour composition avec opacité Tailwind via `<alpha-value>`.

| Token | Valeur HSL | Rôle |
|---|---|---|
| `--noir-profond` | `240 8% 4%` | Background hero, footer, sections dark |
| `--noir-doux` | `240 6% 11%` | Cards dark, contrastes secondaires |
| `--blanc-pur` | `0 0% 100%` | Texte sur fond sombre |
| `--blanc-creme` | `36 35% 97%` | Background des sections claires |
| `--orange-ivoire` | `30 100% 48%` | **Primaire**, CTAs, badges, accents |
| `--orange-brule` | `22 67% 52%` | Hover sur orange-ivoire |
| `--orange-soft` | `28 100% 67%` | Tags, labels colorés sur fond sombre |
| `--orange-soft-bg` | `30 100% 95%` | Fond des badges oranges sur fond clair |
| `--vert-ivoire` | `147 100% 30%` | **Secondaire**, onglets actifs, accents |
| `--vert-soft` | `147 55% 55%` | Vert clair |
| `--vert-soft-bg` | `147 50% 94%` | Fond des badges verts |
| `--sable` | `33 35% 87%` | Séparateurs, fonds neutres |
| `--gris-500` | `24 5% 47%` | Texte secondaire |

Le logo HIE utilise déjà ces deux couleurs primaires. Aucune adaptation chromatique nécessaire.

## II.2 Typographies

Cinq polices web depuis Google Fonts. Hiérarchie d'usage stricte.

| Rôle | Police | Particularités |
|---|---|---|
| Titres de section principaux | Playfair Display (serif) | Bold, 40–96px |
| Variante display sobre | Fraunces (variable serif) | `opsz: 144, SOFT: 50` |
| Mots-clés italiques colorés | Fraunces italic | `opsz: 144, SOFT: 100` |
| Corps, UI, nav, boutons | Inter (sans-serif) | Regular / Medium / Semibold |
| Titres section programme | Manrope (sans-serif) | Weight 200, letter-spacing -0.04em |
| Chiffres, codes, monospaces | JetBrains Mono | Features `tnum`, `ss01` |

**Pattern de titres systématique** : Inter bold (ou Manrope pour le programme) + un ou deux mots-clés en Fraunces italic coloré (orange-ivoire ou vert-ivoire). Cette tension typographique est la signature du site.

Exemples adaptés au Hub :
- « Résilience et **compétitivité** pour *conquérir les marchés.* »
- « Quatre **ateliers** pour *s'outiller en pratique.* »
- « D'une *ambition* partagée à des *résultats concrets.* »
- « Quatre jours, *une trajectoire d'outillage.* »
- « Ils nous *accompagnent.* »
- « Rejoignez le *Hub.* »

## II.3 Tailles et espacements

Typographie fluide en `clamp()`.

| Élément | Taille |
|---|---|
| H1 hero | `clamp(2.5rem, 6vw, 5rem)`, line-height 1.05, letter-spacing -0.02em |
| H2 section | `clamp(2rem, 4vw, 3.5rem)`, line-height 1.1, letter-spacing -0.02em |
| Sous-titre / intro | 1.125–1.25rem, color `--gris-500` |
| Corps | 1rem, line-height 1.6 |
| Kicker | 0.75rem, uppercase, letter-spacing 0.15em, bold |

Conteneur principal : `max-width: 1280px`, padding 1.5rem (mobile) à 2.5rem (desktop). Sections espacées 6rem (mobile) à 8rem (desktop). Cards arrondies `rounded-2xl` (16px) à `rounded-3xl` (24px).

## II.4 Glassmorphisme

Trois variantes en classes utilitaires CSS.

**`.glass`** (variante hero, fond sombre) :
- `background: hsla(0 0% 100% / 0.08)`
- `backdrop-filter: blur(20px)` + équivalent `-webkit-`
- `border: 1px solid hsla(0 0% 100% / 0.18)`
- `box-shadow: 0 8px 32px 0 rgba(0,0,0,0.18)`

**`.glass-light`** (variante header au scroll) :
- `background: hsla(0 0% 100% / 0.5)`
- `backdrop-filter: blur(12px)`
- `border: 1px solid hsla(0 0% 100% / 0.6)`

**`.glass-dark`** (variante bandeau patronage dans le hero) :
- `background: hsla(240 8% 4% / 0.45)`
- `backdrop-filter: blur(16px)`
- `border: 1px solid hsla(0 0% 100% / 0.08)`

**Fallback Safari** : sans support `backdrop-filter` → opacité augmentée (0.85 sur `--blanc-pur` au lieu de 0.5).

## II.5 Animations — catalogue exhaustif

13 keyframes à implémenter. Toutes utilisent `cubic-bezier(0.16, 1, 0.3, 1)` (out-expo doux) sauf mention. À coder strictement comme décrites — pas de variation.

### II.5.1 `draw` — tracé SVG du logo (au mount)
- `stroke-dasharray: 3000` + `stroke-dashoffset: 3000` au repos → `stroke-dashoffset: 0`.
- Durée 2.8s, easing `cubic-bezier(0.16, 1, 0.3, 1)`, `forwards`.
- Application : paths du logo HIE dans le hero. Une seule fois au chargement.

### II.5.2 `fade-up` — apparition au scroll
- Opacité 0 → 1, translateY 12px → 0.
- Durée 0.8s, easing `cubic-bezier(0.16, 1, 0.3, 1)`, `both`.
- Déclencheur : Intersection Observer, ajout `.is-visible` à seuil 15 %.
- Application : éléments avec classe `.reveal`.

### II.5.3 `v10-fade-in` — apparition rapide et subtile
- Opacité 0 → 1, translateY 6px → 0.
- Durée 0.35s, `ease-out`, `both`.
- Application : changement de contenu sur tabs (jour 1/2/3/4 du programme).

### II.5.4 `marquee` — défilement horizontal des sponsors
- Translation `0` à `-50%`.
- Durée 30s, linéaire, `infinite`.
- Liste de logos dupliquée 4 fois côte à côte pour continuité visuelle.
- Pause au hover : non (défilement continu).
- Application : section "Ils nous accompagnent".

### II.5.5 `pulse` — pulsation d'opacité
- Opacité 1 → 0.5 → 1.
- Durée 2s, `cubic-bezier(0.4, 0, 0.6, 1)`, `infinite`.
- Application : dot orange au-dessus du label "Rendez-vous à Abidjan dans".

### II.5.6 `v10-pulse` — halo orange pulsant
- `box-shadow` orange (#E8741C) s'étend de 0 à 10px puis disparaît.
- Durée 2s, `ease`, `infinite`.
- Application : dot statut dans les dates-clés du hero.

### II.5.7 `v10-green-pulse` — halo vert pulsant
- Identique mais en vert (#009A44), expansion à 8px.
- Durée 2.4s, `ease`, `infinite`.
- Application : dot devant les labels de jours dans Programme.

### II.5.8 `v10-accent-rule` — ligne d'accent animée sous un mot
- Pseudo-élément `::after`, gradient vert transparent-vert-transparent, `scaleX(0) → 1` depuis la gauche.
- Durée 1.4s, easing `cubic-bezier(0.16, 1, 0.3, 1)`, délai 0.4s, `forwards`.
- Déclenchement : Intersection Observer.
- Application : sous le mot italique vert dans le titre de la section Programme.

### II.5.9 `v10-float` — flottement d'icône au hover
- translateY 0 → -6px → 0, légère rotation -2deg au milieu.
- Durée 3s, `ease-in-out`, `infinite`.
- Déclenchement : hover sur cards d'ateliers et formats.

### II.5.10 `v10-draw` — redessinage d'icône SVG au hover
- `stroke-dashoffset` 200 → 0.
- Durée 1.2s, `cubic-bezier(0.16, 1, 0.3, 1)`, `both`.
- Déclenchement : hover sur cards d'ateliers et formats.

### II.5.11 Hover des cards `.v10-format-card` — 5 effets simultanés

Composant le plus expressif du site. Au hover, **cinq effets simultanés** :

1. La card monte de 8px (`translateY(-8px)`), transition 500ms.
2. Gradient diagonal vert léger (`linear-gradient(135deg, rgba(0,154,68,0.1), transparent 60%)`) apparaît (`opacity: 0 → 1`).
3. Coin supérieur droit (triangle décoratif `border-style`) passe d'un vert très estompé à un vert plus visible.
4. Fine ligne verte en bas (`scaleX(0) → 1` depuis la gauche, durée 600ms).
5. L'icône SVG se met à flotter (`v10-float`) ET ses paths se retracent (`v10-draw`).

À coder exactement. Pas d'omission. Pas d'ajout.

### II.5.12 Hover des lignes de programme `.v10-prog-row` — 4 effets simultanés

1. Fond passe à dégradé horizontal (`rgba(0,154,68,0.04)` → transparent à 70%).
2. Barre verticale verte 3px apparaît à gauche, `height: 0 → 70%`.
3. Numéro de ligne passe en vert et se décale de 4px vers la droite.
4. Tag de catégorie se décale de 4px vers la gauche.

Effet d'écartement délicat. Transition 400–500ms.

### II.5.13 Bouton `.btn-fill` — remplissage gauche-droite
- Background `--orange-ivoire`, texte blanc à l'état initial.
- Hover : pseudo-élément `::before` en `--orange-brule`, `scaleX(0) → 1` depuis l'origine gauche.
- Durée 0.45s, `cubic-bezier(0.16, 1, 0.3, 1)`.
- Application : tous les CTAs principaux.

### II.5.14 Liens `.link-underline` — soulignement animé
- Underline `scaleX(0)` invisible à l'état initial.
- Hover : `scaleX(0) → 1` depuis la gauche, durée 0.35s.
- Application : tous les liens de nav, footer, inline.

## II.6 Comportement du header au scroll

État initial (top) : transparent, texte blanc, logo standard.

À partir de 80px de scroll : classe `.glass-light` appliquée (blur 12px + fond blanc translucide), texte en noir profond, logo en version sombre.

Transition : `transition-all duration-300`. Implémentation via Alpine.js (`x-data` qui écoute `window.scrollY`).

## II.7 Composants visuels signature

### Pastille "kicker"
Badge horizontal : `inline-block px-3 py-1` ou `px-4 py-1.5`, fond `orange-soft-bg` ou `vert-soft-bg`, texte `orange-brule` ou `vert-ivoire`, `text-xs`, uppercase, `tracking-widest`, bold. Parfois précédé de `── ` pour les sections du milieu.

### Carte glassmorphique du hero (dates clés)
`rounded-3xl`, padding 24–32px, classe `.glass`. Contient :
- Kicker "DATES CLÉS"
- Liste verticale (3 à 4 lignes) : icône épingle (vert sur fond `bg-orange-ivoire/20`) + label ville + sous-label + date en gros JetBrains Mono à droite
- Séparateurs : `border-b border-blanc-pur/10`
- En bas, countdown avec dot orange pulsant

### Card stat
`rounded-2xl`, fond `bg-blanc-creme`, padding 24px, `shadow-card`. Contient :
- Grand chiffre Playfair bold 36–40px, couleur `--orange-ivoire` (ou `--vert-ivoire`)
- Label en `font-semibold`
- Caption en `text-sm text-gris-500`

### Card workshop / format
`rounded-3xl`, fond `bg-blanc-pur`, padding 24px, `shadow-card`. `relative overflow-hidden`. Contient :
- Triangle décoratif vert au coin supérieur droit (`border-style` trick)
- Kicker en haut (ex: "Plénière", "Atelier", "Débat", "B2B")
- Wrapper d'icône carrée 56×56 fond orange avec SVG blanc
- Titre Playfair bold
- Description courte
- Ligne verte qui apparaît au hover en bas (`scaleX` animation)

### Ligne de programme
Padding `py-5 pl-4`. Contient :
- Numéro 01, 02… en JetBrains Mono bold 24px, gris
- Libellé en `font-medium`
- Tag rond `text-xs font-semibold uppercase` à droite, couleur selon catégorie (Accueil = orange, Plénière = vert, Atelier = vert, Pause = sable, B2B = violet, Presse = orange).

---

# PLAN III — FONCTIONNEL

## III.1 Structure du site public

Une page d'accueil unique en scroll vertical, plus pages secondaires.

### Page d'accueil — 11 sections dans l'ordre

1. **Header** sticky avec logo, nav inline, CTA "S'inscrire".
2. **Hero** plein écran. Background : photo aérienne Port Autonome d'Abidjan au crépuscule. Overlay sombre dégradé. À gauche : logo SVG animé `draw`, H1 "Hub *Import-Export* 2026", sous-titre, 2 CTAs, bandeau glassmorphique patronage. À droite : carte glassmorphique "DATES CLÉS" + countdown live.
3. **Sous l'autorité des plus hautes institutions** : 2 cards — Haut Patronage (Ministre du Commerce) et Parrainage stratégique (TradeMark Africa + GIZ).
4. **Mot du Ministre** : portrait + texte à gauche, 4 cards stats à droite (180 auditeurs / 17 016 Mds FCFA / 4 ateliers / +165 %).
5. **Cap stratégique** : titre signature, intro, **tabbed component à 3 onglets** (Objectifs / Résultats attendus / Pourquoi participer).
6. **Quatre ateliers** : grille 1×4 (desktop) / 2×2 (tablet) / 1×1 (mobile) de cards workshop. ZLECAf-CEDEAO, Financement, Commerce électronique, Conformité-Qualité. Hover 5-effets.
7. **Quatre formats d'échange** : Cérémonie d'ouverture, Ateliers, Panels, Forums & espaces partenaires.
8. **Programme provisoire — Quatre jours, une trajectoire d'outillage** : titre Manrope, tabs (22/23/24/25 juin), lignes de programme avec numéro/libellé/tag. Hover lignes à 4 effets.
9. **Rejoignez le Hub** : section noire, formulaire newsletter (email + bouton orange).
10. **Ils nous accompagnent** : marquee horizontal pleine largeur des logos partenaires (placeholders).
11. **Footer** : 4 colonnes (Brand / Le Hub / Légales / Contact), copyright en bas.

### Pages secondaires

- `/programme` — version étendue.
- `/ateliers/{slug}` — détail d'un atelier (objectifs, thèmes, public, format).
- `/partenaires` — liste par catégorie (Organisateur / Stratégiques / Agences d'appui / Médias).
- `/actualites` et `/actualites/{slug}`.
- `/presse` — communiqués, photos, contact presse.
- `/faq` — par catégories, accordéons.
- `/contact` — formulaire générique + honeypot.
- `/mentions-legales`, `/politique-de-confidentialite`, `/conditions-utilisation`.

## III.2 Workflow de candidature

Cycle de vie strict matérialisé par un **statut** parmi 10.

### Statuts

| Code | Libellé candidat | Couleur badge |
|---|---|---|
| `draft` | Brouillon | Gris |
| `received` | Candidature reçue | Bleu |
| `incomplete` | Dossier incomplet | Ambre |
| `eligible` | Dossier recevable | Cyan |
| `under_review` | En évaluation | Indigo |
| `shortlisted` | Présélectionné | Violet |
| `accepted` | Retenu — Auditeur confirmé | Vert émeraude |
| `waitlisted` | Sur liste d'attente | Orange |
| `rejected` | Non retenu | Rouge |
| `withdrawn` | Candidature retirée | Gris |

### Phases du cycle

**Phase 1 — Candidature publique.** Visiteur crée compte (email + mot de passe), valide email, ouvre wizard `/candidature` (4 étapes). À soumission → `received`. Email auto + redirection vers page de remerciement avec référence.

**Phase 2 — Vérification administrative.** Agent DGCE consulte les `received`. Trois actions :
- "Marquer recevable" → `eligible` → email.
- "Demander complément" → `incomplete` → email avec lien. Candidat soumet à nouveau → retour en `received`.
- "Rejeter pour dossier non recevable" → `rejected` (cas rare).

**Phase 3 — Évaluation par le comité.** Membres notent sur 5 critères (1–5) :
1. Profil professionnel (pertinence du parcours)
2. Motivation (clarté, sincérité, alignement)
3. Pertinence des ateliers choisis
4. Représentativité (femme, jeune < 35 ans, secteur sous-représenté)
5. Équilibre catégoriel (diversité du groupe)

Statut passe automatiquement à `under_review` dès qu'au moins un membre a noté. Score moyen pondéré calculé en temps réel.

**Phase 4 — Délibération.** Président accède à un dashboard, liste triée par score moyen, filtrable. Décide :
- `accepted` → attribution G1/G2/G3, génération token QR, génération code 6 chiffres, génération badge PDF (job de fond), email d'acceptation.
- `waitlisted` → email courtois. Si retenu se désiste, premier de la liste promu (auto ou manuel à confirmer).
- `rejected` → email courtois et bref.

**Phase 5 — Pendant l'événement.** Chaque jour, l'auditeur se présente. Agent scanne QR OU saisit code 6 chiffres. Système enregistre la présence pour la journée. Un auditeur ne peut être pointé qu'une fois par jour. Dashboard live.

### Règles strictes

- Un utilisateur n'a qu'**une candidature active** (statut ≠ `draft`, `withdrawn`, `rejected`).
- Retrait possible avant délibération (passage en `withdrawn`).
- Période d'inscription bornée : ouverture 1ᵉʳ avril 2026, clôture 15 mai 2026 (paramétrable en back-office). Hors fenêtre, wizard en lecture seule avec message.
- Tous les changements de statut journalisés dans `audit_logs`.
- Aucun statut réversible sans action explicite d'un `super_admin`.

## III.3 Formulaire de candidature — wizard 4 étapes

Page propre avec barre de progression visible (4 segments). Navigation libre entre étapes. Soumission finale seulement après validation des 4 étapes. À chaque "Continuer", persistance brouillon (`draft`).

### Étape 1 — Identité personnelle
- Prénom, Nom
- Civilité / Genre (Femme, Homme, Préfère ne pas dire)
- Date de naissance (candidat majeur)
- Nationalité (par défaut "Ivoirienne")
- Email (lecture seule, repris du compte)
- Téléphone (E.164, validation +225...)
- Ville de résidence
- Pays de résidence (par défaut "Côte d'Ivoire")
- Photo d'identité (facultative en V1 ; si non fournie, badge sans photo)

### Étape 2 — Profil professionnel
- Catégorie (liste déroulante, 12 valeurs) — détermine certains champs conditionnels :
  - Agro-transformateur, PME exportatrice/importatrice, Transitaire, Banque/assurance/finance, Administration douanière, Agent d'agence d'appui (ACIEx, CNE, GUCE-CI, CODINORM, CI-PME), Agent MCIA, Transporteur, Universitaire, Journaliste, Société civile, Autre.
- Nom de l'organisation
- Type d'organisation (Société commerciale, GIE, Coopérative, Banque, Assureur, etc.)
- Poste / fonction
- Secteur d'activité (texte libre + autocomplétion)
- Années d'expérience
- Numéro RCCM (requis si PME exportatrice ou agro-transformateur)
- Site web (facultatif)
- Email professionnel, téléphone professionnel (facultatifs)

### Étape 3 — Motivation et ateliers
- Lettre de motivation (textarea, 500–1500 caractères, compteur visible)
- Ateliers souhaités (**max 2** parmi les 4)
- Attentes spécifiques (textarea facultative, max 600 caractères)
- Comment avez-vous connu le Hub ? (Web, Réseaux sociaux, Bouche-à-oreille, Partenaire, Presse, Autre)
- Première participation ? (oui/non)

### Étape 4 — Pièces et confirmation
- CV (PDF/JPG/PNG, max 5 Mo) — **obligatoire**
- Attestation RCCM (PDF/JPG/PNG, max 5 Mo) — requis si PME exportatrice ou agro-transformateur
- Pièce d'identité (PDF/JPG/PNG, max 5 Mo) — facultative
- Récapitulatif visuel des données (lecture seule, modifiable via retour)
- Case RGPD/ARTCI : « Je consens au traitement de mes données personnelles aux fins de l'organisation du Hub Import-Export 2026, conformément à la loi n°2013-450 sur la protection des données. » — **obligatoire**
- Case communication : « J'accepte de recevoir les communications officielles. » — facultative
- Bouton "Soumettre ma candidature" — large, orange-ivoire, btn-fill

À la soumission : validation serveur, persistance fichiers (S3/MinIO), passage en `received`, envoi email, redirection vers `/candidature/confirmation`.

## III.4 Espace candidat (`/mon-espace`)

Layout simple, max-width 4xl. Trois zones :

**Zone 1 — Statut.** Badge du statut, message contextuel, date de dernière mise à jour.

**Zone 2 — Si `accepted`.** Bloc visuel :
- QR code (SVG ou PNG, 200×200 min)
- Code 6 chiffres en gros (JetBrains Mono, letter-spacing 0.4em, ~48px)
- Groupe (G1/G2/G3)
- Bouton "Télécharger mon badge" (PDF)
- Bouton "Télécharger ma convocation" (PDF A4)
- Indication "À présenter à l'entrée chaque jour"

**Zone 3 — Récapitulatif.** Vue détaillée lecture seule. Si `incomplete`, bouton "Compléter mon dossier".

Lien "Retirer ma candidature" visible si statut ∈ {`received`, `incomplete`, `eligible`, `under_review`}.

## III.5 QR code et code à 6 chiffres

### Format du QR

URL signée vers `https://hubimportexport.ci/scan/qr/{token}?expires=...&signature=...`. Token = 48 caractères aléatoires unique par auditeur, généré au passage en `accepted`. Signature inclut expiration au 25 juin 23h59 Africa/Abidjan.

Niveau de correction d'erreur : élevé (H). Taille minimale affichage : 200×200 pixels. Taille impression sur badge : 30×30 mm.

### Code à 6 chiffres

Aléatoire entre 100000 et 999999 (jamais commençant par 0). Unicité garantie en base. Fallback si QR endommagé ou pas de caméra.

### Génération

À la transition `accepted`, **simultanément** :
- Token QR (unique vérifié)
- Code 6 chiffres (unique vérifié)
- Groupe G1/G2/G3 (équilibrage des effectifs en cours)
- Badge PDF (job de fond)

Email d'acceptation envoyé avec badge en pièce jointe.

### Badge PDF

Format vertical 100×140 mm. Composition :
- Bandeau orange haut : logo HIE blanc + titre "Hub Import-Export 2026 · 22–25 juin · Abidjan"
- Tag vert "Groupe Gx"
- Nom + prénom (Playfair bold 18pt)
- Fonction + organisation (10pt gris)
- QR centré (50×50 mm)
- Code 6 chiffres en JetBrains Mono 18pt, letter-spacing 0.4em, centré
- Bandeau noir bas : référence du dossier + mention "Ministère du Commerce, de l'Industrie et de l'Artisanat"

### Convocation PDF

A4 portrait. Lettre officielle datée, signée du Ministre ou du DGCE :
- Identité du destinataire
- Admission en qualité d'auditeur
- Groupe attribué et ateliers choisis
- Dates, lieux, horaires
- Modalités pratiques
- Programme synthétique en 2e page
- Pied de page institutionnel

## III.6 Module scan d'entrée

Page back-office `/admin/scan-entry`. Accès : `super_admin`, `committee_president`, `agent_entry`.

### Interface

Deux colonnes côte à côte (desktop) / empilées (mobile/tablette).

**Colonne gauche — Scanner QR via caméra.** Zone vidéo plein cadre (`getUserMedia` + `html5-qrcode`). Au QR valide → décodage, extraction token, appel backend, affichage instantané.

**Colonne droite — Saisie manuelle.**
- Lieu de scan (CGECI / CCI-CI / SEEN Hôtel)
- Date de l'événement (par défaut jour J)
- Code 6 chiffres (input numérique grand format, monospace, letter-spacing 0.4em)
- Bouton "Valider le pointage"

### Cas d'usage et messages

- Code valide + `accepted` + pas encore pointé aujourd'hui → toast vert "✓ Pointage validé · Nom Prénom · Groupe Gx" + enregistrement.
- Code valide + déjà pointé aujourd'hui → toast bleu "Déjà pointé à HH:mm" + non doublon.
- Code valide + auditeur non `accepted` → toast jaune "Auditeur non confirmé — Statut : <libellé>" + pas d'enregistrement.
- Code inconnu → toast rouge "Code invalide".

Sous la zone, "carte du dernier pointage" : nom complet, fonction, organisation, groupe, référence, heure exacte.

### Journal `/admin/attendances`

Liste temps réel des pointages, filtres par jour/groupe/lieu. Export Excel.

## III.7 Back-office Filament

### Resources CRUD

- **Applications** — table avec filtres riches, vue détail, transitions de statut, exports en lot.
- **Auditors** — vue dérivée des applications en `accepted`. Affiche groupe, code, présences.
- **Users** — comptes (candidats + staff). Édition des rôles.
- **Workshops** — 4 ateliers (description, objectifs, capacité, icône).
- **Partners** — champ tier (organizer / strategic / partner / media), ordre d'affichage, logo + logo blanc.
- **News** — slug, titre, excerpt, contenu (éditeur riche), cover, auteur, date.
- **Speakers** — nom, titre, organisation, bio, photo, LinkedIn, ordre, publié.
- **FAQ items** — par catégorie.
- **Newsletter subscribers** — état, source, export Excel.
- **Settings** — paramétrage runtime (dates, quotas).
- **Audit logs** — lecture seule, ordre antéchronologique.

### Pages custom

- **Dashboard** — accueil panel.
- **Scan Entry** — voir III.6.
- **Committee Board** — délibération avec scoring agrégé + quotas live.
- **Export Center** — exports : auditeurs par groupe, feuilles de présence, badges en lot.

### Widgets du Dashboard (11)

1. **StatsOverview** — 4 cartes : Candidatures reçues + delta 24h + sparkline 7j ; Auditeurs retenus / 180 + % ; Présents aujourd'hui (pendant l'événement) + % ; Dossiers à évaluer.
2. **Timeline cumulée** — line chart 30 derniers jours, courbe orange-ivoire avec aire remplie.
3. **Répartition par genre** — doughnut chart : Femmes / Hommes / Autre.
4. **Tranches d'âge** — bar chart : < 25 / 25–34 / 35–44 / 45–54 / 55+, vert-ivoire.
5. **Catégories professionnelles** — bar chart horizontal par effectif décroissant.
6. **Choix d'ateliers** — bar chart : ZLECAf / Financement / E-commerce / Conformité.
7. **Funnel des statuts** — Received → Eligible → Under Review → Shortlisted → Accepted + branches Waitlisted/Rejected. Absolus + % conversion.
8. **Quotas de représentativité** — 2 barres : Femmes (cible 50 %) / Moins de 35 ans (cible 40 %). Vert si atteint, orange sinon. Données sur `accepted` uniquement.
9. **Présences par jour** (pendant l'événement) — bar chart : 22/23/24/25 juin. Polling 15 s.
10. **Heatmap géographique** — 15 villes les plus représentées + barre proportionnelle.
11. **Sources de connaissance** — bar chart : Web / Réseaux / Bouche-à-oreille / Partenaire / Presse / Autre.

### Filtres et exports

Filtres table candidatures :
- Statut (multi-sélection)
- Catégorie pro
- Genre
- Tranche d'âge
- Atelier souhaité
- Ville
- Plage de date de soumission
- Toggles : "Femmes uniquement", "Moins de 35 ans uniquement"

Exports :
- Liste complète Excel
- Liste par groupe (G1/G2/G3) Excel
- Feuille de présence par jour (CGECI / CCI-CI / SEEN) PDF imprimable
- Pack de badges PDF en ZIP (job de fond, email à l'admin quand prêt)

## III.8 Rôles et permissions

| Rôle | Vue dossiers | Évaluer | Marquer recevable | Retenir | Refuser | Scanner | Gérer contenus | Gérer système |
|---|---|---|---|---|---|---|---|---|
| `super_admin` | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |
| `committee_president` | ✓ | ✓ | — | ✓ | ✓ | — | — | — |
| `committee_member` | ✓ | ✓ | — | — | — | — | — | — |
| `admin_dgce` | ✓ | — | ✓ | — | — | — | — | — |
| `communication` | — | — | — | — | — | — | ✓ | — |
| `agent_entry` | — | — | — | — | — | ✓ | — | — |
| `reader` | ✓ (lecture seule) | — | — | — | — | — | — | — |

## III.9 Emails transactionnels

10 templates, layout institutionnel commun (voir `CONTENT.md` § B layout) :
- Largeur 600px, fond beige `--blanc-creme`
- Header bandeau orange `--orange-ivoire` + logo HIE blanc
- Corps blanc, Playfair pour titres, Helvetica/Arial pour corps (compat clients mail)
- Footer noir avec coordonnées DGCE
- Mention de désinscription en bas (token unique)

| Trigger | Mailable | Sujet |
|---|---|---|
| Création de compte | Standard Laravel verify-email | Vérifiez votre adresse email |
| `received` | `ApplicationReceived` | Votre candidature au Hub Import-Export 2026 a bien été reçue |
| `eligible` | `ApplicationEligible` | Votre dossier est recevable — en cours d'évaluation par le comité |
| `incomplete` | `ApplicationIncomplete` | Action requise : complément de dossier nécessaire |
| `shortlisted` | `ApplicationShortlisted` | Vous êtes présélectionné(e) — décision finale sous 7 jours |
| `accepted` | `ApplicationAccepted` | Félicitations — Convocation officielle en pièce jointe |
| `waitlisted` | `ApplicationWaitlisted` | Liste d'attente — Hub Import-Export 2026 |
| `rejected` | `ApplicationRejected` | Décision du comité de sélection |
| Newsletter double opt-in | `NewsletterConfirmation` | Confirmez votre inscription à la newsletter du Hub |
| Rappel J-7 | `EventReminder` | Rendez-vous dans 7 jours — Hub Import-Export 2026 |

Contenu de tous les emails dans `CONTENT.md` § B.

## III.10 RGPD / ARTCI

- Consentement explicite à l'étape 4 du wizard.
- Page `/politique-de-confidentialite` détaillée (voir `CONTENT.md` § C.2).
- Bouton "Supprimer mon compte et mes données" dans `/mon-espace` → soft delete + tâche de purge effective à J+30.
- Conservation des candidatures : 3 ans après l'événement.
- Aucune donnée transmise à un tiers non listé.
- Lien de désinscription token-based dans tous les emails.
- Journal d'audit conservé 5 ans.

## III.11 SEO et partages sociaux

- Title pattern : "Hub Import-Export 2026 · {nom de la page} · MCIA Côte d'Ivoire"
- Description : 150–160 caractères, axée "Plateforme officielle du Hub Import-Export 2026 organisé par le Ministère du Commerce de Côte d'Ivoire, du 22 au 25 juin 2026 à Abidjan."
- OG image : 1200×630, photo Port d'Abidjan + logo + titre + dates
- Twitter card : `summary_large_image`
- Sitemap XML automatique (`/sitemap.xml`)
- `robots.txt` autorise tout sauf `/admin/`, `/mon-espace/`, `/candidature/`
- Données structurées Schema.org type `Event` sur la home (startDate, endDate, location, organizer)

---

# PLAN IV — TECHNIQUE

## IV.1 Stack imposée

| Couche | Choix | Version cible |
|---|---|---|
| Langage serveur | PHP | 8.3+ |
| Framework | Laravel | 12.x |
| UI dynamique | Livewire | 3.x |
| Hyper-interactions client | Alpine.js | 3.x |
| CSS | Tailwind CSS | 3.4+ |
| Back-office | Filament | 3.x |
| Auth candidats | Laravel Breeze (Blade) | 2.x |
| Auth admin | Filament natif | — |
| RBAC | Spatie Laravel Permission | 6.x |
| Base de données | MySQL | 8.0+ |
| Cache, sessions, queue | Fichier / base de données | — |
| Mail driver | SMTP (Brevo / Postmark / SMTP institutionnel) | — |
| QR Code | `simplesoftwareio/simple-qrcode` | dernière |
| PDF | `spatie/laravel-pdf` (Browsershot) | dernière |
| Excel | `maatwebsite/excel` | 3.x |
| Storage fichiers | Disque local (public) | — |
| Scan QR client | `html5-qrcode` (CDN, lazy load) | dernière |
| Tests | Pest | 3.x |

**Justifications de fond** :
- Full Laravel demandé par le commanditaire → pas d'Inertia ni de Next, on reste Blade + Livewire + Alpine.
- Filament non négociable pour le back-office : widgets graphiques (ApexCharts intégré), exports, intégration Spatie Permission, RBAC, tables filtrables, formulaires schémas — ~80 % du back-office gratuit.
- MySQL + MAMP : stack simplifiée adaptée au volume réel (~150 personnes). Pas de Redis ni MinIO requis à cette échelle.

## IV.2 Domaines, environnements, variables

**Domaines** :
- Production : `hubimportexport.ci`
- Recette : `staging.hubimportexport.ci`
- Local dev : `hub.test` (MAMP) ou `localhost`

**Variables d'environnement** (`.env.example` exhaustif) :
- `APP_*` standards Laravel
- `DB_*` MySQL
- `MAIL_*`
- `HUB_APPLICATION_OPENS_AT` (date ouverture candidatures)
- `HUB_APPLICATION_CLOSES_AT` (date clôture)
- `HUB_EVENT_STARTS_AT` (2026-06-22 09:00:00)
- `HUB_EVENT_ENDS_AT` (2026-06-25 18:00:00)
- `HUB_TARGET_AUDITORS` (180)
- `HUB_QUOTA_WOMEN_MIN_PCT` (50)
- `HUB_QUOTA_YOUTH_MIN_PCT` (40)
- `HUB_QUOTA_YOUTH_MAX_AGE` (35)

**Stack locale** : MAMP (Apache + MySQL + PHP 8.3).

## IV.3 Schéma de base de données

Casts Eloquent, soft deletes, indexes selon bonnes pratiques. Tables clés :

### `users`
id, first_name, last_name, email (unique), email_verified_at, password, phone, birth_date, gender (énum F/M/X), nationality, city, country, photo_path, is_active (bool), timestamps, soft deletes.

### `applications`
id, reference_code (UUID lisible `HIE2026-XXXXXX`, unique), user_id (FK), status (énum 10 valeurs), current_step (1-4), category (énum 12 valeurs), organization_name, organization_type, position, sector, experience_years, rccm_number, website, professional_email, professional_phone, motivation (text), chosen_workshops (JSON), referral_source, expectations (text), is_first_participation (bool), rgpd_consent (bool), communication_consent (bool), submitted_at, submission_ip, submission_user_agent, average_score (decimal 5,2), evaluations_count (int), group_label (G1/G2/G3), qr_token (unique), check_in_code (6 chiffres, unique), badge_path, accepted_at, notified_at, admin_notes (text), rejection_reason (text), timestamps, soft deletes.

Index composés : `(status, submitted_at)`, `(category)`.

### `application_documents`
id, application_id (FK cascade delete), type (cv / rccm / id_card / other), original_name, storage_path, mime_type, size_bytes, timestamps.

### `workshops`
id, slug (unique), title, short_description, full_description (text), objectives (JSON array), themes (JSON array), icon_path (SVG), capacity (int, défaut 60), display_order (int), is_published (bool), timestamps.

### `application_workshops` (pivot)
id, application_id, workshop_id, timestamps. Max 2 par candidature.

### `evaluations`
id, application_id (FK), evaluator_id (FK users), score_profile (1-5), score_motivation (1-5), score_relevance (1-5), score_representativity (1-5), score_balance (1-5), weighted_score (decimal 5,2 calculé), comment (text), timestamps.

Contrainte unique : `(application_id, evaluator_id)`.

### `attendances`
id, application_id (FK), event_date (date), scanned_at (timestamp), scanned_by_user_id (FK users), location (CGECI / CCI-CI / SEEN), scan_method (qr / code), scanner_ip, timestamps.

Contrainte unique : `(application_id, event_date)` — un seul pointage par jour.

### `partners`
id, name, logo_path, logo_white_path, website, tier (organizer / strategic / partner / media), display_order, show_in_marquee (bool), show_in_footer (bool), timestamps.

### `news`
id, slug (unique), title, excerpt (280 chars), content (long text), cover_path, author_id (FK users), published_at (nullable timestamp), is_featured (bool), timestamps.

### `speakers`
id, first_name, last_name, title, organization, bio (text), photo_path, linkedin, display_order, is_featured (bool), is_published (bool), timestamps.

### `faq_items`
id, question, answer (text), category (candidature / programme / pratique / autre), display_order, is_published (bool), timestamps.

### `newsletter_subscribers`
id, email (unique), confirmation_token (unique), unsubscribe_token (unique), confirmed_at (nullable), unsubscribed_at (nullable), source (hero / footer / news), timestamps.

### `settings`
id, key (unique), value (text), type (string / int / bool / json / date), group, label, description, timestamps. Paramétrage runtime sans toucher `.env`.

### `audit_logs`
id, user_id (FK nullable), action (string), subject_type, subject_id, old_values (JSON), new_values (JSON), ip, user_agent, timestamps.

### Tables Spatie
`roles`, `permissions`, `model_has_roles`, `model_has_permissions`, `role_has_permissions`.

## IV.4 Seeders à fournir

- **RolesSeeder** : 7 rôles + permissions.
- **WorkshopsSeeder** : 4 ateliers (contenu = `CONTENT.md` § A.3).
- **PartnersSeeder** : placeholders nominatifs sans inventer de relation (à compléter via back-office).
- **FaqSeeder** : 15 questions/réponses initiales (`CONTENT.md` § A.5).
- **SettingsSeeder** : valeurs runtime initiales.
- **DemoDataSeeder** (env local uniquement) : 50 candidatures factices avec statuts variés.

## IV.5 Structure des dossiers Laravel

Respecter convention Laravel + Filament :

```
app/
  Console/
  Enums/                  ← énums PHP 8.1+ (Status, Category…)
  Filament/
    Pages/                ← Dashboard, ScanEntry, CommitteeBoard, ExportCenter
    Resources/            ← ApplicationResource, UserResource…
    Widgets/              ← 11 widgets
  Http/
    Controllers/
    Middleware/
    Requests/             ← FormRequests par étape du wizard
  Jobs/                   ← GenerateBadgePdf, SendStatusEmail…
  Livewire/
    Public/               ← composants front public
    Application/          ← composants wizard candidature
    Candidate/            ← composants espace candidat
  Mail/                   ← Mailables
  Models/
  Notifications/
  Policies/
  Providers/
  Services/               ← ApplicationStatusService, QrCodeService, BadgePdfService, ScoringService, QuotaCalculatorService
resources/
  css/app.css             ← variables CSS + Tailwind + classes utilitaires
  js/
    app.js
    countdown.js
    reveal.js
    marquee.js            ← (optionnel, CSS suffit)
  views/
    layouts/
    components/           ← Blade components réutilisables
    public/
    application/          ← wizard
    candidate/            ← espace candidat
    pdf/                  ← templates badge + convocation
    emails/               ← templates emails + layout institutionnel
  images/                 ← logos, photos hero, og-image
lang/fr/                  ← traductions
database/
  migrations/
  factories/
  seeders/
routes/
tests/
  Feature/
  Unit/
docker/
```

## IV.6 Routes nommées (liste exhaustive)

| URL | Nom | Auth | Rôle |
|---|---|---|---|
| `/` | `home` | public | — |
| `/programme` | `program` | public | — |
| `/ateliers` | `workshops` | public | — |
| `/ateliers/{slug}` | `workshops.show` | public | — |
| `/partenaires` | `partners` | public | — |
| `/actualites` | `news.index` | public | — |
| `/actualites/{slug}` | `news.show` | public | — |
| `/presse` | `press` | public | — |
| `/faq` | `faq` | public | — |
| `/contact` | `contact` | public | — |
| `/contact` (POST) | `contact.submit` | public | — |
| `/mentions-legales` | `legal.mentions` | public | — |
| `/politique-de-confidentialite` | `legal.privacy` | public | — |
| `/conditions-utilisation` | `legal.terms` | public | — |
| `/newsletter/subscribe` (POST) | `newsletter.subscribe` | public | — |
| `/newsletter/confirm/{token}` | `newsletter.confirm` | public | — |
| `/newsletter/unsubscribe/{token}` | `newsletter.unsubscribe` | public | — |
| `/register`, `/login`, `/logout`, `/email/verify`… | Breeze standard | — | — |
| `/candidature` | `application.wizard` | ✓ | candidate |
| `/candidature/etape/{step}` (POST) | `application.step.store` | ✓ | candidate |
| `/candidature/soumettre` (POST) | `application.submit` | ✓ | candidate |
| `/candidature/confirmation` | `application.confirmation` | ✓ | candidate |
| `/mon-espace` | `candidate.dashboard` | ✓ | candidate |
| `/mon-espace/dossier` | `candidate.application` | ✓ | candidate |
| `/mon-espace/badge` | `candidate.badge.view` | ✓ | candidate accepted |
| `/mon-espace/badge/telecharger` | `candidate.badge.download` | ✓ | candidate accepted |
| `/mon-espace/convocation` | `candidate.convocation.view` | ✓ | candidate accepted |
| `/mon-espace/convocation/telecharger` | `candidate.convocation.download` | ✓ | candidate accepted |
| `/admin` et sous-paths | Filament natif | ✓ | staff |
| `/scan/qr/{token}` (signed URL) | `scan.qr` | ✓ | agent_entry |

## IV.7 Performance et optimisation

- Toutes les images en WebP avec fallback JPG. Qualité 80 max.
- Lazy loading : `loading="lazy"` sous le fold.
- Preconnect Google Fonts + preload des 2 polices critiques (Playfair, Inter), `font-display: swap`.
- Critical CSS inline pour le fold.
- Vite avec code splitting + tree shaking.
- Cache HTTP : 1 an sur assets versionnés (`build/*`), 1 heure sur pages publiques (avec ETag), aucune sur pages authentifiées.
- Queries Eloquent : eager loading systématique anti N+1.
- Index DB sur tous champs filtrables/triables du back-office.
- Filament : désactiver les bulk actions non utilisées pour réduire le JS.
- Marquee partenaires : CSS pur (transform + animation), pas de JS.

## IV.8 Sécurité

- HTTPS forcé en production (HSTS).
- CSP stricte : `default-src 'self'; img-src 'self' data: https://storage.minio.dgce.ci; font-src 'self' https://fonts.gstatic.com; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; script-src 'self' https://unpkg.com/html5-qrcode;`
- Rate limiting : login (5/min/IP), candidature submit (3/h/user), contact form (5/h/IP), newsletter (3/h/IP).
- CSRF protection native Laravel.
- XSS : `{{ }}` Blade par défaut, jamais `{!! !!}` sauf sur contenu admin validé.
- Validation server-side stricte sur tous inputs (types fichiers, taille).
- Stockage fichiers candidats hors webroot, accès via routes signées.
- Hash passwords : bcrypt cost 12.
- Sessions fichier avec rotation à login.
- Headers : `X-Frame-Options: DENY`, `X-Content-Type-Options: nosniff`, `Referrer-Policy: strict-origin-when-cross-origin`.

## IV.9 Tests automatisés (Pest)

**Feature tests** :
- Soumission complète d'une candidature (les 4 étapes)
- Utilisateur non authentifié redirigé vers login
- Transition `received` → `eligible` → `accepted` avec side-effects (QR, code, badge, email)
- Génération QR : token unique, URL signée valide, expiration correcte
- Scan d'entrée : QR valide, code valide, doublon refusé, statut non-accepted refusé
- Calcul des quotas : femmes ≥ 50 %, jeunes ≥ 40 %
- Envoi des emails à chaque transition
- Anti-spam newsletter : double opt-in obligatoire
- RBAC : un `committee_member` ne peut pas marquer `accepted`
- Désinscription newsletter via token

**Unit tests** :
- `ApplicationStatusService::transition` (toutes transitions valides et invalides)
- `QrCodeService::generateUniqueQrToken` (unicité sous concurrence)
- `QuotaCalculatorService` (jeu de données contrôlé)
- `ScoringService::computeAverage` (moyenne pondérée)

Tous les tests dans des transactions DB roll-backées. Aucun fichier écrit sur disque (fake storage).

## IV.10 Livrables attendus

À la fin de la construction :

1. Repo Git complet avec `README.md` (installation locale, déploiement, paramétrage).
2. Tous les seeders : `php artisan migrate:fresh --seed` démarre une démo fonctionnelle.
4. Documentation des routes nommées (auto-générée via `php artisan route:list`).
5. `docs/ADMIN.md` : workflow comité, exports, scan d'entrée.
6. `docs/DEPLOY.md` : Nginx, supervisor (queue worker), cron (scheduler), sauvegardes.
7. Suite Pest passante, couverture services critiques > 70 %.

---

# Assets à produire en parallèle (hors code)

Ces éléments sont fournis par la DGCE et ne relèvent pas du code Claude Code. À demander explicitement à Vincent si absents au démarrage d'une phase qui en dépend :

1. **Logo HIE en SVG natif** (le fichier fourni est rastérisé) — pour que l'animation `draw` fonctionne sur les paths. Deux versions : couleurs originales (orange + vert sur fond clair) et version blanche (pour fond sombre).
2. **Favicon** dérivé du logo (16×16, 32×32, 180×180 Apple touch, manifest PWA).
3. **Photo aérienne du Port Autonome d'Abidjan au crépuscule** — 16:9 ou 21:9, min 2400×1350, lumière chaude, libre de droits. À recadrer en WebP < 200 ko.
4. **Photo portrait du Ministre du Commerce** (placeholder en attente de validation cabinet).
5. **Logos vectoriels des partenaires** (placeholders en attente) : MCIA, TradeMark Africa, GIZ, ACIEx, CNE, GUCE-CI, CODINORM, CI-PME, CGECI, CCI-CI, Administration des Douanes. Couleurs + monochrome blanc pour chaque.
6. **OG image** pour partages sociaux : 1200×630.
7. **Signature scannée du Ministre** pour les convocations PDF.
8. **Icônes SVG sur-mesure** pour les 4 ateliers + 4 formats (8 icônes uniques, trait fin cohérent). Icônes back-office : Heroicons (intégrées à Filament).

---

# Roadmap post-MVP (V2+, hors périmètre V1)

À anticiper dans l'architecture pour éviter le refactor :

- **i18n EN** : structure `lang/` préparée, juste à dupliquer.
- **Application mobile dédiée au scan** (PWA ou Capacitor) si couverture wifi des lieux non garantie.
- **Espace post-événement** : galerie, replay vidéos plénières, pressbook, rapport d'évaluation, mentorship CI-PME.
- **Tableau de bord candidat enrichi** : feedback, certificat post-évaluation, réseautage entre auditeurs.
- **Module d'évaluation post-formation** intégré : QCM par atelier, génération certificat conditionnée à la réussite.
- **Newsletter campagnes multiples** : segmentation par catégorie, ville, atelier.
- **API publique** pour partenaires : actualités, programme, statistiques agrégées anonymisées.

---

**Fin du brief technique.** Pour les textes officiels, voir `CONTENT.md`. Toute ambiguïté restante relève du jugement technique → consigner dans `DECISIONS.md`.