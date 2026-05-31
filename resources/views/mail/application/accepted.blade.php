<x-mail::message>
# Félicitations {{ $user->first_name }} — vous êtes retenu(e).

Bonjour {{ $user->first_name }},

Le comité de sélection du Hub Import-Export 2026 a le plaisir de vous confirmer votre admission en qualité d'**auditeur officiel** pour l'édition 2026.

<x-mail::panel>
**Vos informations de présence :**

- **Référence :** `{{ $application->reference_code }}`
- **Groupe :** {{ $application->group_label }}
- **Code d'accès :** {{ $application->check_in_code }}
</x-mail::panel>

Vous trouverez en pièce jointe votre **badge officiel** et votre **convocation officielle** signée. Vous pouvez également télécharger ces documents depuis votre espace candidat à tout moment.

**Rappel des dates :** du lundi **22 au jeudi 25 juin 2026**, à Abidjan.

Au plaisir de vous y accueillir.

<x-mail::button :url="route('candidate.dashboard')" color="primary">
Accéder à mon espace
</x-mail::button>

*La Direction Générale du Commerce Extérieur*

---
<small>Hub Import-Export 2026 — hub-import-export@commerce.gouv.ci</small>
</x-mail::message>
