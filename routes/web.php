<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\PreInscriptionController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

// ── Site public (BRIEF §III.1, §IV.6) ───────────────────────────────────────
Route::get('/', fn () => view('public.home'))->name('home');
Route::get('/programme', fn () => view('public.programme'))->name('programme');
Route::get('/ateliers', fn () => view('public.ateliers'))->name('ateliers.index');
Route::get('/ateliers/{slug}', fn (string $slug) => view('public.atelier-show', compact('slug')))->name('ateliers.show');
Route::get('/partenaires', fn () => view('public.partenaires'))->name('partenaires');
Route::get('/actualites', fn () => view('public.actualites'))->name('actualites.index');
Route::get('/actualites/{slug}', fn (string $slug) => view('public.actualite-show', compact('slug')))->name('actualites.show');
Route::get('/presse', fn () => view('public.presse'))->name('presse');
Route::get('/faq', fn () => view('public.faq'))->name('faq');
Route::get('/contact', fn () => view('public.contact'))->name('contact');
Route::post('/contact', fn () => back()->with('contact_sent', true))->name('contact.submit');
Route::get('/mentions-legales', fn () => view('public.mentions-legales'))->name('mentions-legales');
Route::get('/politique-de-confidentialite', fn () => view('public.politique-confidentialite'))->name('politique-confidentialite');
Route::get('/conditions-utilisation', fn () => view('public.conditions-utilisation'))->name('conditions-utilisation');

Route::get('/inscription', fn () => view('public.inscription'))->name('inscription');

// ── Newsletter (double opt-in, BRIEF §IV.6) ──────────────────────────────────
Route::post('/newsletter/subscribe', [NewsletterController::class, 'store'])->name('newsletter.subscribe');
Route::post('/newsletter', [NewsletterController::class, 'store'])->name('newsletter.store');
Route::get('/newsletter/confirm/{token}', [NewsletterController::class, 'confirm'])->name('newsletter.confirm');
Route::get('/newsletter/unsubscribe/{token}', [NewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe');

Route::post('/pre-inscription', [PreInscriptionController::class, 'store'])->name('pre-inscription.store');

// ── Sitemap XML (BRIEF §III.11) ───────────────────────────────────────────────
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// ── Page de démo composants (env local uniquement) ───────────────────────────
if (app()->isLocal()) {
    Route::get('/dev/components', fn () => view('public.dev-components'))->name('dev.components');
}

// ── Redirection dashboard Breeze → portail selon rôle ───────────────────────
Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user?->hasRole('candidate')) {
        return redirect()->route('candidate.dashboard');
    }
    return redirect()->route('filament.admin.pages.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified', 'candidate'])->group(function () {
    Route::get('/candidature', fn () => view('candidature.index'))
        ->name('candidature.index');
    Route::get('/mon-espace', fn () => view('candidate.dashboard'))
        ->name('candidate.dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
