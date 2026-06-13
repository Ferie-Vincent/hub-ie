<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Models\Edition;
use Illuminate\Auth\Events\Login;

class ReactivateReturningUser
{
    public function handle(Login $event): void
    {
        $user = $event->user;

        if ($user->is_active) {
            return;
        }

        if (! Edition::current()?->isRegistrationOpen()) {
            return;
        }

        $user->update(['is_active' => true]);
    }
}
