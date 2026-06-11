<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ConversationResource\Pages;
use App\Models\Conversation;
use App\Models\ConversationMessage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ConversationResource extends Resource
{
    protected static ?string $model = Conversation::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?string $navigationGroup = 'Communication';

    protected static ?int $navigationSort = 50;

    protected static ?string $navigationLabel = 'Conversations';

    protected static ?string $modelLabel = 'Conversation';

    protected static ?string $pluralModelLabel = 'Conversations';

    public static function getNavigationBadge(): ?string
    {
        $count = ConversationMessage::whereNull('read_at')
            ->whereHas('conversation')
            ->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): string
    {
        return 'warning';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Conversation')
                ->columns(2)
                ->schema([
                    Forms\Components\Select::make('application_id')
                        ->label('Candidature')
                        ->relationship('application', 'reference_code')
                        ->searchable()
                        ->required(),
                    Forms\Components\Select::make('workshop_id')
                        ->label('Atelier')
                        ->relationship('workshop', 'title')
                        ->searchable()
                        ->nullable(),
                    Forms\Components\Select::make('trainer_user_id')
                        ->label('Formateur assigné')
                        ->relationship('trainer', 'name')
                        ->searchable()
                        ->nullable(),
                    Forms\Components\TextInput::make('subject')
                        ->label('Sujet')
                        ->nullable()
                        ->maxLength(255),
                    Forms\Components\Toggle::make('is_closed')
                        ->label('Conversation fermée'),
                ]),
        ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Infolists\Components\Section::make('Détails')
                ->columns(3)
                ->schema([
                    Infolists\Components\TextEntry::make('application.reference_code')
                        ->label('Référence candidature'),
                    Infolists\Components\TextEntry::make('workshop.title')
                        ->label('Atelier')
                        ->placeholder('—'),
                    Infolists\Components\TextEntry::make('trainer.name')
                        ->label('Formateur')
                        ->placeholder('Équipe Hub IE'),
                    Infolists\Components\TextEntry::make('subject')
                        ->label('Sujet')
                        ->placeholder('—')
                        ->columnSpanFull(),
                    Infolists\Components\IconEntry::make('is_closed')
                        ->label('Fermée')
                        ->boolean(),
                    Infolists\Components\TextEntry::make('last_message_at')
                        ->label('Dernier message')
                        ->dateTime('d/m/Y H:i')
                        ->placeholder('—'),
                ]),

            Infolists\Components\Section::make('Messages')
                ->schema([
                    Infolists\Components\RepeatableEntry::make('messages')
                        ->label('')
                        ->schema([
                            Infolists\Components\TextEntry::make('sender.name')
                                ->label('Expéditeur')
                                ->weight('bold'),
                            Infolists\Components\TextEntry::make('body')
                                ->label('Message')
                                ->columnSpanFull(),
                            Infolists\Components\TextEntry::make('created_at')
                                ->label('Date')
                                ->dateTime('d/m/Y H:i')
                                ->extraAttributes(['class' => 'text-xs text-gray-400']),
                            Infolists\Components\IconEntry::make('read_at')
                                ->label('Lu')
                                ->boolean()
                                ->getStateUsing(fn ($record) => $record->read_at !== null),
                        ])
                        ->columns(2),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->withCount('messages'))
            ->columns([
                Tables\Columns\TextColumn::make('application.reference_code')
                    ->label('Référence')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('participant.name')
                    ->label('Participant')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('workshop.title')
                    ->label('Atelier')
                    ->placeholder('—')
                    ->limit(30),
                Tables\Columns\TextColumn::make('trainer.name')
                    ->label('Formateur')
                    ->placeholder('Équipe Hub IE'),
                Tables\Columns\TextColumn::make('messages_count')
                    ->label('Messages')
                    ->sortable(),
                Tables\Columns\TextColumn::make('last_message_at')
                    ->label('Dernier message')
                    ->since()
                    ->placeholder('—')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_closed')
                    ->label('Fermée')
                    ->boolean(),
            ])
            ->defaultSort('last_message_at', 'desc')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_closed')
                    ->label('Statut')
                    ->trueLabel('Fermées')
                    ->falseLabel('Ouvertes'),
                Tables\Filters\SelectFilter::make('workshop_id')
                    ->label('Atelier')
                    ->relationship('workshop', 'title'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->iconButton(),
                Tables\Actions\Action::make('reply')
                    ->label('Répondre')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('primary')
                    ->iconButton()
                    ->visible(fn (Conversation $record): bool => ! $record->is_closed)
                    ->form([
                        Forms\Components\Textarea::make('body')
                            ->label('Votre réponse')
                            ->required()
                            ->minLength(3)
                            ->maxLength(2000)
                            ->rows(4),
                    ])
                    ->action(function (Conversation $record, array $data): void {
                        ConversationMessage::create([
                            'conversation_id' => $record->id,
                            'sender_id' => auth()->id(),
                            'body' => $data['body'],
                        ]);

                        Notification::make()
                            ->title('Réponse envoyée.')
                            ->success()
                            ->send();
                    }),
                Tables\Actions\Action::make('close')
                    ->label('Clôturer')
                    ->icon('heroicon-o-lock-closed')
                    ->color('gray')
                    ->iconButton()
                    ->requiresConfirmation()
                    ->visible(fn (Conversation $record): bool => ! $record->is_closed)
                    ->action(fn (Conversation $record) => $record->update(['is_closed' => true])),
                Tables\Actions\Action::make('reopen')
                    ->label('Rouvrir')
                    ->icon('heroicon-o-lock-open')
                    ->color('success')
                    ->iconButton()
                    ->visible(fn (Conversation $record): bool => $record->is_closed)
                    ->action(fn (Conversation $record) => $record->update(['is_closed' => false])),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('close_selected')
                    ->label('Clôturer la sélection')
                    ->icon('heroicon-o-lock-closed')
                    ->requiresConfirmation()
                    ->action(fn ($records) => $records->each->update(['is_closed' => true]))
                    ->deselectRecordsAfterCompletion(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListConversations::route('/'),
            'create' => Pages\CreateConversation::route('/create'),
            'view' => Pages\ViewConversation::route('/{record}'),
        ];
    }
}
