# Guide d'administration — Hub Import-Export 2026

Guide opérationnel pour la DGCE et les membres du comité de sélection.

---

## 1. Accès au back-office

URL : `https://hubimportexport.ci/admin`

Comptes créés par le super-admin DGCE. Contacter `admin@hubimportexport.ci` pour tout accès supplémentaire.

---

## 2. Workflow du comité de sélection

### Cycle de vie d'une candidature

```
Draft → Received → Eligible → Under Review → Shortlisted → Accepted
                                                           → Waitlisted
                ↓           ↓              ↓               → Rejected
             Incomplete  Withdrawn     Withdrawn
```

### Étapes opérationnelles

| Étape | Qui | Action dans Filament |
|---|---|---|
| 1. Réception | Agent DGCE | Candidatures → Marquer recevable |
| 2. Vérification | Agent DGCE | Candidatures → Demander complément (si manque) |
| 3. Évaluation | Membre comité | Candidatures → Évaluer (score 1–5 par critère) |
| 4. Présélection | Président comité | Candidatures → Présélectionner |
| 5. Délibération | Président comité | Tableau de délibération → Retenir / Waitlist / Refuser |

### Tableau de délibération

`/admin/committee-board` — vue kanban avec 3 colonnes : Éligibles, En évaluation, Présélectionnés. Chaque carte permet une transition de statut directe.

---

## 3. Exports

`/admin/export-center` — trois exports disponibles :

| Export | Contenu |
|---|---|
| Toutes les candidatures | Tous les dossiers soumis (hors brouillons et retirés) |
| Candidatures retenues | Statut `accepted` uniquement |
| Liste d'attente | Statut `waitlisted` uniquement |

Format : XLSX. Colonnes : Référence, Prénom, Nom, Email, Genre, Date de naissance, Ville, Pays, Nationalité, Profil, Organisation, Poste, Expérience, Statut, Score, Groupe, Soumis le, Accepté le.

---

## 4. Pointage (jour de l'événement)

`/admin/scan-entry`

**Deux modes :**
1. **QR Code** — pointez la caméra sur le badge du participant. La page confirme l'identité en vert.
2. **Code manuel** — saisissez le code à 6 chiffres affiché sur le badge. Appuyer Entrée pour valider.

**Codes couleur :**
- Vert ✓ — présence enregistrée
- Orange ⚠ — déjà pointé aujourd'hui (doublon bloqué automatiquement)
- Rouge ✗ — code inconnu ou candidature non retenue

**Consultation des présences :** `/admin/attendances` — liste en temps réel, filtrée par jour/groupe/lieu, actualisée toutes les 15 secondes.

---

## 5. Gestion du contenu

| Section | URL admin |
|---|---|
| Actualités | `/admin/news` |
| Intervenants | `/admin/speakers` |
| Partenaires | `/admin/partners` |
| FAQ | `/admin/faq-items` |
| Ateliers | `/admin/workshops` |
| Paramètres | `/admin/settings` |

---

## 6. Gestion des utilisateurs

`/admin/users` — créer, modifier, activer/désactiver des comptes. Attribuer des rôles.

**Rôles disponibles :**
- `super_admin` — accès total
- `agent_dgce` — recevabilité et demandes de complément
- `committee_member` — évaluation uniquement
- `committee_president` — présélection et décision finale
- `reader` — lecture seule
- `candidate` — espace candidat uniquement (compte Breeze)

---

## 7. Journal d'audit

`/admin/audit-logs` — toutes les modifications de candidatures tracées (qui, quoi, quand, depuis quelle IP).

---

## 8. Newsletter

`/admin/newsletter-subscribers` — liste des abonnés confirmés. Lecture seule.
