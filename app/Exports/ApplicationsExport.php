<?php

namespace App\Exports;

use App\Enums\ApplicationStatus;
use App\Models\Application;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ApplicationsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    public function __construct(private array $statuses = []) {}

    public function query()
    {
        $query = Application::query()
            ->with('user')
            ->whereNotIn('status', [ApplicationStatus::Draft->value, ApplicationStatus::Withdrawn->value])
            ->orderBy('submitted_at', 'desc');

        if ($this->statuses) {
            $query->whereIn('status', array_map(fn($s) => $s->value, $this->statuses));
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'Référence', 'Prénom', 'Nom', 'Email', 'Genre',
            'Date de naissance', 'Ville', 'Pays', 'Nationalité',
            'Profil', 'Organisation', 'Poste', 'Expérience (ans)',
            'Statut', 'Score', 'Nb évaluations',
            'Groupe', 'Soumis le', 'Accepté le',
        ];
    }

    public function map($app): array
    {
        return [
            $app->reference_code,
            $app->user->first_name,
            $app->user->last_name,
            $app->user->email,
            $app->user->gender?->label(),
            $app->user->birth_date?->format('d/m/Y'),
            $app->user->city,
            $app->user->country,
            $app->user->nationality,
            $app->category?->label(),
            $app->organization_name,
            $app->position,
            $app->experience_years,
            $app->status->label(),
            $app->average_score,
            $app->evaluations_count,
            $app->group_label,
            $app->submitted_at?->format('d/m/Y H:i'),
            $app->accepted_at?->format('d/m/Y H:i'),
        ];
    }
}
