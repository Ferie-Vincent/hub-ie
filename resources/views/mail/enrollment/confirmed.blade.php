<x-mail::message>
# Inscription confirmée — {{ $workshop->title }}

Bonjour {{ $user->first_name }},

Nous avons le plaisir de confirmer votre inscription à la session ci-dessous dans le cadre du **Hub Import-Export 2026**.

<x-mail::panel>
**Détails de votre inscription :**

- **Session :** {{ $workshop->title }}
- **Dates de l'événement :** du lundi **22 au jeudi 25 juin 2026**, Abidjan
- **Participant :** {{ $user->first_name }} {{ $user->last_name }}
</x-mail::panel>

Votre place est désormais réservée. Si vous souhaitez annuler votre inscription, vous pouvez le faire jusqu'au **{{ \Carbon\Carbon::parse($deadline)->translatedFormat('d F Y à H\hi') }}** en cliquant sur le lien ci-dessous. Passé ce délai, votre place sera maintenue et ne pourra plus être libérée.

<x-mail::button :url="$cancelUrl" color="error">
Annuler mon inscription
</x-mail::button>

@if($user->badge_pdf_path ?? false)
Votre **badge officiel** est joint à ce message. Vous pouvez également le télécharger à tout moment depuis votre espace.
@else
Votre badge officiel sera généré prochainement et vous sera transmis par email dès qu'il sera disponible.
@endif

Nous vous souhaitons une excellente participation.

*La Direction Générale du Commerce Extérieur*

---
<small>Hub Import-Export 2026 — hub-import-export@commerce.gouv.ci</small>
</x-mail::message>
