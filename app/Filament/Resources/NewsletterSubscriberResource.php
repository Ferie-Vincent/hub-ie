<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NewsletterSubscriberResource\Pages;
use App\Models\NewsletterSubscriber;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class NewsletterSubscriberResource extends Resource
{
    protected static ?string $model = NewsletterSubscriber::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $navigationGroup = 'Communication';

    protected static ?int $navigationSort = 51;

    protected static ?string $navigationLabel = 'Newsletter';

    protected static ?string $modelLabel = 'Abonné';

    protected static ?string $pluralModelLabel = 'Abonnés newsletter';

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasPermissionTo('manage-content') ?? false;
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
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')->searchable()->copyable()->icon('heroicon-m-envelope'),
                Tables\Columns\TextColumn::make('source')
                    ->label('Source')->badge()->toggleable(),
                Tables\Columns\TextColumn::make('confirmed_at')
                    ->label('Confirmé le')->dateTime('d/m/Y')->placeholder('Non confirmé')->sortable(),
                Tables\Columns\TextColumn::make('unsubscribed_at')
                    ->label('Désabonné le')->dateTime('d/m/Y')->placeholder('—')->sortable()->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Inscrit le')->date('d/m/Y')->sortable()->toggleable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('confirmed_at')
                    ->label('Email confirmé')->nullable(),
                Tables\Filters\TernaryFilter::make('unsubscribed_at')
                    ->label('Désabonné')->nullable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNewsletterSubscribers::route('/'),
        ];
    }
}
