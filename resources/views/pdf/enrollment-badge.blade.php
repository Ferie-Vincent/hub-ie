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
    background: #0A1628;
    color: #f0f4f0;
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
    background: #0A1628;
}
/* Accent stripe at top */
.top-stripe {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 1.5mm;
    background: hsl(145, 70%, 42%);
    border-radius: 0;
}
/* Header */
.header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    border-bottom: 0.3mm solid rgba(240,244,240,0.12);
    padding-bottom: 4mm;
    margin-bottom: 4mm;
    padding-top: 2mm;
}
.header-left {
    display: flex;
    flex-direction: column;
    gap: 1mm;
}
.logo-text {
    font-size: 8.5pt;
    font-weight: 700;
    letter-spacing: 0.06em;
    color: hsl(145, 70%, 42%);
    text-transform: uppercase;
}
.logo-sub {
    font-size: 5.5pt;
    color: rgba(240,244,240,0.45);
    letter-spacing: 0.04em;
}
.session-pill {
    background: hsl(145, 70%, 42%);
    color: #0A1628;
    font-size: 6pt;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    padding: 0.8mm 2mm;
    border-radius: 1mm;
    align-self: flex-start;
    white-space: nowrap;
}
/* Participant info */
.participant-section {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
}
.name {
    font-size: 13.5pt;
    font-weight: 700;
    line-height: 1.2;
    color: #f0f4f0;
    margin-bottom: 2mm;
    word-break: break-word;
}
.divider {
    width: 12mm;
    height: 0.5mm;
    background: hsl(145, 70%, 42%);
    margin-bottom: 3mm;
    border-radius: 0.5mm;
}
/* Workshop block */
.workshop-block {
    background: rgba(255,255,255,0.04);
    border: 0.3mm solid rgba(240,244,240,0.1);
    border-left: 0.8mm solid hsl(145, 70%, 42%);
    border-radius: 1.5mm;
    padding: 2.5mm 3mm;
    margin-bottom: 4mm;
}
.workshop-label {
    font-size: 5.5pt;
    color: hsl(145, 70%, 42%);
    text-transform: uppercase;
    letter-spacing: 0.1em;
    font-weight: 600;
    margin-bottom: 1mm;
}
.workshop-title {
    font-size: 7.5pt;
    font-weight: 600;
    color: #f0f4f0;
    line-height: 1.3;
}
/* QR + code section */
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
    display: block;
}
.code-block {
    text-align: right;
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 1mm;
}
.code-label {
    font-size: 5.5pt;
    color: rgba(240,244,240,0.4);
    text-transform: uppercase;
    letter-spacing: 0.12em;
}
.code-value {
    font-family: 'Courier New', monospace;
    font-size: 20pt;
    font-weight: 700;
    letter-spacing: 0.1em;
    color: hsl(145, 70%, 55%);
    line-height: 1;
}
.code-hint {
    font-size: 5.5pt;
    color: rgba(240,244,240,0.3);
    font-family: 'Courier New', monospace;
    letter-spacing: 0.06em;
}
/* Footer */
.footer-line {
    font-size: 5.5pt;
    color: rgba(240,244,240,0.22);
    text-align: center;
    padding-top: 3mm;
    border-top: 0.3mm solid rgba(240,244,240,0.08);
    margin-top: 3mm;
    line-height: 1.4;
}
</style>
</head>
<body>
<div class="badge">
    <div class="top-stripe"></div>

    <div class="header">
        <div class="header-left">
            <div class="logo-text">Hub Import-Export 2026</div>
            <div class="logo-sub">22 – 25 Juin 2026 · Abidjan, Côte d'Ivoire</div>
        </div>
        <div class="session-pill">Session</div>
    </div>

    <div class="participant-section">
        <div class="name">{{ $user->full_name }}</div>
        <div class="divider"></div>

        <div class="workshop-block">
            <div class="workshop-label">Atelier inscrit</div>
            <div class="workshop-title">{{ $workshop->title }}</div>
        </div>
    </div>

    <div>
        <div class="qr-section">
            <div class="qr-code">{!! $qrSvg !!}</div>
            <div class="code-block">
                <div class="code-label">Code d'entrée</div>
                <div class="code-value">{{ $enrollment->check_in_code }}</div>
                <div class="code-hint">Scanner ou saisir ce code</div>
            </div>
        </div>

        <div class="footer-line">
            Ministère du Commerce, de l'Industrie et de l'Artisanat — République de Côte d'Ivoire
        </div>
    </div>
</div>
</body>
</html>
