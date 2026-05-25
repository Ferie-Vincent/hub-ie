<?php

namespace App\Filament\Resources;

use App\Enums\ApplicationStatus;
use App\Filament\Resources\EvaluationResource\Pages;
use App\Models\Application;
use App\Models\Evaluation;
use Filament\Forms;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontFamily;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class EvaluationResource extends Resource
{
    protected static ?string $model             = Evaluation::class;
    protected static ?string $navigationIcon    = 'heroicon-o-star';
    protected static ?string $navigationGroup   = 'Candidatures';
    protected static ?int    $navigationSort    = 10;
    protected static ?string $navigationLabel   = 'Évaluations';
    protected static ?string $modelLabel        = 'Évaluation';
    protected static ?string $pluralModelLabel  = 'Évaluations';

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()->with(['application.user', 'evaluator']);

        if (! auth()->user()?->hasRole('super_admin') && ! auth()->user()?->hasRole('admin')) {
            $query->where('evaluator_id', auth()->id());
        }

        return $query;
    }

    public static function form(Forms\Form $form): Forms\Form
    {
        $scoreOptions = array_combine(range(1, 5), range(1, 5));

        return $form->schema([
            Forms\Components\Section::make('Candidature à évaluer')
                ->schema([
                    Forms\Components\Select::make('application_id')
                        ->label('Candidature')
                        ->options(
                            Application::whereIn('status', [
                                ApplicationStatus::Eligible->value,
                                ApplicationStatus::UnderReview->value,
                            ])
                            ->with('user')
                            ->get()
                            ->mapWithKeys(fn($app) => [
                                $app->id => "{$app->reference_code} — {$app->user?->full_name}",
                            ])
                        )
                        ->searchable()
                        ->required()
                        ->disabledOn('edit'),

                    Forms\Components\Hidden::make('evaluator_id')
                        ->default(fn() => auth()->id()),
                ]),

            Forms\Components\Section::make('Notation — 1 (insuffisant) à 5 (excellent)')
                ->columns(2)
                ->schema([
                    Forms\Components\Select::make('score_profile')
                        ->label('Profil professionnel (25 %)')
                        ->options($scoreOptions)
                        ->required(),

                    Forms\Components\Select::make('score_motivation')
                        ->label('Motivation (25 %)')
                        ->options($scoreOptions)
                        ->required(),

                    Forms\Components\Select::make('score_relevance')
                        ->label('Pertinence du projet (20 %)')
                        ->options($scoreOptions)
                        ->required(),

                    Forms\Components\Select::make('score_representativity')
                        ->label('Représentativité (15 %)')
                        ->options($scoreOptions)
                        ->required(),

                    Forms\Components\Select::make('score_balance')
                        ->label('Équilibre de profil (15 %)')
                        ->options($scoreOptions)
                        ->required(),
                ]),

            Forms\Components\Section::make('Commentaire')
                ->schema([
                    Forms\Components\Textarea::make('comment')
                        ->label('Observations du membre du comité')
                        ->rows(4)
                        ->placeholder('Justification de la note, points forts, réserves éventuelles…'),
                ]),
        ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Infolists\Components\Section::make('Candidature')
                ->columns(2)
                ->schema([
                    Infolists\Components\TextEntry::make('application.reference_code')
                        ->label('Référence')
                        ->badge()
                        ->color('gray')
                        ->fontFamily(FontFamily::Mono),
                    Infolists\Components\TextEntry::make('application.user.full_name')
                        ->label('Candidat(e)'),
                    Infolists\Components\TextEntry::make('evaluator.name')
                        ->label('Évaluateur'),
                    Infolists\Components\TextEntry::make('weighted_score')
                        ->label('Score pondéré')
                        ->numeric(decimalPlaces: 2)
                        ->badge()
                        ->color(fn($state) => match(true) {
                            $state >= 4   => 'success',
                            $state >= 2.5 => 'warning',
                            default       => 'danger',
                        }),
                ]),

            Infolists\Components\Section::make('Détail des scores')
                ->columns(5)
                ->schema([
                    Infolists\Components\TextEntry::make('score_profile')
                        ->label('Profil'),
                    Infolists\Components\TextEntry::make('score_motivation')
                        ->label('Motivation'),
                    Infolists\Components\TextEntry::make('score_relevance')
                        ->label('Pertinence'),
                    Infolists\Components\TextEntry::make('score_representativity')
                        ->label('Repr.'),
                    Infolists\Components\TextEntry::make('score_balance')
                        ->label('Équilibre'),
                ]),

            Infolists\Components\Section::make('Commentaire')
                ->schema([
                    Infolists\Components\TextEntry::make('comment')
                        ->label('Observations')
                        ->placeholder('Aucune observation saisie')
                        ->prose()
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('application.reference_code')
                    ->label('Réf.')
                    ->searchable()
                    ->badge()
                    ->color('gray')
                    ->fontFamily(FontFamily::Mono),

                Tables\Columns\TextColumn::make('application.user.full_name')
                    ->label('Candidat(e)')
                    ->searchable(),

                Tables\Columns\TextColumn::make('evaluator.name')
                    ->label('Évaluateur')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('score_profile')
                    ->label('Profil')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('score_motivation')
                    ->label('Motivation')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('weighted_score')
                    ->label('Score /5')
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->badge()
                    ->color(fn($state) => match(true) {
                        $state >= 4   => 'success',
                        $state >= 2.5 => 'warning',
                        default       => 'danger',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Soumis le')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('application_status')
                    ->label('Statut candidature')
                    ->options([
                        ApplicationStatus::Eligible->value    => ApplicationStatus::Eligible->label(),
                        ApplicationStatus::UnderReview->value => ApplicationStatus::UnderReview->label(),
                    ])
                    ->query(fn(Builder $query, array $data) => blank($data['value'])
                        ? $query
                        : $query->whereHas('application', fn($q) => $q->where('status', $data['value']))
                    ),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->visible(fn(Evaluation $record) =>
                        auth()->id() === $record->evaluator_id
                        || auth()->user()?->hasRole('super_admin')
                        || auth()->user()?->hasRole('admin')
                    ),
            ]);
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::count();
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListEvaluations::route('/'),
            'create' => Pages\CreateEvaluation::route('/create'),
            'view'   => Pages\ViewEvaluation::route('/{record}'),
            'edit'   => Pages\EditEvaluation::route('/{record}/edit'),
        ];
    }
}
