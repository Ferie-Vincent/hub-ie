<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::hex('#c07a30'),
                'gray'    => Color::Slate,
            ])
            ->sidebarCollapsibleOnDesktop()
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                \App\Filament\Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([])
            ->renderHook('panels::head.end', fn () => <<<'HTML'
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,700..900;1,9..144,700&family=JetBrains+Mono:wght@400;700&family=Manrope:wght@400;600;700&display=swap" rel="stylesheet">
<style>
body { font-family: "Manrope", sans-serif !important; }

/* ═══════════════════════════════════════════════════════════════
   SIDEBAR SOMBRE — Hub Import-Export
   Palette : noir-vert #0F1210 · sable/or #C8AC6C · orange #E8741C
═══════════════════════════════════════════════════════════════ */

/* ── 1. Fond principal ────────────────────────────────────── */
.fi-sidebar,
.fi-sidebar-header {
  background-color: #0F1210 !important;
}
.fi-sidebar {
  border-right: 1px solid rgba(200, 172, 108, 0.12) !important;
  box-shadow: none !important;
}

/* ── 2. En-tête : supprimer le ring gris, ajouter séparateur ─ */
.fi-sidebar-header {
  box-shadow: none !important;
  --tw-ring-shadow: 0 0 #0000 !important;
  border-bottom: 1px solid rgba(200, 172, 108, 0.12) !important;
}

/* ── 3. Logo / marque ─────────────────────────────────────── */
.fi-logo,
.fi-sidebar-header a,
.fi-brand-name {
  color: #F5F2ED !important;
}

/* ── 4. Boutons rétraction / expansion ───────────────────── */
.fi-sidebar-header .fi-icon-btn,
.fi-sidebar-header .fi-icon-btn-icon {
  color: rgba(200, 172, 108, 0.5) !important;
}
.fi-sidebar-header .fi-icon-btn:hover {
  background-color: rgba(245, 242, 237, 0.05) !important;
}
.fi-sidebar-header .fi-icon-btn:hover .fi-icon-btn-icon {
  color: rgba(245, 242, 237, 0.8) !important;
}

/* ── 5. Labels de groupe ──────────────────────────────────── */
.fi-sidebar-group-label {
  font-family: "JetBrains Mono", monospace !important;
  font-size: 0.58rem !important;
  letter-spacing: 0.2em !important;
  color: rgba(200, 172, 108, 0.4) !important;
  text-transform: uppercase !important;
}

/* Séparateurs entre groupes */
.fi-sidebar-nav-groups > li + li {
  padding-top: 0.5rem !important;
  margin-top: 0.25rem !important;
  border-top: 1px solid rgba(200, 172, 108, 0.07) !important;
}

/* ── 6. Items — état repos ────────────────────────────────── */
.fi-sidebar-item:not(.fi-active) .fi-sidebar-item-icon {
  color: rgba(245, 242, 237, 0.3) !important;
}
.fi-sidebar-item:not(.fi-active) .fi-sidebar-item-label {
  color: rgba(245, 242, 237, 0.5) !important;
}

/* ── 7. Items — hover ─────────────────────────────────────── */
.fi-sidebar-item-button:hover,
.fi-sidebar-item-button:focus-visible {
  background-color: rgba(245, 242, 237, 0.05) !important;
}
.fi-sidebar-item-button:hover .fi-sidebar-item-icon,
.fi-sidebar-item-button:focus-visible .fi-sidebar-item-icon {
  color: rgba(245, 242, 237, 0.7) !important;
}
.fi-sidebar-item-button:hover .fi-sidebar-item-label,
.fi-sidebar-item-button:focus-visible .fi-sidebar-item-label {
  color: rgba(245, 242, 237, 0.85) !important;
}

/* ── 8. Item actif ────────────────────────────────────────── */
.fi-sidebar-item.fi-active .fi-sidebar-item-button {
  background-color: rgba(232, 116, 28, 0.1) !important;
  position: relative !important;
}
.fi-sidebar-item.fi-active .fi-sidebar-item-button::before {
  content: '';
  position: absolute;
  inset-inline-start: 0;
  top: 20%;
  bottom: 20%;
  width: 2px;
  background: #E8741C;
  border-radius: 0 2px 2px 0;
}
.fi-sidebar-item.fi-active .fi-sidebar-item-icon {
  color: #E8741C !important;
}
.fi-sidebar-item.fi-active .fi-sidebar-item-label {
  color: #E8741C !important;
  font-weight: 600 !important;
}

/* ── 9. Badges ────────────────────────────────────────────── */
.fi-sidebar-item .fi-badge {
  background-color: rgba(200, 172, 108, 0.12) !important;
  color: rgba(200, 172, 108, 0.8) !important;
  border: none !important;
}
.fi-sidebar-item.fi-active .fi-badge {
  background-color: rgba(232, 116, 28, 0.18) !important;
  color: #E8741C !important;
}

/* ── 10. Sous-items (grouped border dots) ─────────────────── */
.fi-sidebar-item-grouped-border > div {
  background-color: rgba(200, 172, 108, 0.18) !important;
}
.fi-sidebar-item-grouped-border .rounded-full {
  background-color: rgba(200, 172, 108, 0.35) !important;
}
.fi-sidebar-item.fi-active .fi-sidebar-item-grouped-border .rounded-full {
  background-color: #E8741C !important;
}

/* ── 11. Bouton collapse de groupe ───────────────────────── */
.fi-sidebar-group-collapse-button .fi-icon-btn-icon {
  color: rgba(200, 172, 108, 0.4) !important;
}

/* ── 12. Transition douce sur l'élargissement ─────────────── */
.fi-sidebar {
  transition: width 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
}
</style>
HTML)
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
