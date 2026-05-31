# Hub Import-Export 2026

Plateforme institutionnelle du Hub Import-Export 2026, organisé par la Direction Générale du Commerce Extérieur (DGCE) — Ministère du Commerce, de l'Industrie et de l'Artisanat de Côte d'Ivoire.

**Dates :** 22–25 juin 2026, Abidjan  
**Capacité :** 180 auditeurs sélectionnés sur candidature

---

## Stack

| Composant | Version |
|---|---|
| PHP | 8.3+ |
| Laravel | 12 |
| Livewire | 3 |
| Alpine.js | 3 |
| Tailwind CSS | 3.4 |
| Filament | 3 |
| MySQL | 8 |
| Pest | 3 |

---

## Installation locale (< 15 minutes)

### Prérequis

- MAMP (PHP 8.3+, MySQL 8)
- Composer
- Node.js 20+

### Étapes

```bash
# 1. Cloner le dépôt
git clone <repo-url> hub-ie
cd hub-ie

# 2. Variables d'environnement
cp .env.example .env
# Éditer .env : DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD

# 3. Dépendances PHP
composer install

# 4. Clé d'application
php artisan key:generate

# 5. Migrations + seeders
php artisan migrate:fresh --seed

# 6. Dépendances JS + build
npm install && npm run build

# 7. Lancer le serveur
php artisan serve
```

Accéder à :
- Site public : `http://127.0.0.1:8000`
- Back-office Filament : `http://127.0.0.1:8000/admin`

### Compte admin par défaut (seeder)

```
Email    : admin@hubimportexport.ci
Password : password
```

---

## Commandes utiles

```bash
# Tests
php artisan test
php artisan test --coverage

# Compilation assets
npm run dev     # watch mode
npm run build   # production

# Linter PHP
./vendor/bin/pint

# Fresh database avec données de test
php artisan migrate:fresh --seed

# Queue worker (requis pour les emails et les PDFs)
php artisan queue:work

# Scheduler (optionnel en dev)
php artisan schedule:run
```

---

## Architecture

```
app/
├── Enums/           # ApplicationStatus, ApplicationCategory, Gender
├── Filament/
│   ├── Pages/       # Dashboard, CommitteeBoard, ExportCenter, ScanEntry
│   ├── Resources/   # 11 ressources Filament
│   └── Widgets/     # 11 widgets dashboard
├── Http/
│   ├── Controllers/ # ApplicationController, QrScanController, Newsletter...
│   └── Middleware/  # EnsureCandidateRole
├── Jobs/            # GenerateBadgePdf
├── Livewire/        # ApplicationWizard (formulaire 4 étapes)
├── Mail/            # 10 Mailables
├── Models/          # 13 modèles Eloquent
├── Observers/       # ApplicationObserver (audit log)
├── Policies/        # Policies par modèle
└── Services/        # ApplicationStatusService, QrCodeService, GroupAssignmentService...

resources/
├── css/app.css      # Design system (variables HSL, composants)
├── js/              # countdown.js, reveal.js, app.js
└── views/
    ├── public/      # 11 sections site public
    ├── candidate/   # Espace candidat
    ├── candidature/ # Wizard formulaire
    ├── filament/    # Vues custom Filament
    ├── mail/        # 10 templates email Markdown
    └── pdf/         # Badge 100×140mm, Convocation A4
```

---

## Rôles

| Rôle | Accès |
|---|---|
| `super_admin` | Tout |
| `agent_dgce` | Candidatures : recevabilité, demande complément |
| `committee_member` | Évaluation |
| `committee_president` | Présélection, décision finale |
| `reader` | Lecture seule |
| `candidate` | Espace candidat uniquement |

---

## Documentation

- `docs/ADMIN.md` — Guide opérationnel DGCE
- `docs/DEPLOY.md` — Installation production
- `docs/DECISIONS.md` — Journal des décisions techniques
- `docs/BRIEF.md` — Cahier des charges complet
- `docs/IMPLEMENTATION-PLAN.md` — Plan de phases
