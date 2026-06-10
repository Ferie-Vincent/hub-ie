<x-layouts.public>

<x-page-hero kicker="Atelier thématique" :description="$workshop['tagline']">
    {{ $workshop['titre'] }}
</x-page-hero>

<div class="pb-24 bg-blanc-creme">
    <div class="max-w-3xl mx-auto px-6 pt-14">
        <a href="{{ route('ateliers.index') }}" class="inline-flex items-center gap-2 text-sm text-gris-500 hover:text-vert-ivoire transition-colors mb-10 link-underline">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Tous les ateliers
        </a>

        <div class="space-y-8">

            @if(!empty($workshop['desc']) && $workshop['desc'] !== $workshop['tagline'])
            <div>
                <p class="text-noir-profond/70 text-base leading-relaxed">{{ $workshop['desc'] }}</p>
            </div>
            @endif

            @if(!empty($workshop['objectifs']))
            <div>
                <h2 class="font-serif font-bold text-noir-profond text-xl mb-4">Objectifs pédagogiques</h2>
                <ul class="space-y-3">
                    @foreach($workshop['objectifs'] as $obj)
                    <li class="flex items-start gap-3">
                        <span class="w-5 h-5 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5"
                              style="background: hsl(var(--vert-soft-bg));">
                            <svg class="w-3 h-3" fill="none" stroke="hsl(var(--vert-ivoire))" viewBox="0 0 24 24" stroke-width="2.5" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </span>
                        <span class="text-noir-profond/80">{{ $obj }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif

            @if(!empty($workshop['themes']))
            <div>
                <h2 class="font-serif font-bold text-noir-profond text-xl mb-4">Thèmes couverts</h2>
                <div class="flex flex-wrap gap-2">
                    @foreach($workshop['themes'] as $theme)
                    <span class="kicker-vert rounded-full">{{ $theme }}</span>
                    @endforeach
                </div>
            </div>
            @endif

            @if($workshop['capacity'])
            <div class="flex items-center gap-3 text-sm text-noir-profond/50">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Groupe limité à {{ $workshop['capacity'] }} participants
            </div>
            @endif

            <div class="bg-vert-soft-bg rounded-2xl p-6">
                <p class="text-vert-fonce font-semibold mb-3">Participer à cet atelier</p>
                <p class="text-sm text-noir-profond/70 mb-4">L'accès à cet atelier est conditionné à la sélection de votre candidature. Candidatez dès maintenant pour rejoindre le Hub Import-Export 2026.</p>
                <a href="{{ route('inscription') }}" class="btn-fill px-5 py-2.5 text-sm"><span>S'inscrire au Hub</span></a>
            </div>
        </div>
    </div>
</div>

</x-layouts.public>
