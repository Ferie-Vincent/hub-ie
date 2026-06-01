<?php

namespace App\Livewire\Participant;

use App\Enums\ApplicationStatus;
use App\Models\Application;
use App\Models\WorkshopCourseFile;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('components.layouts.candidate', ['title' => 'Mes documents'])]
class Downloads extends Component
{
    public ?Application $application = null;

    public function mount(): void
    {
        $user = auth()->user();

        $application = $user->applications()
            ->where('status', ApplicationStatus::Accepted->value)
            ->with('workshops')
            ->latest('accepted_at')
            ->first();

        if (! $application) {
            session()->flash('error', 'Vous devez avoir une candidature acceptée pour accéder aux documents de cours.');
            $this->redirect(route('candidate.dashboard'), navigate: true);

            return;
        }

        $this->application = $application;
    }

    #[Computed]
    public function workshops(): Collection
    {
        if (! $this->application) {
            return collect();
        }

        return $this->application->workshops()->orderBy('display_order')->get();
    }

    #[Computed]
    public function courseFiles(): Collection
    {
        if (! $this->application) {
            return collect();
        }

        $workshopIds = $this->workshops->pluck('id');

        return WorkshopCourseFile::where('is_published', true)
            ->whereIn('workshop_id', $workshopIds)
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('workshop_id');
    }

    #[Computed]
    public function hasNewFiles(): bool
    {
        if (! $this->application) {
            return false;
        }

        $workshopIds = $this->workshops->pluck('id');

        return WorkshopCourseFile::where('is_published', true)
            ->whereIn('workshop_id', $workshopIds)
            ->where('created_at', '>=', now()->subDays(7))
            ->exists();
    }

    #[Computed]
    public function newFilesCount(): int
    {
        if (! $this->application) {
            return 0;
        }

        $workshopIds = $this->workshops->pluck('id');

        return WorkshopCourseFile::where('is_published', true)
            ->whereIn('workshop_id', $workshopIds)
            ->where('created_at', '>=', now()->subDays(7))
            ->count();
    }

    public function download(int $fileId): ?StreamedResponse
    {
        if (! $this->application) {
            session()->flash('error', 'Accès non autorisé.');

            return null;
        }

        $workshopIds = $this->workshops->pluck('id');

        $file = WorkshopCourseFile::where('id', $fileId)
            ->where('is_published', true)
            ->whereIn('workshop_id', $workshopIds)
            ->firstOrFail();

        $storagePath = storage_path('app/public/'.$file->file_path);

        if (! file_exists($storagePath)) {
            session()->flash('error', 'Le fichier demandé est introuvable. Veuillez contacter l\'équipe organisatrice.');

            return null;
        }

        return response()->download($storagePath, $file->original_filename, [
            'Content-Type' => $file->mime_type,
        ]);
    }

    public function render()
    {
        return view('livewire.participant.downloads', [
            'workshops' => $this->workshops,
            'courseFiles' => $this->courseFiles,
            'hasNewFiles' => $this->hasNewFiles,
            'newFilesCount' => $this->newFilesCount,
        ]);
    }
}
