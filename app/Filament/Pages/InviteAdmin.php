<?php

namespace App\Filament\Pages;

use App\Mail\AdminInvited;
use App\Models\AdminInvitation;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class InviteAdmin extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-envelope-open';

    protected static ?string $navigationLabel = 'Inviter un admin';

    protected static ?string $navigationGroup = 'Système';

    protected static ?int $navigationSort = 90;

    protected static string $view = 'filament.pages.invite-admin';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('email')
                    ->label('Adresse e-mail')
                    ->email()
                    ->required()
                    ->unique('admin_invitations', 'email', ignoreRecord: false)
                    ->placeholder('collaborateur@hubimportexport.ci'),

                Select::make('role')
                    ->label('Rôle')
                    ->required()
                    ->options([
                        'super_admin' => 'Super Administrateur',
                        'committee_president' => 'Président du comité',
                        'committee_member' => 'Membre du comité',
                        'admin_dgce' => 'Admin DGCE',
                        'communication' => 'Communication',
                        'agent_entry' => 'Agent de pointage',
                        'reader' => 'Lecteur',
                    ]),
            ])
            ->statePath('data');
    }

    public function invite(): void
    {
        $data = $this->form->getState();

        $invitation = AdminInvitation::create([
            'email' => $data['email'],
            'role' => $data['role'],
            'token' => Str::random(64),
            'invited_by' => auth()->id(),
            'expires_at' => now()->addDays(7),
        ]);

        Mail::to($invitation->email)->send(new AdminInvited($invitation));

        $this->form->fill();

        Notification::make()
            ->title('Invitation envoyée')
            ->body("Un email a été envoyé à {$invitation->email}.")
            ->success()
            ->send();
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->hasRole('super_admin') ?? false;
    }
}
