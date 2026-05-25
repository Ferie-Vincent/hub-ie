<?php

namespace App\Services;

use App\Models\Application;
use Illuminate\Support\Facades\Storage;
use Spatie\LaravelPdf\Facades\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

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
            'qrSvg'       => $qrSvg,
        ])
        ->format([100, 140]) // mm
        ->save(Storage::disk('local')->path($path));

        return $path;
    }
}
