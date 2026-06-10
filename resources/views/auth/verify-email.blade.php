<x-guest-layout>
    <x-slot name="title">Vérifier votre e-mail</x-slot>

    <div class="text-center mb-6">
        <div class="inline-flex items-center justify-center w-14 h-14 rounded-full mb-4"
             style="background:hsl(var(--vert-ivoire)/0.15);">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                 style="color:hsl(var(--vert-ivoire));">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
        </div>
        <h2 class="text-xl font-semibold" style="font-family:'Fraunces',serif;">Vérifiez votre e-mail</h2>
    </div>

    <p class="text-sm text-center mb-5" style="color:hsl(var(--blanc-casse)/0.7);">
        Merci pour votre inscription ! Un lien de vérification vous a été envoyé. Cliquez sur ce lien pour activer votre compte.
    </p>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 p-3 rounded-lg text-sm text-center"
             style="background:hsl(var(--vert-ivoire)/0.15);color:hsl(var(--vert-ivoire));">
            Un nouvel e-mail de vérification a été envoyé à votre adresse.
        </div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}" class="mb-3">
        @csrf
        <button type="submit"
                class="w-full py-3 rounded-lg font-semibold text-sm text-white transition-all hover:opacity-90"
                style="background:hsl(var(--vert-ivoire));">
            Renvoyer l'e-mail de vérification
        </button>
    </form>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit"
                class="w-full py-2 text-sm hover:underline"
                style="color:hsl(var(--blanc-casse)/0.5);">
            Se déconnecter
        </button>
    </form>
</x-guest-layout>
