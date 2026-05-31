<x-layouts.public :title="$article->title . ' — Hub Import-Export 2026'">

<x-page-hero kicker="Actualités">
    {{ $article->title }}
</x-page-hero>

<div class="pb-24 bg-blanc-creme">
    <div class="max-w-3xl mx-auto px-6 pt-14">

        <a href="{{ route('actualites.index') }}" class="inline-flex items-center gap-2 text-sm text-gris-500 hover:text-orange-ivoire transition-colors mb-10 link-underline">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Actualités
        </a>

        {{-- Meta --}}
        <div class="flex flex-wrap items-center gap-4 text-xs mb-8" style="color: hsl(var(--noir-profond) / 0.45);">
            <span>{{ $article->published_at->translatedFormat('d M Y') }}</span>
            @if($article->author)
            <span>·</span>
            <span>{{ $article->author->full_name }}</span>
            @endif
            @if($article->is_featured)
            <span class="kicker-orange rounded-full">À la une</span>
            @endif
        </div>

        {{-- Cover image --}}
        @if($article->cover_path)
        <div class="rounded-2xl overflow-hidden mb-10 shadow-card">
            <img src="{{ Storage::url($article->cover_path) }}"
                 alt="{{ $article->title }}"
                 class="w-full h-64 sm:h-80 object-cover">
        </div>
        @endif

        {{-- Excerpt --}}
        @if($article->excerpt)
        <p class="text-lg font-semibold text-noir-profond/70 leading-relaxed mb-8 border-l-4 pl-5"
           style="border-color: hsl(var(--orange-ivoire));">
            {{ $article->excerpt }}
        </p>
        @endif

        {{-- Content --}}
        @if($article->content)
        <div class="prose prose-lg max-w-none text-noir-profond/80 leading-relaxed">
            {!! nl2br(e($article->content)) !!}
        </div>
        @endif

    </div>
</div>

</x-layouts.public>
