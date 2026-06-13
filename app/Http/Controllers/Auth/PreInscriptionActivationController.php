<?php

namespace App\Http\Controllers\Auth;

use App\Enums\ApplicationStatus;
use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Edition;
use App\Models\PreInscription;
use App\Models\User;
use App\Models\Workshop;
use App\Services\ApplicationStatusService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class PreInscriptionActivationController extends Controller
{
    // Maps pre-inscription atelier slug → Workshop slug
    private const WORKSHOP_MAP = [
        'zlecaf-cedeao' => 'zlecaf-cedeao',
        'financement-garanties' => 'financement-international',
        'commerce-electronique' => 'commerce-electronique',
        'conformite-qualite' => 'conformite-qualite',
    ];

    public function show(string $token): View|RedirectResponse
    {
        $preInscription = PreInscription::where('invitation_token', $token)
            ->whereNull('user_id')
            ->first();

        if (! $preInscription || $this->isExpired($preInscription)) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Ce lien d\'activation est invalide ou expiré.']);
        }

        return view('auth.pre-inscription-activation', compact('preInscription', 'token'));
    }

    public function store(Request $request, string $token): RedirectResponse
    {
        $preInscription = PreInscription::where('invitation_token', $token)
            ->whereNull('user_id')
            ->first();

        if (! $preInscription || $this->isExpired($preInscription)) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Ce lien d\'activation est invalide ou expiré.']);
        }

        $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'first_name' => $preInscription->prenom,
            'last_name' => $preInscription->nom,
            'email' => $preInscription->email,
            'phone' => $preInscription->telephone,
            'password' => Hash::make($request->password),
            'is_active' => true,
        ]);

        $user->markEmailAsVerified();
        $user->assignRole('candidate');

        $preInscription->update(['user_id' => $user->id, 'invitation_token' => null]);

        $this->createAndAcceptApplication($user, $preInscription);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('candidate.dashboard');
    }

    private function createAndAcceptApplication(User $user, PreInscription $preInscription): void
    {
        $edition = Edition::current();

        if (! $edition) {
            return;
        }

        $application = Application::create([
            'edition_id' => $edition->id,
            'user_id' => $user->id,
            'status' => ApplicationStatus::Received,
            'reference_code' => $this->generateReferenceCode(),
            'organization_name' => $preInscription->entreprise,
            'sector' => $preInscription->secteur,
            'position' => $preInscription->poste,
            'motivation' => $preInscription->motivation_projet,
            'chosen_workshops' => $preInscription->atelier ? [$preInscription->atelier] : [],
            'submitted_at' => now(),
            'rgpd_consent' => true,
        ]);

        // Link workshop
        $workshopSlug = self::WORKSHOP_MAP[$preInscription->atelier] ?? null;
        if ($workshopSlug) {
            $workshop = Workshop::where('slug', $workshopSlug)->first();
            if ($workshop) {
                $application->workshops()->attach($workshop->id);
            }
        }

        // Auto-accept if spots available, otherwise waitlist
        $acceptedCount = Application::where('edition_id', $edition->id)
            ->where('status', ApplicationStatus::Accepted->value)
            ->count();

        $targetStatus = ($edition->max_participants && $acceptedCount >= $edition->max_participants)
            ? ApplicationStatus::Waitlisted
            : ApplicationStatus::Accepted;

        app(ApplicationStatusService::class)->transition($application, $targetStatus);
    }

    private function generateReferenceCode(): string
    {
        do {
            $code = 'HIE-2026-'.strtoupper(substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 6));
        } while (Application::where('reference_code', $code)->exists());

        return $code;
    }

    private function isExpired(PreInscription $preInscription): bool
    {
        return $preInscription->invitation_sent_at?->addDays(7)->isPast() ?? true;
    }
}
