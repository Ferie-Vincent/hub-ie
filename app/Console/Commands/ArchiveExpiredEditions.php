<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Edition;
use Illuminate\Console\Command;

class ArchiveExpiredEditions extends Command
{
    protected $signature = 'hub:archive-editions';

    protected $description = 'Archive editions whose event has ended (event_ends_at < now)';

    public function handle(): int
    {
        $expired = Edition::where('is_active', true)
            ->whereNotNull('event_ends_at')
            ->where('event_ends_at', '<', now())
            ->get();

        if ($expired->isEmpty()) {
            $this->info('No editions to archive.');

            return self::SUCCESS;
        }

        foreach ($expired as $edition) {
            $edition->update([
                'is_active' => false,
                'registration_open' => false,
            ]);
            $this->info("Archived: {$edition->title} ({$edition->year})");
        }

        return self::SUCCESS;
    }
}
