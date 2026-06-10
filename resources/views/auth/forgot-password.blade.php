<x-guest-layout>
    <x-slot name="title">Mot de passe oublié</x-slot>

    <h2 class="text-xl font-semibold mb-3" style="font-family:'Fraunces',serif;">Mot de passe oublié ?</h2>
    <p class="text-sm mb-6" style="color:hsl(var(--blanc-casse)/0.6);">
        Saisissez votre adresse e-mail et nous vous enverrons un lien pour réinitialiser votre mot de passe.
    </p>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" value="Adresse e-mail" />
            <x-text-input id="email" type="email" name="email"
                          :value="old('email')" required autofocus class="block mt-1 w-full" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <button type="submit"
                class="w-full py-3 rounded-lg font-semibold text-sm text-white transition-all hover:opacity-90"
                style="background:hsl(var(--vert-ivoire));">
            Envoyer le lien de réinitialisation
        </button>

        <p class="text-center text-sm" style="color:hsl(var(--blanc-casse)/0.6);">
            <a href="{{ route('login') }}"
               class="hover:underline"
               style="color:hsl(var(--vert-ivoire));">
                ← Retour à la connexion
            </a>
        </p>
    </form>
</x-guest-layout>
