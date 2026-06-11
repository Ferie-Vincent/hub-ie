<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EditionResource\Pages;
use App\Jobs\SendNewEditionAnnouncement;
use App\Models\Edition;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class EditionResource extends Resource
{
    protected static ?string $model = Edition::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationGroup = 'Administration';

    protected static ?string $navigationLabel = 'Éditions';

    protected static ?int $navigationSort = 61;

    protected static ?string $modelLabel = 'Édition';

    protected static ?string $pluralModelLabel = 'Éditions';

    // ── Form ─────────────────────────────────────────────────────────────────

    public static function form(Form $form): Form
    {
        return $form->schema([

            Forms\Components\Section::make('Identité de l\'édition')
                ->schema([
                    Forms\Components\TextInput::make('year')
                        ->label('Année')
                        ->required()
                        ->numeric()
                        ->minValue(2024)
                        ->maxValue(2040)
                        ->columnSpan(1),

                    Forms\Components\TextInput::make('title')
                        ->label('Titre officiel')
                        ->required()
                        ->placeholder('Hub Import-Export 2026')
                        ->maxLength(255)
                        ->columnSpan(2),

                    Forms\Components\TextInput::make('theme')
                        ->label('Thème')
                        ->nullable()
                        ->maxLength(255)
                        ->columnSpan(3),

                    Forms\Components\TextInput::make('location')
                        ->label('Lieu')
                        ->default('Abidjan, Côte d\'Ivoire')
                        ->maxLength(255)
                        ->columnSpan(3),
                ])
                ->columns(3),

            Forms\Components\Section::make('Calendrier')
                ->schema([
                    Forms\Components\DateTimePicker::make('application_opens_at')
                        ->label('Ouverture candidatures')
                        ->nullable(),

                    Forms\Components\DateTimePicker::make('application_closes_at')
                        ->label('Clôture candidatures')
                        ->nullable(),

                    Forms\Components\DateTimePicker::make('event_starts_at')
                        ->label('Début événement')
                        ->nullable(),

                    Forms\Components\DateTimePicker::make('event_ends_at')
                        ->label('Fin événement')
                        ->nullable(),
                ])
                ->columns(2),

            Forms\Components\Section::make('Quotas et capacité')
                ->schema([
                    Forms\Components\TextInput::make('max_participants')
                        ->label('Nombre max participants')
                        ->numeric()
                        ->minValue(1)
                        ->nullable(),

                    Forms\Components\TextInput::make('quota_women_min_pct')
                        ->label('Quota femmes min')
                        ->numeric()
                        ->minValue(0)
                        ->maxValue(100)
                        ->suffix('%')
                        ->nullable(),

                    Forms\Components\TextInput::make('quota_youth_min_pct')
                        ->label('Quota jeunes min')
                        ->numeric()
                        ->minValue(0)
                        ->maxValue(100)
                        ->suffix('%')
                        ->nullable(),

                    Forms\Components\TextInput::make('quota_youth_max_age')
                        ->label('Âge max jeune (ans)')
                        ->numeric()
                        ->minValue(1)
                        ->nullable(),
                ])
                ->columns(2),

            Forms\Components\Section::make('Statut')
                ->schema([
                    Forms\Components\Toggle::make('registration_open')
                        ->label('Inscriptions ouvertes')
                        ->default(false),

                    Forms\Components\Placeholder::make('is_active')
                        ->label('Édition active')
                        ->content(fn (?Edition $record): string => match (true) {
                            $record === null => '—',
                            $record->is_active => 'Oui — édition courante',
                            default => 'Non — archivée',
                        })
                        ->helperText('Géré via l\'action "Activer" dans la liste.'),

                    Forms\Components\Placeholder::make('launched_at')
                        ->label('Date de lancement annonce')
                        ->content(fn (?Edition $record): string => $record?->launched_at
                            ? $record->launched_at->translatedFormat('d F Y à H:i')
                            : 'Annonce non encore envoyée'),
                ])
                ->columns(1),
        ]);
    }

    // ── Table ────────────────────────────────────────────────────────────────

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->withCount('applications'))
            ->columns([
                Tables\Columns\TextColumn::make('year')
                    ->label('Année')
                    ->sortable()
                    ->weight(FontWeight::Bold),

                Tables\Columns\TextColumn::make('title')
                    ->label('Titre')
                    ->searchable()
                    ->limit(40),

                Tables\Columns\TextColumn::make('theme')
                    ->label('Thème')
                    ->limit(35)
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('event_starts_at')
                    ->label('Début événement')
                    ->date('d/m/Y')
                    ->sortable()
                    ->placeholder('—'),

                Tables\Columns\BadgeColumn::make('is_active')
                    ->label('Statut')
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Actuelle' : 'Archivée')
                    ->colors([
                        'success' => true,
                        'gray' => false,
                    ]),

                Tables\Columns\IconColumn::make('registration_open')
                    ->label('Inscriptions')
                    ->boolean()
                    ->trueIcon('heroicon-o-lock-open')
                    ->falseIcon('heroicon-o-lock-closed')
                    ->trueColor('success')
                    ->falseColor('gray'),

                Tables\Columns\TextColumn::make('applications_count')
                    ->label('Candidatures')
                    ->sortable()
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('launched_at')
                    ->label('Annonce envoyée')
                    ->date('d/m/Y')
                    ->placeholder('—')
                    ->sortable(),
            ])
            ->defaultSort('year', 'desc')
            ->actions([
                Tables\Actions\EditAction::make(),

                Tables\Actions\Action::make('activate')
                    ->label('Activer')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Activer cette édition ?')
                    ->modalDescription('Les autres éditions seront archivées. Les candidatures restent visibles par édition.')
                    ->action(function (Edition $record): void {
                        $record->activate();

                        Notification::make()
                            ->title('Édition activée')
                            ->body("L'édition {$record->year} est maintenant l'édition courante.")
                            ->success()
                            ->send();
                    })
                    ->visible(fn (Edition $record): bool => ! $record->is_active),

                Tables\Actions\Action::make('launch_announcement')
                    ->label('Lancer l\'annonce')
                    ->icon('heroicon-o-megaphone')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Envoyer l\'email à tous les anciens participants ?')
                    ->modalDescription('Un email sera envoyé à tous les utilisateurs ayant participé aux éditions précédentes.')
                    ->action(function (Edition $record): void {
                        SendNewEditionAnnouncement::dispatch($record);

                        $record->update(['launched_at' => now()]);

                        Notification::make()
                            ->title('Annonce en cours d\'envoi...')
                            ->body("L'email de lancement de l'édition {$record->year} est en file d'attente.")
                            ->warning()
                            ->send();
                    })
                    ->visible(fn (Edition $record): bool => $record->is_active && ! $record->hasBeenLaunched()),

                Tables\Actions\DeleteAction::make()
                    ->visible(fn (Edition $record): bool => $record->applications_count === 0),
            ])
            ->bulkActions([]);
    }

    // ── Pages ────────────────────────────────────────────────────────────────

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEditions::route('/'),
            'create' => Pages\CreateEdition::route('/create'),
            'edit' => Pages\EditEdition::route('/{record}/edit'),
        ];
    }
}
