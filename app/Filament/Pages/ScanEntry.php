<?php

namespace App\Filament\Pages;

use App\Enums\AttendanceScanMethod;
use App\Enums\BadgeStatus;
use App\Enums\EnrollmentStatus;
use App\Models\Attendance;
use App\Models\Enrollment;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ScanEntry extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-qr-code';

    protected static ?string $navigationGroup = 'Événement';

    protected static ?int $navigationSort = 30;

    protected static ?string $title = 'Pointage';

    protected static string $view = 'filament.pages.scan-entry';

    public static function canAccess(): bool
    {
        $user = auth()->user();
        if (! $user) {
            return false;
        }
        if ($user->hasRole('super_admin')) {
            return true;
        }

        return $user->hasPermissionTo('scan-attendance');
    }

    public string $manualCode = '';

    public ?array $lastScan = null;

    public string $scanResult = '';

    public string $location = 'CGECI';

    public function submitManualCode(): void
    {
        $code = trim($this->manualCode);

        if (! preg_match('/^\d{6}$/', $code)) {
            $this->scanResult = 'error';
            $this->lastScan = ['message' => 'Code invalide. Saisissez exactement 6 chiffres.'];
            Notification::make()->title('Code invalide')->danger()->send();

            return;
        }

        $this->processCheckIn((int) $code);
        $this->manualCode = '';
    }

    public function processQrToken(string $token): void
    {
        $enrollment = Enrollment::where('qr_token', $token)
            ->where('status', EnrollmentStatus::Enrolled->value)
            ->with('user', 'workshop')
            ->first();

        if (! $enrollment) {
            $this->scanResult = 'error';
            $this->lastScan = ['message' => 'QR inconnu ou inscription non valide.'];
            Notification::make()->title('QR invalide')->danger()->send();

            return;
        }

        if ($enrollment->badge_status !== BadgeStatus::Valid) {
            $this->scanResult = 'error';
            $this->lastScan = ['message' => 'Badge invalidé — accès refusé.'];
            Notification::make()->title('Badge invalidé')->danger()->send();

            return;
        }

        $this->recordAttendance($enrollment, AttendanceScanMethod::Qr);
    }

    private function processCheckIn(int $checkInCode): void
    {
        $enrollment = Enrollment::where('check_in_code', $checkInCode)
            ->where('status', EnrollmentStatus::Enrolled->value)
            ->with('user', 'workshop')
            ->first();

        if (! $enrollment) {
            $this->scanResult = 'error';
            $this->lastScan = ['message' => 'Code inconnu ou inscription non retenue.'];
            Notification::make()->title('Code inconnu')->danger()->send();

            return;
        }

        if ($enrollment->badge_status !== BadgeStatus::Valid) {
            $this->scanResult = 'error';
            $this->lastScan = ['message' => 'Badge invalidé — accès refusé.'];
            Notification::make()->title('Badge invalidé')->danger()->send();

            return;
        }

        $this->recordAttendance($enrollment, AttendanceScanMethod::Code);
    }

    private function recordAttendance(Enrollment $enrollment, AttendanceScanMethod $method): void
    {
        $alreadyCheckedIn = Attendance::where('enrollment_id', $enrollment->id)
            ->whereDate('event_date', today())
            ->exists();

        if ($alreadyCheckedIn) {
            $this->scanResult = 'warning';
            $this->lastScan = [
                'message' => 'Déjà pointé aujourd\'hui.',
                'name' => $enrollment->user->full_name,
                'workshop' => $enrollment->workshop->title,
            ];
            Notification::make()->title('Déjà pointé aujourd\'hui')->warning()->send();

            return;
        }

        Attendance::create([
            'enrollment_id' => $enrollment->id,
            'event_date' => today(),
            'scanned_at' => now(),
            'scanned_by_user_id' => auth()->id(),
            'location' => $this->location,
            'scan_method' => $method,
            'scanner_ip' => request()->ip(),
        ]);

        $this->scanResult = 'success';
        $this->lastScan = [
            'message' => 'Pointage enregistré.',
            'name' => $enrollment->user->full_name,
            'workshop' => $enrollment->workshop->title,
            'check_in_code' => $enrollment->check_in_code,
        ];
        Notification::make()
            ->title("✓ {$enrollment->user->full_name} — {$enrollment->workshop->title}")
            ->success()
            ->send();
    }
}
