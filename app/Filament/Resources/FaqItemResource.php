<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FaqItemResource\Pages;
use App\Models\FaqItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FaqItemResource extends Resource
{
    protected static ?string $model              = FaqItem::class;
    protected static ?string $navigationIcon     = 'heroicon-o-question-mark-circle';
    protected static ?string $navigationGroup    = 'Contenu';
    protected static ?int    $navigationSort     = 24;
    protected static ?string $navigationLabel    = 'FAQ';
    protected static ?string $modelLabel         = 'Question FAQ';
    protected static ?string $pluralModelLabel   = 'Questions FAQ';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make()->schema([
                Forms\Components\TextInput::make('question')
                    ->label('Question')->required()->maxLength(500)->columnSpanFull(),
                Forms\Components\Textarea::make('answer')
                    ->label('Réponse')->required()->rows(5)->columnSpanFull(),
                Forms\Components\TextInput::make('category')
                    ->label('Catégorie')->maxLength(100),
                Forms\Components\TextInput::make('display_order')
                    ->label('Ordre')->integer()->default(0),
                Forms\Components\Toggle::make('is_published')
                    ->label('Publié'),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('display_order')->label('#')->sortable()->width('50px'),
                Tables\Columns\TextColumn::make('question')->label('Question')->searchable()->limit(80),
                Tables\Columns\TextColumn::make('category')->label('Catégorie')->badge()->toggleable(),
                Tables\Columns\IconColumn::make('is_published')->label('Publié')->boolean(),
            ])
            ->defaultSort('display_order')
            ->reorderable('display_order')
            ->actions([Tables\Actions\EditAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListFaqItems::route('/'),
            'create' => Pages\CreateFaqItem::route('/create'),
            'edit'   => Pages\EditFaqItem::route('/{record}/edit'),
        ];
    }
}
