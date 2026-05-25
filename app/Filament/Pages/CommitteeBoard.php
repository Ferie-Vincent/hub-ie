<?php

namespace App\Filament\Pages;

use App\Enums\ApplicationStatus;
use App\Models\Application;
use App\Services\ApplicationStatusService;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Database\Eloquent\Collection;

class CommitteeBoard extends Page
{
    protected static ?string $navigationIcon  = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationGroup = 'Candidatures';
    protected static ?int    $navigationSort  = 5;
    protected static ?string $title           = 'Tableau de délibération';
    protected static string  $view            = 'filament.pages.committee-board';

    public Collection $eligible;
    public Collection $underReview;
    public Collection $shortlisted;
    public array      $quotas = [];

    public function mount(): void
    {
        $this->refresh();
    }

    public function refresh(): void
    {
        $this->eligible    = Application::with('user')
            ->where('status', ApplicationStatus::Eligible->value)
            ->orderBy('submitted_at')
            ->get();

        $this->underReview = Application::with('user')
            ->where('status', ApplicationStatus::UnderReview->value)
            ->orderBy('average_score', 'desc')
            ->get();

        $this->shortlisted = Application::with('user')
            ->where('status', ApplicationStatus::Shortlisted->value)
            ->orderBy('average_score', 'desc')
            ->get();

        $accepted = Application::where('status', ApplicationStatus::Accepted->value)->count();
        $this->quotas = [
            'accepted'   => $accepted,
            'quota'      => 180,
            'remaining'  => max(0, 180 - $accepted),
            'shortlisted' => $this->shortlisted->count(),
        ];
    }

    public function transition(int $applicationId, string $to): void
    {
        $app     = Application::findOrFail($applicationId);
        $service = app(ApplicationStatusService::class);

        try {
            $service->transition($app, ApplicationStatus::from($to));
            $this->refresh();
            Notification::make()->title('Statut mis à jour')->success()->send();
        } catch (\DomainException $e) {
            Notification::make()->title($e->getMessage())->danger()->send();
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('refresh')
                ->label('Actualiser')
                ->icon('heroicon-m-arrow-path')
                ->action('refresh'),
        ];
    }
}
