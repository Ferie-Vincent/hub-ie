<?php

declare(strict_types=1);

namespace App\Livewire\Enrollment;

use App\Enums\EnrollmentStatus;
use App\Models\Enrollment;
use App\Models\Workshop;
use App\Services\EnrollmentService;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.candidate', ['title' => 'Choisir votre session'])]
class SessionPicker extends Component
{
    public ?int $selectedWorkshopId = null;

    public function mount(): void
    {
        $user = auth()->user();

        $existing = Enrollment::where('user_id', $user->id)
            ->whereIn('status', [
                EnrollmentStatus::Enrolled->value,
                EnrollmentStatus::Waitlisted->value,
            ])
            ->first();

        if ($existing !== null) {
            session()->flash(
                'info',
                'Vous êtes déjà inscrit(e) à une session. Consultez votre tableau de bord.'
            );

            $this->redirectRoute('candidate.dashboard', navigate: true);
        }
    }

    /**
     * Liste des workshops publiés, triés par display_order.
     *
     * @return Collection<int, Workshop>
     */
    #[Computed]
    public function workshops(): Collection
    {
        return Workshop::where('is_published', true)
            ->orderBy('display_order')
            ->orderBy('title')
            ->get();
    }

    public function submit(EnrollmentService $service): void
    {
        $this->validate(
            ['selectedWorkshopId' => ['required', 'integer', 'exists:workshops,id']],
            ['selectedWorkshopId.required' => 'Veuillez sélectionner une session avant de confirmer.']
        );

        $workshop = Workshop::find($this->selectedWorkshopId);

        if ($workshop === null || ! $workshop->is_published) {
            $this->addError('selectedWorkshopId', 'Cette session n\'est pas disponible.');

            return;
        }

        try {
            $enrollment = $service->enroll(auth()->user(), $workshop);
        } catch (\RuntimeException $e) {
            $this->addError('selectedWorkshopId', $e->getMessage());

            return;
        }

        if ($enrollment->status === EnrollmentStatus::Waitlisted) {
            session()->flash('success', 'Vous êtes sur liste d\'attente pour « '.$workshop->title.' ».');
        } else {
            session()->flash('success', 'Inscription confirmée pour « '.$workshop->title.' » !');
        }

        $this->redirectRoute('candidate.dashboard', navigate: true);
    }

    public function render(): View
    {
        return view('livewire.enrollment.session-picker');
    }
}
