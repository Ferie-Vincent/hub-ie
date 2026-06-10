<?php

namespace App\Http\Controllers;

use App\Models\Edition;
use App\Models\News;
use App\Models\Partner;
use App\Models\Workshop;

class PublicController extends Controller
{
    private const WORKSHOP_VISUAL = [
        1 => [
            'accent' => '--vert-ivoire',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 004 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
        ],
        2 => [
            'accent' => '--vert-fonce',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>',
        ],
        3 => [
            'accent' => '--vert-ivoire',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>',
        ],
        4 => [
            'accent' => '--vert-fonce',
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>',
        ],
    ];

    public function ateliers()
    {
        $workshops = Workshop::where('is_published', true)
            ->orderBy('display_order')
            ->get()
            ->map(fn ($w) => $this->workshopCard($w))
            ->all();

        return view('public.ateliers', compact('workshops'));
    }

    public function atelier(string $slug)
    {
        $w = Workshop::where('slug', $slug)->where('is_published', true)->firstOrFail();
        $workshop = $this->workshopCard($w);

        return view('public.atelier-show', compact('workshop'));
    }

    public function partenaires()
    {
        $grouped = Partner::orderBy('display_order')->get()->groupBy(fn ($p) => $p->tier->value);

        return view('public.partenaires', compact('grouped'));
    }

    public function actualites()
    {
        $news = News::whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->with('author')
            ->get();

        return view('public.actualites', compact('news'));
    }

    public function actualite(string $slug)
    {
        $article = News::where('slug', $slug)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->with('author')
            ->firstOrFail();

        return view('public.actualite-show', compact('article'));
    }

    public function portfolio()
    {
        $pastEditions = Edition::where('is_active', false)
            ->orderByDesc('year')
            ->withCount([
                'applications',
                'applications as accepted_count' => fn ($q) => $q->where('status', 'accepted'),
            ])
            ->get();

        $currentEdition = Edition::current();

        return view('public.portfolio', compact('pastEditions', 'currentEdition'));
    }

    private function workshopCard(Workshop $w): array
    {
        $cfg = self::WORKSHOP_VISUAL[$w->display_order] ?? self::WORKSHOP_VISUAL[1];
        $accent = $cfg['accent'];

        return [
            'slug' => $w->slug,
            'num' => str_pad($w->display_order, 2, '0', STR_PAD_LEFT),
            'titre' => $w->title,
            'tagline' => $w->short_description,
            'desc' => $w->full_description ?? $w->short_description,
            'themes' => $w->themes ?? [],
            'objectifs' => $w->objectives ?? [],
            'capacity' => $w->capacity,
            'accent' => $accent,
            'accentBg' => "hsl(var({$accent}) / 0.10)",
            'accentBorder' => "hsl(var({$accent}) / 0.35)",
            'numColor' => "hsl(var({$accent}) / 0.08)",
            'icon' => $cfg['icon'],
        ];
    }
}
