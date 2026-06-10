<?php

namespace App\Providers;

use App\Jobs\CheckExpiredWaitlistOffers;
use App\Models\Application;
use App\Models\Attendance;
use App\Models\ConversationMessage;
use App\Models\Evaluation;
use App\Models\News;
use App\Models\Partner;
use App\Models\User;
use App\Models\WorkshopCourseFile;
use App\Observers\ApplicationObserver;
use App\Observers\ConversationMessageObserver;
use App\Observers\EvaluationObserver;
use App\Observers\WorkshopCourseFileObserver;
use App\Policies\ApplicationPolicy;
use App\Policies\AttendancePolicy;
use App\Policies\EvaluationPolicy;
use App\Policies\NewsPolicy;
use App\Policies\PartnerPolicy;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        $this->callAfterResolving(Schedule::class, function (Schedule $schedule) {
            $schedule->job(CheckExpiredWaitlistOffers::class)
                ->dailyAt('08:00')
                ->timezone('Africa/Abidjan')
                ->withoutOverlapping();
        });
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
        WorkshopCourseFile::observe(WorkshopCourseFileObserver::class);
        ConversationMessage::observe(ConversationMessageObserver::class);
    }
}
