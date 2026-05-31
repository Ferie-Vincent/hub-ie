<?php

namespace App\Providers;

use App\Models\Application;
use App\Models\Attendance;
use App\Models\Evaluation;
use App\Models\News;
use App\Models\Partner;
use App\Models\User;
use App\Observers\ApplicationObserver;
use App\Observers\EvaluationObserver;
use App\Policies\ApplicationPolicy;
use App\Policies\AttendancePolicy;
use App\Policies\EvaluationPolicy;
use App\Policies\NewsPolicy;
use App\Policies\PartnerPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // super_admin bypasses all policy checks
        Gate::before(function (User $user, string $ability) {
            if ($user->hasRole('super_admin')) {
                return true;
            }
        });

        Gate::policy(Application::class, ApplicationPolicy::class);
        Gate::policy(Evaluation::class, EvaluationPolicy::class);
        Gate::policy(Attendance::class, AttendancePolicy::class);
        Gate::policy(News::class, NewsPolicy::class);
        Gate::policy(Partner::class, PartnerPolicy::class);

        Application::observe(ApplicationObserver::class);
        Evaluation::observe(EvaluationObserver::class);
    }
}
