<x-mail::message>
# Invitation à rejoindre le Hub Import-Export 2026

Bonjour,

**{{ $inviterName }}** vous invite à rejoindre la plateforme de gestion du Hub Import-Export 2026 en tant que **{{ $role }}**.

Cliquez sur le bouton ci-dessous pour créer votre compte. Ce lien expire le **{{ $expiresAt }}**.

<x-mail::button :url="$acceptUrl" color="success">
Créer mon compte
</x-mail::button>

Si vous n'attendiez pas cette invitation, ignorez simplement cet email.

Cordialement,
L'équipe Hub Import-Export 2026
</x-mail::message>
