<?php

namespace App\Http\Controllers;

use App\Enums\ApplicationStatus;
use App\Models\Application;
use App\Services\ApplicationStatusService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class ApplicationController extends Controller
{
    public function withdraw(ApplicationStatusService $service): RedirectResponse
    {
        $app = Application::where('user_id', auth()->id())
            ->whereNotIn('status', [
                ApplicationStatus::Withdrawn->value,
                ApplicationStatus::Rejected->value,
            ])
            ->firstOrFail();

        $service->transition($app, ApplicationStatus::Withdrawn);

        return redirect()->route('candidate.dashboard')
            ->with('status_flash', 'Votre candidature a été retirée.');
    }

    public function qrCode(Application $application): Response
    {
        $this->authorize('view', $application);

        // Phase 7: generate QR image via QrCodeService
        // Placeholder: return 404 until Phase 7
        abort(404, 'QR code disponible après confirmation de votre sélection.');
    }

    public function downloadBadge(Application $application): Response
    {
        $this->authorize('view', $application);

        abort(404, 'Badge disponible après confirmation de votre sélection.');
    }

    public function downloadConvocation(Application $application): Response
    {
        $this->authorize('view', $application);

        abort(404, 'Convocation disponible après confirmation de votre sélection.');
    }
}
