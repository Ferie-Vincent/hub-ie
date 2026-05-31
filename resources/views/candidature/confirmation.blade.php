<x-layouts.candidate title="Candidature reçue">

<div class="max-w-lg mx-auto text-center py-16">

    {{-- Icône succès --}}
    <div class="w-20 h-20 mx-auto rounded-full flex items-center justify-center mb-6"
         style="background:hsl(var(--vert-soft-bg))">
        <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke-width="2"
             style="color:hsl(var(--vert-ivoire))" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
        </svg>
    </div>

    <h1 class="font-serif font-bold text-3xl text-noir-profond mb-3">
        Candidature reçue !
    </h1>

    @if(session('application_reference'))
    <div class="inline-block bg-sable-doux/50 rounded-xl px-5 py-3 mb-5">
        <p class="text-xs text-gris-500 mb-0.5">Référence de votre dossier</p>
        <p class="font-mono font-bold text-2xl text-noir-profond tracking-widest">{{ session('application_reference') }}</p>
    </div>
    @endif

    <p class="text-base leading-relaxed mb-6" style="color:hsl(var(--gris-700))">
        Votre candidature au <strong>Hub Import-Export 2026</strong> a bien été enregistrée.
        Un email de confirmation vous a été envoyé. Vous pouvez suivre l'avancement de votre dossier
        depuis votre espace candidat.
    </p>

    <div class="space-y-3">
        <a href="{{ route('candidate.dashboard') }}" class="btn-fill px-8 py-3 text-base inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Accéder à mon espace
        </a>
        <p class="text-xs" style="color:hsl(var(--gris-700))">
            Questions ? <a href="mailto:contact@dgce.ci" class="link-underline">contact@dgce.ci</a>
        </p>
    </div>
</div>

</x-layouts.candidate>
