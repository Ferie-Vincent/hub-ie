<x-mail::message>
# Candidature reçue — Hub Import-Export 2026

Bonjour {{ $user->first_name }} {{ $user->last_name }},

Nous avons bien reçu votre candidature au **Hub Import-Export 2026** (22–25 juin 2026, Abidjan).

**Référence :** `{{ $application->reference_code }}`

Votre dossier est en cours d'instruction administrative. Vous serez informé(e) par email de l'avancement de votre candidature.

<x-mail::button :url="route('candidate.dashboard')" color="primary">
Suivre ma candidature
</x-mail::button>

Cordialement,\
L'équipe Hub Import-Export 2026\
Direction Générale du Commerce Extérieur — Ministère du Commerce de Côte d'Ivoire

---
<small>Cet email a été envoyé automatiquement. En cas de question, contactez-nous à <a href="mailto:contact@dgce.ci">contact@dgce.ci</a>.</small>
</x-mail::message>
