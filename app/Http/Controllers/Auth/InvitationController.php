<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AdminInvitation;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class InvitationController extends Controller
{
    public function show(string $token): View|RedirectResponse
    {
        $invitation = AdminInvitation::where('token', $token)
            ->whereNull('accepted_at')
            ->first();

        if (! $invitation || $invitation->isExpired()) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Ce lien d\'invitation est invalide ou expiré.']);
        }

        return view('auth.invitation', compact('invitation', 'token'));
    }

    public function store(Request $request, string $token): RedirectResponse
    {
        $invitation = AdminInvitation::where('token', $token)
            ->whereNull('accepted_at')
            ->first();

        if (! $invitation || $invitation->isExpired()) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Ce lien d\'invitation est invalide ou expiré.']);
        }

        $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $invitation->email,
            'password' => Hash::make($request->password),
            'is_active' => true,
        ]);

        $user->markEmailAsVerified();

        $user->assignRole($invitation->role);

        $invitation->update(['accepted_at' => now()]);

        event(new Registered($user));

        Auth::login($user);

        return redirect('/admin');
    }
}
