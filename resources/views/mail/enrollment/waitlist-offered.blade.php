<x-mail::message>
# Une place vient de se libérer — {{ $workshop->title }}

Bonjour {{ $user->first_name }},

Bonne nouvelle : une place s'est libérée dans la session à laquelle vous étiez inscrit(e) sur liste d'attente.

<x-mail::panel>
**Session concernée :**

- **Intitulé :** {{ $workshop->title }}
- **Événement :** Hub Import-Export 2026 — 22 au 25 juin 2026, Abidjan
</x-mail::panel>

Votre dossier est en cours d'examen par notre équipe. **Vous recevrez un email de confirmation dès que votre inscription aura été validée** par l'administration. Aucune action de votre part n'est requise à ce stade.

Nous traitons les demandes dans l'ordre de la liste d'attente et vous tiendrons informé(e) dans les plus brefs délais.

*La Direction Générale du Commerce Extérieur*

---
<small>Hub Import-Export 2026 — hub-import-export@commerce.gouv.ci</small>
</x-mail::message>
