<?php

namespace App\Jobs;

use App\Models\Enrollment;
use App\Services\BadgePdfService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GenerateEnrollmentBadge implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(public readonly Enrollment $enrollment) {}

    public function handle(BadgePdfService $service): void
    {
        $path = $service->generateForEnrollment($this->enrollment);

        $this->enrollment->update(['badge_path' => $path]);
    }

    public function failed(\Throwable $e): void
    {
        Log::error('GenerateEnrollmentBadge failed', [
            'enrollment_id' => $this->enrollment->id,
            'error' => $e->getMessage(),
        ]);
    }
}
