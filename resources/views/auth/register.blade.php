<x-guest-layout>
    <x-slot name="title">Créer un compte</x-slot>

    <h2 class="text-xl font-semibold mb-2" style="font-family:'Fraunces',serif;">Créer un compte</h2>
    <p class="text-sm mb-6" style="color:hsl(var(--blanc-casse)/0.6);">
        Pour candidater au Hub Import-Export 2026
    </p>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <div class="grid grid-cols-2 gap-4">
            <div>
                <x-input-label for="first_name" value="Prénom" />
                <x-text-input id="first_name" type="text" name="first_name"
                              :value="old('first_name')" required autofocus autocomplete="given-name"
                              class="block mt-1 w-full" />
                <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="last_name" value="Nom" />
                <x-text-input id="last_name" type="text" name="last_name"
                              :value="old('last_name')" required autocomplete="family-name"
                              class="block mt-1 w-full" />
                <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
            </div>
        </div>

        <div>
            <x-input-label for="email" value="Adresse e-mail" />
            <x-text-input id="email" type="email" name="email"
                          :value="old('email')" required autocomplete="username"
                          class="block mt-1 w-full" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" value="Mot de passe" />
            <x-text-input id="password" type="password" name="password"
                          required autocomplete="new-password" class="block mt-1 w-full" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password_confirmation" value="Confirmer le mot de passe" />
            <x-text-input id="password_confirmation" type="password" name="password_confirmation"
                          required autocomplete="new-password" class="block mt-1 w-full" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <button type="submit"
                class="w-full py-3 rounded-lg font-semibold text-sm text-white transition-all hover:opacity-90 active:scale-[0.98] mt-2"
                style="background:hsl(var(--orange-ivoire));">
            Créer mon compte
        </button>

        <p class="text-center text-sm" style="color:hsl(var(--blanc-casse)/0.6);">
            Déjà inscrit ?
            <a href="{{ route('login') }}"
               class="font-medium hover:underline"
               style="color:hsl(var(--orange-ivoire));">
                Se connecter
            </a>
        </p>
    </form>
</x-guest-layout>
