<?php

namespace App\Http\Controllers;

use App\Enums\EnrollmentStatus;
use App\Models\Enrollment;
use App\Services\BadgePdfService;
use App\Services\CancellationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class EnrollmentController extends Controller
{
    public function __construct(private readonly CancellationService $cancellation) {}

    public function downloadBadge(Request $request, BadgePdfService $badgeService): Response|RedirectResponse
    {
        $enrollment = $request->user()
            ->enrollment()
            ->where('status', EnrollmentStatus::Enrolled->value)
            ->first();

        if (! $enrollment || ! $enrollment->isBadgeValid()) {
            return redirect()->route('participant.badge')
                ->with('error', 'Votre badge n\'est pas encore disponible.');
        }

        // Génération à la volée si le PDF n'a pas encore été produit
        if (! $enrollment->hasBadge()) {
            try {
                $path = $badgeService->generateForEnrollment($enrollment);
                $enrollment->update(['badge_path' => $path]);
                $enrollment->refresh();
            } catch (\Throwable $e) {
                return redirect()->route('participant.badge')
                    ->with('error', 'Génération du badge impossible. Veuillez réessayer.');
            }
        }

        $filename = 'badge-hub-ie-2026.pdf';

        return response(Storage::disk('local')->get($enrollment->badge_path), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }

    public function showCancelPage(Request $request, string $token)
    {
        abort_unless($request->hasValidSignature(), 403);

        if (! $this->cancellation->canCancel()) {
            return view('enrollment.cancel-expired', [
                'deadline' => $this->cancellation->deadlineLabel(),
            ]);
        }

        return view('enrollment.cancel-confirm', [
            'token' => $token,
            'deadline' => $this->cancellation->deadlineLabel(),
        ]);
    }

    public function confirmCancel(Request $request, string $token)
    {
        abort_unless($request->hasValidSignature(), 403);

        try {
            $enrollment = $this->cancellation->cancelByToken($token);
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return view('enrollment.cancel-success', [
            'workshop' => $enrollment->workshop,
        ]);
    }

    public function scan(Request $request, string $token)
    {
        abort_unless($request->hasValidSignature(), 403);

        $enrollment = Enrollment::where('qr_token', $token)->firstOrFail();

        if (! $enrollment->isBadgeValid()) {
            return response()->json([
                'status' => 'invalid',
                'message' => 'Inscription annulée — accès refusé.',
            ], 403);
        }

        return response()->json([
            'status' => 'valid',
            'name' => $enrollment->user->full_name,
            'workshop' => $enrollment->workshop->title,
        ]);
    }
}
