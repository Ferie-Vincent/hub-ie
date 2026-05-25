<x-layouts.email
    title="Confirmez votre inscription — Hub Import-Export 2026"
    preheader="Un clic pour confirmer votre inscription aux actualités du Hub Import-Export 2026."
>
    <h2 style="margin:0 0 16px;font-family:'Playfair Display',Georgia,serif;font-size:24px;font-weight:700;color:#0A0A0F;line-height:1.2;">
        Confirmez votre inscription
    </h2>

    <p style="margin:0 0 16px;font-family:Arial,sans-serif;font-size:15px;line-height:1.6;color:rgba(10,10,15,0.75);">
        Vous avez demandé à recevoir les actualités et informations officielles du
        <strong style="color:#0A0A0F;">Hub Import-Export 2026</strong>,
        organisé par le Ministère du Commerce, de l'Industrie et de l'Artisanat de la République de Côte d'Ivoire.
    </p>

    <p style="margin:0 0 28px;font-family:Arial,sans-serif;font-size:15px;line-height:1.6;color:rgba(10,10,15,0.75);">
        Cliquez sur le bouton ci-dessous pour confirmer votre adresse e-mail et finaliser votre inscription.
    </p>

    <table cellpadding="0" cellspacing="0" border="0" style="margin-bottom:28px;">
        <tr>
            <td style="background-color:#E8741C;border-radius:10px;padding:14px 28px;">
                <a href="{{ route('newsletter.confirm', $subscriber->confirmation_token) }}"
                   style="font-family:Arial,sans-serif;font-size:15px;font-weight:700;color:#FFFFFF;text-decoration:none;display:inline-block;">
                    Confirmer mon inscription
                </a>
            </td>
        </tr>
    </table>

    <p style="margin:0 0 8px;font-family:Arial,sans-serif;font-size:12px;line-height:1.5;color:rgba(10,10,15,0.4);">
        Si le bouton ne fonctionne pas, copiez ce lien dans votre navigateur :
    </p>
    <p style="margin:0 0 24px;font-family:'Courier New',monospace;font-size:11px;color:rgba(10,10,15,0.4);word-break:break-all;">
        {{ route('newsletter.confirm', $subscriber->confirmation_token) }}
    </p>

    <hr style="border:none;border-top:1px solid rgba(10,10,15,0.08);margin:24px 0;">

    <p style="margin:0;font-family:Arial,sans-serif;font-size:11px;line-height:1.5;color:rgba(10,10,15,0.35);">
        Vous recevez cet email parce que l'adresse <strong>{{ $subscriber->email }}</strong> a été utilisée pour s'inscrire sur hubimportexport.ci.
        Si vous n'êtes pas à l'origine de cette demande, vous pouvez ignorer cet email.
        Conformément à la <strong>Loi n°2013-450</strong> sur la protection des données à caractère personnel (ARTCI).
    </p>
</x-layouts.email>
