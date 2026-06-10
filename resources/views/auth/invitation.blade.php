<x-guest-layout>
<x-slot name="title">Créer votre compte</x-slot>

<div style="margin-bottom:2rem;">
    <p style="font-size:0.75rem; font-weight:700; letter-spacing:0.12em; text-transform:uppercase; color:hsl(var(--vert-ivoire)); margin:0 0 0.5rem;">
        Invitation
    </p>
    <h1 style="font-family:'Fraunces',serif; font-size:2rem; font-weight:900; line-height:1.1; margin:0 0 0.75rem; color:hsl(var(--noir-ivoire));">
        Bienvenue.
    </h1>
    <p style="font-size:0.9rem; color:rgba(15,12,8,0.55); margin:0;">
        Vous avez été invité en tant que <strong>{{ $invitation->role }}</strong>.<br>
        Créez votre mot de passe pour activer votre compte.
    </p>
</div>

<form method="POST" action="{{ route('invitation.store', $token) }}">
    @csrf

    <div style="margin-bottom:1.5rem;">
        <label class="auth-label">Adresse e-mail</label>
        <input type="email" value="{{ $invitation->email }}"
               class="auth-input" readonly
               style="opacity:0.6; cursor:not-allowed;">
    </div>

    <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1.5rem;">
        <div>
            <label class="auth-label" for="first_name">Prénom <span style="color:#ef4444;">*</span></label>
            <input id="first_name" type="text" name="first_name"
                   value="{{ old('first_name') }}"
                   class="auth-input"
                   placeholder="Kouamé"
                   required autofocus>
            @error('first_name')
            <p class="auth-error">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="auth-label" for="last_name">Nom <span style="color:#ef4444;">*</span></label>
            <input id="last_name" type="text" name="last_name"
                   value="{{ old('last_name') }}"
                   class="auth-input"
                   placeholder="DIALLO">
            @error('last_name')
            <p class="auth-error">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div style="margin-bottom:1.5rem;">
        <label class="auth-label" for="password">Mot de passe <span style="color:#ef4444;">*</span></label>
        <input id="password" type="password" name="password"
               class="auth-input"
               placeholder="••••••••"
               required autocomplete="new-password">
        @error('password')
        <p class="auth-error">{{ $message }}</p>
        @enderror
    </div>

    <div style="margin-bottom:2rem;">
        <label class="auth-label" for="password_confirmation">Confirmer le mot de passe <span style="color:#ef4444;">*</span></label>
        <input id="password_confirmation" type="password" name="password_confirmation"
               class="auth-input"
               placeholder="••••••••"
               required autocomplete="new-password">
    </div>

    <button type="submit" class="auth-btn">
        Activer mon compte
    </button>

    <p style="text-align:center; margin-top:1.75rem; font-size:0.8125rem; color:rgba(15,12,8,0.40);">
        Déjà un compte ?
        <a href="{{ route('login') }}"
           style="font-weight:600; color:hsl(var(--vert-ivoire)); text-decoration:none;">
            Se connecter
        </a>
    </p>
</form>
</x-guest-layout>
