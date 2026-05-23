# Hub Import-Export 2026 — Guide opérationnel Claude Code

> **Lis ce fichier en entier avant ta première action.** Puis lis `docs/BRIEF.md` (technique), `docs/IMPLEMENTATION-PLAN.md` (phasing), `docs/CONTENT.md` (textes officiels). Ne lance aucune commande tant que tu n'as pas lu les quatre.

---

## 1. Le projet en 5 lignes

Plateforme institutionnelle (`hubimportexport.ci`) commanditée par le Ministère du Commerce de Côte d'Ivoire pour l'événement Hub Import-Export 2026 (22–25 juin 2026, Abidjan, 180 auditeurs). Trois missions : vitrine publique, portail de candidature avec validation comité, outil de pilotage et de pointage (QR + code 6 chiffres). Stack imposée : **Laravel 12 + Livewire 3 + Alpine + Tailwind 3.4 + Filament 3 + PostgreSQL 16 + Redis 7**. Référence visuelle obligatoire à reproduire : `https://diasporaforgrowth.ci/`. Langue V1 : français uniquement.

## 2. Commande de démarrage

```bash
# Étape unique pour bootstrap (Phase 0 du plan d'implémentation)
composer create-project laravel/laravel . "^12.0" \
  && php artisan key:generate \
  && cp .env .env.example
```

Détails complets et packages : voir `docs/IMPLEMENTATION-PLAN.md` § Phase 0.

## 3. Règles non-négociables

| # | Règle |
|---|---|
| 1 | **Tu suis le plan de phases.** Phases 0 → 8 en ordre. Tu ne démarres une phase que si la précédente passe ses critères de validation (voir `IMPLEMENTATION-PLAN.md`). |
| 2 | **Tu commits à la fin de chaque phase.** Branche par phase : `phase-0-bootstrap`, `phase-1-data-model`, etc. Merges via PR vers `main`. Messages de commit en anglais, présent : `feat: add application wizard step 2 validation`. |
| 3 | **Tu ne consignes aucune mention "généré par Claude" dans le code, les commits ou les PR.** Aucun co-author Claude. Aucun `🤖 Generated with Claude Code`. |
| 4 | **Tu utilises uniquement les textes officiels de `docs/CONTENT.md`** pour tout contenu visible utilisateur (Mot du Ministre, ateliers, FAQ, emails, mentions légales). Tu n'inventes aucun texte institutionnel. |
| 5 | **Tu n'inventes aucune relation institutionnelle.** Les partenaires (TradeMark Africa, GIZ, ACIEx, CNE, etc.) sont des placeholders nominatifs en seeders, sans logos ni descriptions fabriqués. |
| 6 | **Tu reproduis fidèlement les animations** du § II.5 du `BRIEF.md` (13 keyframes + 5 effets de hover sur cards + 4 effets sur lignes de programme). Tu n'en ajoutes pas. Tu n'en omets pas. |
| 7 | **Tu écris en français pour tout ce qui est utilisateur final** (libellés UI, emails, validations, messages d'erreur). Code, commentaires de code, identifiants : anglais. |
| 8 | **Tu écris des tests Pest pour toute logique métier critique** (transitions de statut, génération QR, calcul de quotas, scan d'entrée). Pas de logique métier sans test. |
| 9 | **Tu consignes toute hypothèse technique dans `docs/DECISIONS.md`** (à créer dès la Phase 0). Format : date, contexte, décision, alternatives écartées, justification. |
| 10 | **Tu ne touches jamais au `.env` réel** ; tout va dans `.env.example` avec valeurs vides ou placeholders explicites. |

## 4. Quand tu dois t'arrêter et demander

- Une exigence du `BRIEF.md` contredit une autre.
- Un fichier asset listé en `docs/BRIEF.md` annexe D est requis et absent (ex : logo SVG natif pour l'animation `draw`).
- Une décision impacte le coût d'hébergement ou la conformité ARTCI.
- Le scope d'une phase déborde de plus de 30 % sur la phase suivante.

Dans tous les autres cas, tu décides, tu documentes dans `DECISIONS.md`, tu avances.

## 5. Conventions de code

**Structure** : convention Laravel standard + Filament. Voir `BRIEF.md` § IV.5 pour l'arborescence imposée.

**Naming** :
- Modèles : singulier, PascalCase (`Application`, `Workshop`)
- Tables : pluriel, snake_case (`applications`, `application_documents`)
- Énums : `App\Enums\ApplicationStatus`, valeurs en snake_case (`received`, `under_review`)
- Routes nommées : voir `BRIEF.md` § IV.6 (liste exhaustive imposée)
- Services : suffixe `Service` (`ApplicationStatusService`, `QrCodeService`)
- Jobs : verbe + nom (`GenerateBadgePdf`, `SendStatusEmail`)
- Mailables : nom du trigger (`ApplicationReceived`, `ApplicationAccepted`)

**Style** :
- PHP : Pint avec preset Laravel.
- Blade : 2 espaces, attributs `wire:` et `x-` toujours en dernier.
- CSS : variables HSL strictes (voir `BRIEF.md` § II.1), classes utilitaires Tailwind d'abord, classes custom seulement pour les patterns signature (`.glass`, `.btn-fill`, `.link-underline`, `.v10-*`).
- JS : modules ES sous `resources/js/`, pas de jQuery, pas de bundle externe sauf `html5-qrcode` (lazy load CDN).

**Migrations** : une migration par table, jamais de `Schema::table` pour ajouter des colonnes à une table que tu viens de créer (refonds la migration). Toujours `down()` réversible.

**Énums PHP 8.1+** : `string` backed, méthodes `label(): string` pour les libellés FR, `color(): string` pour les couleurs Filament.

**Eloquent** : `$fillable` exhaustif, `$casts` systématique, relations typées (`HasMany`, `BelongsTo`).

## 6. Definition of Done globale

À la fin de la Phase 8, le projet est livré si :

- `php artisan migrate:fresh --seed` aboutit sur une base vide en < 30 secondes.
- `npm run build` produit un bundle < 500 ko gzipped (hors fonts et images).
- `php artisan test` passe à 100 %, couverture services critiques ≥ 70 %.
- Lighthouse Performance ≥ 90 sur `/` en mobile et desktop (mesure locale en build prod).
- WCAG 2.1 AA : tous les boutons et liens navigables au clavier, contrastes vérifiés.
- README.md à la racine permet à un nouveau développeur d'installer en local en < 15 minutes.
- `docker-compose up` lance app + nginx + postgres + redis + minio + mailpit fonctionnels.
- 10 mailables visibles dans Mailpit en local lors du déroulement complet d'une candidature jusqu'au pointage.
- Le dossier `docs/` contient `ADMIN.md`, `DEPLOY.md`, `DECISIONS.md` à jour.

## 7. Index des documents du projet

| Document | Quand le lire |
|---|---|
| `CLAUDE.md` (ce fichier) | En premier, en entier. |
| `docs/BRIEF.md` | Une fois en lecture intégrale au début, puis section par section selon la phase courante. |
| `docs/IMPLEMENTATION-PLAN.md` | Au démarrage de chaque phase pour identifier les tâches, dépendances et critères de validation. |
| `docs/CONTENT.md` | Au moment d'intégrer du texte institutionnel (hero, ateliers, emails, légal). Ne jamais paraphraser. |
| `docs/DECISIONS.md` | À créer en Phase 0. À enrichir à chaque hypothèse retenue. |
| `docs/ADMIN.md` | À écrire en Phase 8. Guide d'exploitation pour la DGCE. |
| `docs/DEPLOY.md` | À écrire en Phase 8. Guide d'installation production. |

## 8. Stack et versions (rappel synthétique)

PHP 8.3+ · Laravel 12 · Livewire 3 · Alpine 3 · Tailwind 3.4 · Filament 3 · Breeze (Blade) · Spatie Laravel Permission 6 · PostgreSQL 16 · Redis 7 · `simplesoftwareio/simple-qrcode` · `spatie/laravel-pdf` · `maatwebsite/excel` 3 · MinIO (S3-compatible) · `html5-qrcode` (CDN) · Sentry · Pest 3 · Docker Compose.

Détails et justifications : `BRIEF.md` § IV.1.

---

**Fin du guide opérationnel.** Tu peux maintenant lire `docs/BRIEF.md`.