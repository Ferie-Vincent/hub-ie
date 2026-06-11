<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NewsResource\Pages;
use App\Models\News;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class NewsResource extends Resource
{
    protected static ?string $model = News::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $navigationGroup = 'Contenu';

    protected static ?int $navigationSort = 40;

    protected static ?string $navigationLabel = 'Actualités';

    protected static ?string $modelLabel = 'Actualité';

    protected static ?string $pluralModelLabel = 'Actualités';

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasPermissionTo('manage-content') ?? false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make()->columns(2)->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Titre')->required()->maxLength(255)->columnSpanFull(),
                Forms\Components\TextInput::make('slug')
                    ->label('Slug')->required()->unique(ignoreRecord: true)->maxLength(255),
                Forms\Components\Textarea::make('excerpt')
                    ->label('Résumé')->rows(2)->maxLength(500)->columnSpanFull(),
                Forms\Components\RichEditor::make('content')
                    ->label('Contenu')->columnSpanFull(),
                Forms\Components\DateTimePicker::make('published_at')
                    ->label('Date de publication')->displayFormat('d/m/Y H:i'),
                Forms\Components\Toggle::make('is_featured')
                    ->label('À la une'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Titre')->searchable()->weight('medium')->limit(60),
                Tables\Columns\TextColumn::make('published_at')
                    ->label('Publié le')->dateTime('d/m/Y H:i')->sortable()->placeholder('Brouillon'),
                Tables\Columns\IconColumn::make('is_featured')->label('Une')->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Créé le')->date('d/m/Y')->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\EditAction::make()->iconButton(),
                Tables\Actions\DeleteAction::make()->iconButton(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNews::route('/'),
            'create' => Pages\CreateNews::route('/create'),
            'edit' => Pages\EditNews::route('/{record}/edit'),
        ];
    }
}
