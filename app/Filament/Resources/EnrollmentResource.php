<?php

namespace App\Filament\Resources;

use App\Enums\EnrollmentStatus;
use App\Filament\Resources\EnrollmentResource\Pages;
use App\Models\Enrollment;
use App\Models\Workshop;
use App\Services\EnrollmentService;
use App\Services\WaitlistService;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class EnrollmentResource extends Resource
{
    protected static ?string $model = Enrollment::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Inscriptions';

    protected static ?string $modelLabel = 'Inscription';

    protected static ?string $pluralModelLabel = 'Inscriptions';

    protected static ?string $navigationGroup = 'Participants';

    protected static ?int $navigationSort = 10;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.full_name')
                    ->label('Participant')
                    ->searchable(['first_name', 'last_name'])
                    ->sortable(),

                TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable()
                    ->copyable(),

                TextColumn::make('workshop.title')
                    ->label('Session')
                    ->sortable(),

                BadgeColumn::make('status')
                    ->label('Statut')
                    ->getStateUsing(fn (Enrollment $r) => $r->status->label())
                    ->color(fn (Enrollment $r) => $r->status->color()),

                BadgeColumn::make('badge_status')
                    ->label('Badge')
                    ->getStateUsing(fn (Enrollment $r) => $r->badge_status?->label() ?? '—')
                    ->color(fn (Enrollment $r) => $r->badge_status?->color() ?? 'gray'),

                TextColumn::make('waitlist_offered_at')
                    ->label('Offre envoyée')
                    ->dateTime('d/m/Y H:i')
                    ->placeholder('—')
                    ->description(fn (Enrollment $r) => $r->waitlist_offered_at
                        ? ($r->waitlist_offered_at->lt(now()->subHours(24)) ? '⚠️ Expirée' : '')
                        : null),

                TextColumn::make('enrolled_at')
                    ->label('Inscrit le')
                    ->dateTime('d/m/Y H:i')
                    ->placeholder('—')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Statut')
                    ->options([
                        'enrolled' => 'Inscrit',
                        'waitlisted' => 'En attente',
                        'cancelled' => 'Annulé',
                    ]),

                SelectFilter::make('workshop_id')
                    ->label('Session')
                    ->options(Workshop::pluck('title', 'id')),
            ])
            ->actions([
                // Promouvoir un waitlisté
                Action::make('promote')
                    ->label('Valider l\'inscription')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->iconButton()
                    ->visible(fn (Enrollment $r) => $r->status === EnrollmentStatus::Waitlisted)
                    ->requiresConfirmation()
                    ->action(function (Enrollment $enrollment) {
                        try {
                            app(WaitlistService::class)->promoteManually($enrollment, auth()->user());
                            Notification::make()->title('Inscription validée')->success()->send();
                        } catch (\Exception $e) {
                            Notification::make()->title($e->getMessage())->danger()->send();
                        }
                    }),

                // Déplacer vers une autre session
                Action::make('move')
                    ->label('Déplacer vers...')
                    ->icon('heroicon-o-arrow-right-circle')
                    ->color('warning')
                    ->iconButton()
                    ->visible(fn (Enrollment $r) => $r->status === EnrollmentStatus::Enrolled)
                    ->form([
                        Select::make('workshop_id')
                            ->label('Nouvelle session')
                            ->options(fn (Enrollment $r) => Workshop::where('id', '!=', $r->workshop_id)
                                ->pluck('title', 'id'))
                            ->required(),
                    ])
                    ->action(function (Enrollment $enrollment, array $data) {
                        try {
                            $target = Workshop::findOrFail($data['workshop_id']);
                            app(EnrollmentService::class)->moveToWorkshop($enrollment, $target, auth()->user());
                            Notification::make()->title('Participant déplacé')->success()->send();
                        } catch (\Exception $e) {
                            Notification::make()->title($e->getMessage())->danger()->send();
                        }
                    }),

                Tables\Actions\ViewAction::make()->iconButton(),
            ])
            ->bulkActions([])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEnrollments::route('/'),
        ];
    }
}
