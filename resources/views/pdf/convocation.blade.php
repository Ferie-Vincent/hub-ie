<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: Arial, Helvetica, sans-serif; font-size: 11pt; color: #1a1a1a; line-height: 1.55; }
.page { width: 210mm; min-height: 297mm; padding: 20mm 22mm; }
.header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10mm; }
.header-left { font-size: 8pt; color: #555; line-height: 1.6; }
.header-right { font-size: 8pt; color: #555; text-align: right; }
.logo-block { font-size: 13pt; font-weight: 700; color: #0a0a0a; letter-spacing: -0.01em; }
.subject-line { font-size: 9pt; color: #c07a30; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; margin: 8mm 0 4mm; }
h1 { font-size: 15pt; font-weight: 700; margin-bottom: 6mm; border-bottom: 0.3mm solid #c07a30; padding-bottom: 3mm; }
p { margin-bottom: 4mm; }
.recipient { background: #f5f0e8; border-left: 3mm solid #c07a30; padding: 4mm 6mm; margin: 6mm 0; font-weight: 600; }
.programme { margin: 6mm 0; border: 0.3mm solid #ddd; border-radius: 2mm; overflow: hidden; }
.prog-header { background: #0a0a0a; color: #f5f0e8; padding: 2.5mm 5mm; font-size: 9pt; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; }
.prog-row { padding: 2mm 5mm; border-bottom: 0.2mm solid #eee; display: flex; gap: 8mm; }
.prog-row:last-child { border-bottom: none; }
.prog-day { font-weight: 700; color: #c07a30; width: 22mm; flex-shrink: 0; font-size: 9pt; }
.prog-desc { font-size: 9pt; }
.code-block { background: #0a0a0a; color: #f5f0e8; border-radius: 2mm; padding: 4mm 6mm; margin: 6mm 0; display: flex; justify-content: space-between; align-items: center; }
.code-label { font-size: 8pt; color: rgba(245,240,232,0.5); text-transform: uppercase; letter-spacing: 0.1em; }
.code-value { font-family: monospace; font-size: 20pt; font-weight: 700; letter-spacing: 0.15em; }
.signature-block { margin-top: 10mm; }
.signature-name { font-weight: 700; margin-top: 1mm; }
.footer { margin-top: 10mm; padding-top: 4mm; border-top: 0.3mm solid #eee; font-size: 8pt; color: #888; text-align: center; }
</style>
</head>
<body>
<div class="page">
    <div class="header">
        <div class="header-left">
            <img src="{{ asset('images/logo.svg') }}" alt="Hub Import-Export 2026" style="height:10mm; width:auto; object-fit:contain; display:block; margin-bottom:2mm;">
            <div>DGCE — Ministère du Commerce, de l'Industrie et de l'Artisanat</div>
            <div>Abidjan, Côte d'Ivoire</div>
        </div>
        <div class="header-right">
            Abidjan, le {{ now()->locale('fr')->isoFormat('D MMMM YYYY') }}<br>
            Réf. : {{ $application->reference_code }}
        </div>
    </div>

    <div class="subject-line">Convocation officielle</div>
    <h1>Hub Import-Export 2026 — Lettre de convocation</h1>

    <p>Madame, Monsieur,</p>

    <p>Nous avons le plaisir de vous informer que votre candidature au programme <strong>Hub Import-Export 2026</strong>, organisé par la Direction Générale du Commerce Extérieur (DGCE) sous l'égide du Ministère du Commerce, de l'Industrie et de l'Artisanat de la République de Côte d'Ivoire, a été <strong>retenue</strong>.</p>

    <div class="recipient">
        {{ $application->user->first_name }} {{ strtoupper($application->user->last_name) }}<br>
        {{ $application->position }} — {{ $application->organization_name }}<br>
        Groupe : <strong>{{ $application->group_label ?? 'À préciser' }}</strong>
    </div>

    <p>Vous êtes convoqué(e) à participer aux ateliers thématiques qui se tiendront à Abidjan du <strong>22 au 25 juin 2026</strong>. Votre présence est obligatoire à l'ensemble des sessions prévues pour votre groupe.</p>

    <div class="programme">
        <div class="prog-header">Programme (extrait — votre groupe : {{ $application->group_label ?? '—' }})</div>
        <div class="prog-row"><span class="prog-day">22 juin</span><span class="prog-desc">Ouverture officielle, discours du Ministre, présentation du programme ZLECAf</span></div>
        <div class="prog-row"><span class="prog-day">23 juin</span><span class="prog-desc">Atelier Financement des exportations — outils BEI, ITFC, Afreximbank</span></div>
        <div class="prog-row"><span class="prog-day">24 juin</span><span class="prog-desc">Atelier E-commerce et conformité internationale — normes SPS/TBT</span></div>
        <div class="prog-row"><span class="prog-day">25 juin</span><span class="prog-desc">Mise en réseau, table ronde, clôture et remise des attestations</span></div>
    </div>

    <p><strong>Lieu :</strong> Centre de Conférence d'Abidjan (adresse et plan transmis par email séparé).</p>

    <div class="code-block">
        <div>
            <div class="code-label">Code d'accès journalier</div>
            <div class="code-value">{{ $application->check_in_code }}</div>
        </div>
        <div style="font-size:8pt; color:rgba(245,240,232,0.6); max-width: 70mm; text-align:right;">
            À présenter à l'accueil ou à saisir sur la borne de pointage. Votre badge nominatif vous sera remis à l'entrée le 22 juin.
        </div>
    </div>

    <p>Pour toute question, écrivez à <strong>dgce-hub@commerce.gouv.ci</strong> en indiquant votre numéro de référence.</p>

    <p>Nous vous souhaitons des échanges fructueux lors de ces ateliers.</p>

    <div class="signature-block">
        <p>Veuillez agréer, Madame, Monsieur, l'expression de nos salutations distinguées.</p>
        <br>
        <div class="signature-name">Pour le Directeur Général du Commerce Extérieur</div>
        <div style="font-size:9pt; color:#555;">Direction Générale du Commerce Extérieur (DGCE)</div>
    </div>

    <div class="footer">
        Hub Import-Export 2026 — hubimportexport.ci — dgce-hub@commerce.gouv.ci<br>
        République de Côte d'Ivoire — Ministère du Commerce, de l'Industrie et de l'Artisanat
    </div>
</div>
</body>
</html>
