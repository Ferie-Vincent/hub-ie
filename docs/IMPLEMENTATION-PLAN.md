# Plan d'implémentation — Hub Import-Export 2026

> Découpage en 8 phases séquentielles. Tu ne passes à la phase suivante que si **tous** les critères de validation de la phase courante sont remplis. À la fin de chaque phase : commit, PR, merge sur `main`.

---

## Vue d'ensemble

| Phase | Nom | Durée indicative | Bloquante pour |
|---|---|---|---|
| 0 | Bootstrap & infra | 1 jour | Tout le reste |
| 1 | Modèle de données | 1–2 jours | 2, 3, 5, 6 |
| 2 | Auth & RBAC | 0,5 jour | 5, 6 |
| 3 | Design system & layouts | 1–2 jours | 4 |
| 4 | Site public | 2–3 jours | — (parallélisable avec 5) |
| 5 | Wizard candidature & espace candidat | 2–3 jours | 6 (partiellement) |
| 6 | Back-office Filament | 3–4 jours | 7 |
| 7 | PDF, QR, codes, emails, scan | 2 jours | 8 |
| 8 | Tests, perf, doc, déploiement | 1–2 jours | Livraison |

**Total cible** : 13–20 jours-développeur.

---

## Phase 0 — Bootstrap & infrastructure

**Objectif** : projet Laravel installé, conteneurs Docker fonctionnels, tous les packages clés présents, base vide migrable.

### Tâches

1. `composer create-project laravel/laravel . "^12.0"`
2. Installer les packages : `livewire/livewire`, `filament/filament:^3.0`, `spatie/laravel-permission`, `spatie/laravel-pdf`, `simplesoftwareio/simple-qrcode`, `maatwebsite/excel`, `sentry/sentry-laravel`, `laravel/breeze`, `pestphp/pest` (dev), `pestphp/pest-plugin-laravel` (dev).
3. Installer Breeze en mode Blade : `php artisan breeze:install blade`.
4. Installer Filament : `php artisan filament:install --panels`.
5. Publier les migrations Spatie Permission.
6. Initialiser Tailwind 3.4 et configurer `tailwind.config.js` avec :
   - `darkMode: 'class'`
   - Tokens HSL exposés via `theme.extend.colors` avec syntaxe `hsl(var(--orange-ivoire) / <alpha-value>)`
   - Familles de polices Google (Playfair Display, Fraunces, Inter, Manrope, JetBrains Mono)
   - Animations custom (voir `BRIEF.md` § II.5) déclarées dans `theme.extend.keyframes` et `theme.extend.animation`.
7. Configurer `docker-compose.yml` avec services : `app` (PHP 8.3-fpm), `nginx`, `postgres:16`, `redis:7`, `minio`, `mailpit`.
8. Configurer `.env.example` avec toutes les variables listées au `BRIEF.md` § IV.2.
9. Créer `docs/DECISIONS.md` vide avec en-tête : `# Décisions techniques` + un exemple commenté.
10. Configurer Pint avec preset Laravel (`pint.json`).
11. Premier commit sur `main` : `chore: bootstrap laravel 12 + filament 3 + docker`.

### Critères de validation

- [ ] `docker-compose up -d` lance tous les services sans erreur.
- [ ] `php artisan migrate` aboutit sur la base postgres conteneurisée.
- [ ] `npm run dev` lance Vite sans erreur.
- [ ] `/admin/login` (Filament) répond en 200.
- [ ] `.env.example` contient toutes les variables `HUB_*`.
- [ ] `composer.json` et `package.json` versionnés.

---

## Phase 1 — Modèle de données

**Objectif** : tout le schéma BDD est en place, énums créés, factories pour les tests, seeders minimaux.

### Tâches

1. Créer les énums PHP 8.1+ sous `app/Enums/` : `ApplicationStatus`, `ApplicationCategory`, `Gender`, `PartnerTier`, `AttendanceLocation`, `AttendanceScanMethod`, `NewsletterSubscriberStatus`, `EvaluationCriterion`, `Workshop` (slug list).
2. Pour chaque énum, méthodes `label(): string` (FR) et `color(): string` (Filament).
3. Migrations dans l'ordre, une par table, voir `BRIEF.md` § IV.3 :
   - `users` (extension de la migration Breeze : ajouter `first_name`, `last_name`, `phone`, `birth_date`, `gender`, `nationality`, `city`, `country`, `photo_path`, `is_active`)
   - `applications`
   - `application_documents`
   - `workshops`
   - `application_workshops`
   - `evaluations`
   - `attendances`
   - `partners`
   - `news`
   - `speakers`
   - `faq_items`
   - `newsletter_subscribers`
   - `settings`
   - `audit_logs`
4. Index composés (voir `BRIEF.md` § IV.3).
5. Soft deletes sur `users` et `applications`.
6. Modèles Eloquent avec `$fillable`, `$casts`, relations typées.
7. Factories pour `User`, `Application`, `Workshop`, `Partner`, `Speaker`, `FaqItem`.
8. Seeders : `RolesSeeder`, `WorkshopsSeeder`, `PartnersSeeder` (placeholders), `FaqSeeder` (depuis `CONTENT.md` § A.5), `SettingsSeeder`, `DemoDataSeeder` (local uniquement, 50 candidatures factices).
9. `DatabaseSeeder` orchestre l'ensemble.

### Critères de validation

- [ ] `php artisan migrate:fresh --seed` aboutit en < 30 s sur un cluster local vide.
- [ ] Toutes les énums sont consommées par les casts Eloquent.
- [ ] `php artisan tinker` → `App\Models\Application::factory()->count(10)->create()` aboutit.
- [ ] `php artisan db:show` confirme 14 tables + tables Spatie + tables Breeze.
- [ ] Aucune migration ne contient `Schema::table()` sur une table créée dans une autre migration du même commit.

---

## Phase 2 — Authentification & RBAC

**Objectif** : Breeze configuré pour les candidats, Filament configuré pour le staff, 7 rôles Spatie créés et liés aux permissions.

### Tâches

1. Customiser les pages Breeze (`login`, `register`, `verify-email`, `forgot-password`) avec le layout institutionnel (palette + typos du `BRIEF.md` § II).
2. Étendre `User` avec `HasRoles` (Spatie).
3. Définir les 7 rôles dans `RolesSeeder` : `super_admin`, `committee_president`, `committee_member`, `admin_dgce`, `communication`, `agent_entry`, `reader`, `candidate`.
4. Créer les permissions atomiques (`view-applications`, `evaluate-applications`, `mark-eligible`, `accept-applications`, `reject-applications`, `scan-attendance`, `manage-content`, `manage-system`) et les attacher aux rôles selon la matrice du `BRIEF.md` § III.8.
5. Créer les Policies Laravel : `ApplicationPolicy`, `EvaluationPolicy`, `AttendancePolicy`, `NewsPolicy`, `PartnerPolicy`.
6. Configurer le panel Filament `/admin` pour n'accepter que les utilisateurs ayant un rôle staff (interdire les `candidate`).
7. Implémenter le middleware `EnsureCandidateRole` qui protège `/candidature` et `/mon-espace`.
8. Email de vérification : utiliser le template institutionnel (voir `CONTENT.md` § B.0 layout commun).

### Critères de validation

- [ ] Un `candidate` ne peut pas accéder à `/admin`.
- [ ] Un `committee_member` ne peut pas marquer une candidature `accepted` (test Pest passant).
- [ ] Un `agent_entry` n'accède qu'à `/admin/scan-entry` et `/admin/attendances`.
- [ ] L'email de vérification reprend le layout institutionnel (visible dans Mailpit).

---

## Phase 3 — Design system & layouts

**Objectif** : variables CSS, classes utilitaires signature, composants Blade réutilisables, animations toutes opérationnelles isolément.

### Tâches

1. `resources/css/app.css` : déclarer toutes les variables HSL du `BRIEF.md` § II.1 dans `:root`.
2. Déclarer les classes utilitaires : `.glass`, `.glass-light`, `.glass-dark` (avec fallback Safari), `.btn-fill`, `.link-underline`, `.kicker`.
3. Déclarer les 13 keyframes (§ II.5) et leurs animations utilitaires (`animate-draw`, `animate-fade-up`, `animate-v10-pulse`, etc.).
4. Composants Blade sous `resources/views/components/` :
   - `<x-kicker>`, `<x-section-title>` (combinaison Inter bold + Fraunces italic colorée), `<x-stat-card>`, `<x-workshop-card>`, `<x-format-card>`, `<x-program-row>`, `<x-glass-panel>`, `<x-cta-button>`, `<x-tag>`, `<x-countdown>`.
5. Layouts :
   - `layouts/public.blade.php` : header sticky avec switch glass-light au scroll (Alpine `x-data` qui écoute `window.scrollY`), footer 4 colonnes.
   - `layouts/candidate.blade.php` : layout simplifié pour l'espace candidat.
   - `layouts/email.blade.php` : layout institutionnel pour les Mailables.
6. JS sous `resources/js/` :
   - `reveal.js` : Intersection Observer pour `.reveal` (seuil 15 %, ajout de classe `.is-visible`).
   - `countdown.js` : compteur live jusqu'au 22 juin 2026 09:00 Africa/Abidjan.
   - Pas de JS pour le marquee (CSS pur suffit).
7. Page de démonstration interne `/dev/components` (uniquement env local) qui affiche toutes les variantes des composants pour vérification visuelle.

### Critères de validation

- [ ] Toutes les 13 keyframes existent dans `tailwind.config.js`.
- [ ] La page `/dev/components` affiche tous les composants sans erreur console.
- [ ] Le header passe en `glass-light` après 80 px de scroll, transition 300 ms.
- [ ] Le countdown se met à jour à la seconde.
- [ ] Lighthouse Accessibility ≥ 95 sur `/dev/components`.
- [ ] Safari : le glassmorphisme a un fallback opacité visible.

---

## Phase 4 — Site public

**Objectif** : page d'accueil + pages secondaires intégralement fonctionnelles, contenus injectés depuis BDD ou `CONTENT.md`.

### Tâches

1. **Page d'accueil** (`/`) — 11 sections dans l'ordre du `BRIEF.md` § III.1 :
   - Header sticky
   - Hero plein écran (photo Port d'Abidjan, logo SVG `draw`, carte glassmorphique DATES CLÉS + countdown)
   - Sous l'autorité des plus hautes institutions
   - Mot du Ministre + 4 cards stats
   - Cap stratégique (tabbed component 3 onglets : Objectifs / Résultats / Pourquoi)
   - Quatre ateliers (grille de cards avec hover 5-effets)
   - Quatre formats d'échange
   - Programme provisoire (tabs jour par jour)
   - Rejoignez le Hub (newsletter)
   - Ils nous accompagnent (marquee)
   - Footer
2. **Pages secondaires** :
   - `/programme`
   - `/ateliers` et `/ateliers/{slug}`
   - `/partenaires` (groupé par tier)
   - `/actualites` et `/actualites/{slug}`
   - `/presse`
   - `/faq` (accordéons par catégorie)
   - `/contact` (formulaire + honeypot anti-spam)
   - `/mentions-legales`, `/politique-de-confidentialite`, `/conditions-utilisation`
3. **Newsletter** : Livewire component avec POST `/newsletter/subscribe`, double opt-in (génère token, envoie email `NewsletterConfirmation`).
4. **Métas SEO** : title pattern, description, OG image, Twitter card, sitemap, robots.txt, schema.org `Event` sur `/`.
5. **Performance** : preconnect + preload des deux polices critiques, lazy-load des images sous le fold, WebP avec fallback.

### Critères de validation

- [ ] Lighthouse Performance ≥ 90 sur `/` en mobile et desktop (build prod).
- [ ] Toutes les routes publiques du `BRIEF.md` § IV.6 répondent en 200.
- [ ] Tous les textes officiels viennent de `CONTENT.md` (aucun texte rédigé inline dans les Blade).
- [ ] Newsletter : tentative d'inscription → email reçu dans Mailpit → clic → statut `confirmed`.
- [ ] Sitemap XML accessible à `/sitemap.xml`.
- [ ] `robots.txt` bloque `/admin/`, `/mon-espace/`, `/candidature/`.
- [ ] Aucune erreur console JS sur les pages publiques.

---

## Phase 5 — Wizard candidature & espace candidat

**Objectif** : un candidat authentifié peut soumettre une candidature complète, suivre son statut, télécharger badge/convocation s'il est retenu.

### Tâches

1. **Wizard 4 étapes** (`/candidature`) en Livewire :
   - Étape 1 — Identité (voir `BRIEF.md` § III.3.1)
   - Étape 2 — Profil professionnel (champs conditionnels selon catégorie)
   - Étape 3 — Motivation et ateliers (compteur de caractères, max 2 ateliers)
   - Étape 4 — Pièces + RGPD + soumission
2. **FormRequests** un par étape, validation server-side stricte (types fichiers, taille, format téléphone E.164, etc.).
3. **Persistance brouillon** : à chaque "Continuer", `Application` créée/mise à jour avec `status = draft`.
4. **Soumission** : transition `draft → received`, envoi `ApplicationReceived`, redirection vers `/candidature/confirmation`.
5. **Espace candidat** (`/mon-espace`) :
   - Zone Statut (badge couleur du statut)
   - Zone Badge si `accepted` (QR + code 6 chiffres + groupe + boutons téléchargement)
   - Zone Récapitulatif (lecture seule, bouton "Compléter" si `incomplete`)
   - Lien "Retirer ma candidature" conditionnel
6. **Période d'inscription** : si `now() < HUB_APPLICATION_OPENS_AT` ou `now() > HUB_APPLICATION_CLOSES_AT`, wizard en lecture seule avec message explicite.
7. **Une seule candidature active par utilisateur** : middleware ou contrainte dans la policy.

### Critères de validation

- [ ] Test Pest : soumission complète des 4 étapes → statut `received` → email reçu.
- [ ] Test Pest : un utilisateur ne peut pas créer deux candidatures actives.
- [ ] Sur mobile (viewport 375×667), le wizard est utilisable, le clavier ne masque pas les champs.
- [ ] Compteur de caractères motivation : visible et bloque le submit hors plage 500–1500.
- [ ] Upload CV : refuse > 5 Mo et type ≠ pdf/jpg/png côté serveur.
- [ ] Hors fenêtre d'inscription : message s'affiche, bouton submit absent.

---

## Phase 6 — Back-office Filament

**Objectif** : la DGCE peut piloter le cycle complet en interface : tri, vérification, évaluation, délibération, exports.

### Tâches

1. **Resources Filament** (voir `BRIEF.md` § III.7) :
   - `ApplicationResource` (table riche, filtres exhaustifs, vue détail, actions de transition de statut)
   - `UserResource`, `WorkshopResource`, `PartnerResource`, `NewsResource`, `SpeakerResource`, `FaqItemResource`, `NewsletterSubscriberResource`, `SettingResource`, `AuditLogResource` (lecture seule).
2. **Pages custom** :
   - `Dashboard` (page d'accueil du panel)
   - `ScanEntry` (voir Phase 7)
   - `CommitteeBoard` (tableau de délibération)
   - `ExportCenter`
3. **Widgets du Dashboard** (11 widgets, voir `BRIEF.md` § III.7) :
   - StatsOverview, Timeline cumulée, Répartition par genre, Tranches d'âge, Catégories pro, Choix d'ateliers, Funnel statuts, Quotas représentativité, Présences par jour (polling 15 s pendant l'événement), Heatmap géographique, Sources de connaissance.
4. **Actions de transition de statut** sur `ApplicationResource` :
   - "Marquer recevable" (eligible) — agent_dgce, super_admin
   - "Demander complément" (incomplete) — agent_dgce, super_admin
   - "Évaluer" (under_review déclenché par première note) — committee_member
   - "Présélectionner" (shortlisted) — committee_president
   - "Retenir" (accepted) — committee_president, super_admin
   - "Liste d'attente" (waitlisted) — idem
   - "Refuser" (rejected) — idem
5. **Side-effects** : chaque transition déclenche un job (`SendStatusEmail`) et éventuellement `GenerateBadgePdf` + `AssignGroup` pour `accepted`.
6. **Audit log** : observer sur `Application` qui écrit dans `audit_logs` à chaque update.
7. **Exports** : Excel (liste complète, listes par groupe), PDF (feuilles de présence par jour), ZIP de badges (job de fond).

### Critères de validation

- [ ] Les 11 widgets s'affichent sur `/admin` sans erreur.
- [ ] Filtres "Femmes uniquement" et "< 35 ans uniquement" fonctionnent.
- [ ] Transition `eligible → accepted` déclenche bien : génération QR + code + groupe + badge PDF + email (vérifiable dans Mailpit + storage).
- [ ] Export Excel "Liste complète" produit un fichier ouvrable avec toutes les colonnes.
- [ ] Audit log contient une entrée pour chaque changement de statut.
- [ ] Un `reader` voit les ressources en lecture seule (pas de bouton d'action).

---

## Phase 7 — PDF, QR, codes, emails, scan d'entrée

**Objectif** : tout le cycle post-acceptation est opérationnel : badge généré, convocation produite, email envoyé, scan d'entrée fonctionne en présentiel.

### Tâches

1. **Services dédiés** :
   - `QrCodeService::generateUniqueQrToken()` : 48 caractères aléatoires, unicité vérifiée.
   - `QrCodeService::generateSignedUrl(string $token): string` : URL signée Laravel avec expiration au 25 juin 23:59 Africa/Abidjan.
   - `QrCodeService::generateUniqueCheckInCode()` : entier entre 100000 et 999999, jamais commençant par 0, unicité vérifiée.
   - `GroupAssignmentService::assign(Application $application): string` : retourne G1/G2/G3 selon l'équilibre des effectifs en cours.
   - `BadgePdfService::generate(Application $application): string` : produit le PDF via `spatie/laravel-pdf` (Browsershot), template Blade dédié, format 100×140 mm.
   - `ConvocationPdfService::generate(Application $application): string` : produit la lettre A4 signée.
2. **Job `GenerateBadgePdf`** : appelé en queue à la transition `accepted`.
3. **10 Mailables** (voir `BRIEF.md` § III.9 et `CONTENT.md` § B), tous avec layout institutionnel commun.
4. **Page `/admin/scan-entry`** :
   - Deux colonnes (caméra QR via `html5-qrcode` lazy-loadé + saisie manuelle code 6 chiffres).
   - Logique de validation : voir `BRIEF.md` § III.6 (4 cas avec toast colorisé).
   - Carte du dernier pointage affichée sous la zone.
5. **Page `/admin/attendances`** : liste live des pointages, filtres par jour/groupe/lieu, export Excel.
6. **Route signée `/scan/qr/{token}`** : valide la signature, enregistre l'`attendance` si non doublon, renvoie une page de confirmation visuelle.
7. **Désistement automatique** : un retenu qui retire sa candidature → premier de la liste d'attente promu (à confirmer en seeders : automatique ou manuel via bouton ?).

### Critères de validation

- [ ] Badge PDF généré : ouvre dans un viewer, contient QR scannable, code 6 chiffres lisible, dimensions 100×140 mm.
- [ ] QR scanné par smartphone (test manuel) → page de confirmation Hub.
- [ ] Test Pest : QR token unique sous concurrence (création parallèle de 100 candidatures `accepted`).
- [ ] Test Pest : un même candidat ne peut être pointé qu'une fois par jour.
- [ ] Test Pest : code à 6 chiffres invalide → toast rouge.
- [ ] Les 10 emails se déclenchent correctement et sont visibles dans Mailpit lors d'un parcours complet.
- [ ] Convocation A4 : signature scannée visible, en-tête institutionnel, programme synthétique en 2e page.

---

## Phase 8 — Tests, performance, documentation, déploiement

**Objectif** : tout est testé, optimisé, documenté, prêt pour la mise en production.

### Tâches

1. **Couverture Pest** ≥ 70 % sur les services (`ApplicationStatusService`, `QrCodeService`, `GroupAssignmentService`, `QuotaCalculatorService`, `ScoringService`).
2. **Feature tests** : voir liste exhaustive du `BRIEF.md` § IV.9.
3. **Audit performance** :
   - Lighthouse Performance ≥ 90 sur `/` (mobile + desktop, build prod).
   - Toutes les images ≤ 200 ko après optimisation, servies en WebP.
   - Critical CSS inline pour le fold.
   - Code splitting et tree shaking Vite vérifiés.
4. **Audit sécurité** :
   - CSP stricte vérifiée dans les headers de réponse.
   - Rate limiting actif sur login (5/min), candidature submit (3/h), contact (5/h), newsletter (3/h).
   - HSTS activé.
   - X-Frame-Options DENY, X-Content-Type-Options nosniff.
5. **Audit accessibilité** : WCAG 2.1 AA validé (au minimum par axe-core en CI).
6. **Documentation** :
   - `README.md` à la racine : description, installation locale, commandes utiles, structure.
   - `docs/ADMIN.md` : workflow comité, exports, scan d'entrée, gestion des rôles.
   - `docs/DEPLOY.md` : Nginx, PHP-FPM, supervisor (queue worker), cron (scheduler), sauvegardes, restauration, monitoring Sentry.
   - `docs/DECISIONS.md` à jour avec toutes les décisions retenues.
7. **CI** : workflow GitHub Actions qui lance Pint + Pest + npm build sur chaque PR.
8. **Préparation déploiement** : Dockerfile prod multi-stage, script de release, configuration supervisord pour le queue worker.

### Critères de validation (= Definition of Done globale du projet)

Voir `CLAUDE.md` § 6.

---

## Notes d'orchestration

### Parallélisation possible

- Phase 4 (site public) et Phase 5 (wizard) peuvent être menées en parallèle dès que Phase 3 est validée.
- Phase 6 (back-office) peut commencer sur les Resources de contenu (News, Partners, Speakers, Faq) en parallèle de la Phase 5.

### Points de synchronisation obligatoires

- Fin de Phase 1 : aucun travail UI ne démarre tant que le modèle de données n'est pas figé (sauf design system pur en Phase 3).
- Fin de Phase 6 : les actions de transition doivent être testées avant la Phase 7 (qui les déclenche en cascade).

### Branches Git

- `main` : protégée, mergeable uniquement via PR.
- Une branche par phase : `phase-0-bootstrap`, `phase-1-data-model`, etc.
- Sous-branches si nécessaire : `phase-6-filament-resources`, `phase-6-filament-widgets`.

### Quand demander avant d'agir

Voir `CLAUDE.md` § 4.