<?php

namespace App\Filament\Resources\ApplicationResource\Pages;

use App\Enums\ApplicationStatus;
use App\Filament\Resources\ApplicationResource;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewApplication extends ViewRecord
{
    protected static string $resource = ApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('change_status')
                ->label('Changer le statut')
                ->icon('heroicon-m-arrow-path')
                ->color('warning')
                ->modalHeading('Changer le statut')
                ->modalWidth('md')
                ->form([
                    Forms\Components\Select::make('status')
                        ->label('Nouveau statut')
                        ->options(
                            collect(ApplicationStatus::cases())
                                ->reject(fn($s) => $s === ApplicationStatus::Draft)
                                ->mapWithKeys(fn($s) => [$s->value => $s->label()])
                        )
                        ->required()
                        ->default(fn() => $this->record->status->value),

                    Forms\Components\Textarea::make('admin_notes')
                        ->label('Notes internes')
                        ->placeholder('Visible uniquement par les administrateurs')
                        ->rows(3)
                        ->default(fn() => $this->record->admin_notes),

                    Forms\Components\Textarea::make('rejection_reason')
                        ->label('Motif de refus')
                        ->placeholder('Communiqué au candidat si statut = Non retenu(e)')
                        ->rows(2)
                        ->visible(fn(Forms\Get $get) => $get('status') === ApplicationStatus::Rejected->value),
                ])
                ->action(function(array $data): void {
                    try {
                        app(\App\Services\ApplicationStatusService::class)->transition(
                            $this->record,
                            ApplicationStatus::from($data['status']),
                            $data['admin_notes'] ?? null,
                        );
                        if (! blank($data['rejection_reason'] ?? null)) {
                            $this->record->update(['rejection_reason' => $data['rejection_reason']]);
                        }
                        $this->refreshFormData(['status', 'admin_notes', 'rejection_reason']);
                        Notification::make()->title('Statut mis à jour')->success()->send();
                    } catch (\DomainException $e) {
                        Notification::make()->title('Transition impossible')->body($e->getMessage())->danger()->send();
                    }
                }),
        ];
    }
}
