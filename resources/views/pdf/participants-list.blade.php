<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 9px; color: #1A1208; margin: 0; padding: 0; }
    .page-header { background: #1A1208; color: #fff; padding: 14px 20px; margin-bottom: 16px; }
    .page-header h1 { font-size: 14px; margin: 0 0 2px; font-weight: bold; }
    .page-header p  { font-size: 8px; margin: 0; opacity: 0.6; }
    .meta { padding: 0 20px 10px; display: flex; justify-content: space-between; border-bottom: 1px solid #E8E0D2; margin-bottom: 12px; }
    .meta p { margin: 0; font-size: 8px; color: #6B6358; }
    table { width: 100%; border-collapse: collapse; margin: 0 20px; width: calc(100% - 40px); }
    thead tr { background: #F5F0E8; }
    th { padding: 6px 8px; text-align: left; font-size: 7.5px; text-transform: uppercase; letter-spacing: 0.08em; color: #6B6358; font-weight: bold; border-bottom: 1px solid #E8E0D2; }
    td { padding: 5px 8px; border-bottom: 1px solid #F5F0E8; vertical-align: top; }
    tr:nth-child(even) td { background: #FAFAF8; }
    .ref  { font-family: monospace; font-size: 8px; color: #6B6358; }
    .group { display: inline-block; padding: 2px 6px; border-radius: 10px; font-size: 7.5px; font-weight: bold;
             background: #FFF0E0; color: #B45309; }
    .footer { padding: 12px 20px 0; border-top: 1px solid #E8E0D2; margin-top: 12px; font-size: 7px; color: #9B9189; text-align: center; }
</style>
</head>
<body>

<div class="page-header">
    <h1>Hub Import-Export 2026 — Liste des participants retenus</h1>
    <p>Ministère du Commerce de Côte d'Ivoire — Direction Générale du Commerce Extérieur</p>
</div>

<div class="meta">
    <p>Générée le {{ now()->format('d/m/Y à H:i') }}</p>
    <p>{{ $applications->count() }} participant(s) retenu(s)</p>
</div>

<table>
    <thead>
        <tr>
            <th>Réf.</th>
            <th>Nom complet</th>
            <th>Organisation</th>
            <th>Ville</th>
            <th>Groupe</th>
            <th>Code accès</th>
            <th>Ateliers</th>
        </tr>
    </thead>
    <tbody>
        @foreach($applications as $app)
        @php
            $workshopLabels = [1 => 'ZLECAf', 2 => 'Financement', 3 => 'E-commerce', 4 => 'Conformité'];
            $workshops = collect($app->chosen_workshops ?? [])
                ->map(fn($id) => $workshopLabels[(int)$id] ?? "A{$id}")
                ->join(', ');
        @endphp
        <tr>
            <td class="ref">{{ $app->reference_code }}</td>
            <td><strong>{{ $app->user?->last_name }}</strong> {{ $app->user?->first_name }}</td>
            <td>{{ $app->organization_name }}</td>
            <td>{{ $app->user?->city }}</td>
            <td><span class="group">{{ $app->group_label ?? '—' }}</span></td>
            <td class="ref">{{ $app->check_in_code ?? '—' }}</td>
            <td>{{ $workshops ?: '—' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="footer">
    Document confidentiel — Usage interne — Hub Import-Export 2026
</div>

</body>
</html>
