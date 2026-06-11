<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Dashboard;
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
                'primary' => Color::hex('#1DA853'),
                'gray' => Color::Zinc,
                'success' => Color::Emerald,
                'warning' => Color::Amber,
                'danger' => Color::Rose,
                'info' => Color::Sky,
            ])
            ->brandLogo(asset('images/logo-hie.png'))
            ->brandLogoHeight('2.25rem')
            ->sidebarCollapsibleOnDesktop()
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([])
            ->renderHook('panels::head.end', fn () => <<<'HTML'
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;700&family=Fraunces:ital,opsz,wght@0,9..144,700..900;1,9..144,700&display=swap" rel="stylesheet">
<style>
/* ════════════════════════════════════════════════════════════════
   HUB IMPORT-EXPORT 2026 — SIMPLE-INSPIRED GREEN AUTHORITY
   Inspired by Coderthemes Simple · Green Nature variant
   Sidebar : forêt #1B3829 · émeraude #27AE60 · page : #EEF2F0
════════════════════════════════════════════════════════════════ */

/* Filament font variable override */
:root, [data-filament-panel] {
  --fi-font-family: "Nunito", ui-sans-serif, system-ui, sans-serif;
  --s-sidebar:     #1B3829;
  --s-sidebar-2:   #214430;
  --s-sidebar-3:   #274E37;
  --s-green:       #27AE60;
  --s-green-b:     #2ECC71;
  --s-green-sub:   rgba(39,174,96,0.10);
  --s-green-glow:  rgba(39,174,96,0.18);
  --s-ivory:       #D6EBD8;
  --s-ivory-60:    rgba(214,235,216,0.60);
  --s-ivory-30:    rgba(214,235,216,0.30);
  --s-ivory-15:    rgba(214,235,216,0.15);
  --s-page:        #EEF2F0;
  --s-border:      #DDE8E2;
  --s-ink:         #1A2920;
  --s-ink-60:      rgba(26,41,32,0.60);
  --s-ink-40:      rgba(26,41,32,0.40);
  --s-card-shadow: 0 2px 6px rgba(0,0,0,0.05), 0 8px 24px rgba(0,0,0,0.04);
}

body { font-family: "Nunito", sans-serif !important; }

/* ═══════════════════════════════════════════════════════════════
   LAYOUT — page background (Simple's light-gray body)
═══════════════════════════════════════════════════════════════ */
.fi-main,
main.fi-main,
.fi-layout-main,
.fi-body { background-color: var(--s-page) !important; }

/* ═══════════════════════════════════════════════════════════════
   SIDEBAR
═══════════════════════════════════════════════════════════════ */
.fi-sidebar {
  background-color: var(--s-sidebar) !important;
  border-right: none !important;
  box-shadow: 2px 0 12px rgba(0,0,0,0.18) !important;
  position: relative !important;
  transition: width 0.22s cubic-bezier(0.4,0,0.2,1) !important;
}

/* ── Sidebar header ──────────────────────────────────────────── */
.fi-sidebar-header {
  background-color: var(--s-sidebar) !important;
  border-bottom: 1px solid rgba(39,174,96,0.12) !important;
  box-shadow: none !important;
  --tw-ring-shadow: 0 0 #0000 !important;
  padding-top: 1.1rem !important;
  padding-bottom: 1.1rem !important;
}

/* Logo */
.fi-logo img, .fi-sidebar-header img[alt] {
  filter: brightness(1.6) saturate(0.75) !important;
  max-height: 2rem !important;
  width: auto !important;
  object-fit: contain !important;
}
.fi-logo, .fi-sidebar-header a, .fi-brand-name {
  color: #FFFFFF !important;
  font-family: "Nunito", sans-serif !important;
  font-weight: 700 !important;
}

/* Header toggle buttons */
.fi-sidebar-header .fi-icon-btn-icon {
  color: var(--s-ivory-30) !important;
  transition: color 0.15s !important;
}
.fi-sidebar-header .fi-icon-btn:hover {
  background: var(--s-green-sub) !important;
  border-radius: 6px !important;
}
.fi-sidebar-header .fi-icon-btn:hover .fi-icon-btn-icon {
  color: var(--s-green-b) !important;
}

/* ── Navigation ──────────────────────────────────────────────── */
.fi-sidebar-nav { padding: 0.5rem 0 !important; }

/* Group labels — compact mono */
.fi-sidebar-group-label {
  font-family: "JetBrains Mono", monospace !important;
  font-size: 0.58rem !important;
  letter-spacing: 0.18em !important;
  text-transform: uppercase !important;
  color: rgba(39,174,96,0.45) !important;
  padding-inline-start: 1.1rem !important;
  padding-block: 0.15rem !important;
}

/* Group separators */
.fi-sidebar-nav-groups > li + li {
  padding-top: 0.625rem !important;
  margin-top: 0.375rem !important;
  border-top: 1px solid rgba(214,235,216,0.06) !important;
}

/* Collapse buttons */
.fi-sidebar-group-collapse-button .fi-icon-btn-icon {
  color: var(--s-ivory-30) !important;
}

/* ── Nav items — Simple: 42px height, clean solid active ───── */
.fi-sidebar-item-button {
  border-radius: 5px !important;
  margin-inline: 12px !important;
  margin-block: 2px !important;
  min-height: 42px !important;
  padding-block: 0.5rem !important;
  padding-inline: 0.75rem !important;
  transition: background 0.15s ease !important;
}

/* Rest state */
.fi-sidebar-item:not(.fi-active) .fi-sidebar-item-icon {
  color: rgba(214,235,216,0.42) !important;
  transition: color 0.15s !important;
}
.fi-sidebar-item:not(.fi-active) .fi-sidebar-item-label {
  color: rgba(214,235,216,0.65) !important;
  font-size: 0.855rem !important;
  font-weight: 500 !important;
  letter-spacing: 0.01em !important;
  transition: color 0.15s !important;
}

/* Hover state */
.fi-sidebar-item-button:hover,
.fi-sidebar-item-button:focus-visible {
  background: rgba(255,255,255,0.06) !important;
}
.fi-sidebar-item-button:hover .fi-sidebar-item-icon,
.fi-sidebar-item-button:focus-visible .fi-sidebar-item-icon {
  color: rgba(214,235,216,0.80) !important;
}
.fi-sidebar-item-button:hover .fi-sidebar-item-label,
.fi-sidebar-item-button:focus-visible .fi-sidebar-item-label {
  color: rgba(214,235,216,0.92) !important;
}

/* Active state — Simple: solid emerald fill, white text, subtle shadow */
.fi-sidebar-item.fi-active .fi-sidebar-item-button {
  background: var(--s-green) !important;
  border-radius: 5px !important;
  box-shadow: 0 2px 8px rgba(39,174,96,0.35) !important;
}
.fi-sidebar-item.fi-active .fi-sidebar-item-icon {
  color: #ffffff !important;
}
.fi-sidebar-item.fi-active .fi-sidebar-item-label {
  color: #ffffff !important;
  font-weight: 700 !important;
  font-size: 0.855rem !important;
  letter-spacing: 0.01em !important;
}

/* Badges */
.fi-sidebar-item .fi-badge {
  background: rgba(39,174,96,0.18) !important;
  color: var(--s-green-b) !important;
  border: none !important;
  font-family: "JetBrains Mono", monospace !important;
  font-size: 0.6rem !important;
  font-weight: 700 !important;
  border-radius: 20px !important;
}
.fi-sidebar-item.fi-active .fi-badge {
  background: rgba(255,255,255,0.25) !important;
  color: #fff !important;
}

/* Grouped sub-items */
.fi-sidebar-item-grouped-border > div {
  background: rgba(39,174,96,0.20) !important;
}
.fi-sidebar-item-grouped-border .rounded-full {
  background: rgba(39,174,96,0.40) !important;
}
.fi-sidebar-item.fi-active .fi-sidebar-item-grouped-border .rounded-full {
  background: rgba(255,255,255,0.7) !important;
}

/* ── Footer (user) ───────────────────────────────────────────── */
.fi-sidebar-footer {
  border-top: 1px solid rgba(214,235,216,0.07) !important;
  background: rgba(0,0,0,0.12) !important;
  padding-block: 0.625rem !important;
}
.fi-user-menu-trigger {
  border-radius: 6px !important;
  transition: background 0.15s !important;
}
.fi-user-menu-trigger:hover {
  background: var(--s-green-sub) !important;
}
[class*="fi-user-name"] {
  color: var(--s-ivory-60) !important;
  font-size: 0.8rem !important;
  font-weight: 600 !important;
}
[class*="fi-user-email"] {
  color: var(--s-ivory-30) !important;
  font-family: "JetBrains Mono", monospace !important;
  font-size: 0.6rem !important;
}

/* ═══════════════════════════════════════════════════════════════
   TOPBAR — blanc propre, Simple style
═══════════════════════════════════════════════════════════════ */
.fi-topbar {
  background: #ffffff !important;
  border-bottom: 1px solid var(--s-border) !important;
  box-shadow: 0 1px 4px rgba(0,0,0,0.04) !important;
  min-height: 60px !important;
}

/* ═══════════════════════════════════════════════════════════════
   PAGE HEADER & BREADCRUMB
═══════════════════════════════════════════════════════════════ */
.fi-page-header {
  padding-bottom: 0.75rem !important;
  border-bottom: none !important;
  margin-bottom: 1rem !important;
}
.fi-page-header-heading {
  font-family: "Fraunces", serif !important;
  font-weight: 800 !important;
  font-size: 1.5rem !important;
  color: var(--s-ink) !important;
  letter-spacing: -0.01em !important;
}
[class*="fi-breadcrumbs"] {
  font-size: 0.75rem !important;
  color: var(--s-ink-40) !important;
}
[class*="fi-breadcrumbs"] a { color: var(--s-ink-60) !important; }
[class*="fi-breadcrumbs"] a:hover { color: var(--s-green) !important; }

/* ═══════════════════════════════════════════════════════════════
   CARDS & SECTIONS — Simple: white float on light bg
═══════════════════════════════════════════════════════════════ */
.fi-section {
  background: #ffffff !important;
  border-radius: 8px !important;
  border: 1px solid var(--s-border) !important;
  box-shadow: var(--s-card-shadow) !important;
}
.fi-section-header {
  border-bottom: 1px solid var(--s-border) !important;
  padding-block: 1rem !important;
}
.fi-section-header-heading {
  font-family: "Nunito", sans-serif !important;
  font-weight: 700 !important;
  font-size: 0.95rem !important;
  color: var(--s-ink) !important;
}

/* ═══════════════════════════════════════════════════════════════
   STAT WIDGETS — Simple's KPI cards
═══════════════════════════════════════════════════════════════ */
.fi-wi-stats-overview-stat {
  background: #ffffff !important;
  border: 1px solid var(--s-border) !important;
  border-radius: 8px !important;
  box-shadow: var(--s-card-shadow) !important;
  transition: box-shadow 0.2s ease, transform 0.2s ease !important;
  overflow: hidden !important;
  position: relative !important;
}
/* Thin left accent bar (Simple style) */
.fi-wi-stats-overview-stat::before {
  content: '';
  position: absolute;
  inset-inline-start: 0;
  top: 0; bottom: 0;
  width: 3px;
  background: var(--s-green) !important;
  border-radius: 2px 0 0 2px;
}
.fi-wi-stats-overview-stat:hover {
  box-shadow: 0 6px 24px rgba(0,0,0,0.09) !important;
  transform: translateY(-2px) !important;
}
.fi-wi-stats-overview-stat-value {
  font-family: "Fraunces", serif !important;
  font-weight: 800 !important;
  font-size: 1.85rem !important;
  color: var(--s-ink) !important;
  letter-spacing: -0.02em !important;
}
.fi-wi-stats-overview-stat-label {
  font-size: 0.75rem !important;
  font-weight: 600 !important;
  color: var(--s-ink-60) !important;
  text-transform: none !important;
  letter-spacing: 0 !important;
}
.fi-wi-stats-overview-stat-description {
  font-size: 0.75rem !important;
  color: var(--s-ink-40) !important;
}

/* ═══════════════════════════════════════════════════════════════
   TABLES — Simple's clean data tables
═══════════════════════════════════════════════════════════════ */
.fi-ta-header-cell {
  font-size: 0.72rem !important;
  font-weight: 700 !important;
  text-transform: uppercase !important;
  letter-spacing: 0.06em !important;
  color: var(--s-ink-40) !important;
  background: #F8FAF9 !important;
}
.fi-ta-row:hover > td {
  background: rgba(39,174,96,0.03) !important;
}
.fi-ta-row:nth-child(even) > td {
  background: rgba(238,242,240,0.5) !important;
}

/* ═══════════════════════════════════════════════════════════════
   BUTTONS
═══════════════════════════════════════════════════════════════ */
.fi-btn {
  font-weight: 600 !important;
  border-radius: 6px !important;
}
.fi-btn-color-primary.fi-btn-style-filled {
  box-shadow: 0 2px 6px rgba(39,174,96,0.25) !important;
  transition: box-shadow 0.18s ease, transform 0.15s ease !important;
}
.fi-btn-color-primary.fi-btn-style-filled:hover {
  box-shadow: 0 4px 14px rgba(39,174,96,0.40) !important;
  transform: translateY(-1px) !important;
}

/* ═══════════════════════════════════════════════════════════════
   FORM INPUTS
═══════════════════════════════════════════════════════════════ */
.fi-input {
  border-radius: 6px !important;
  border-color: var(--s-border) !important;
  font-size: 0.875rem !important;
}
.fi-input:focus {
  border-color: var(--s-green) !important;
  box-shadow: 0 0 0 3px rgba(39,174,96,0.12) !important;
}
.fi-select-input {
  border-radius: 6px !important;
}

/* ═══════════════════════════════════════════════════════════════
   MODAL & DROPDOWN
═══════════════════════════════════════════════════════════════ */
.fi-modal-window {
  border-radius: 10px !important;
  box-shadow: 0 20px 60px rgba(0,0,0,0.15) !important;
}
.fi-dropdown-panel {
  border-radius: 8px !important;
  border: 1px solid var(--s-border) !important;
  box-shadow: 0 8px 24px rgba(0,0,0,0.10) !important;
}

/* ═══════════════════════════════════════════════════════════════
   BADGES & STATUS
═══════════════════════════════════════════════════════════════ */
.fi-badge {
  border-radius: 20px !important;
  font-size: 0.7rem !important;
  font-weight: 700 !important;
  padding: 0.15rem 0.6rem !important;
}

/* ═══════════════════════════════════════════════════════════════
   NOTIFICATIONS
═══════════════════════════════════════════════════════════════ */
.fi-notification {
  border-radius: 8px !important;
  box-shadow: 0 8px 24px rgba(0,0,0,0.12) !important;
}
.fi-notification-success {
  border-left: 3px solid var(--s-green) !important;
}

/* ═══════════════════════════════════════════════════════════════
   LOGIN PAGE — card centrée sur fond page
═══════════════════════════════════════════════════════════════ */
.fi-simple-page {
  background: var(--s-page) !important;
}
.fi-simple-main {
  background: var(--s-page) !important;
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
