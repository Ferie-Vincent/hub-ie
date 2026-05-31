<x-mail::message>
# Votre dossier est *en cours d'évaluation.*

Bonjour {{ $user->first_name }},

Votre candidature au Hub Import-Export 2026 (référence `{{ $application->reference_code }}`) est actuellement **examinée par le comité de sélection**.

Cette phase d'évaluation dure généralement **5 à 10 jours ouvrés**. Vous recevrez un email dès que la décision sera prononcée.

<x-mail::button :url="route('candidate.dashboard')" color="primary">
Suivre ma candidature
</x-mail::button>

Merci de votre patience.

*La Direction Générale du Commerce Extérieur*

---
<small>Hub Import-Export 2026 — hub-import-export@commerce.gouv.ci</small>
</x-mail::message>
