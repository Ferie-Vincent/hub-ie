<?php

namespace App\Filament\Resources;

use App\Enums\EnrollmentStatus;
use App\Filament\Resources\WorkshopResource\Pages;
use App\Models\AuditLog;
use App\Models\Workshop;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class WorkshopResource extends Resource
{
    protected static ?string $model = Workshop::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationGroup = 'Ateliers';

    protected static ?int $navigationSort = 20;

    protected static ?string $navigationLabel = 'Ateliers';

    protected static ?string $modelLabel = 'Atelier';

    protected static ?string $pluralModelLabel = 'Ateliers';

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasPermissionTo('manage-content') ?? false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make()->columns(2)->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Titre')->required()->maxLength(255),
                Forms\Components\TextInput::make('slug')
                    ->label('Slug')->required()->maxLength(100)->unique(ignoreRecord: true),
                Forms\Components\Textarea::make('short_description')
                    ->label('Description courte')->rows(2)->columnSpanFull(),
                Forms\Components\Textarea::make('full_description')
                    ->label('Description complète')->rows(5)->columnSpanFull(),
                Forms\Components\TextInput::make('capacity')
                    ->label('Capacité')->integer()->minValue(1)->default(45),
                Forms\Components\TextInput::make('display_order')
                    ->label('Ordre d\'affichage')->integer()->default(0),
                Forms\Components\Toggle::make('is_published')
                    ->label('Publié')->default(false),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->withCount([
                'enrollments as enrolled_count' => fn ($q) => $q->where('status', EnrollmentStatus::Enrolled->value),
            ]))
            ->columns([
                Tables\Columns\TextColumn::make('display_order')
                    ->label('#')->sortable()->width('50px'),
                Tables\Columns\TextColumn::make('title')
                    ->label('Titre')->searchable()->weight('medium'),
                Tables\Columns\TextColumn::make('capacity')
                    ->label('Capacité')->numeric()->alignEnd()->sortable(),
                Tables\Columns\TextColumn::make('registered_count')
                    ->label('Inscrits')->numeric()->alignEnd()->sortable(),
                Tables\Columns\TextColumn::make('spots_left')
                    ->label('Places restantes')
                    ->alignEnd()
                    ->getStateUsing(fn (Workshop $record): int => $record->spotsLeft())
                    ->badge()
                    ->color(fn (int $state): string => $state === 0 ? 'danger' : 'success'),
                Tables\Columns\TextColumn::make('enrolled_count')
                    ->label('Confirmés')->numeric()->alignEnd()->sortable(),
                Tables\Columns\IconColumn::make('is_published')
                    ->label('Publié')->boolean(),
            ])
            ->defaultSort('display_order')
            ->reorderable('display_order')
            ->actions([
                Tables\Actions\Action::make('voir_inscrits')
                    ->label('Voir les inscrits')
                    ->icon('heroicon-o-user-group')
                    ->url(fn (Workshop $record): string => EnrollmentResource::getUrl('index', [
                        'tableFilters[workshop_id][value]' => $record->id,
                    ])),
                Tables\Actions\Action::make('reset_count')
                    ->label('Réinitialiser compteur')
                    ->icon('heroicon-o-arrow-path')
                    ->visible(fn (): bool => auth()->user()?->hasPermissionTo('manage-content') ?? false)
                    ->requiresConfirmation()
                    ->modalHeading('Réinitialiser le compteur d\'inscrits')
                    ->modalDescription('Le compteur sera recalculé depuis la table des inscriptions confirmées. Continuer ?')
                    ->modalSubmitActionLabel('Réinitialiser')
                    ->action(function (Workshop $record): void {
                        $oldCount = $record->registered_count;

                        $newCount = $record->enrollments()
                            ->where('status', EnrollmentStatus::Enrolled->value)
                            ->count();

                        $record->update(['registered_count' => $newCount]);

                        AuditLog::create([
                            'user_id' => auth()->id(),
                            'action' => 'reset_registered_count',
                            'subject_type' => Workshop::class,
                            'subject_id' => $record->id,
                            'old_values' => ['registered_count' => $oldCount],
                            'new_values' => ['registered_count' => $newCount],
                            'ip' => request()->ip(),
                            'user_agent' => request()->userAgent(),
                        ]);

                        Notification::make()
                            ->title('Compteur réinitialisé')
                            ->body("Inscrits confirmés : {$newCount} (était {$oldCount}).")
                            ->success()
                            ->send();
                    }),
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWorkshops::route('/'),
            'create' => Pages\CreateWorkshop::route('/create'),
            'edit' => Pages\EditWorkshop::route('/{record}/edit'),
        ];
    }
}
