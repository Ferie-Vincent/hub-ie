@props(['title' => 'Hub Import-Export 2026', 'preheader' => ''])
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ $title }}</title>
    <!--[if mso]>
    <noscript>
        <xml>
            <o:OfficeDocumentSettings>
                <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
        </xml>
    </noscript>
    <![endif]-->
    <style>
        body { margin: 0; padding: 0; font-family: 'Inter', Arial, sans-serif; background-color: #F5F0E8; color: #0A0A0F; }
        a { color: #E8741C; }
        .wrapper { max-width: 600px; margin: 0 auto; }
    </style>
</head>
<body>

@if($preheader)
<div style="display:none;max-height:0;overflow:hidden;mso-hide:all;">
    {{ $preheader }}
</div>
@endif

<table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#F5F0E8;">
    <tr>
        <td align="center" style="padding: 32px 16px;">
            <div class="wrapper" style="max-width:600px;margin:0 auto;">

                {{-- Header institutionnel --}}
                <table width="100%" cellpadding="0" cellspacing="0" border="0"
                       style="background-color:#0A0A0F; border-radius:16px 16px 0 0; overflow:hidden;">
                    <tr>
                        <td style="padding: 24px 32px;">
                            <table cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td>
                                        <div style="display:inline-block;width:40px;height:40px;background:#E8741C;border-radius:10px;text-align:center;line-height:40px;font-family:Inter,Arial,sans-serif;font-size:13px;font-weight:700;color:white;letter-spacing:-0.5px;">HIE</div>
                                    </td>
                                    <td style="padding-left: 12px;">
                                        <p style="margin:0;font-family:Arial,sans-serif;font-size:13px;font-weight:700;color:#FFFFFF;">Hub Import-Export 2026</p>
                                        <p style="margin:2px 0 0;font-family:Arial,sans-serif;font-size:11px;color:rgba(255,255,255,0.5);">22–25 juin · Abidjan</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    {{-- Filet orange --}}
                    <tr>
                        <td style="height:3px;background:linear-gradient(to right, #E8741C, rgba(255,255,255,0.3) 50%, #009A44);"></td>
                    </tr>
                </table>

                {{-- Corps --}}
                <table width="100%" cellpadding="0" cellspacing="0" border="0"
                       style="background-color:#FFFFFF; border-radius:0 0 16px 16px;">
                    <tr>
                        <td style="padding: 40px 32px;">
                            {{ $slot }}
                        </td>
                    </tr>
                </table>

                {{-- Footer institutionnel --}}
                <table width="100%" cellpadding="0" cellspacing="0" border="0"
                       style="margin-top:24px;">
                    <tr>
                        <td style="padding: 16px 0; text-align:center;">
                            <p style="margin:0;font-family:Arial,sans-serif;font-size:10px;color:rgba(10,10,15,0.4);">
                                Direction Générale du Commerce Extérieur (DGCE) — Ministère du Commerce, de l'Industrie et de l'Artisanat<br>
                                Plateau, Abidjan — République de Côte d'Ivoire<br>
                                <a href="{{ config('app.url') }}" style="color:#E8741C;text-decoration:none;">hubimportexport.ci</a>
                            </p>
                        </td>
                    </tr>
                </table>

            </div>
        </td>
    </tr>
</table>

</body>
</html>
