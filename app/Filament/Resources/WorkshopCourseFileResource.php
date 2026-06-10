<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WorkshopCourseFileResource\Pages;
use App\Models\Workshop;
use App\Models\WorkshopCourseFile;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class WorkshopCourseFileResource extends Resource
{
    protected static ?string $model = WorkshopCourseFile::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-arrow-up';

    protected static ?string $navigationGroup = 'Ateliers';

    protected static ?int $navigationSort = 30;

    protected static ?string $navigationLabel = 'Fichiers de cours';

    protected static ?string $modelLabel = 'Fichier de cours';

    protected static ?string $pluralModelLabel = 'Fichiers de cours';

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasPermissionTo('manage-content') ?? false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make()->columns(2)->schema([
                Forms\Components\Select::make('workshop_id')
                    ->label('Atelier')
                    ->relationship('workshop', 'title')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('title')
                    ->label('Titre du fichier')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('sort_order')
                    ->label('Ordre d\'affichage')
                    ->numeric()
                    ->default(0),

                Forms\Components\Textarea::make('description')
                    ->label('Description')
                    ->rows(3)
                    ->nullable()
                    ->columnSpanFull(),

                Forms\Components\FileUpload::make('file_path')
                    ->label('Fichier')
                    ->disk('public')
                    ->directory('workshop-courses')
                    ->acceptedFileTypes([
                        'application/pdf',
                        'application/vnd.ms-powerpoint',
                        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                        'application/msword',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        'application/zip',
                        'video/mp4',
                        'image/*',
                    ])
                    ->maxSize(512000)
                    ->storeFileNamesIn('original_filename')
                    ->required()
                    ->columnSpanFull()
                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                        if (! $state) {
                            return;
                        }

                        // $state is a TemporaryUploadedFile when a new file is uploaded
                        if ($state instanceof TemporaryUploadedFile) {
                            $set('mime_type', $state->getMimeType());
                            $set('file_size_bytes', $state->getSize());
                        } elseif (is_string($state)) {
                            // Already stored path — read from disk
                            $disk = Storage::disk('public');
                            if ($disk->exists($state)) {
                                $set('file_size_bytes', $disk->size($state));
                            }
                        }
                    })
                    ->live(),

                // Hidden computed fields — auto-filled by afterStateUpdated
                Forms\Components\Hidden::make('mime_type'),
                Forms\Components\Hidden::make('file_size_bytes'),

                Forms\Components\Toggle::make('is_published')
                    ->label('Visible aux inscrits')
                    ->default(true)
                    ->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('workshop.title')
                    ->label('Atelier')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),

                Tables\Columns\TextColumn::make('title')
                    ->label('Titre')
                    ->searchable()
                    ->description(fn (WorkshopCourseFile $record): string => $record->original_filename ?? ''),

                Tables\Columns\TextColumn::make('original_filename')
                    ->label('Fichier original')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('file_size_human')
                    ->label('Taille')
                    ->alignEnd(),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label('#')
                    ->sortable()
                    ->width('50px'),

                Tables\Columns\IconColumn::make('is_published')
                    ->label('Publié')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Ajouté le')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('workshop_id')
                    ->label('Atelier')
                    ->options(fn () => Workshop::orderBy('display_order')->pluck('title', 'id'))
                    ->searchable(),

                Tables\Filters\TernaryFilter::make('is_published')
                    ->label('Statut de publication')
                    ->trueLabel('Publiés uniquement')
                    ->falseLabel('Non publiés uniquement')
                    ->placeholder('Tous'),
            ])
            ->defaultSort('sort_order')
            ->actions([
                Tables\Actions\Action::make('download')
                    ->label('Télécharger')
                    ->icon('heroicon-m-arrow-down-tray')
                    ->color('gray')
                    ->url(fn (WorkshopCourseFile $record): string => Storage::disk('public')->url($record->file_path))
                    ->openUrlInNewTab(),

                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),

                    Tables\Actions\BulkAction::make('toggle_published')
                        ->label('Basculer la publication')
                        ->icon('heroicon-m-eye')
                        ->action(function (Collection $records): void {
                            foreach ($records as $record) {
                                $record->update(['is_published' => ! $record->is_published]);
                            }
                        })
                        ->deselectRecordsAfterCompletion()
                        ->requiresConfirmation()
                        ->modalHeading('Basculer la publication')
                        ->modalDescription('Le statut de publication de chaque fichier sélectionné sera inversé.')
                        ->successNotificationTitle('Publication mise à jour'),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWorkshopCourseFiles::route('/'),
            'create' => Pages\CreateWorkshopCourseFile::route('/create'),
            'edit' => Pages\EditWorkshopCourseFile::route('/{record}/edit'),
        ];
    }
}
