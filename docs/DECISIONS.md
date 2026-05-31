# Décisions techniques

> Journal des hypothèses retenues et choix d'architecture pris en cours d'implémentation. Chaque entrée : date, contexte, décision, alternatives écartées, justification.

---

## Modèle d'entrée

### YYYY-MM-DD · Titre court de la décision

**Contexte** : ce qui a motivé la question.

**Décision** : ce qui a été retenu, formulé en une phrase actionnable.

**Alternatives écartées** :
- Option A — raison du rejet
- Option B — raison du rejet

**Justification** : pourquoi cette option l'emporte, en 1 à 3 lignes.

**Impact** : phases, fichiers ou composants affectés.

---

<!-- Les décisions s'ajoutent ci-dessous au fil de l'implémentation. -->

### 2026-05-23 · Stack simplifiée — MySQL + fichier (sans Redis, sans MinIO, sans Docker)

**Contexte** : volume réel de l'événement ~150 personnes. Stack PostgreSQL + Redis + MinIO + Docker initialement prévue surdimensionnée.

**Décision** : MySQL 8 (MAMP) + sessions fichier + cache fichier + queues database + stockage disque local.

**Alternatives écartées** :
- PostgreSQL + Redis — justifié pour > 10 000 users concurrents, pas pour 150
- MinIO / S3 — inutile pour ~50 fichiers uploadés (CVs, pièces)
- Docker Compose — complexité inutile sur environnement MAMP déjà fonctionnel

**Justification** : décision de l'auteur du brief. Simplifie le développement, réduit les dépendances, zéro impact sur les fonctionnalités à cette échelle.

**Impact** : `.env`, `.env.example`, `BRIEF.md`, `CLAUDE.md`, `docker-compose.yml` (conservé pour référence prod future).

### 2026-05-23 · PHP binaire pour le développement local

**Contexte** : PHP 8.4.x Homebrew cassé (`libnetsnmp.40.dylib` manquante sur macOS 26.1). La commande `php` système pointe sur 8.4 non fonctionnel.

**Décision** : utiliser `/opt/homebrew/opt/php@8.3/bin/php` et forcer `PATH=/opt/homebrew/opt/php@8.3/bin:$PATH` pour toutes les commandes locales (artisan, composer, pint, pest).

**Alternatives écartées** :
- PHP MAMP 8.3 — extensions redis/opcache non compilées
- Corriger net-snmp — risque de casse du système macOS

**Justification** : PHP 8.3.19 Homebrew fonctionnel avec toutes les extensions requises. Version conforme à la spec (8.3+). Docker Compose utilisé pour isolation en staging/CI.

**Impact** : Phase 0 uniquement. Consigner dans `mem:suggested_commands` Serena.

---

### 2026-05-23 · Architecture dev local — hybride hôte + Docker

**Contexte** : projet dans MAMP htdocs, PostgreSQL 16 et Redis 7 non installés nativement. Docker Desktop disponible.

**Décision** : PHP/artisan/npm tournent sur la machine hôte ; postgres, redis, minio, mailpit via `docker-compose up -d`.

**Alternatives écartées** :
- Full Docker — volumes macOS lents, DX dégradé
- SQLite local — incompatible avec indexes et types PostgreSQL-spécifiques du modèle de données

**Justification** : approche hybride standard Laravel. Meilleure performance DX. Conforme critères Phase 0.

**Impact** : Phase 0. `.env` local pointe sur `127.0.0.1` ports exposés Docker.

---

### 2026-05-24 · Single Livewire component pour le wizard de candidature

**Contexte** : formulaire 4 étapes — options : multi-composants Livewire ou composant unique avec état `step`.

**Décision** : composant unique `ApplicationWizard` avec propriété `step` (1–4), draft persisté à chaque `nextStep()`.

**Alternatives écartées** :
- Multi-composants Livewire (un par étape) — complexité de synchronisation d'état entre composants
- Session server-side uniquement — perte de données si expiration de session

**Justification** : un seul composant simplifie la logique de validation par étape et le persist de draft. Pas de navigation multi-page = meilleure UX. Le draft en DB évite les pertes si l'utilisateur revient plus tard.

**Impact** : `ApplicationWizard.php`, candidature migrations, `Application` model.

---

### 2026-05-24 · QR token : 48 chars hex via random_bytes

**Contexte** : le QR token doit être non-devinable, unique, et inclus dans une URL signée Laravel.

**Décision** : `bin2hex(random_bytes(24))` = 48 caractères hexadécimaux, unicité vérifiée par boucle do-while.

**Alternatives écartées** :
- UUID v4 — structure prévisible (variante + version visibles)
- random_int → base62 — plus complexe sans gain de sécurité

**Justification** : 48 chars hex = 192 bits d'entropie. Probabilité de collision négligeable (<1/2^192). Conforme OWASP recommandations tokens uniques.

**Impact** : `QrCodeService`, `Application` model (`qr_token` column), badge PDF, route `/scan/qr/{token}`.

---

### 2026-05-24 · Audit log via Eloquent Observer

**Contexte** : traçabilité de tous les changements de statut de candidature requise.

**Décision** : `ApplicationObserver` enregistré dans `AppServiceProvider`, écrit dans `audit_logs` sur `created` et `updated`.

**Alternatives écartées** :
- Log fichier — non requêtable depuis Filament
- Event/Listener dédié — plus de code pour même résultat

**Justification** : Observer = pattern standard Laravel pour les side-effects liés aux modèles. Centralisé, transparent pour les appelants.

**Impact** : `ApplicationObserver`, `AppServiceProvider`, `AuditLogResource` Filament.