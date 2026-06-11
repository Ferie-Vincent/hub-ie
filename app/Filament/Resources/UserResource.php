<?php

namespace App\Filament\Resources;

use App\Enums\Gender;
use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Administration';

    protected static ?int $navigationSort = 60;

    protected static ?string $navigationLabel = 'Utilisateurs';

    protected static ?string $modelLabel = 'Utilisateur';

    protected static ?string $pluralModelLabel = 'Utilisateurs';

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasPermissionTo('manage-system') ?? false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Identité')->columns(2)->schema([
                Forms\Components\TextInput::make('first_name')
                    ->label('Prénom')->required()->maxLength(100),
                Forms\Components\TextInput::make('last_name')
                    ->label('Nom')->required()->maxLength(100),
                Forms\Components\TextInput::make('email')
                    ->label('Email')->email()->required()->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('phone')
                    ->label('Téléphone')->tel()->placeholder('+2250700000000'),
                Forms\Components\Select::make('gender')
                    ->label('Genre')
                    ->options(collect(Gender::cases())->mapWithKeys(fn ($g) => [$g->value => $g->label()])),
                Forms\Components\DatePicker::make('birth_date')
                    ->label('Date de naissance')->displayFormat('d/m/Y'),
                Forms\Components\TextInput::make('city')->label('Ville'),
                Forms\Components\TextInput::make('country')->label('Pays'),
                Forms\Components\TextInput::make('nationality')->label('Nationalité'),
            ]),
            Forms\Components\Section::make('Accès')->schema([
                Forms\Components\Toggle::make('is_active')->label('Compte actif')->default(true),
                Forms\Components\TextInput::make('password')
                    ->label('Mot de passe')
                    ->password()
                    ->revealable()
                    ->dehydrated(fn ($state) => filled($state))
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->required(fn (string $operation) => $operation === 'create'),
                Forms\Components\Select::make('roles')
                    ->label('Rôle(s)')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('full_name')
                    ->label('Nom complet')
                    ->searchable(['first_name', 'last_name'])
                    ->weight(FontWeight::Medium),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-m-envelope'),
                Tables\Columns\BadgeColumn::make('gender')
                    ->label('Genre')
                    ->formatStateUsing(fn ($state) => $state?->label())
                    ->color(fn ($state) => $state?->color())
                    ->toggleable(),
                Tables\Columns\TextColumn::make('city')
                    ->label('Ville')
                    ->toggleable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Actif')
                    ->boolean(),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->label('Vérifié le')
                    ->dateTime('d/m/Y')
                    ->placeholder('Non vérifié')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Créé le')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')->label('Compte actif'),
                Tables\Filters\TernaryFilter::make('email_verified_at')
                    ->label('Email vérifié')
                    ->nullable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
