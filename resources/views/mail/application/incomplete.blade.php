<x-mail::message>
# Votre dossier nécessite un *complément.*

Bonjour {{ $user->first_name }},

Lors de la vérification de votre candidature (référence `{{ $application->reference_code }}`), notre équipe a identifié des éléments nécessitant votre attention.

@if($application->admin_notes)
**Éléments à compléter :**

{{ $application->admin_notes }}

@endif

Merci de bien vouloir mettre à jour votre dossier depuis votre espace candidat dans les **5 jours** suivant la réception de cet email. Passé ce délai, votre candidature sera réputée caduque.

<x-mail::button :url="route('candidature.index')" color="primary">
Compléter mon dossier
</x-mail::button>

*La Direction Générale du Commerce Extérieur*

---
<small>Hub Import-Export 2026 — hub-import-export@commerce.gouv.ci</small>
</x-mail::message>
