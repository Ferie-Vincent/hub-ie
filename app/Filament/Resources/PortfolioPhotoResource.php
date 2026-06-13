<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PortfolioPhotoResource\Pages;
use App\Models\Edition;
use App\Models\PortfolioPhoto;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;

class PortfolioPhotoResource extends Resource
{
    protected static ?string $model = PortfolioPhoto::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationGroup = 'Contenu';

    protected static ?string $navigationLabel = 'Photos portfolio';

    protected static ?string $modelLabel = 'Photo';

    protected static ?string $pluralModelLabel = 'Photos portfolio';

    protected static ?int $navigationSort = 51;

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasPermissionTo('manage-content') ?? false;
    }

    // ── Form (edit single photo) ──────────────────────────────────────────────

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make()->schema([
                Forms\Components\Select::make('edition_id')
                    ->label('Édition')
                    ->options(Edition::orderByDesc('year')->pluck('title', 'id'))
                    ->required()
                    ->searchable(),

                Forms\Components\TextInput::make('caption')
                    ->label('Légende')
                    ->maxLength(255)
                    ->placeholder('Description optionnelle…'),

                Forms\Components\TextInput::make('sort_order')
                    ->label('Ordre d\'affichage')
                    ->numeric()
                    ->default(0),

                Forms\Components\FileUpload::make('image')
                    ->label('Photo')
                    ->image()
                    ->disk('public')
                    ->directory('portfolio')
                    ->required()
                    ->columnSpanFull(),
            ])->columns(2),
        ]);
    }

    // ── Table ────────────────────────────────────────────────────────────────

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Photo')
                    ->disk('public')
                    ->height(56)
                    ->width(80)
                    ->extraImgAttributes(['style' => 'object-fit:cover;border-radius:.5rem;']),

                Tables\Columns\TextColumn::make('edition.title')
                    ->label('Édition')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('success'),

                Tables\Columns\TextColumn::make('caption')
                    ->label('Légende')
                    ->limit(50)
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label('#')
                    ->sortable()
                    ->alignCenter()
                    ->width('60px'),
            ])
            ->defaultSort('edition_id', 'desc')
            ->reorderable('sort_order')
            ->headerActions([
                Action::make('import_photos')
                    ->label('Importer des photos')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->color('primary')
                    ->form([
                        Forms\Components\Select::make('edition_id')
                            ->label('Édition')
                            ->options(Edition::orderByDesc('year')->pluck('title', 'id'))
                            ->required()
                            ->searchable(),

                        Forms\Components\FileUpload::make('images')
                            ->label('Photos')
                            ->image()
                            ->multiple()
                            ->disk('public')
                            ->directory('portfolio')
                            ->required()
                            ->helperText('Sélectionnez une ou plusieurs photos. Formats : JPG, PNG, WebP.'),
                    ])
                    ->action(function (array $data): void {
                        $count = 0;
                        foreach ($data['images'] as $image) {
                            PortfolioPhoto::create([
                                'edition_id' => $data['edition_id'],
                                'image' => $image,
                                'sort_order' => 0,
                            ]);
                            $count++;
                        }

                        Notification::make()
                            ->title("{$count} photo(s) importée(s)")
                            ->success()
                            ->send();
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('edition_id')
                    ->label('Édition')
                    ->options(Edition::orderByDesc('year')->pluck('title', 'id')),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->iconButton(),
                Tables\Actions\DeleteAction::make()->iconButton(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPortfolioPhotos::route('/'),
            'create' => Pages\CreatePortfolioPhoto::route('/create'),
            'edit' => Pages\EditPortfolioPhoto::route('/{record}/edit'),
        ];
    }
}
