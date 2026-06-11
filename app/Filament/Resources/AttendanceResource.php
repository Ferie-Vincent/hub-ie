<?php

namespace App\Filament\Resources;

use App\Enums\AttendanceLocation;
use App\Enums\AttendanceScanMethod;
use App\Filament\Resources\AttendanceResource\Pages;
use App\Models\Attendance;
use App\Models\Workshop;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AttendanceResource extends Resource
{
    protected static ?string $model = Attendance::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationGroup = 'Événement';

    protected static ?int $navigationSort = 32;

    protected static ?string $navigationLabel = 'Présences';

    protected static ?string $modelLabel = 'Pointage';

    protected static ?string $pluralModelLabel = 'Présences';

    public static function canViewAny(): bool
    {
        $user = auth()->user();

        return $user?->hasRole('super_admin')
            || $user?->hasPermissionTo('scan-attendance')
            || false;
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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['enrollment.user', 'enrollment.workshop', 'scannedBy']);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('event_date')
                    ->label('Jour')
                    ->date('d/m/Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('enrollment.user.full_name')
                    ->label('Participant(e)')
                    ->searchable(query: fn (Builder $q, string $s) => $q->whereHas(
                        'enrollment.user',
                        fn ($q) => $q->whereRaw("CONCAT(first_name,' ',last_name) LIKE ?", ["%{$s}%"])
                    )),

                Tables\Columns\TextColumn::make('enrollment.user.email')
                    ->label('Email')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('enrollment.workshop.title')
                    ->label('Atelier')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('scanned_at')
                    ->label('Heure scan')
                    ->dateTime('H:i:s')
                    ->sortable(),

                Tables\Columns\TextColumn::make('scan_method')
                    ->label('Méthode')
                    ->badge()
                    ->formatStateUsing(fn (AttendanceScanMethod $state) => $state->label())
                    ->color(fn (AttendanceScanMethod $state) => $state->color()),

                Tables\Columns\TextColumn::make('location')
                    ->label('Salle')
                    ->badge()
                    ->formatStateUsing(fn (AttendanceLocation $state) => $state->label())
                    ->color(fn (AttendanceLocation $state) => $state->color())
                    ->toggleable(),

                Tables\Columns\TextColumn::make('scannedBy.full_name')
                    ->label('Opérateur')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('scanner_ip')
                    ->label('IP')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('event_date')
                    ->label('Jour')
                    ->options([
                        '2026-06-22' => '22 juin 2026',
                        '2026-06-23' => '23 juin 2026',
                        '2026-06-24' => '24 juin 2026',
                        '2026-06-25' => '25 juin 2026',
                    ])
                    ->query(fn (Builder $q, array $data) => blank($data['value'])
                        ? $q
                        : $q->whereDate('event_date', $data['value'])
                    ),

                Tables\Filters\SelectFilter::make('workshop_id')
                    ->label('Atelier')
                    ->options(fn () => Workshop::pluck('title', 'id'))
                    ->query(fn (Builder $q, array $data) => blank($data['value'])
                        ? $q
                        : $q->whereHas('enrollment', fn ($q) => $q->where('workshop_id', $data['value']))
                    ),

                Tables\Filters\SelectFilter::make('location')
                    ->label('Salle')
                    ->options(collect(AttendanceLocation::cases())->mapWithKeys(
                        fn (AttendanceLocation $l) => [$l->value => $l->label()]
                    )),

                Tables\Filters\SelectFilter::make('scan_method')
                    ->label('Méthode')
                    ->options(collect(AttendanceScanMethod::cases())->mapWithKeys(
                        fn (AttendanceScanMethod $m) => [$m->value => $m->label()]
                    )),
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
