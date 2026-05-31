<?php

namespace App\Jobs;

use App\Mail\ApplicationAccepted;
use App\Models\Application;
use App\Services\BadgePdfService;
use App\Services\ConvocationPdfService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class GenerateBadgePdf implements ShouldQueue
{
    use Queueable;

    public int $tries   = 3;
    public int $timeout = 120;

    public function __construct(public readonly Application $application) {}

    public function handle(BadgePdfService $badgeService, ConvocationPdfService $convService): void
    {
        $app = $this->application->fresh();

        $badgePath = $badgeService->generate($app);
        $app->update(['badge_path' => $badgePath]);

        $convService->generate($app);

        \Illuminate\Support\Facades\Mail::to($app->user)->send(new ApplicationAccepted($app));
    }
}
