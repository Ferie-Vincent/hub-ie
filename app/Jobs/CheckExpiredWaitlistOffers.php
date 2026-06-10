<?php

namespace App\Jobs;

use App\Models\Enrollment;
use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckExpiredWaitlistOffers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $expired = Enrollment::expiredOffer()->with(['user', 'workshop'])->get();

        if ($expired->isEmpty()) {
            return;
        }

        // Notifie les admins via Filament Notification
        $admins = User::role(['super_admin', 'admin_dgce'])->get();

        foreach ($admins as $admin) {
            Notification::make()
                ->title("{$expired->count()} offre(s) waitlist expirée(s) — action requise")
                ->body('Des places en liste d\'attente n\'ont pas été acceptées dans les 24h. Vérifiez la liste.')
                ->warning()
                ->sendToDatabase($admin);
        }
    }
}
