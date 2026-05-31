<?php

namespace App\Http\Controllers;

use App\Enums\ApplicationStatus;
use App\Models\Application;
use App\Services\ApplicationStatusService;
use App\Services\BadgePdfService;
use App\Services\ConvocationPdfService;
use App\Services\QrCodeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

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

    public function qrCode(Application $application, QrCodeService $qrService): Response
    {
        $this->authorize('view', $application);

        abort_unless($application->status === ApplicationStatus::Accepted, 403, 'QR code disponible après sélection.');

        if (! $application->qr_token) {
            abort(404, 'QR code non encore généré.');
        }

        $svg = QrCode::size(300)->style('round')->errorCorrection('H')
            ->generate($qrService->generateSignedUrl($application->qr_token));

        return response($svg, 200, ['Content-Type' => 'image/svg+xml']);
    }

    public function downloadBadge(Application $application, BadgePdfService $badgeService): Response
    {
        $this->authorize('view', $application);

        abort_unless($application->status === ApplicationStatus::Accepted, 403, 'Badge disponible après sélection.');

        if (! $application->badge_path || ! Storage::disk('local')->exists($application->badge_path)) {
            $path = $badgeService->generate($application);
            $application->update(['badge_path' => $path]);
        }

        $content = Storage::disk('local')->get($application->badge_path);

        return response($content, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "inline; filename=\"badge-{$application->reference_code}.pdf\"",
        ]);
    }

    public function downloadConvocation(Application $application, ConvocationPdfService $convService): Response
    {
        $this->authorize('view', $application);

        abort_unless($application->status === ApplicationStatus::Accepted, 403, 'Convocation disponible après sélection.');

        $path = "convocations/convocation-{$application->reference_code}.pdf";
        if (! Storage::disk('local')->exists($path)) {
            $convService->generate($application);
        }

        $content = Storage::disk('local')->get($path);

        return response($content, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "inline; filename=\"convocation-{$application->reference_code}.pdf\"",
        ]);
    }
}
