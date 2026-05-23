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