<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AuditLogResource\Pages;
use App\Models\AuditLog;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AuditLogResource extends Resource
{
    protected static ?string $model = AuditLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Administration';

    protected static ?int $navigationSort = 64;

    protected static ?string $navigationLabel = 'Journal d\'audit';

    protected static ?string $modelLabel = 'Entrée';

    protected static ?string $pluralModelLabel = 'Journal d\'audit';

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasPermissionTo('manage-system') ?? false;
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')->dateTime('d/m/Y H:i:s')->sortable(),
                Tables\Columns\TextColumn::make('user.full_name')
                    ->label('Utilisateur')->searchable()->placeholder('Système'),
                Tables\Columns\TextColumn::make('action')
                    ->label('Action')->badge()->searchable(),
                Tables\Columns\TextColumn::make('subject_type')
                    ->label('Objet')
                    ->formatStateUsing(fn ($state) => class_basename($state))
                    ->toggleable(),
                Tables\Columns\TextColumn::make('subject_id')
                    ->label('ID')->numeric()->toggleable(),
                Tables\Columns\TextColumn::make('ip')
                    ->label('IP')->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('action')
                    ->label('Action')
                    ->options(fn () => AuditLog::distinct()->pluck('action', 'action')->toArray()),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAuditLogs::route('/'),
        ];
    }
}
