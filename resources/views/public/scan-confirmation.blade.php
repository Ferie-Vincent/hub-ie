<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Pointage — Hub Import-Export 2026</title>
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }
body { background: #0a0a0a; color: #f5f0e8; font-family: 'Helvetica Neue', Arial, sans-serif; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 2rem; }
.card { background: #141414; border: 1px solid rgba(245,240,232,0.08); border-radius: 1.5rem; padding: 3rem 2.5rem; max-width: 420px; width: 100%; text-align: center; }
.status-icon { font-size: 4rem; margin-bottom: 1.5rem; }
.status-title { font-size: 1.75rem; font-weight: 700; margin-bottom: 0.5rem; }
.status-sub { font-size: 0.9rem; color: rgba(245,240,232,0.5); margin-bottom: 2rem; }
.name { font-size: 1.25rem; font-weight: 700; margin-bottom: 0.25rem; }
.org { font-size: 0.85rem; color: rgba(245,240,232,0.6); }
.group-badge { display: inline-block; background: #c07a30; color: #0a0a0a; font-size: 0.75rem; font-weight: 700; padding: 0.3rem 0.9rem; border-radius: 99px; margin-top: 1rem; letter-spacing: 0.05em; }
.ref { font-family: monospace; font-size: 0.75rem; color: rgba(245,240,232,0.3); margin-top: 0.5rem; }
.warning { color: #f59e0b; }
.success { color: #22c55e; }
.footer { margin-top: 2.5rem; font-size: 0.75rem; color: rgba(245,240,232,0.2); }
</style>
</head>
<body>
<div class="card">
    @if($alreadyCheckedIn)
    <div class="status-icon warning">⚠</div>
    <div class="status-title warning">Déjà pointé</div>
    <div class="status-sub">Ce badge a déjà été scanné aujourd'hui.</div>
    @else
    <div class="status-icon success">✓</div>
    <div class="status-title success">Bienvenue !</div>
    <div class="status-sub">Votre présence est enregistrée.</div>
    @endif

    <div class="name">{{ $application->user->first_name }} {{ strtoupper($application->user->last_name) }}</div>
    <div class="org">{{ $application->organization_name }}</div>
    <div class="group-badge">{{ $application->group_label }}</div>
    <div class="ref">{{ $application->reference_code }}</div>

    <div class="footer">Hub Import-Export 2026 — Abidjan, 22–25 juin 2026</div>
</div>
</body>
</html>
