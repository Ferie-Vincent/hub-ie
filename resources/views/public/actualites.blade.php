<x-layouts.public :title="'Actualités — Hub Import-Export 2026'">
<x-page-hero kicker="Actualités">
    Toute l'<em class="font-fraunces italic text-orange-soft" style="font-variation-settings: 'opsz' 144, 'SOFT' 100;">actualité</em> du Hub.
</x-page-hero>

<div class="pb-24 bg-blanc-creme">
    <div class="max-w-hub mx-auto px-6 pt-14">

        @if($news->isEmpty())
            <p class="text-gris-500">Les actualités seront publiées prochainement.</p>
        @else
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($news as $article)
                <a href="{{ route('actualites.show', $article->slug) }}"
                   class="group relative overflow-hidden rounded-2xl bg-blanc-pur transition duration-300 hover:-translate-y-1 hover:shadow-card"
                   style="border: 1px solid hsl(var(--noir-profond) / 0.08);">

                    @if($article->cover_path)
                    <div class="h-48 overflow-hidden">
                        <img src="{{ Storage::url($article->cover_path) }}"
                             alt="{{ $article->title }}"
                             class="w-full h-full object-cover transition duration-500 group-hover:scale-105">
                    </div>
                    @else
                    <div class="h-48 flex items-center justify-center" style="background: hsl(var(--noir-profond) / 0.04);">
                        <svg class="w-10 h-10" style="color: hsl(var(--noir-profond) / 0.15);" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                        </svg>
                    </div>
                    @endif

                    <div class="p-6">
                        @if($article->is_featured)
                        <span class="kicker-orange rounded-full mb-3 inline-block">À la une</span>
                        @endif

                        <h2 class="font-serif font-bold text-noir-profond text-lg leading-snug mb-2 group-hover:text-orange-brule transition-colors">
                            {{ $article->title }}
                        </h2>

                        @if($article->excerpt)
                        <p class="text-sm text-noir-profond/60 leading-relaxed mb-4 line-clamp-3">{{ $article->excerpt }}</p>
                        @endif

                        <div class="flex items-center justify-between text-xs" style="color: hsl(var(--noir-profond) / 0.40);">
                            <span>{{ $article->published_at->translatedFormat('d M Y') }}</span>
                            @if($article->author)
                            <span>{{ $article->author->full_name }}</span>
                            @endif
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        @endif

    </div>
</div>
</x-layouts.public>
