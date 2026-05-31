<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Carbon;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $staticUrls = [
            ['/', 1.0, 'daily'],
            ['/programme', 0.8, 'weekly'],
            ['/ateliers', 0.8, 'weekly'],
            ['/partenaires', 0.6, 'monthly'],
            ['/actualites', 0.7, 'daily'],
            ['/presse', 0.5, 'monthly'],
            ['/faq', 0.6, 'monthly'],
            ['/contact', 0.4, 'monthly'],
            ['/mentions-legales', 0.2, 'yearly'],
            ['/politique-de-confidentialite', 0.2, 'yearly'],
            ['/conditions-utilisation', 0.2, 'yearly'],
        ];

        $atelierSlugs = [
            'zlecaf-cedeao',
            'financement-garanties',
            'commerce-electronique',
            'conformite-qualite',
        ];

        $base = rtrim(config('app.url'), '/');
        $now = Carbon::now()->toAtomString();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";

        foreach ($staticUrls as [$path, $priority, $freq]) {
            $xml .= "  <url>\n";
            $xml .= "    <loc>{$base}{$path}</loc>\n";
            $xml .= "    <lastmod>{$now}</lastmod>\n";
            $xml .= "    <changefreq>{$freq}</changefreq>\n";
            $xml .= "    <priority>{$priority}</priority>\n";
            $xml .= "  </url>\n";
        }

        foreach ($atelierSlugs as $slug) {
            $xml .= "  <url>\n";
            $xml .= "    <loc>{$base}/ateliers/{$slug}</loc>\n";
            $xml .= "    <lastmod>{$now}</lastmod>\n";
            $xml .= "    <changefreq>monthly</changefreq>\n";
            $xml .= "    <priority>0.7</priority>\n";
            $xml .= "  </url>\n";
        }

        $xml .= '</urlset>';

        return response($xml, 200, [
            'Content-Type' => 'application/xml',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }
}
