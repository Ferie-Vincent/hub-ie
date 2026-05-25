<x-mail::message>
# Votre candidature est sur *liste d'attente.*

Bonjour {{ $user->first_name }},

Votre candidature au Hub Import-Export 2026 (référence `{{ $application->reference_code }}`) a retenu l'attention du comité de sélection. Toutefois, en raison du nombre limité de places (180 auditeurs), elle a été placée sur **liste d'attente**.

En cas de désistement d'un auditeur retenu, vous serez contacté(e) en priorité pour intégrer la promotion. Nous vous tiendrons informé(e) jusqu'au démarrage de l'événement.

Nous vous remercions de votre intérêt et restons à votre disposition.

<x-mail::button :url="route('candidate.dashboard')" color="primary">
Consulter mon espace
</x-mail::button>

*La Direction Générale du Commerce Extérieur*

---
<small>Hub Import-Export 2026 — hub-import-export@commerce.gouv.ci</small>
</x-mail::message>
