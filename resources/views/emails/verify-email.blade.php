<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vérifiez votre e-mail — Hub Import-Export 2026</title>
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
        <p>Bonjour,</p>
        <p>
            Merci pour votre inscription sur la plateforme du <strong>Hub Import-Export 2026</strong>.
            Veuillez confirmer votre adresse e-mail en cliquant sur le bouton ci-dessous.
        </p>

        <div style="text-align:center;margin:32px 0;">
            <a href="{{ $url }}" class="btn">Vérifier mon adresse e-mail</a>
        </div>

        <p>
            Ce lien expirera dans 60 minutes. Si vous n'avez pas créé de compte, aucune action n'est requise.
        </p>

        <p class="link-fallback">
            Si le bouton ne fonctionne pas, copiez ce lien dans votre navigateur :<br>
            {{ $url }}
        </p>
    </div>

    <div class="footer">
        Direction Générale du Commerce Extérieur (DGCE) · Abidjan, Côte d'Ivoire<br>
        Cet e-mail a été envoyé automatiquement, merci de ne pas y répondre.
    </div>
</div>
</body>
</html>
