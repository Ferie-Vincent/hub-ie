<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WorkshopResource\Pages;
use App\Models\Workshop;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class WorkshopResource extends Resource
{
    protected static ?string $model = Workshop::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationGroup = 'Contenu';

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
            ->columns([
                Tables\Columns\TextColumn::make('display_order')
                    ->label('#')->sortable()->width('50px'),
                Tables\Columns\TextColumn::make('title')
                    ->label('Titre')->searchable()->weight('medium'),
                Tables\Columns\TextColumn::make('capacity')
                    ->label('Capacité')->numeric()->alignEnd(),
                Tables\Columns\IconColumn::make('is_published')
                    ->label('Publié')->boolean(),
            ])
            ->defaultSort('display_order')
            ->reorderable('display_order')
            ->actions([
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
