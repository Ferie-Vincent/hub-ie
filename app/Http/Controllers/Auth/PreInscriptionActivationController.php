<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PreInscription;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class PreInscriptionActivationController extends Controller
{
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

        $preInscription->update(['user_id' => $user->id]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('candidate.dashboard');
    }

    private function isExpired(PreInscription $preInscription): bool
    {
        return $preInscription->invitation_sent_at?->addDays(7)->isPast() ?? true;
    }
}
