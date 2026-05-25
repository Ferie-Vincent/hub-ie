<x-mail::message>
# Dans *7 jours* à Abidjan.

Bonjour {{ $user->first_name }},

Le Hub Import-Export 2026 ouvre ses portes dans une semaine. Vous figurez parmi les 180 auditeurs retenus pour cette édition.

<x-mail::panel>
**Rappel pratique :**

- Du lundi **22 au jeudi 25 juin 2026**
- CGECI · CCI-CI · SEEN Hôtel (Plateau, Abidjan)
- Apportez votre badge (téléchargeable depuis votre espace)
- Code d'accès : **{{ $application->check_in_code }}**
- Apportez une pièce d'identité
</x-mail::panel>

À très bientôt.

<x-mail::button :url="route('candidate.dashboard')" color="primary">
Accéder à mon espace candidat
</x-mail::button>

*La Direction Générale du Commerce Extérieur*

---
<small>Hub Import-Export 2026 — hub-import-export@commerce.gouv.ci</small>
</x-mail::message>
