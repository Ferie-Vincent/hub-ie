<?php

namespace App\Filament\Pages;

use App\Enums\AttendanceLocation;
use App\Enums\AttendanceScanMethod;
use App\Enums\EnrollmentStatus;
use App\Models\Attendance;
use App\Models\Enrollment;
use App\Models\Workshop;
use Filament\Pages\Page;
use Illuminate\Support\Carbon;

class AttendanceStats extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationGroup = 'Événement';

    protected static ?int $navigationSort = 62;

    protected static ?string $title = 'Statistiques pointage';

    protected static ?string $navigationLabel = 'Stats présences';

    protected static string $view = 'filament.pages.attendance-stats';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user?->hasRole('super_admin')
            || $user?->hasPermissionTo('scan-attendance')
            || false;
    }

    // ── KPIs ──────────────────────────────────────────────────────────────────

    public int $totalInscrits = 0;

    public int $totalPointes = 0;

    public int $tauxPresence = 0;

    public int $pointesAujourdHui = 0;

    // ── Par jour ──────────────────────────────────────────────────────────────

    /** @var array<string,int> */
    public array $parJour = [];

    // ── Par atelier ───────────────────────────────────────────────────────────

    /** @var array<array{title:string,count:int,pct:int}> */
    public array $parAtelier = [];

    // ── Par salle ─────────────────────────────────────────────────────────────

    /** @var array<array{label:string,count:int,color:string}> */
    public array $parSalle = [];

    // ── Par méthode ───────────────────────────────────────────────────────────

    public int $countQr = 0;

    public int $countCode = 0;

    // ── Derniers scans ────────────────────────────────────────────────────────

    /** @var array<array{name:string,workshop:string,heure:string,method:string}> */
    public array $derniersScans = [];

    // ── Dates événement ───────────────────────────────────────────────────────

    private const EVENT_DATES = ['2026-06-22', '2026-06-23', '2026-06-24', '2026-06-25'];

    private const EVENT_LABELS = ['22 juin', '23 juin', '24 juin', '25 juin'];

    public function mount(): void
    {
        $this->refresh();
    }

    public function refresh(): void
    {
        $now = Carbon::now('Africa/Abidjan');

        // KPIs
        $this->totalInscrits = Enrollment::where('status', EnrollmentStatus::Enrolled->value)->count();
        $this->totalPointes = Attendance::distinct('enrollment_id')->count('enrollment_id');
        $this->tauxPresence = $this->totalInscrits > 0
            ? (int) round($this->totalPointes / $this->totalInscrits * 100)
            : 0;
        $this->pointesAujourdHui = Attendance::whereDate('event_date', $now->toDateString())
            ->distinct('enrollment_id')->count('enrollment_id');

        // Par jour
        $this->parJour = [];
        foreach (self::EVENT_DATES as $i => $date) {
            $this->parJour[self::EVENT_LABELS[$i]] = Attendance::whereDate('event_date', $date)
                ->distinct('enrollment_id')->count('enrollment_id');
        }

        // Par atelier
        $maxAtelier = 1;
        $ateliers = [];
        foreach (Workshop::all() as $ws) {
            $c = Attendance::whereHas(
                'enrollment',
                fn ($q) => $q->where('workshop_id', $ws->id)
            )->distinct('enrollment_id')->count('enrollment_id');
            $ateliers[] = ['title' => $ws->title, 'count' => $c];
            $maxAtelier = max($maxAtelier, $c);
        }
        usort($ateliers, fn ($a, $b) => $b['count'] <=> $a['count']);
        $this->parAtelier = array_map(
            fn ($a) => [...$a, 'pct' => (int) round($a['count'] / $maxAtelier * 100)],
            $ateliers
        );

        // Par salle
        $this->parSalle = [];
        foreach (AttendanceLocation::cases() as $loc) {
            $this->parSalle[] = [
                'label' => $loc->label(),
                'count' => Attendance::where('location', $loc->value)->count(),
                'color' => $loc->color(),
            ];
        }

        // Par méthode
        $this->countQr = Attendance::where('scan_method', AttendanceScanMethod::Qr->value)->count();
        $this->countCode = Attendance::where('scan_method', AttendanceScanMethod::Code->value)->count();

        // Derniers 10 scans
        $this->derniersScans = Attendance::with(['enrollment.user', 'enrollment.workshop'])
            ->latest('scanned_at')
            ->limit(10)
            ->get()
            ->map(fn ($a) => [
                'name' => $a->enrollment?->user?->full_name ?? '—',
                'workshop' => $a->enrollment?->workshop?->title ?? '—',
                'heure' => Carbon::parse($a->scanned_at)->setTimezone('Africa/Abidjan')->format('H:i:s'),
                'method' => $a->scan_method instanceof AttendanceScanMethod
                    ? $a->scan_method->label()
                    : $a->scan_method,
            ])
            ->toArray();
    }
}
