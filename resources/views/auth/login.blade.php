<x-guest-layout>
<x-slot name="title">Connexion</x-slot>

<x-auth-session-status class="mb-5" :status="session('status')" />

<form method="POST" action="{{ route('login') }}">
    @csrf

    <div style="margin-bottom: 2rem;">
        <label class="auth-label" for="email">Adresse e-mail <span style="color:#ef4444;">*</span></label>
        <input id="email" type="email" name="email"
               value="{{ old('email') }}"
               class="auth-input"
               placeholder="amara@entreprise.ci"
               required autofocus autocomplete="username">
        @error('email')
        <p class="auth-error">{{ $message }}</p>
        @enderror
    </div>

    <div style="margin-bottom: 1.5rem;">
        <label class="auth-label" for="password">Mot de passe <span style="color:#ef4444;">*</span></label>
        <input id="password" type="password" name="password"
               class="auth-input"
               placeholder="••••••••"
               required autocomplete="current-password">
        @error('password')
        <p class="auth-error">{{ $message }}</p>
        @enderror
    </div>

    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:2rem; gap:1rem; flex-wrap:wrap;">
        <label style="display:flex; align-items:center; gap:0.5rem; cursor:pointer; font-size:0.8125rem; color:rgba(15,12,8,0.50);">
            <input type="checkbox" name="remember"
                   style="width:14px; height:14px; accent-color:hsl(var(--vert-ivoire));">
            Se souvenir de moi
        </label>
        @if (Route::has('password.request'))
        <a href="{{ route('password.request') }}"
           style="font-size:0.8125rem; font-weight:600; color:hsl(var(--vert-ivoire)); text-decoration:none;">
            Mot de passe oublié ?
        </a>
        @endif
    </div>

    <button type="submit" class="auth-btn">
        Se connecter
    </button>

    <p style="text-align:center; margin-top:1.75rem; font-size:0.8125rem; color:rgba(15,12,8,0.40);">
        Pas encore de compte ?
        <a href="{{ route('inscription') }}"
           style="font-weight:600; color:hsl(var(--vert-ivoire)); text-decoration:none;">
            S'inscrire
        </a>
    </p>
</form>
</x-guest-layout>
