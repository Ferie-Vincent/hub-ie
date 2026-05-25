<x-layouts.public>
<x-page-hero kicker="Actualités">
    Article
</x-page-hero>

<div class="pb-24 bg-blanc-creme">
    <div class="max-w-3xl mx-auto px-6 pt-14">
        <a href="{{ route('actualites.index') }}" class="inline-flex items-center gap-2 text-sm text-gris-500 hover:text-orange-ivoire transition-colors mb-8 link-underline">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Actualités
        </a>
        <p class="text-gris-500">Article introuvable.</p>
    </div>
</div>
</x-layouts.public>
