<?php

use App\Http\Middleware\EnsureCandidateRole;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
                $middleware->trustProxies(at: '*');
        $middleware->alias([
            'candidate' => EnsureCandidateRole::class,
        ]);

        $middleware->web(append: [
            AddLinkHeadersForPreloadedAssets::class,
        ]);
    })
    ->booted(function () {
        RateLimiter::for('login', fn (Request $r) => Limit::perMinute(5)->by($r->ip()));
        RateLimiter::for('candidature', fn (Request $r) => Limit::perHour(3)->by($r->user()?->id ?: $r->ip()));
        RateLimiter::for('contact', fn (Request $r) => Limit::perHour(5)->by($r->ip()));
        RateLimiter::for('newsletter', fn (Request $r) => Limit::perHour(3)->by($r->ip()));
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
