<?php

namespace App\Services;

use App\Models\Application;
use Illuminate\Support\Facades\Storage;
use Spatie\LaravelPdf\Facades\Pdf;

class ConvocationPdfService
{
    public function generate(Application $application): string
    {
        $application->loadMissing('user', 'workshops');

        $path = "convocations/convocation-{$application->reference_code}.pdf";

        Pdf::view('pdf.convocation', ['application' => $application])
            ->format('A4')
            ->save(Storage::disk('local')->path($path));

        return $path;
    }
}
