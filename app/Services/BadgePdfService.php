<?php

namespace App\Services;

use App\Models\Application;
use App\Models\Enrollment;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Spatie\LaravelPdf\Facades\Pdf;

class BadgePdfService
{
    public function generate(Application $application): string
    {
        $application->loadMissing('user', 'workshops');

        $qrSvg = QrCode::size(200)
            ->style('round')
            ->errorCorrection('H')
            ->generate($application->qr_signed_url ?? '');

        $path = "badges/badge-{$application->reference_code}.pdf";

        Pdf::view('pdf.badge', [
            'application' => $application,
            'qrSvg' => $qrSvg,
        ])
            ->paperSize(100, 140, 'mm')
            ->save(Storage::disk('local')->path($path));

        return $path;
    }

    public function generateForEnrollment(Enrollment $enrollment): string
    {
        $enrollment->loadMissing('user', 'workshop');

        $expiry = Carbon::parse('2026-06-25 23:59:59', 'Africa/Abidjan');
        $scanUrl = URL::temporarySignedRoute('enrollment.scan', $expiry, [
            'token' => $enrollment->qr_token,
        ]);

        $qrSvg = QrCode::size(200)
            ->style('round')
            ->errorCorrection('H')
            ->generate($scanUrl);

        // Purge ancien badge si présent
        if ($enrollment->badge_path) {
            $this->purgeOrphan($enrollment->badge_path);
        }

        $path = "badges/enrollment-{$enrollment->id}.pdf";

        Pdf::view('pdf.enrollment-badge', [
            'enrollment' => $enrollment,
            'user' => $enrollment->user,
            'workshop' => $enrollment->workshop,
            'qrSvg' => $qrSvg,
        ])
            ->paperSize(100, 140, 'mm')
            ->save(Storage::disk('local')->path($path));

        return $path;
    }

    public function purgeOrphan(string $path): void
    {
        if (Storage::disk('local')->exists($path)) {
            Storage::disk('local')->delete($path);
        }
    }
}
