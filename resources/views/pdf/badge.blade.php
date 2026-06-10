<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }
@import url('https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700&display=swap');
body {
    width: 100mm;
    height: 140mm;
    font-family: 'Manrope', Arial, sans-serif;
    background: #0a0a0a;
    color: #f5f0e8;
    overflow: hidden;
}
.badge {
    width: 100mm;
    height: 140mm;
    position: relative;
    padding: 6mm;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}
.header {
    display: flex;
    align-items: center;
    gap: 3mm;
    border-bottom: 0.3mm solid rgba(245,240,232,0.15);
    padding-bottom: 4mm;
    margin-bottom: 4mm;
}
.logo-text {
    font-size: 8pt;
    font-weight: 700;
    letter-spacing: 0.05em;
    color: #c07a30;
    text-transform: uppercase;
}
.event-tag {
    font-size: 6pt;
    color: rgba(245,240,232,0.5);
    margin-top: 0.5mm;
}
.group-badge {
    margin-left: auto;
    background: #c07a30;
    color: #0a0a0a;
    font-size: 11pt;
    font-weight: 700;
    width: 10mm;
    height: 10mm;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}
.name {
    font-size: 14pt;
    font-weight: 700;
    line-height: 1.2;
    margin-bottom: 1.5mm;
}
.title-line {
    font-size: 7.5pt;
    color: rgba(245,240,232,0.6);
    margin-bottom: 0.8mm;
}
.org-line {
    font-size: 7.5pt;
    color: #c07a30;
    font-weight: 600;
    margin-bottom: 4mm;
}
.qr-section {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
}
.qr-code svg {
    width: 26mm;
    height: 26mm;
    background: #ffffff;
    border-radius: 2mm;
    padding: 1mm;
}
.code-block {
    text-align: right;
}
.code-label {
    font-size: 6pt;
    color: rgba(245,240,232,0.4);
    text-transform: uppercase;
    letter-spacing: 0.12em;
    margin-bottom: 1mm;
}
.code-value {
    font-family: 'JetBrains Mono', 'Courier New', monospace;
    font-size: 18pt;
    font-weight: 700;
    letter-spacing: 0.08em;
    color: #f5f0e8;
}
.ref {
    font-size: 6pt;
    color: rgba(245,240,232,0.3);
    font-family: monospace;
    margin-top: 1mm;
}
.footer-line {
    font-size: 6pt;
    color: rgba(245,240,232,0.25);
    text-align: center;
    padding-top: 3mm;
    border-top: 0.3mm solid rgba(245,240,232,0.08);
    margin-top: 3mm;
}
</style>
</head>
<body>
<div class="badge">
    <div>
        <div class="header">
            <div>
                <img src="{{ asset('images/logo.svg') }}" alt="Hub Import-Export" style="height:8mm; width:auto; object-fit:contain; display:block;">
                <div class="event-tag">22–25 juin 2026 · Abidjan</div>
            </div>
            @if($application->group_label)
            <div class="group-badge">{{ $application->group_label }}</div>
            @endif
        </div>
        <div class="name">{{ $application->user->first_name }}<br>{{ strtoupper($application->user->last_name) }}</div>
        <div class="title-line">{{ $application->position }}</div>
        <div class="org-line">{{ $application->organization_name }}</div>
    </div>

    <div class="qr-section">
        <div class="qr-code">{!! $qrSvg !!}</div>
        <div class="code-block">
            <div class="code-label">Code d'accès</div>
            <div class="code-value">{{ $application->check_in_code }}</div>
            <div class="ref">{{ $application->reference_code }}</div>
        </div>
    </div>

    <div class="footer-line">
        Ministère du Commerce, de l'Industrie et de l'Artisanat — République de Côte d'Ivoire
    </div>
</div>
</body>
</html>
