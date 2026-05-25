<x-mail::message>
# Vous êtes *présélectionné(e).*

Bonjour {{ $user->first_name }},

Votre candidature au Hub Import-Export 2026 (référence `{{ $application->reference_code }}`) a été **retenue au stade de la présélection** par le comité.

La décision finale sera prononcée par le Président du comité dans les 7 jours à venir. Nous vous remercions par avance de la qualité de votre dossier et reviendrons vers vous rapidement.

<x-mail::button :url="route('candidate.dashboard')" color="primary">
Consulter mon espace
</x-mail::button>

*La Direction Générale du Commerce Extérieur*

---
<small>Hub Import-Export 2026 — hub-import-export@commerce.gouv.ci</small>
</x-mail::message>
