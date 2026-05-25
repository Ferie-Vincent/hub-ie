<?php

namespace App\Filament\Pages;

use App\Models\Application;
use App\Models\Attendance;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ScanEntry extends Page
{
    protected static ?string $navigationIcon  = 'heroicon-o-qr-code';
    protected static ?string $navigationGroup = 'Événement';
    protected static ?int    $navigationSort  = 60;
    protected static ?string $title           = 'Pointage';
    protected static string  $view            = 'filament.pages.scan-entry';

    public string $manualCode = '';
    public ?array $lastScan   = null;
    public string $scanResult = '';

    public function submitManualCode(): void
    {
        $code = trim($this->manualCode);

        if (! preg_match('/^\d{6}$/', $code)) {
            $this->scanResult = 'error';
            $this->lastScan   = ['message' => 'Code invalide. Saisissez exactement 6 chiffres.'];
            Notification::make()->title('Code invalide')->danger()->send();
            return;
        }

        $this->processCheckIn(checkInCode: (int) $code);
        $this->manualCode = '';
    }

    public function processQrToken(string $token): void
    {
        $app = Application::where('qr_token', $token)
            ->where('status', 'accepted')
            ->with('user')
            ->first();

        if (! $app) {
            $this->scanResult = 'error';
            $this->lastScan   = ['message' => 'QR inconnu ou candidature non retenue.'];
            Notification::make()->title('QR invalide')->danger()->send();
            return;
        }

        $this->recordAttendance($app, 'qr');
    }

    private function processCheckIn(int $checkInCode): void
    {
        $app = Application::where('check_in_code', $checkInCode)
            ->where('status', 'accepted')
            ->with('user')
            ->first();

        if (! $app) {
            $this->scanResult = 'error';
            $this->lastScan   = ['message' => 'Code inconnu ou candidature non retenue.'];
            Notification::make()->title('Code inconnu')->danger()->send();
            return;
        }

        $this->recordAttendance($app, 'manual');
    }

    private function recordAttendance(Application $app, string $method): void
    {
        $alreadyCheckedIn = Attendance::where('application_id', $app->id)
            ->whereDate('event_date', today())
            ->exists();

        if ($alreadyCheckedIn) {
            $this->scanResult = 'warning';
            $this->lastScan   = [
                'message'   => 'Déjà pointé aujourd\'hui.',
                'app'       => $app,
            ];
            Notification::make()->title('Déjà pointé aujourd\'hui')->warning()->send();
            return;
        }

        Attendance::create([
            'application_id'     => $app->id,
            'event_date'         => today(),
            'scanned_at'         => now(),
            'scanned_by_user_id' => auth()->id(),
            'scan_method'        => $method,
            'scanner_ip'         => request()->ip(),
        ]);

        $this->scanResult = 'success';
        $this->lastScan   = [
            'message' => 'Pointage enregistré.',
            'app'     => $app,
        ];
        Notification::make()
            ->title("✓ {$app->user->full_name} — {$app->group_label}")
            ->success()
            ->send();
    }
}
