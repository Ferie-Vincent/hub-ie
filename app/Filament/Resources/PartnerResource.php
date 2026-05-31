<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PartnerResource\Pages;
use App\Models\Partner;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PartnerResource extends Resource
{
    protected static ?string $model = Partner::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $navigationGroup = 'Contenu';

    protected static ?int $navigationSort = 21;

    protected static ?string $navigationLabel = 'Partenaires';

    protected static ?string $modelLabel = 'Partenaire';

    protected static ?string $pluralModelLabel = 'Partenaires';

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasPermissionTo('manage-content') ?? false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make()->columns(2)->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nom')->required()->maxLength(255),
                Forms\Components\TextInput::make('website')
                    ->label('Site web')->url()->maxLength(255),
                Forms\Components\Select::make('tier')
                    ->label('Niveau')
                    ->options(['gold' => 'Or', 'silver' => 'Argent', 'bronze' => 'Bronze', 'institutional' => 'Institutionnel'])
                    ->required(),
                Forms\Components\TextInput::make('display_order')
                    ->label('Ordre')->integer()->default(0),
                Forms\Components\Toggle::make('show_in_marquee')->label('Bandeau défilant'),
                Forms\Components\Toggle::make('show_in_footer')->label('Pied de page'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('display_order')->label('#')->sortable()->width('50px'),
                Tables\Columns\TextColumn::make('name')->label('Nom')->searchable()->weight('medium'),
                Tables\Columns\BadgeColumn::make('tier')
                    ->label('Niveau')
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'gold' => 'Or', 'silver' => 'Argent', 'bronze' => 'Bronze',
                        default => 'Institutionnel',
                    })
                    ->color(fn ($state) => match ($state) {
                        'gold' => 'warning', 'silver' => 'gray', 'bronze' => 'danger',
                        default => 'info',
                    }),
                Tables\Columns\IconColumn::make('show_in_marquee')->label('Bandeau')->boolean(),
                Tables\Columns\IconColumn::make('show_in_footer')->label('Footer')->boolean(),
            ])
            ->defaultSort('display_order')
            ->reorderable('display_order')
            ->actions([Tables\Actions\EditAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPartners::route('/'),
            'create' => Pages\CreatePartner::route('/create'),
            'edit' => Pages\EditPartner::route('/{record}/edit'),
        ];
    }
}
