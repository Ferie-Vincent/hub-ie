@component('mail::message')
# Hub Import-Export {{ $edition->year }} — Les candidatures sont ouvertes !

Bonjour {{ $recipient->name }},

Nous avons le plaisir de vous annoncer le lancement de la **{{ $edition->title }}**.

@if($edition->theme)
Thème de cette édition : **{{ $edition->theme }}**

@endif
@if($edition->event_starts_at && $edition->event_ends_at)
Dates de l'événement : du **{{ $edition->event_starts_at->locale('fr')->isoFormat('D MMMM YYYY') }}** au **{{ $edition->event_ends_at->locale('fr')->isoFormat('D MMMM YYYY') }}**@if($edition->location) à **{{ $edition->location }}**@endif.

@endif
En tant qu'ancien participant au Hub Import-Export, vous êtes invité(e) à candidater pour cette nouvelle édition.

@component('mail::button', ['url' => route('candidature.index'), 'color' => 'success'])
Déposer ma candidature
@endcomponent

@if($edition->application_closes_at)
⏰ Les candidatures sont ouvertes jusqu'au **{{ $edition->application_closes_at->locale('fr')->isoFormat('D MMMM YYYY') }}**.

@endif
**Pourquoi participer ?**
- Renforcer vos compétences en import-export
- Développer votre réseau avec des professionnels du commerce international
- Bénéficier de l'accompagnement du Ministère du Commerce

Merci de votre confiance et à très bientôt,

**L'équipe Hub Import-Export**
Ministère du Commerce, de l'Industrie et de la Promotion des PME
Côte d'Ivoire

@component('mail::subcopy')
Vous recevez cet email car vous avez participé à une édition précédente du Hub Import-Export.
Pour ne plus recevoir ces communications, [cliquez ici]({{ route('newsletter.unsubscribe', ['token' => 'PLACEHOLDER']) }}).
@endcomponent
@endcomponent
