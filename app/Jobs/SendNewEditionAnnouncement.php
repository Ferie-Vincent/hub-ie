<?php

namespace App\Jobs;

use App\Mail\NewEditionLaunched;
use App\Models\Edition;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendNewEditionAnnouncement implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 600;

    public int $tries = 3;

    public function __construct(public readonly Edition $edition) {}

    public function handle(): void
    {
        // All users who participated in a previous edition (submitted application, any status)
        User::whereHas('applications', function ($q) {
            $q->whereNotNull('submitted_at')
                ->where('edition_id', '!=', $this->edition->id);
        })
            ->whereNotNull('email_verified_at')
            ->chunkById(100, function ($users) {
                foreach ($users as $user) {
                    Mail::to($user->email)
                        ->queue(new NewEditionLaunched($this->edition, $user));
                }
            });

        $this->edition->update(['launched_at' => now()]);
    }
}
