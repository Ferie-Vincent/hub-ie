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
<link href="https://fonts.googleapis.com/css2?family=Geist:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
<style>
/* ════════════════════════════════════════════════════════════════
   HUB IMPORT-EXPORT 2026 — CODERTHEMES SIMPLE (exact tokens)
   Font    : Geist 13px · weight 400/500/600/700
   Body    : #f9f9f9 · Border : #e5e7eb · Radius-xl : 1rem
   Sidebar : #1B3829 (green dark variant) · Active : #27AE60
   Topbar  : #ffffff · shadow: 0px 1px 4px rgba(130,143,163,0.15)
════════════════════════════════════════════════════════════════ */

/* ── Exact Simple design tokens ─────────────────────────────── */
:root {
  /* Font */
  --ins-font:          "Geist", ui-sans-serif, system-ui, sans-serif;
  --ins-font-size:     13px;
  --ins-font-mono:     "JetBrains Mono", ui-monospace, monospace;

  /* Body */
  --ins-body-bg:       #f9f9f9;
  --ins-body-color:    #374151;
  --ins-secondary-bg:  #ffffff;

  /* Borders */
  --ins-border:        #e5e7eb;
  --ins-radius:        0.4rem;
  --ins-radius-sm:     0.3rem;
  --ins-radius-lg:     0.6rem;
  --ins-radius-xl:     1rem;
  --ins-radius-pill:   50rem;

  /* Shadows */
  --ins-shadow:        0px 1px 4px 0px rgba(130,143,163,0.15);
  --ins-shadow-lg:     0 0.25rem 1rem rgba(55,65,81,0.20);

  /* Sidebar (light white variant) */
  --ins-nav-bg:        #ffffff;
  --ins-nav-border:    #e5e7eb;
  --ins-nav-color:     #4a7c59;
  --ins-nav-hover-bg:  rgba(39,174,96,0.07);
  --ins-nav-hover-cl:  #1B3829;
  --ins-nav-active-bg: #27AE60;
  --ins-nav-active-cl: #ffffff;
  --ins-nav-grp-cl:    #a3c4a8;

  /* Topbar */
  --ins-topbar-bg:     #ffffff;
  --ins-topbar-h:      60px;

  /* Brand green */
  --ins-green:         #27AE60;
  --ins-green-b:       #2ECC71;

  /* Content ink */
  --ins-ink:           #111827;
  --ins-ink-muted:     #6b7280;

  /* Filament font override */
  --fi-font-family: var(--ins-font);
}

/* ── Typography — base 13px Geist ───────────────────────────── */
body, .fi-body {
  font-family: var(--ins-font) !important;
  font-size: var(--ins-font-size) !important;
  color: var(--ins-body-color) !important;
  background-color: var(--ins-body-bg) !important;
}
*, *::before, *::after { font-family: inherit; }

/* ── Page body background ────────────────────────────────────── */
.fi-main, main.fi-main, .fi-layout-main {
  background-color: var(--ins-body-bg) !important;
}

/* ═══════════════════════════════════════════════════════════════
   SIDEBAR — Simple dark-green variant
   Width: 220px | font: 13px Geist | active: solid #27AE60
═══════════════════════════════════════════════════════════════ */
.fi-sidebar {
  background-color: var(--ins-nav-bg) !important;
  border-right: 1px solid var(--ins-nav-border) !important;
  width: 260px !important;
  transition: width 0.22s cubic-bezier(0.4,0,0.2,1) !important;
}

/* Sidebar header */
.fi-sidebar-header {
  background-color: var(--ins-nav-bg) !important;
  border-bottom: 1px solid var(--ins-nav-border) !important;
  box-shadow: none !important;
  --tw-ring-shadow: 0 0 #0000 !important;
  height: var(--ins-topbar-h) !important;
  display: flex !important;
  align-items: center !important;
  padding-inline: 1.25rem !important;
}

/* Logo */
.fi-logo img, .fi-sidebar-header img[alt] {
  filter: none !important;
  max-height: 1.875rem !important;
  width: auto !important;
  object-fit: contain !important;
}
.fi-logo, .fi-sidebar-header a, .fi-brand-name {
  color: #1B3829 !important;
  font-family: var(--ins-font) !important;
  font-size: 15px !important;
  font-weight: 700 !important;
}

/* Header collapse buttons */
.fi-sidebar-header .fi-icon-btn-icon { color: #a3c4a8 !important; }
.fi-sidebar-header .fi-icon-btn:hover { background: rgba(39,174,96,0.07) !important; border-radius: var(--ins-radius) !important; }
.fi-sidebar-header .fi-icon-btn:hover .fi-icon-btn-icon { color: #27AE60 !important; }

/* Navigation padding */
.fi-sidebar-nav { padding-block: 0.5rem !important; }

/* Group labels — Simple: 11px, uppercase, very muted */
.fi-sidebar-group-label {
  font-family: var(--ins-font) !important;
  font-size: 10.5px !important;
  font-weight: 600 !important;
  letter-spacing: 0.06em !important;
  text-transform: uppercase !important;
  color: var(--ins-nav-grp-cl) !important;
  padding-inline: 1.25rem !important;
  padding-block: 0.5rem 0.25rem !important;
  white-space: nowrap !important;
  overflow: hidden !important;
  text-overflow: ellipsis !important;
}

/* Group separators — Simple: subtle line */
.fi-sidebar-nav-groups > li + li {
  margin-top: 0.5rem !important;
  padding-top: 0.5rem !important;
  border-top: 1px solid #f3f4f6 !important;
}

/* Collapse arrow — muted */
.fi-sidebar-group-collapse-button .fi-icon-btn-icon { color: #c8dcc9 !important; }

/* ── Nav items — Simple exact: px 20, py 8, icon 16px, 13px ── */
.fi-sidebar-item-button {
  border-radius: var(--ins-radius) !important;
  margin-inline: 0.625rem !important;
  margin-block: 1px !important;
  padding-block: 0.5rem !important;
  padding-inline: 1.25rem !important;
  min-height: 36px !important;
  display: flex !important;
  align-items: center !important;
  gap: 8px !important;
  transition: background 0.15s ease, color 0.15s ease !important;
}

.fi-sidebar-item-icon {
  width: 16px !important;
  height: 16px !important;
  flex-shrink: 0 !important;
}

/* Rest */
.fi-sidebar-item:not(.fi-active) .fi-sidebar-item-icon {
  color: var(--ins-nav-color) !important;
  transition: color 0.15s !important;
}
.fi-sidebar-item:not(.fi-active) .fi-sidebar-item-label {
  color: var(--ins-nav-color) !important;
  font-size: 0.8125rem !important;
  font-weight: 500 !important;
  transition: color 0.15s !important;
  white-space: nowrap !important;
  overflow: hidden !important;
  text-overflow: ellipsis !important;
}

/* Hover */
.fi-sidebar-item-button:hover,
.fi-sidebar-item-button:focus-visible {
  background: var(--ins-nav-hover-bg) !important;
}
.fi-sidebar-item-button:hover .fi-sidebar-item-icon,
.fi-sidebar-item-button:focus-visible .fi-sidebar-item-icon { color: var(--ins-nav-hover-cl) !important; }
.fi-sidebar-item-button:hover .fi-sidebar-item-label,
.fi-sidebar-item-button:focus-visible .fi-sidebar-item-label { color: var(--ins-nav-hover-cl) !important; }

/* Active — Simple: solid primary fill, white text */
.fi-sidebar-item.fi-active .fi-sidebar-item-button {
  background: var(--ins-nav-active-bg) !important;
}
.fi-sidebar-item.fi-active .fi-sidebar-item-icon { color: var(--ins-nav-active-cl) !important; }
.fi-sidebar-item.fi-active .fi-sidebar-item-label {
  color: var(--ins-nav-active-cl) !important;
  font-weight: 600 !important;
  font-size: 0.8125rem !important;
  white-space: nowrap !important;
  overflow: hidden !important;
  text-overflow: ellipsis !important;
}

/* Sidebar badges */
.fi-sidebar-item .fi-badge {
  font-family: var(--ins-font-mono) !important;
  font-size: 11px !important;
  font-weight: 600 !important;
  background: rgba(39,174,96,0.18) !important;
  color: var(--ins-green-b) !important;
  border: none !important;
  border-radius: var(--ins-radius-pill) !important;
}
.fi-sidebar-item.fi-active .fi-badge { background: rgba(255,255,255,0.2) !important; color: #fff !important; }

/* Sub-items connector dots */
.fi-sidebar-item-grouped-border > div { background: rgba(39,174,96,0.22) !important; }
.fi-sidebar-item-grouped-border .rounded-full { background: rgba(39,174,96,0.45) !important; }
.fi-sidebar-item.fi-active .fi-sidebar-item-grouped-border .rounded-full { background: rgba(255,255,255,0.7) !important; }

/* Sidebar footer (user info) */
.fi-sidebar-footer {
  border-top: 1px solid #f3f4f6 !important;
  background: #fafafa !important;
  padding-block: 0.5rem !important;
}
.fi-user-menu-trigger { border-radius: var(--ins-radius) !important; transition: background 0.15s !important; }
.fi-user-menu-trigger:hover { background: rgba(39,174,96,0.07) !important; }
[class*="fi-user-name"] { color: #1B3829 !important; font-size: 13px !important; font-weight: 600 !important; }
[class*="fi-user-email"] { color: #a3c4a8 !important; font-family: var(--ins-font-mono) !important; font-size: 11px !important; }

/* ═══════════════════════════════════════════════════════════════
   TOPBAR — Simple: #ffffff, height 60px, shadow
═══════════════════════════════════════════════════════════════ */
.fi-topbar {
  background: var(--ins-topbar-bg) !important;
  border-bottom: 1px solid var(--ins-border) !important;
  box-shadow: var(--ins-shadow) !important;
  min-height: var(--ins-topbar-h) !important;
}
.fi-topbar-item { color: var(--ins-ink-muted) !important; font-size: 13px !important; }
.fi-topbar-item:hover { color: var(--ins-ink) !important; }

/* ═══════════════════════════════════════════════════════════════
   PAGE HEADER — Simple: compact, 15px title
═══════════════════════════════════════════════════════════════ */
.fi-page-header { padding-bottom: 0.75rem !important; border-bottom: none !important; }
.fi-page-header-heading {
  font-family: var(--ins-font) !important;
  font-size: 15px !important;
  font-weight: 700 !important;
  color: var(--ins-ink) !important;
  letter-spacing: -0.01em !important;
}
[class*="fi-breadcrumbs"] { font-size: 12px !important; color: var(--ins-ink-muted) !important; }
[class*="fi-breadcrumbs"] a { color: var(--ins-ink-muted) !important; }
[class*="fi-breadcrumbs"] a:hover { color: var(--ins-green) !important; }

/* ═══════════════════════════════════════════════════════════════
   CARDS & SECTIONS — Simple exact: 1rem radius, 1px border, NO shadow
═══════════════════════════════════════════════════════════════ */
.fi-section {
  background: var(--ins-secondary-bg) !important;
  border-radius: var(--ins-radius-xl) !important;
  border: 1px solid var(--ins-border) !important;
  box-shadow: none !important;
}
.fi-section-header {
  border-bottom: 1px solid var(--ins-border) !important;
  padding-block: 0.9375rem !important;
  padding-inline: 1.0625rem !important;
}
.fi-section-header-heading {
  font-family: var(--ins-font) !important;
  font-weight: 600 !important;
  font-size: 13px !important;
  color: var(--ins-ink) !important;
}
.fi-section-content { padding: 0.9375rem 1.0625rem !important; }

/* ═══════════════════════════════════════════════════════════════
   STAT WIDGETS — Simple exact: 1rem radius, 1px border, no shadow
   KPI value: 24px Geist bold · label: 11px muted uppercase
═══════════════════════════════════════════════════════════════ */
.fi-wi-stats-overview-stat {
  background: var(--ins-secondary-bg) !important;
  border: 1px solid var(--ins-border) !important;
  border-radius: var(--ins-radius-xl) !important;
  box-shadow: none !important;
  padding: 0.9375rem 1.0625rem !important;
  transition: box-shadow 0.18s ease !important;
}
.fi-wi-stats-overview-stat:hover {
  box-shadow: var(--ins-shadow) !important;
}
.fi-wi-stats-overview-stat-value {
  font-family: var(--ins-font) !important;
  font-weight: 700 !important;
  font-size: 24px !important;
  color: var(--ins-ink) !important;
  letter-spacing: -0.02em !important;
  line-height: 1.2 !important;
}
.fi-wi-stats-overview-stat-label {
  font-family: var(--ins-font) !important;
  font-size: 11px !important;
  font-weight: 600 !important;
  color: var(--ins-ink-muted) !important;
  text-transform: uppercase !important;
  letter-spacing: 0.08em !important;
}
.fi-wi-stats-overview-stat-description {
  font-size: 12px !important;
  color: var(--ins-ink-muted) !important;
}

/* ═══════════════════════════════════════════════════════════════
   TABLES — Simple: 13px Geist, #e5e7eb borders, hover tint
═══════════════════════════════════════════════════════════════ */
.fi-ta-content { border-radius: var(--ins-radius-xl) !important; overflow: hidden !important; }
.fi-ta-header-cell {
  font-family: var(--ins-font) !important;
  font-size: 11px !important;
  font-weight: 600 !important;
  text-transform: uppercase !important;
  letter-spacing: 0.08em !important;
  color: var(--ins-ink-muted) !important;
  background: #f9fafb !important;
  padding-block: 0.625rem !important;
  border-bottom: 1px solid var(--ins-border) !important;
}
.fi-ta-cell {
  font-family: var(--ins-font) !important;
  font-size: 13px !important;
  color: var(--ins-body-color) !important;
  border-bottom: 1px solid #f3f4f6 !important;
}
.fi-ta-row:hover > td, .fi-ta-row:hover > .fi-ta-cell {
  background: #f9fafb !important;
}

/* ═══════════════════════════════════════════════════════════════
   BUTTONS — Simple: 0.4rem radius, 13px, weight 500
═══════════════════════════════════════════════════════════════ */
.fi-btn {
  font-family: var(--ins-font) !important;
  font-size: 13px !important;
  font-weight: 500 !important;
  border-radius: var(--ins-radius) !important;
  padding-block: 0.4532rem !important;
  padding-inline: 1.1rem !important;
}
.fi-btn-color-primary.fi-btn-style-filled {
  box-shadow: none !important;
  transition: opacity 0.15s ease !important;
}
.fi-btn-color-primary.fi-btn-style-filled:hover { opacity: 0.9 !important; }

/* ═══════════════════════════════════════════════════════════════
   FORM INPUTS — Simple: 0.4rem radius, 13px, #e5e7eb border
═══════════════════════════════════════════════════════════════ */
.fi-input,
.fi-select-input,
input[type="text"],
input[type="email"],
input[type="password"],
textarea,
select {
  font-family: var(--ins-font) !important;
  font-size: 13px !important;
  border-radius: var(--ins-radius) !important;
  border-color: var(--ins-border) !important;
  background: var(--ins-secondary-bg) !important;
  color: var(--ins-body-color) !important;
  padding-block: 0.4532rem !important;
  padding-inline: 0.77rem !important;
}
.fi-input:focus,
.fi-select-input:focus,
input:focus {
  border-color: var(--ins-green) !important;
  box-shadow: 0 0 0 3px rgba(39,174,96,0.10) !important;
  outline: none !important;
}
label, .fi-fo-field-wrp-label { font-size: 13px !important; font-weight: 500 !important; color: var(--ins-ink) !important; }

/* ═══════════════════════════════════════════════════════════════
   MODAL — Simple: 0.6rem radius, shadow-lg
═══════════════════════════════════════════════════════════════ */
.fi-modal-window {
  border-radius: var(--ins-radius-lg) !important;
  box-shadow: var(--ins-shadow-lg) !important;
  border: 1px solid var(--ins-border) !important;
}
.fi-modal-header { border-bottom: 1px solid var(--ins-border) !important; }
.fi-modal-footer { border-top: 1px solid var(--ins-border) !important; }
.fi-modal-heading { font-size: 15px !important; font-weight: 600 !important; color: var(--ins-ink) !important; }

/* ═══════════════════════════════════════════════════════════════
   DROPDOWN — Simple: 0.4rem radius, 1px border, shadow
═══════════════════════════════════════════════════════════════ */
.fi-dropdown-panel {
  border-radius: var(--ins-radius) !important;
  border: 1px solid var(--ins-border) !important;
  box-shadow: var(--ins-shadow-lg) !important;
  background: var(--ins-secondary-bg) !important;
}
.fi-dropdown-list-item-label { font-size: 13px !important; }

/* ═══════════════════════════════════════════════════════════════
   BADGES — Simple: pill shape, 11px, weight 600
═══════════════════════════════════════════════════════════════ */
.fi-badge {
  font-family: var(--ins-font) !important;
  font-size: 11px !important;
  font-weight: 600 !important;
  border-radius: var(--ins-radius-pill) !important;
  padding-block: 0.125rem !important;
  padding-inline: 0.5rem !important;
}

/* ═══════════════════════════════════════════════════════════════
   NOTIFICATIONS — Simple: shadow, 0.4rem radius
═══════════════════════════════════════════════════════════════ */
.fi-notification {
  font-family: var(--ins-font) !important;
  font-size: 13px !important;
  border-radius: var(--ins-radius) !important;
  border: 1px solid var(--ins-border) !important;
  box-shadow: var(--ins-shadow-lg) !important;
}
.fi-notification-title { font-weight: 600 !important; font-size: 13px !important; }
.fi-notification-body  { font-size: 12px !important; color: var(--ins-ink-muted) !important; }
.fi-notification-success { border-left: 3px solid var(--ins-green) !important; }
.fi-notification-warning { border-left: 3px solid #eab308 !important; }
.fi-notification-danger  { border-left: 3px solid #ef4444 !important; }

/* ═══════════════════════════════════════════════════════════════
   LOGIN PAGE — Simple body bg + card
═══════════════════════════════════════════════════════════════ */
.fi-simple-page, .fi-simple-main {
  background: var(--ins-body-bg) !important;
}
.fi-simple-main .fi-section {
  border-radius: var(--ins-radius-xl) !important;
  box-shadow: var(--ins-shadow) !important;
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
