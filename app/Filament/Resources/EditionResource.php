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

            Forms\Components\Section::make('Portfolio public')
                ->description('Contenu affiché sur la page /portfolio une fois l\'édition archivée.')
                ->schema([
                    Forms\Components\Textarea::make('description')
                        ->label('Description / bilan')
                        ->rows(4)
                        ->maxLength(1000)
                        ->placeholder('Résumé de l\'édition, résultats clés, impact…')
                        ->columnSpanFull(),

                    Forms\Components\FileUpload::make('cover_image')
                        ->label('Image de couverture')
                        ->image()
                        ->disk('public')
                        ->directory('editions')
                        ->imagePreviewHeight('120')
                        ->columnSpanFull(),

                    Forms\Components\KeyValue::make('key_figures')
                        ->label('Chiffres clés')
                        ->keyLabel('Indicateur')
                        ->valueLabel('Valeur')
                        ->addActionLabel('Ajouter un chiffre')
                        ->columnSpanFull()
                        ->helperText('Ex : "Taux de satisfaction" → "94 %", "Femmes" → "52 %"'),
                ])
                ->columns(1),

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

            // ── Contenu du site ─────────────────────────────────────────────

            Forms\Components\Section::make('Hero — Textes')
                ->description('Bandeau d\'accueil et introduction de la page principale.')
                ->schema([
                    Forms\Components\Textarea::make('hero_patron_text')
                        ->label('Texte de patronage (bandeau)')
                        ->rows(2)
                        ->placeholder('Sous le haut patronage de Monsieur le Ministre...')
                        ->columnSpanFull(),

                    Forms\Components\Textarea::make('hero_subtitle')
                        ->label('Sous-titre hero')
                        ->rows(3)
                        ->placeholder('Le rendez-vous stratégique des acteurs du commerce extérieur ivoirien…')
                        ->columnSpanFull(),

                    Forms\Components\Repeater::make('dates_cles')
                        ->label('Dates clés (carte flottante hero)')
                        ->schema([
                            Forms\Components\TextInput::make('lieu')
                                ->label('Lieu')
                                ->required()
                                ->columnSpan(1),
                            Forms\Components\TextInput::make('sublabel')
                                ->label('Libellé')
                                ->required()
                                ->columnSpan(2),
                            Forms\Components\TextInput::make('date')
                                ->label('Date affichée')
                                ->required()
                                ->columnSpan(1),
                        ])
                        ->columns(4)
                        ->maxItems(6)
                        ->addActionLabel('Ajouter une date')
                        ->collapsed()
                        ->columnSpanFull(),
                ])
                ->collapsible()
                ->collapsed(),

            Forms\Components\Section::make('Mot du Ministre')
                ->description('Portrait, nom, titre et texte du discours.')
                ->schema([
                    Forms\Components\TextInput::make('minister_name')
                        ->label('Nom du Ministre')
                        ->placeholder('Kalil KONATÉ'),

                    Forms\Components\TextInput::make('minister_title')
                        ->label('Titre')
                        ->placeholder('Ministre du Commerce, de l\'Industrie et de l\'Artisanat'),

                    Forms\Components\Repeater::make('minister_speech')
                        ->label('Paragraphes du discours')
                        ->schema([
                            Forms\Components\Textarea::make('text')
                                ->label('Paragraphe')
                                ->rows(4)
                                ->required(),
                        ])
                        ->maxItems(6)
                        ->addActionLabel('Ajouter un paragraphe')
                        ->collapsed()
                        ->columnSpanFull(),
                ])
                ->columns(2)
                ->collapsible()
                ->collapsed(),

            Forms\Components\Section::make('Statistiques (section Ministre)')
                ->description('Les 4 cartes de chiffres clés sous le discours du Ministre.')
                ->schema([
                    Forms\Components\Repeater::make('stats_cards')
                        ->label('Cartes statistiques')
                        ->schema([
                            Forms\Components\TextInput::make('value')
                                ->label('Valeur')
                                ->required()
                                ->columnSpan(1),
                            Forms\Components\TextInput::make('label')
                                ->label('Intitulé')
                                ->required()
                                ->columnSpan(2),
                            Forms\Components\TextInput::make('caption')
                                ->label('Légende')
                                ->columnSpan(2),
                            Forms\Components\Select::make('color')
                                ->label('Couleur')
                                ->options(['orange' => 'Orange', 'vert' => 'Vert'])
                                ->default('orange')
                                ->columnSpan(1),
                        ])
                        ->columns(6)
                        ->maxItems(4)
                        ->addActionLabel('Ajouter une statistique')
                        ->collapsed(),
                ])
                ->collapsible()
                ->collapsed(),

            Forms\Components\Section::make('Cap stratégique — Objectifs')
                ->schema([
                    Forms\Components\Repeater::make('cap_objectifs')
                        ->label('Items objectifs')
                        ->schema([
                            Forms\Components\TextInput::make('num')->label('N°')->default('01')->columnSpan(1),
                            Forms\Components\TextInput::make('title')->label('Titre')->required()->columnSpan(3),
                            Forms\Components\Textarea::make('body')->label('Corps')->rows(2)->required()->columnSpanFull(),
                        ])
                        ->columns(4)
                        ->maxItems(6)
                        ->addActionLabel('Ajouter un item')
                        ->collapsed(),
                ])
                ->collapsible()
                ->collapsed(),

            Forms\Components\Section::make('Cap stratégique — Résultats attendus')
                ->schema([
                    Forms\Components\Repeater::make('cap_resultats')
                        ->label('Items résultats')
                        ->schema([
                            Forms\Components\TextInput::make('num')->label('N°')->default('01')->columnSpan(1),
                            Forms\Components\TextInput::make('title')->label('Titre')->required()->columnSpan(3),
                            Forms\Components\Textarea::make('body')->label('Corps')->rows(2)->required()->columnSpanFull(),
                        ])
                        ->columns(4)
                        ->maxItems(6)
                        ->addActionLabel('Ajouter un item')
                        ->collapsed(),
                ])
                ->collapsible()
                ->collapsed(),

            Forms\Components\Section::make('Cap stratégique — Pourquoi participer')
                ->schema([
                    Forms\Components\Repeater::make('cap_pourquoi')
                        ->label('Items pourquoi')
                        ->schema([
                            Forms\Components\TextInput::make('num')->label('N°')->default('01')->columnSpan(1),
                            Forms\Components\TextInput::make('title')->label('Titre')->required()->columnSpan(3),
                            Forms\Components\Textarea::make('body')->label('Corps')->rows(2)->required()->columnSpanFull(),
                        ])
                        ->columns(4)
                        ->maxItems(6)
                        ->addActionLabel('Ajouter un item')
                        ->collapsed(),
                ])
                ->collapsible()
                ->collapsed(),

            Forms\Components\Section::make('Programme — Jour 1 (22 juin · Ouverture)')
                ->schema([self::programmeDayRepeater('programme_j1')])
                ->collapsible()
                ->collapsed(),

            Forms\Components\Section::make('Programme — Jour 2 (23 juin · Ateliers)')
                ->schema([self::programmeDayRepeater('programme_j2')])
                ->collapsible()
                ->collapsed(),

            Forms\Components\Section::make('Programme — Jour 3 (24 juin · Ateliers)')
                ->schema([self::programmeDayRepeater('programme_j3')])
                ->collapsible()
                ->collapsed(),

            Forms\Components\Section::make('Programme — Jour 4 (25 juin · Clôture)')
                ->schema([self::programmeDayRepeater('programme_j4')])
                ->collapsible()
                ->collapsed(),

        ]);
    }

    private static function programmeDayRepeater(string $field): Forms\Components\Repeater
    {
        return Forms\Components\Repeater::make($field)
            ->label('Activités du jour')
            ->schema([
                Forms\Components\TextInput::make('label')
                    ->label('Activité')
                    ->required()
                    ->columnSpan(3),
                Forms\Components\Select::make('tag')
                    ->label('Type')
                    ->options([
                        'Accueil' => 'Accueil',
                        'Plénière' => 'Plénière',
                        'Conférence' => 'Conférence',
                        'Atelier' => 'Atelier',
                        'Networking' => 'Networking',
                        'Pause' => 'Pause',
                        'Panel' => 'Panel',
                        'B2B' => 'B2B',
                        'Presse' => 'Presse',
                        'Restitution' => 'Restitution',
                        'Évaluation' => 'Évaluation',
                        'Cérémonie' => 'Cérémonie',
                    ])
                    ->required()
                    ->columnSpan(1),
            ])
            ->columns(4)
            ->maxItems(10)
            ->addActionLabel('Ajouter une activité')
            ->collapsed();
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
                Tables\Actions\EditAction::make()->iconButton(),

                Tables\Actions\Action::make('activate')
                    ->label('Activer')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->iconButton()
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
                    ->iconButton()
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
                    ->iconButton()
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
