<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Activez votre espace — Hub Import-Export 2026</title>
    <style>
        body { margin: 0; padding: 0; background: #0a0b0f; font-family: 'Helvetica Neue', Arial, sans-serif; color: #f0ede6; }
        .wrapper { max-width: 580px; margin: 40px auto; }
        .header { padding: 32px 40px 24px; text-align: center; border-bottom: 1px solid rgba(255,255,255,0.08); }
        .badge { font-size: 11px; letter-spacing: 0.15em; text-transform: uppercase; color: #f57a00; margin-bottom: 8px; }
        .brand { font-size: 22px; font-weight: 700; color: #f0ede6; }
        .brand em { color: #e8c46a; font-style: normal; }
        .body { padding: 40px; }
        .body p { font-size: 15px; line-height: 1.7; color: rgba(240,237,230,0.8); margin: 0 0 16px; }
        .btn { display: inline-block; padding: 14px 32px; background: #f57a00; color: #fff; font-size: 15px; font-weight: 600; border-radius: 8px; text-decoration: none; margin: 8px 0 24px; }
        .link-fallback { font-size: 12px; color: rgba(240,237,230,0.45); word-break: break-all; }
        .footer { padding: 24px 40px; text-align: center; border-top: 1px solid rgba(255,255,255,0.08); font-size: 12px; color: rgba(240,237,230,0.35); }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="header">
        <div class="badge">Ministère du Commerce · Côte d'Ivoire</div>
        <div class="brand">Hub Import-Export <em>2026</em></div>
    </div>

    <div class="body">
        <p>Bonjour {{ $preInscription->prenom }},</p>
        <p>
            Votre pré-inscription au <strong>Hub Import-Export 2026</strong> a bien été enregistrée.
            Pour suivre l'avancement de votre dossier, activez votre espace personnel en créant votre mot de passe.
        </p>

        <div style="text-align:center;margin:32px 0;">
            <a href="{{ $activationUrl }}" class="btn">Créer mon mot de passe</a>
        </div>

        <p>
            Ce lien est valable <strong>7 jours</strong>. Passé ce délai, contactez-nous pour obtenir un nouveau lien.
        </p>

        <p class="link-fallback">
            Si le bouton ne fonctionne pas, copiez ce lien dans votre navigateur :<br>
            {{ $activationUrl }}
        </p>
    </div>

    <div class="footer">
        Direction Générale du Commerce Extérieur (DGCE) · Abidjan, Côte d'Ivoire<br>
        Cet e-mail a été envoyé automatiquement, merci de ne pas y répondre.
    </div>
</div>
</body>
</html>
