<x-guest-layout>
    <x-slot name="title">Nouveau mot de passe</x-slot>

    <h2 class="text-xl font-semibold mb-6" style="font-family:'Fraunces',serif;">Choisir un nouveau mot de passe</h2>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div>
            <x-input-label for="email" value="Adresse e-mail" />
            <x-text-input id="email" type="email" name="email"
                          :value="old('email', $request->email)"
                          required autofocus autocomplete="username"
                          class="block mt-1 w-full" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" value="Nouveau mot de passe" />
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
                class="w-full py-3 rounded-lg font-semibold text-sm text-white transition-all hover:opacity-90"
                style="background:hsl(var(--orange-ivoire));">
            Réinitialiser le mot de passe
        </button>
    </form>
</x-guest-layout>
