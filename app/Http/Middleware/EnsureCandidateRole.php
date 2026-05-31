<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCandidateRole
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()?->hasRole('candidate')) {
            if ($request->user()?->hasAnyRole(['super_admin', 'committee_president', 'committee_member', 'admin_dgce', 'communication', 'agent_entry', 'reader'])) {
                return redirect()->route('filament.admin.pages.dashboard');
            }

            abort(403, 'Accès réservé aux candidats.');
        }

        return $next($request);
    }
}
