<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\PreInscriptionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\QrScanController;
use App\Http\Controllers\SitemapController;
use App\Livewire\Participant\Badge as ParticipantBadge;
use App\Livewire\Participant\Downloads as ParticipantDownloads;
use App\Livewire\Participant\Messages as ParticipantMessages;
use App\Livewire\Participant\Profile;
use Illuminate\Support\Facades\Route;

// ── Site public (BRIEF §III.1, §IV.6) ───────────────────────────────────────
Route::get('/', fn () => view('public.home'))->name('home');
Route::get('/programme', fn () => view('public.programme'))->name('programme');
Route::get('/ateliers', [PublicController::class, 'ateliers'])->name('ateliers.index');
Route::get('/ateliers/{slug}', [PublicController::class, 'atelier'])->name('ateliers.show');
Route::get('/partenaires', [PublicController::class, 'partenaires'])->name('partenaires');
Route::get('/actualites', [PublicController::class, 'actualites'])->name('actualites.index');
Route::get('/actualites/{slug}', [PublicController::class, 'actualite'])->name('actualites.show');
Route::get('/portfolio', [PublicController::class, 'portfolio'])->name('portfolio');
Route::get('/presse', fn () => view('public.presse'))->name('presse');
Route::get('/faq', fn () => view('public.faq'))->name('faq');
Route::get('/contact', fn () => view('public.contact'))->name('contact');
Route::post('/contact', fn () => back()->with('contact_sent', true))->middleware('throttle:contact')->name('contact.submit');
Route::get('/mentions-legales', fn () => view('public.mentions-legales'))->name('mentions-legales');
Route::get('/politique-de-confidentialite', fn () => view('public.politique-confidentialite'))->name('politique-confidentialite');
Route::get('/conditions-utilisation', fn () => view('public.conditions-utilisation'))->name('conditions-utilisation');

Route::get('/inscription', fn () => view('public.inscription'))->name('inscription');

// ── Newsletter (double opt-in, BRIEF §IV.6) ──────────────────────────────────
Route::post('/newsletter/subscribe', [NewsletterController::class, 'store'])->middleware('throttle:newsletter')->name('newsletter.subscribe');
Route::post('/newsletter', [NewsletterController::class, 'store'])->middleware('throttle:newsletter')->name('newsletter.store');
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
    Route::get('/candidature/confirmation', fn () => view('candidature.confirmation'))
        ->name('application.confirmation');
    Route::get('/mon-espace', fn () => view('candidate.dashboard'))
        ->name('candidate.dashboard');
    Route::delete('/mon-espace/retirer', [ApplicationController::class, 'withdraw'])
        ->name('application.withdraw');
    Route::get('/mon-espace/badge', ParticipantBadge::class)
        ->name('participant.badge');
    Route::get('/mon-espace/badge/telecharger', [EnrollmentController::class, 'downloadBadge'])
        ->name('participant.badge.download');
    Route::get('/mon-espace/documents', ParticipantDownloads::class)
        ->name('participant.downloads');
    Route::get('/mon-espace/messages', ParticipantMessages::class)
        ->name('participant.messages');
    Route::get('/mon-espace/profil', Profile::class)
        ->name('participant.profile');
});

// ── QR scan confirmation (signed URL, BRIEF §III.6) ─────────────────────────
Route::get('/scan/qr/{token}', [QrScanController::class, 'handle'])
    ->middleware('signed')
    ->name('scan.qr');

// ── Enrollments — désinscription + scan ─────────────────────────────────────
Route::get('/inscription/annuler/{token}', [EnrollmentController::class, 'showCancelPage'])
    ->middleware('signed')
    ->name('enrollment.cancel');

Route::post('/inscription/annuler/{token}', [EnrollmentController::class, 'confirmCancel'])
    ->middleware('signed')
    ->name('enrollment.cancel.confirm');

Route::get('/scan/enrollment/{token}', [EnrollmentController::class, 'scan'])
    ->middleware('signed')
    ->name('enrollment.scan');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
