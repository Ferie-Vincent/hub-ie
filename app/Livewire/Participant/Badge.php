<?php

declare(strict_types=1);

namespace App\Livewire\Participant;

use App\Enums\EnrollmentStatus;
use App\Models\Enrollment;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.candidate', ['title' => 'Mon badge'])]
class Badge extends Component
{
    public ?Enrollment $enrollment = null;

    public function mount(): void
    {
        $this->enrollment = auth()->user()
            ->enrollment()
            ->with('workshop')
            ->first();
    }

    #[Computed]
    public function isEnrolled(): bool
    {
        return $this->enrollment?->status === EnrollmentStatus::Enrolled;
    }

    #[Computed]
    public function isWaitlisted(): bool
    {
        return $this->enrollment?->status === EnrollmentStatus::Waitlisted;
    }

    public function render()
    {
        return view('livewire.participant.badge', [
            'enrollment' => $this->enrollment,
            'isEnrolled' => $this->isEnrolled,
            'isWaitlisted' => $this->isWaitlisted,
        ]);
    }
}
