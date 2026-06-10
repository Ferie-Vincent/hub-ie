<x-mail::message>
# Félicitations — votre inscription est confirmée !

Bonjour {{ $user->first_name }},

Nous avons le plaisir de vous informer que votre inscription à la session ci-dessous a été **officiellement validée**. Vous étiez sur liste d'attente — une place s'est libérée et elle vous a été attribuée.

<x-mail::panel>
**Détails de votre inscription :**

- **Session :** {{ $workshop->title }}
- **Dates de l'événement :** du lundi **22 au jeudi 25 juin 2026**, Abidjan
- **Participant :** {{ $user->first_name }} {{ $user->last_name }}
</x-mail::panel>

Si vous ne pouvez finalement pas y assister, nous vous remercions d'annuler votre inscription avant le **{{ \Carbon\Carbon::parse($deadline)->translatedFormat('d F Y à H\hi') }}** afin de permettre à un autre participant d'en bénéficier.

<x-mail::button :url="$cancelUrl" color="error">
Annuler mon inscription
</x-mail::button>

@if($user->badge_pdf_path ?? false)
Votre **badge officiel** est joint à ce message. Vous pouvez également le télécharger à tout moment depuis votre espace.
@else
Votre badge officiel sera généré prochainement et vous sera transmis par email dès qu'il sera disponible.
@endif

Bienvenue parmi les participants confirmés. Nous nous réjouissons de vous accueillir à Abidjan.

*La Direction Générale du Commerce Extérieur*

---
<small>Hub Import-Export 2026 — hub-import-export@commerce.gouv.ci</small>
</x-mail::message>
