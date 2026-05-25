<x-layouts.public title="Désinscription confirmée — Hub Import-Export 2026">

<x-page-hero kicker="Newsletter" kickerColor="orange">
    Désinscription <em class="font-fraunces italic text-vert-soft" style="font-variation-settings: 'opsz' 144, 'SOFT' 100;">confirmée.</em>
</x-page-hero>

<div class="py-24 bg-blanc-creme">
    <div class="max-w-lg mx-auto px-6 text-center">
        <div class="bg-blanc-pur rounded-3xl p-10 shadow-card">
            <svg class="w-12 h-12 text-vert-ivoire mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <h2 class="font-serif font-bold text-noir-profond text-2xl mb-4">Vous avez été désinscrit</h2>
            <p class="text-gris-500 leading-relaxed mb-8">
                Votre adresse e-mail a bien été retirée de notre liste de diffusion.
                Vous ne recevrez plus les actualités du Hub Import-Export 2026.
            </p>
            <a href="{{ route('home') }}" class="btn-fill px-6 py-3 text-sm">
                <span>Retour au site</span>
            </a>
        </div>
    </div>
</div>

</x-layouts.public>
