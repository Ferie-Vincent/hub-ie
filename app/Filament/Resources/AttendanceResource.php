<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AttendanceResource\Pages;
use App\Models\Attendance;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AttendanceResource extends Resource
{
    protected static ?string $model              = Attendance::class;
    protected static ?string $navigationIcon     = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationGroup    = 'Événement';
    protected static ?int    $navigationSort     = 61;
    protected static ?string $navigationLabel    = 'Présences';
    protected static ?string $modelLabel         = 'Pointage';
    protected static ?string $pluralModelLabel   = 'Présences';

    public static function canCreate(): bool { return false; }
    public static function canEdit($record): bool { return false; }
    public static function canDelete($record): bool { return false; }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('event_date')
                    ->label('Jour')->date('d/m/Y')->sortable(),
                Tables\Columns\TextColumn::make('application.user.full_name')
                    ->label('Candidat(e)')->searchable(),
                Tables\Columns\TextColumn::make('application.group_label')
                    ->label('Groupe')->badge(),
                Tables\Columns\TextColumn::make('scanned_at')
                    ->label('Scanné à')->dateTime('H:i:s')->sortable(),
                Tables\Columns\TextColumn::make('scan_method')
                    ->label('Méthode')->badge()
                    ->color(fn($state) => $state === 'qr' ? 'success' : 'info')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('scanner_ip')
                    ->label('IP')->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('event_date')
                    ->label('Jour')
                    ->options([
                        '2026-06-22' => '22 juin 2026',
                        '2026-06-23' => '23 juin 2026',
                        '2026-06-24' => '24 juin 2026',
                        '2026-06-25' => '25 juin 2026',
                    ]),
                Tables\Filters\SelectFilter::make('group_label')
                    ->label('Groupe')
                    ->options(['G1' => 'G1', 'G2' => 'G2', 'G3' => 'G3'])
                    ->query(fn($query, $data) => blank($data['value'])
                        ? $query
                        : $query->whereHas('application', fn($q) => $q->where('group_label', $data['value']))
                    ),
            ])
            ->defaultSort('scanned_at', 'desc')
            ->striped()
            ->poll('15s');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAttendances::route('/'),
        ];
    }
}
