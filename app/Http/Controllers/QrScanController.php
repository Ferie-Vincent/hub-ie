<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Attendance;

class QrScanController extends Controller
{
    public function handle(string $token)
    {
        $application = Application::where('qr_token', $token)
            ->where('status', 'accepted')
            ->with('user')
            ->firstOrFail();

        $alreadyCheckedIn = Attendance::where('application_id', $application->id)
            ->whereDate('event_date', today())
            ->exists();

        if (! $alreadyCheckedIn) {
            Attendance::create([
                'application_id' => $application->id,
                'event_date'     => today(),
                'scanned_at'     => now(),
                'scan_method'    => 'qr',
                'scanner_ip'     => request()->ip(),
            ]);
        }

        return view('public.scan-confirmation', [
            'application'      => $application,
            'alreadyCheckedIn' => $alreadyCheckedIn,
        ]);
    }
}
