<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SpeakerResource\Pages;
use App\Models\Speaker;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SpeakerResource extends Resource
{
    protected static ?string $model              = Speaker::class;
    protected static ?string $navigationIcon     = 'heroicon-o-microphone';
    protected static ?string $navigationGroup    = 'Contenu';
    protected static ?int    $navigationSort     = 23;
    protected static ?string $navigationLabel    = 'Intervenants';
    protected static ?string $modelLabel         = 'Intervenant';
    protected static ?string $pluralModelLabel   = 'Intervenants';

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasPermissionTo('manage-content') ?? false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make()->columns(2)->schema([
                Forms\Components\TextInput::make('first_name')->label('Prénom')->required(),
                Forms\Components\TextInput::make('last_name')->label('Nom')->required(),
                Forms\Components\TextInput::make('title')->label('Titre / Fonction')->required(),
                Forms\Components\TextInput::make('organization')->label('Organisation'),
                Forms\Components\Textarea::make('bio')->label('Biographie')->rows(4)->columnSpanFull(),
                Forms\Components\TextInput::make('linkedin')->label('LinkedIn')->url(),
                Forms\Components\TextInput::make('display_order')->label('Ordre')->integer()->default(0),
                Forms\Components\Toggle::make('is_featured')->label('Mis en avant'),
                Forms\Components\Toggle::make('is_published')->label('Publié'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('display_order')->label('#')->sortable()->width('50px'),
                Tables\Columns\TextColumn::make('last_name')->label('Nom')->searchable()->weight('medium'),
                Tables\Columns\TextColumn::make('first_name')->label('Prénom')->searchable(),
                Tables\Columns\TextColumn::make('title')->label('Titre')->limit(50)->toggleable(),
                Tables\Columns\TextColumn::make('organization')->label('Org.')->limit(40)->toggleable(),
                Tables\Columns\IconColumn::make('is_featured')->label('Vedette')->boolean(),
                Tables\Columns\IconColumn::make('is_published')->label('Publié')->boolean(),
            ])
            ->defaultSort('display_order')
            ->reorderable('display_order')
            ->actions([Tables\Actions\EditAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListSpeakers::route('/'),
            'create' => Pages\CreateSpeaker::route('/create'),
            'edit'   => Pages\EditSpeaker::route('/{record}/edit'),
        ];
    }
}
