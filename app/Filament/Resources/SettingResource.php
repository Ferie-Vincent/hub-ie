<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SettingResource\Pages;
use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'Administration';

    protected static ?int $navigationSort = 63;

    protected static ?string $navigationLabel = 'Paramètres';

    protected static ?string $modelLabel = 'Paramètre';

    protected static ?string $pluralModelLabel = 'Paramètres';

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasPermissionTo('manage-system') ?? false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make()->columns(2)->schema([
                Forms\Components\TextInput::make('key')->label('Clé')->required()->unique(ignoreRecord: true)->maxLength(100),
                Forms\Components\TextInput::make('label')->label('Libellé')->maxLength(255),
                Forms\Components\Select::make('type')
                    ->label('Type')
                    ->options(['string' => 'Texte', 'boolean' => 'Booléen', 'integer' => 'Entier', 'json' => 'JSON'])
                    ->default('string'),
                Forms\Components\TextInput::make('group')->label('Groupe')->maxLength(100),
                Forms\Components\Textarea::make('value')->label('Valeur')->rows(3)->columnSpanFull(),
                Forms\Components\Textarea::make('description')->label('Description')->rows(2)->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('key')->label('Clé')->searchable()->fontFamily('mono')->weight('medium'),
                Tables\Columns\TextColumn::make('label')->label('Libellé')->searchable(),
                Tables\Columns\TextColumn::make('group')->label('Groupe')->badge()->toggleable(),
                Tables\Columns\TextColumn::make('value')->label('Valeur')->limit(50)->toggleable(),
            ])
            ->defaultSort('group')
            ->actions([Tables\Actions\EditAction::make()->iconButton()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSettings::route('/'),
            'create' => Pages\CreateSetting::route('/create'),
            'edit' => Pages\EditSetting::route('/{record}/edit'),
        ];
    }
}
