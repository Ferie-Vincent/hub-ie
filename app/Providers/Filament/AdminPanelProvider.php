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
   HUB IMPORT-EXPORT 2026
   Font    : Geist 14px
   Body    : #f9f9f9 · Border : #e5e7eb · Radius-xl : 1rem
   Sidebar : #1B3829 dark forest · Active: rgba tint + orange bar
   Topbar  : #ffffff
════════════════════════════════════════════════════════════════ */

:root {
  --ins-font:             "Geist", ui-sans-serif, system-ui, sans-serif;
  --ins-font-size:        14px;
  --ins-font-mono:        "JetBrains Mono", ui-monospace, monospace;
  --ins-body-bg:          #f9f9f9;
  --ins-body-color:       #111827;
  --ins-secondary-bg:     #ffffff;
  --ins-border:           #e5e7eb;
  --ins-radius:           0.4rem;
  --ins-radius-sm:        0.3rem;
  --ins-radius-lg:        0.6rem;
  --ins-radius-xl:        1rem;
  --ins-radius-pill:      50rem;
  --ins-shadow:           0px 1px 4px 0px rgba(130,143,163,0.15);
  --ins-shadow-lg:        0 0.25rem 1rem rgba(55,65,81,0.20);
  --ins-nav-bg:           #1B3829;
  --ins-nav-border:       rgba(255,255,255,0.08);
  --ins-nav-color:        rgba(255,255,255,0.70);
  --ins-nav-hover-bg:     rgba(255,255,255,0.07);
  --ins-nav-hover-cl:     #ffffff;
  --ins-nav-active-bg:    rgba(255,255,255,0.12);
  --ins-nav-active-cl:    #ffffff;
  --ins-nav-active-acc:   #e8925a;
  --ins-nav-grp-cl:       rgba(255,255,255,0.38);
  --ins-topbar-bg:        #ffffff;
  --ins-topbar-h:         60px;
  --ins-green:            #27AE60;
  --ins-green-b:          #2ECC71;
  --ins-ink:              #111827;
  --ins-ink-muted:        #6b7280;
  --fi-font-family: var(--ins-font);
}

/* ── Typography ─────────────────────────────────────────────── */
body, .fi-body {
  font-family: var(--ins-font) !important;
  font-size: var(--ins-font-size) !important;
  color: var(--ins-body-color) !important;
  background-color: var(--ins-body-bg) !important;
}
*, *::before, *::after { font-family: inherit; }
.fi-main, main.fi-main, .fi-layout-main { background-color: var(--ins-body-bg) !important; }

/* ═══════════════════════════════════════════════════════════════
   SIDEBAR — dark forest green · 260px
   Active: rgba(255,255,255,0.12) tint + orange left accent bar
═══════════════════════════════════════════════════════════════ */
.fi-sidebar {
  background-color: var(--ins-nav-bg) !important;
  border-right: none !important;
  width: 260px !important;
  transition: width 0.22s cubic-bezier(0.4,0,0.2,1) !important;
}
.fi-sidebar-header {
  background-color: var(--ins-nav-bg) !important;
  border-bottom: 1px solid var(--ins-nav-border) !important;
  box-shadow: none !important;
  --tw-ring-shadow: 0 0 #0000 !important;
  min-height: var(--ins-topbar-h) !important;
  display: flex !important;
  align-items: center !important;
  padding-inline: 1.25rem !important;
  padding-block: 1rem !important;
}
.fi-logo img, .fi-sidebar-header img[alt] {
  filter: brightness(2.2) saturate(0.6) !important;
  max-height: 1.875rem !important;
  width: auto !important;
  object-fit: contain !important;
}
.fi-logo, .fi-sidebar-header a, .fi-brand-name {
  color: #ffffff !important;
  font-family: var(--ins-font) !important;
  font-size: 15px !important;
  font-weight: 700 !important;
}
.fi-sidebar-header .fi-icon-btn-icon { color: rgba(255,255,255,0.45) !important; }
.fi-sidebar-header .fi-icon-btn:hover { background: rgba(255,255,255,0.08) !important; border-radius: var(--ins-radius) !important; }
.fi-sidebar-header .fi-icon-btn:hover .fi-icon-btn-icon { color: #ffffff !important; }
.fi-sidebar-nav { padding-block: 0.25rem !important; }
.fi-sidebar-group-label {
  font-family: var(--ins-font) !important;
  font-size: 10.5px !important;
  font-weight: 700 !important;
  letter-spacing: 0.10em !important;
  text-transform: uppercase !important;
  color: var(--ins-nav-grp-cl) !important;
  padding-inline: 1.25rem !important;
  padding-block: 0.35rem 0.05rem !important;
  white-space: nowrap !important;
  overflow: hidden !important;
  text-overflow: ellipsis !important;
}
.fi-sidebar-nav-groups > li + li {
  margin-top: 0.1rem !important;
  padding-top: 0.1rem !important;
  border-top: 1px solid rgba(255,255,255,0.07) !important;
}
.fi-sidebar-group-collapse-button .fi-icon-btn-icon { color: rgba(255,255,255,0.30) !important; }
.fi-sidebar-item-button {
  border-radius: var(--ins-radius) !important;
  margin-inline: 0.5rem !important;
  margin-block: 0 !important;
  padding-block: 0.25rem !important;
  padding-inline: 0.875rem !important;
  min-height: 30px !important;
  display: flex !important;
  align-items: center !important;
  gap: 10px !important;
  position: relative !important;
  transition: background 0.15s ease !important;
}
.fi-sidebar-item-icon { width: 17px !important; height: 17px !important; flex-shrink: 0 !important; }
.fi-sidebar-item:not(.fi-active) .fi-sidebar-item-icon {
  color: var(--ins-nav-color) !important;
  transition: color 0.15s !important;
}
.fi-sidebar-item:not(.fi-active) .fi-sidebar-item-label {
  color: var(--ins-nav-color) !important;
  font-size: 0.875rem !important;
  font-weight: 500 !important;
  transition: color 0.15s !important;
  white-space: nowrap !important;
  overflow: hidden !important;
  text-overflow: ellipsis !important;
}
.fi-sidebar-item-button:hover,
.fi-sidebar-item-button:focus-visible { background: var(--ins-nav-hover-bg) !important; }
.fi-sidebar-item-button:hover .fi-sidebar-item-icon,
.fi-sidebar-item-button:focus-visible .fi-sidebar-item-icon { color: var(--ins-nav-hover-cl) !important; }
.fi-sidebar-item-button:hover .fi-sidebar-item-label,
.fi-sidebar-item-button:focus-visible .fi-sidebar-item-label { color: var(--ins-nav-hover-cl) !important; }
.fi-sidebar-item.fi-active .fi-sidebar-item-button {
  background: var(--ins-nav-active-bg) !important;
  border-left: 3px solid var(--ins-nav-active-acc) !important;
  padding-inline-start: calc(0.875rem - 3px) !important;
}
.fi-sidebar-item.fi-active .fi-sidebar-item-icon { color: var(--ins-nav-active-cl) !important; }
.fi-sidebar-item.fi-active .fi-sidebar-item-label {
  color: var(--ins-nav-active-cl) !important;
  font-weight: 600 !important;
  font-size: 0.875rem !important;
  white-space: nowrap !important;
  overflow: hidden !important;
  text-overflow: ellipsis !important;
}
.fi-sidebar-item .fi-badge {
  font-family: var(--ins-font-mono) !important;
  font-size: 11px !important;
  font-weight: 600 !important;
  background: rgba(255,255,255,0.12) !important;
  color: rgba(255,255,255,0.80) !important;
  border: none !important;
  border-radius: var(--ins-radius-pill) !important;
}
.fi-sidebar-item.fi-active .fi-badge { background: rgba(255,255,255,0.20) !important; color: #fff !important; }
.fi-sidebar-item-grouped-border > div { background: rgba(255,255,255,0.12) !important; }
.fi-sidebar-item-grouped-border .rounded-full { background: rgba(255,255,255,0.30) !important; }
.fi-sidebar-item.fi-active .fi-sidebar-item-grouped-border .rounded-full { background: rgba(255,255,255,0.7) !important; }
.fi-sidebar-footer {
  border-top: 1px solid var(--ins-nav-border) !important;
  background: rgba(0,0,0,0.14) !important;
  padding-block: 0.625rem !important;
}
.fi-user-menu-trigger { border-radius: var(--ins-radius) !important; transition: background 0.15s !important; }
.fi-user-menu-trigger:hover { background: rgba(255,255,255,0.08) !important; }
[class*="fi-user-name"] { color: #ffffff !important; font-size: 13px !important; font-weight: 700 !important; }
[class*="fi-user-email"] { color: rgba(255,255,255,0.45) !important; font-family: var(--ins-font-mono) !important; font-size: 11px !important; }

/* ═══════════════════════════════════════════════════════════════
   TOPBAR
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
   LAYOUT — full-width content, no excess horizontal whitespace
   Filament wraps content in .fi-body (px-4/6/8) then .fi-page
   (max-w-7xl mx-auto). Override both to use full available width.
═══════════════════════════════════════════════════════════════ */
.fi-body {
  padding-inline: 1.25rem !important;
}
.fi-page,
.fi-page > * {
  max-width: 100% !important;
}
.fi-main-ctn,
.fi-simple-main {
  padding-inline: 0 !important;
  max-width: 100% !important;
}
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
   CARDS & SECTIONS
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
   STAT WIDGETS
═══════════════════════════════════════════════════════════════ */
.fi-wi-stats-overview-stat {
  background: var(--ins-secondary-bg) !important;
  border: 1px solid var(--ins-border) !important;
  border-radius: var(--ins-radius-xl) !important;
  box-shadow: none !important;
  padding: 0.9375rem 1.0625rem !important;
  transition: box-shadow 0.18s ease !important;
}
.fi-wi-stats-overview-stat:hover { box-shadow: var(--ins-shadow) !important; }
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
.fi-wi-stats-overview-stat-description { font-size: 12px !important; color: var(--ins-ink-muted) !important; }

/* ═══════════════════════════════════════════════════════════════
   TABLES
═══════════════════════════════════════════════════════════════ */
.fi-ta-ctn, .fi-ta-content, .fi-section-content > div { overflow-x: auto !important; }
.fi-ta-content { border-radius: var(--ins-radius-xl) !important; min-width: 0 !important; }
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
.fi-ta-row:hover > td, .fi-ta-row:hover > .fi-ta-cell { background: #f9fafb !important; }

/* ═══════════════════════════════════════════════════════════════
   BUTTONS
═══════════════════════════════════════════════════════════════ */
.fi-btn {
  font-family: var(--ins-font) !important;
  font-size: 13px !important;
  font-weight: 500 !important;
  border-radius: var(--ins-radius) !important;
  padding-block: 0.4532rem !important;
  padding-inline: 1.1rem !important;
}
.fi-btn-color-primary.fi-btn-style-filled { box-shadow: none !important; transition: opacity 0.15s ease !important; }
.fi-btn-color-primary.fi-btn-style-filled:hover { opacity: 0.9 !important; }

/* ═══════════════════════════════════════════════════════════════
   FORM INPUTS
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
   MODAL
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
   DROPDOWN
═══════════════════════════════════════════════════════════════ */
.fi-dropdown-panel {
  border-radius: var(--ins-radius) !important;
  border: 1px solid var(--ins-border) !important;
  box-shadow: var(--ins-shadow-lg) !important;
  background: var(--ins-secondary-bg) !important;
}
.fi-dropdown-list-item-label { font-size: 13px !important; }

/* ═══════════════════════════════════════════════════════════════
   BADGES
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
   NOTIFICATIONS
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
   LOGIN PAGE
═══════════════════════════════════════════════════════════════ */
.fi-simple-page, .fi-simple-main { background: var(--ins-body-bg) !important; }
.fi-simple-main .fi-section {
  border-radius: var(--ins-radius-xl) !important;
  box-shadow: var(--ins-shadow) !important;
}

/* ═══════════════════════════════════════════════════════════════
   CONTENT DENSITY
═══════════════════════════════════════════════════════════════ */
.fi-main { padding-inline: 1rem !important; padding-block: 0.75rem !important; }
.fi-page-header { padding-block: 0.5rem 0.75rem !important; }
.fi-page-header .fi-breadcrumbs { margin-bottom: 0.15rem !important; }
.fi-page-header h1.fi-header-heading { font-size: 1.35rem !important; line-height: 1.3 !important; }
.fi-ta-ctn { border-radius: 0.625rem !important; }
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
