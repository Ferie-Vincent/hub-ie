<x-guest-layout>
    <x-slot name="title">Connexion</x-slot>

    <h2 class="text-xl font-semibold mb-6" style="font-family:'Fraunces',serif;">Se connecter</h2>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" value="Adresse e-mail" />
            <x-text-input id="email" type="email" name="email"
                          :value="old('email')" required autofocus autocomplete="username"
                          class="block mt-1 w-full" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" value="Mot de passe" />
            <x-text-input id="password" type="password" name="password"
                          required autocomplete="current-password" class="block mt-1 w-full" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between flex-wrap gap-2">
            <label class="flex items-center gap-2 text-sm cursor-pointer"
                   style="color:hsl(var(--blanc-casse)/0.7);">
                <input type="checkbox" name="remember"
                       class="rounded border-gray-600 text-orange-500 focus:ring-orange-500">
                Se souvenir de moi
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}"
                   class="text-sm hover:underline"
                   style="color:hsl(var(--orange-ivoire));">
                    Mot de passe oublié ?
                </a>
            @endif
        </div>

        <button type="submit"
                class="w-full py-3 rounded-lg font-semibold text-sm text-white transition-all hover:opacity-90 active:scale-[0.98]"
                style="background:hsl(var(--orange-ivoire));">
            Se connecter
        </button>

        <p class="text-center text-sm" style="color:hsl(var(--blanc-casse)/0.6);">
            Pas encore de compte ?
            <a href="{{ route('register') }}"
               class="font-medium hover:underline"
               style="color:hsl(var(--orange-ivoire));">
                Créer un compte
            </a>
        </p>
    </form>
</x-guest-layout>
