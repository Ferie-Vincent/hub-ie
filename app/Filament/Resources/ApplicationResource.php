<?php

namespace App\Filament\Resources;

use App\Enums\ApplicationCategory;
use App\Enums\ApplicationStatus;
use App\Enums\Gender;
use App\Filament\Resources\ApplicationResource\Pages;
use App\Models\Application;
use Filament\Forms;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontFamily;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class ApplicationResource extends Resource
{
    protected static ?string $model                = Application::class;
    protected static ?string $navigationIcon       = 'heroicon-o-users';
    protected static ?string $navigationGroup      = 'Candidatures';
    protected static ?int    $navigationSort       = 1;
    protected static ?string $navigationLabel      = 'Liste des inscrits';
    protected static ?string $modelLabel           = 'Candidature';
    protected static ?string $pluralModelLabel     = 'Candidatures';
    protected static ?string $recordTitleAttribute = 'reference_code';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with('user')
            ->whereNotIn('status', [ApplicationStatus::Draft->value]);
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::whereNotIn('status', [
            ApplicationStatus::Draft->value,
            ApplicationStatus::Withdrawn->value,
        ])->count();
    }

    /* ─────────────────────────────────────────────────────────── */
    /*  TABLE                                                       */
    /* ─────────────────────────────────────────────────────────── */

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('reference_code')
                    ->label('Réf.')
                    ->searchable()
                    ->copyable()
                    ->badge()
                    ->color('gray')
                    ->fontFamily(FontFamily::Mono),

                Tables\Columns\TextColumn::make('user.full_name')
                    ->label('Candidat(e)')
                    ->searchable(query: fn(Builder $query, string $search) => $query->whereHas(
                        'user',
                        fn($q) => $q->where('first_name', 'like', "%{$search}%")
                                    ->orWhere('last_name',  'like', "%{$search}%")
                                    ->orWhere('email',      'like', "%{$search}%")
                    ))
                    ->description(fn(Application $record): string => $record->user?->email ?? '')
                    ->weight(FontWeight::Medium),

                Tables\Columns\TextColumn::make('user.city')
                    ->label('Ville')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('category')
                    ->label('Profil')
                    ->badge()
                    ->formatStateUsing(fn($state) => $state?->label())
                    ->color(fn($state) => $state?->color())
                    ->toggleable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Statut')
                    ->badge()
                    ->formatStateUsing(fn($state) => $state?->label())
                    ->color(fn($state) => $state?->color())
                    ->sortable(),

                Tables\Columns\TextColumn::make('average_score')
                    ->label('Score')
                    ->numeric(decimalPlaces: 1)
                    ->placeholder('—')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('submitted_at')
                    ->label('Soumis le')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Statut')
                    ->options(
                        collect(ApplicationStatus::cases())
                            ->reject(fn($s) => $s === ApplicationStatus::Draft)
                            ->mapWithKeys(fn($s) => [$s->value => $s->label()])
                    )
                    ->multiple()
                    ->preload(),

                Tables\Filters\SelectFilter::make('category')
                    ->label('Profil professionnel')
                    ->options(
                        collect(ApplicationCategory::cases())
                            ->mapWithKeys(fn($c) => [$c->value => $c->label()])
                    )
                    ->multiple()
                    ->preload(),

                Tables\Filters\SelectFilter::make('gender')
                    ->label('Genre')
                    ->options(
                        collect(Gender::cases())
                            ->mapWithKeys(fn($g) => [$g->value => $g->label()])
                    )
                    ->query(fn(Builder $query, array $data) => blank($data['value'])
                        ? $query
                        : $query->whereHas('user', fn($q) => $q->where('gender', $data['value']))
                    ),

                Tables\Filters\Filter::make('women_only')
                    ->label('Femmes uniquement')
                    ->query(fn(Builder $query) => $query->whereHas('user', fn($q) => $q->where('gender', 'F'))),

                Tables\Filters\Filter::make('under_35')
                    ->label('< 35 ans uniquement')
                    ->query(fn(Builder $query) => $query->whereHas(
                        'user',
                        fn($q) => $q->whereNotNull('birth_date')
                                    ->whereDate('birth_date', '>=', now()->subYears(35)->toDateString())
                    )),

                Tables\Filters\Filter::make('submitted')
                    ->label('Dossier soumis uniquement')
                    ->query(fn(Builder $query) => $query->whereNotNull('submitted_at'))
                    ->default(),
            ])
            ->filtersFormColumns(3)
            ->defaultSort('submitted_at', 'desc')
            ->striped()
            ->persistSortInSession()
            ->persistFiltersInSession()
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('change_status')
                    ->label('Statut')
                    ->icon('heroicon-m-arrow-path')
                    ->color('gray')
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
                            ->default(fn(Application $record) => $record->status->value),
                        Forms\Components\Textarea::make('admin_notes')
                            ->label('Notes internes')
                            ->placeholder('Visible uniquement par les administrateurs')
                            ->rows(2)
                            ->default(fn(Application $record) => $record->admin_notes),
                    ])
                    ->action(function(Application $record, array $data): void {
                        $record->update([
                            'status'      => $data['status'],
                            'admin_notes' => $data['admin_notes'] ?? $record->admin_notes,
                        ]);
                    })
                    ->successNotificationTitle('Statut mis à jour'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('bulk_change_status')
                        ->label('Changer le statut')
                        ->icon('heroicon-m-arrow-path')
                        ->modalHeading('Changer le statut des candidatures sélectionnées')
                        ->form([
                            Forms\Components\Select::make('status')
                                ->label('Nouveau statut')
                                ->options(
                                    collect(ApplicationStatus::cases())
                                        ->reject(fn($s) => $s === ApplicationStatus::Draft)
                                        ->mapWithKeys(fn($s) => [$s->value => $s->label()])
                                )
                                ->required(),
                        ])
                        ->action(fn(Collection $records, array $data) =>
                            $records->each->update(['status' => $data['status']])
                        )
                        ->deselectRecordsAfterCompletion()
                        ->successNotificationTitle('Statuts mis à jour'),
                ]),
            ]);
    }

    /* ─────────────────────────────────────────────────────────── */
    /*  INFOLIST (View page)                                        */
    /* ─────────────────────────────────────────────────────────── */

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([

            Infolists\Components\Section::make('Dossier')
                ->icon('heroicon-m-identification')
                ->columns(3)
                ->schema([
                    Infolists\Components\TextEntry::make('reference_code')
                        ->label('Référence')
                        ->badge()
                        ->color('gray')
                        ->fontFamily(FontFamily::Mono),
                    Infolists\Components\TextEntry::make('status')
                        ->label('Statut actuel')
                        ->badge()
                        ->formatStateUsing(fn($state) => $state?->label())
                        ->color(fn($state) => $state?->color()),
                    Infolists\Components\TextEntry::make('submitted_at')
                        ->label('Soumis le')
                        ->dateTime('d/m/Y à H:i')
                        ->placeholder('Non soumis'),
                ]),

            Infolists\Components\Section::make('Identité')
                ->icon('heroicon-m-user')
                ->columns(3)
                ->schema([
                    Infolists\Components\TextEntry::make('user.first_name')
                        ->label('Prénom'),
                    Infolists\Components\TextEntry::make('user.last_name')
                        ->label('Nom'),
                    Infolists\Components\TextEntry::make('user.gender')
                        ->label('Genre')
                        ->badge()
                        ->formatStateUsing(fn($state) => $state?->label())
                        ->color(fn($state) => $state?->color()),
                    Infolists\Components\TextEntry::make('user.email')
                        ->label('Email')
                        ->copyable()
                        ->icon('heroicon-m-envelope'),
                    Infolists\Components\TextEntry::make('user.phone')
                        ->label('Téléphone')
                        ->copyable()
                        ->placeholder('—'),
                    Infolists\Components\TextEntry::make('user.birth_date')
                        ->label('Date de naissance')
                        ->date('d/m/Y')
                        ->placeholder('—'),
                    Infolists\Components\TextEntry::make('user.city')
                        ->label('Ville')
                        ->placeholder('—'),
                    Infolists\Components\TextEntry::make('user.country')
                        ->label('Pays')
                        ->placeholder('—'),
                    Infolists\Components\TextEntry::make('user.nationality')
                        ->label('Nationalité')
                        ->placeholder('—'),
                ]),

            Infolists\Components\Section::make('Profil professionnel')
                ->icon('heroicon-m-briefcase')
                ->columns(2)
                ->schema([
                    Infolists\Components\TextEntry::make('category')
                        ->label('Catégorie')
                        ->badge()
                        ->formatStateUsing(fn($state) => $state?->label())
                        ->color(fn($state) => $state?->color()),
                    Infolists\Components\TextEntry::make('organization_name')
                        ->label('Organisation')
                        ->placeholder('—'),
                    Infolists\Components\TextEntry::make('position')
                        ->label('Fonction / Poste')
                        ->placeholder('—'),
                    Infolists\Components\TextEntry::make('sector')
                        ->label('Secteur d\'activité')
                        ->placeholder('—'),
                    Infolists\Components\TextEntry::make('experience_years')
                        ->label("Années d'expérience")
                        ->suffix(' ans')
                        ->placeholder('—'),
                    Infolists\Components\TextEntry::make('rccm_number')
                        ->label('N° RCCM')
                        ->placeholder('—'),
                    Infolists\Components\TextEntry::make('professional_email')
                        ->label('Email professionnel')
                        ->copyable()
                        ->placeholder('—'),
                    Infolists\Components\TextEntry::make('professional_phone')
                        ->label('Téléphone professionnel')
                        ->placeholder('—'),
                ]),

            Infolists\Components\Section::make('Motivation & Ateliers')
                ->icon('heroicon-m-document-text')
                ->columns(2)
                ->schema([
                    Infolists\Components\TextEntry::make('motivation')
                        ->label('Lettre de motivation')
                        ->prose()
                        ->columnSpanFull(),
                    Infolists\Components\TextEntry::make('chosen_workshops')
                        ->label('Ateliers choisis')
                        ->formatStateUsing(function($state): string {
                            $labels = [
                                1 => 'ZLECAf & CEDEAO',
                                2 => 'Financement',
                                3 => 'E-commerce',
                                4 => 'Conformité',
                            ];
                            return collect((array) $state)
                                ->map(fn($i) => $labels[(int) $i] ?? "Atelier {$i}")
                                ->join(', ');
                        })
                        ->placeholder('—'),
                    Infolists\Components\TextEntry::make('referral_source')
                        ->label('Source de connaissance')
                        ->placeholder('—'),
                    Infolists\Components\TextEntry::make('expectations')
                        ->label('Attentes et objectifs')
                        ->prose()
                        ->columnSpanFull()
                        ->placeholder('—'),
                    Infolists\Components\IconEntry::make('is_first_participation')
                        ->label('Première participation')
                        ->boolean(),
                ]),

            Infolists\Components\Section::make('Évaluation & Décision')
                ->icon('heroicon-m-star')
                ->columns(2)
                ->schema([
                    Infolists\Components\TextEntry::make('average_score')
                        ->label('Score moyen')
                        ->numeric(decimalPlaces: 2)
                        ->placeholder('Non évalué'),
                    Infolists\Components\TextEntry::make('evaluations_count')
                        ->label('Nombre d\'évaluations')
                        ->numeric(),
                    Infolists\Components\TextEntry::make('admin_notes')
                        ->label('Notes internes')
                        ->placeholder('Aucune note')
                        ->columnSpanFull(),
                    Infolists\Components\TextEntry::make('rejection_reason')
                        ->label('Motif de refus')
                        ->placeholder('—')
                        ->columnSpanFull()
                        ->visible(fn($record) => $record->status === ApplicationStatus::Rejected),
                ]),

        ]);
    }

    /* ─────────────────────────────────────────────────────────── */
    /*  PAGES                                                       */
    /* ─────────────────────────────────────────────────────────── */

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListApplications::route('/'),
            'view'  => Pages\ViewApplication::route('/{record}'),
        ];
    }
}
