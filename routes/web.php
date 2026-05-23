<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Compatibility shim for Breeze nav templates that reference route('dashboard')
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
