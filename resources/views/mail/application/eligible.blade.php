<x-mail::message>
# Bonne nouvelle, votre dossier est *recevable.*

Bonjour {{ $user->first_name }},

Votre candidature au Hub Import-Export 2026 (référence `{{ $application->reference_code }}`) a été déclarée **recevable** par notre équipe administrative.

Elle entre désormais en phase d'évaluation par le comité de sélection. Vous serez informé(e) de la décision finale dans les meilleurs délais, au plus tard 10 jours avant l'ouverture du Hub.

Nous vous remercions de votre patience.

<x-mail::button :url="route('candidate.dashboard')" color="primary">
Consulter mon espace
</x-mail::button>

*La Direction Générale du Commerce Extérieur*

---
<small>Hub Import-Export 2026 — hub-import-export@commerce.gouv.ci</small>
</x-mail::message>
