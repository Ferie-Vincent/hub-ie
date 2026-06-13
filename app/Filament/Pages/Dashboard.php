<?php

namespace App\Filament\Pages;

use App\Enums\ApplicationCategory;
use App\Enums\ApplicationStatus;
use App\Enums\EnrollmentStatus;
use App\Models\Application;
use App\Models\Attendance;
use App\Models\Enrollment;
use Filament\Pages\Dashboard as BaseDashboard;
use Illuminate\Support\Carbon;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar-square';

    protected static string $view = 'filament.pages.hub-dashboard';

    protected static string $routePath = '/';

    protected static ?int $navigationSort = -2;

    // KPIs
    public int $totalSubmitted = 0;

    public int $delta24h = 0;

    public int $enrolled = 0;

    public int $waitlisted = 0;

    public int $quota = 180;

    public int $presentToday = 0;

    public int $daysToEvent = 0;

    // Chart data
    public array $sparklineCumul = [];

    public array $sparklineLabels = [];

    public array $genderData = ['F' => 0, 'M' => 0, 'X' => 0];

    public int $genderTotal = 0;

    public array $ageData = [];

    public array $categoryData = [];

    public array $workshopData = [];

    public array $attendanceData = [];

    public array $geographyData = [];

    public array $referralData = [];

    public int $women = 0;

    public int $young = 0;

    public int $womenPct = 0;

    public int $youngPct = 0;

    public function mount(): void
    {
        $exclude = [ApplicationStatus::Draft->value, ApplicationStatus::Withdrawn->value];
        $now = Carbon::now();

        // --- KPIs ---
        $this->totalSubmitted = Application::whereNotIn('status', $exclude)->count();
        $this->delta24h = Application::where('submitted_at', '>=', $now->copy()->subDay())->count();
        $this->enrolled = Enrollment::where('status', EnrollmentStatus::Enrolled->value)->count();
        $this->waitlisted = Enrollment::where('status', EnrollmentStatus::Waitlisted->value)->count();
        $this->presentToday = Attendance::whereDate('event_date', Carbon::today())
            ->distinct('enrollment_id')->count('enrollment_id');
        $this->daysToEvent = max(0, (int) $now->diffInDays(Carbon::parse('2026-06-22'), false));

        // --- Cumulative 30-day sparkline ---
        $cumul = 0;
        $labels = [];
        $values = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $daily = Application::whereDate('submitted_at', $date)->count();
            $cumul += $daily;
            $values[] = $cumul;
            $labels[] = $i % 5 === 0 ? $date->format('d/m') : '';
        }
        $this->sparklineCumul = $values;
        $this->sparklineLabels = $labels;

        // --- Gender (all submitted candidates) ---
        $joinBase = Application::whereNotIn('status', $exclude)
            ->join('users', 'users.id', '=', 'applications.user_id');
        $this->genderData = [
            'F' => (clone $joinBase)->where('users.gender', 'F')->count(),
            'M' => (clone $joinBase)->where('users.gender', 'M')->count(),
            'X' => (clone $joinBase)->where(fn ($q) => $q->where('users.gender', 'X')->orWhereNull('users.gender'))->count(),
        ];
        $this->genderTotal = array_sum($this->genderData);

        // --- Age groups (all submitted candidates) ---
        $ageBase = Application::whereNotIn('status', $exclude)
            ->join('users', 'users.id', '=', 'applications.user_id')
            ->whereNotNull('users.birth_date');
        $this->ageData = [
            '< 25 ans' => (clone $ageBase)->where('users.birth_date', '>=', $now->copy()->subYears(25)->toDateString())->count(),
            '25–34 ans' => (clone $ageBase)->where('users.birth_date', '<', $now->copy()->subYears(25)->toDateString())->where('users.birth_date', '>=', $now->copy()->subYears(35)->toDateString())->count(),
            '35–44 ans' => (clone $ageBase)->where('users.birth_date', '<', $now->copy()->subYears(35)->toDateString())->where('users.birth_date', '>=', $now->copy()->subYears(45)->toDateString())->count(),
            '45–54 ans' => (clone $ageBase)->where('users.birth_date', '<', $now->copy()->subYears(45)->toDateString())->where('users.birth_date', '>=', $now->copy()->subYears(55)->toDateString())->count(),
            '55 ans+' => (clone $ageBase)->where('users.birth_date', '<', $now->copy()->subYears(55)->toDateString())->count(),
        ];

        // --- Category ---
        $catData = [];
        foreach (ApplicationCategory::cases() as $cat) {
            $count = Application::where('category', $cat->value)
                ->whereNotIn('status', $exclude)->count();
            if ($count > 0) {
                $catData[$cat->label()] = $count;
            }
        }
        arsort($catData);
        $this->categoryData = $catData;

        // --- Workshops: real enrollment counts (not application choices) ---
        $this->workshopData = Enrollment::where('enrollments.status', EnrollmentStatus::Enrolled->value)
            ->join('workshops', 'workshops.id', '=', 'enrollments.workshop_id')
            ->selectRaw('workshops.title, COUNT(*) as total')
            ->groupBy('workshops.id', 'workshops.title')
            ->orderByDesc('total')
            ->pluck('total', 'title')
            ->toArray();

        // --- Attendance (4 event days) ---
        $this->attendanceData = [];
        foreach (['2026-06-22', '2026-06-23', '2026-06-24', '2026-06-25'] as $date) {
            $this->attendanceData[] = Attendance::whereDate('event_date', $date)
                ->distinct('enrollment_id')->count('enrollment_id');
        }

        // --- Geography ---
        $this->geographyData = Application::whereNotIn('status', $exclude)
            ->join('users', 'users.id', '=', 'applications.user_id')
            ->whereNotNull('users.city')
            ->selectRaw('users.city as city, COUNT(*) as total')
            ->groupBy('users.city')
            ->orderByDesc('total')
            ->limit(10)
            ->pluck('total', 'city')
            ->toArray();

        // --- Referral ---
        $this->referralData = Application::whereNotIn('status', $exclude)
            ->whereNotNull('referral_source')
            ->selectRaw('referral_source, COUNT(*) as total')
            ->groupBy('referral_source')
            ->orderByDesc('total')
            ->limit(6)
            ->pluck('total', 'referral_source')
            ->toArray();

        // --- Quota inclusivité (based on confirmed enrolled) ---
        $enrolledJoin = Enrollment::where('enrollments.status', EnrollmentStatus::Enrolled->value)
            ->join('users', 'users.id', '=', 'enrollments.user_id');
        $this->women = (clone $enrolledJoin)->where('users.gender', 'F')->count();
        $this->young = (clone $enrolledJoin)->whereNotNull('users.birth_date')
            ->where('users.birth_date', '>=', $now->copy()->subYears(35)->toDateString())->count();
        $this->womenPct = $this->quota > 0 ? round($this->women / $this->quota * 100) : 0;
        $this->youngPct = $this->quota > 0 ? round($this->young / $this->quota * 100) : 0;
    }

    public function getSubheading(): ?string
    {
        return 'J-'.$this->daysToEvent.' · Évènement du 22 au 25 juin 2026, Abidjan';
    }

    public function getWidgets(): array
    {
        return [];
    }
}
