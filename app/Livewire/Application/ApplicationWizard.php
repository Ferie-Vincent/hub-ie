<?php

namespace App\Livewire\Application;

use App\Enums\ApplicationCategory;
use App\Enums\ApplicationStatus;
use App\Enums\DocumentType;
use App\Models\Application;
use App\Models\ApplicationDocument;
use App\Models\Edition;
use App\Models\Workshop;
use App\Services\ApplicationStatusService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;

class ApplicationWizard extends Component
{
    use WithFileUploads;

    public int $step = 1;

    // ── Étape 1 — Identité ───────────────────────────────────────────────────
    public string $firstName = '';

    public string $lastName = '';

    public string $gender = '';

    public string $birthDate = '';

    public string $nationality = 'Ivoirienne';

    public string $phone = '';

    public string $city = '';

    public string $country = "Côte d'Ivoire";

    public $photo = null;

    // ── Étape 2 — Profil professionnel ───────────────────────────────────────
    public string $category = '';

    public string $organizationName = '';

    public string $organizationType = '';

    public string $position = '';

    public string $sector = '';

    public string $experienceYears = '';

    public string $rccmNumber = '';

    public string $website = '';

    public string $professionalEmail = '';

    public string $professionalPhone = '';

    // ── Étape 3 — Motivation et ateliers ─────────────────────────────────────
    public string $motivation = '';

    public ?int $chosenWorkshop = null;

    public string $expectations = '';

    public string $referralSource = '';

    public bool $isFirstParticipation = true;

    // ── Étape 4 — Pièces et confirmation ─────────────────────────────────────
    public $cvFile = null;

    public $rccmFile = null;

    public $idCardFile = null;

    public bool $rgpdConsent = false;

    public bool $communicationConsent = false;

    public ?int $applicationId = null;

    public ?string $draftResumedAt = null;

    public function mount(): void
    {
        $user = auth()->user();
        $this->firstName = $user->first_name ?? '';
        $this->lastName = $user->last_name ?? '';
        $this->phone = $user->phone ?? '';
        $this->city = $user->city ?? '';
        $this->country = $user->country ?? "Côte d'Ivoire";

        if ($user->birth_date) {
            $this->birthDate = $user->birth_date->format('Y-m-d');
        }
        if ($user->gender) {
            $this->gender = $user->gender->value;
        }

        $currentEditionId = Edition::current()?->id;

        $existing = $user->applications()
            ->when($currentEditionId, fn ($q) => $q->where('edition_id', $currentEditionId))
            ->whereNotIn('status', [
                ApplicationStatus::Withdrawn->value,
                ApplicationStatus::Rejected->value,
            ])->first();

        if ($existing) {
            $this->applicationId = $existing->id;
            $this->step = min($existing->current_step + 1, 4);
            $this->draftResumedAt = $existing->updated_at->translatedFormat('d MMMM à H\hi');
            $this->hydrateFromModel($existing);
        }
    }

    private function hydrateFromModel(Application $app): void
    {
        $this->category = $app->category?->value ?? '';
        $this->organizationName = $app->organization_name ?? '';
        $this->organizationType = $app->organization_type ?? '';
        $this->position = $app->position ?? '';
        $this->sector = $app->sector ?? '';
        $this->experienceYears = (string) ($app->experience_years ?? '');
        $this->rccmNumber = $app->rccm_number ?? '';
        $this->website = $app->website ?? '';
        $this->professionalEmail = $app->professional_email ?? '';
        $this->professionalPhone = $app->professional_phone ?? '';
        $this->motivation = $app->motivation ?? '';
        $this->chosenWorkshop = isset($app->chosen_workshops[0]) ? (int) $app->chosen_workshops[0] : null;
        $this->expectations = $app->expectations ?? '';
        $this->referralSource = $app->referral_source ?? '';
        $this->isFirstParticipation = $app->is_first_participation ?? true;
        $this->rgpdConsent = $app->rgpd_consent ?? false;
        $this->communicationConsent = $app->communication_consent ?? false;
    }

    #[Computed]
    public function workshops(): Collection
    {
        return Workshop::where('is_published', true)->orderBy('display_order')->get();
    }

    #[Computed]
    public function requiresRccm(): bool
    {
        if (! $this->category) {
            return false;
        }
        $cat = ApplicationCategory::tryFrom($this->category);

        return $cat?->requiresRccm() ?? false;
    }

    #[Computed]
    public function applicationWindowOpen(): bool
    {
        $opens = config('hub.application_opens_at');
        $closes = config('hub.application_closes_at');
        $now = now();

        if ($opens && $now->lt($opens)) {
            return false;
        }
        if ($closes && $now->gt($closes)) {
            return false;
        }

        return true;
    }

    public function nextStep(): void
    {
        $this->validateStep($this->step);
        $this->persistDraft();
        $this->step++;
    }

    public function previousStep(): void
    {
        $this->step = max(1, $this->step - 1);
    }

    public function goToStep(int $target): void
    {
        if ($target < $this->step) {
            $this->step = $target;
        }
    }

    public function submit(): void
    {
        $this->validateStep(4);

        $app = $this->persistDraft();

        (new ApplicationStatusService)->transition($app, ApplicationStatus::Received);

        $app->update([
            'submitted_at' => now(),
            'submission_ip' => request()->ip(),
            'submission_user_agent' => request()->userAgent(),
        ]);

        $this->storeDocuments($app);

        session()->flash('application_reference', $app->reference_code);

        $this->redirect(route('application.confirmation'), navigate: true);
    }

    private function validateStep(int $step): void
    {
        $this->validate($this->rulesForStep($step), $this->messagesForStep($step));
    }

    private function rulesForStep(int $step): array
    {
        return match ($step) {
            1 => [
                'firstName' => 'required|string|max:80',
                'lastName' => 'required|string|max:80',
                'gender' => ['required', Rule::in(['F', 'M', 'X'])],
                'birthDate' => 'required|date|before:-18 years',
                'nationality' => 'required|string|max:60',
                'phone' => ['required', 'string', 'regex:/^\+[1-9]\d{6,14}$/'],
                'city' => 'required|string|max:100',
                'country' => 'required|string|max:80',
                'photo' => 'nullable|file|mimes:jpg,jpeg,png|max:3072',
            ],
            2 => array_merge([
                'category' => ['required', Rule::enum(ApplicationCategory::class)],
                'organizationName' => 'required|string|max:150',
                'organizationType' => 'required|string|max:100',
                'position' => 'required|string|max:100',
                'sector' => 'required|string|max:120',
                'experienceYears' => 'required|integer|min:0|max:60',
            ], $this->requiresRccm ? ['rccmNumber' => 'required|string|max:50'] : []),
            3 => [
                'motivation' => 'required|string|min:500|max:1500',
                'chosenWorkshop' => 'required|integer|exists:workshops,id',
                'referralSource' => 'required|string|max:50',
            ],
            4 => array_merge([
                'cvFile' => $this->cvFile ? 'file|mimes:pdf,jpg,jpeg,png|max:5120' : 'required',
                'rgpdConsent' => 'accepted',
            ], $this->requiresRccm && ! $this->rccmFile ? ['rccmFile' => 'required'] : [
                'rccmFile' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            ]),
            default => [],
        };
    }

    private function messagesForStep(int $step): array
    {
        return [
            'firstName.required' => 'Le prénom est obligatoire.',
            'lastName.required' => 'Le nom est obligatoire.',
            'gender.required' => 'La civilité est obligatoire.',
            'birthDate.before' => 'Vous devez être majeur(e) pour candidater.',
            'phone.regex' => 'Format international requis : +225XXXXXXXXXX.',
            'city.required' => 'La ville de résidence est obligatoire.',
            'motivation.min' => 'La lettre de motivation doit comporter au moins 500 caractères.',
            'motivation.max' => 'La lettre de motivation ne peut pas dépasser 1500 caractères.',
            'chosenWorkshop.required' => 'Veuillez sélectionner un atelier.',
            'chosenWorkshop.exists' => 'Cet atelier n\'existe pas.',
            'cvFile.required' => 'Le CV est obligatoire.',
            'cvFile.mimes' => 'Le CV doit être au format PDF, JPG ou PNG.',
            'cvFile.max' => 'Le CV ne peut pas dépasser 5 Mo.',
            'rccmFile.required' => "L'attestation RCCM est obligatoire pour votre catégorie.",
            'rgpdConsent.accepted' => 'Vous devez accepter la politique de données personnelles.',
            'rccmNumber.required' => 'Le numéro RCCM est obligatoire pour votre catégorie.',
            'category.required' => 'La catégorie professionnelle est obligatoire.',
            'organizationName.required' => "Le nom de l'organisation est obligatoire.",
        ];
    }

    private function persistDraft(): Application
    {
        $user = auth()->user();
        $data = [
            'status' => ApplicationStatus::Draft,
            'current_step' => $this->step,
            'category' => $this->category ?: null,
            'organization_name' => $this->organizationName ?: null,
            'organization_type' => $this->organizationType ?: null,
            'position' => $this->position ?: null,
            'sector' => $this->sector ?: null,
            'experience_years' => $this->experienceYears !== '' ? (int) $this->experienceYears : null,
            'rccm_number' => $this->rccmNumber ?: null,
            'website' => $this->website ?: null,
            'professional_email' => $this->professionalEmail ?: null,
            'professional_phone' => $this->professionalPhone ?: null,
            'motivation' => $this->motivation ?: null,
            'chosen_workshops' => $this->chosenWorkshop ? [$this->chosenWorkshop] : null,
            'expectations' => $this->expectations ?: null,
            'referral_source' => $this->referralSource ?: null,
            'is_first_participation' => $this->isFirstParticipation,
            'rgpd_consent' => $this->rgpdConsent,
            'communication_consent' => $this->communicationConsent,
        ];

        if ($this->applicationId) {
            $app = Application::findOrFail($this->applicationId);
            $app->update($data);
        } else {
            $data['user_id'] = $user->id;
            $data['reference_code'] = $this->generateReferenceCode();
            $app = Application::create($data);
            $this->applicationId = $app->id;
        }

        $user->update([
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'phone' => $this->phone,
            'city' => $this->city,
            'country' => $this->country,
            'birth_date' => $this->birthDate ?: null,
            'gender' => $this->gender ?: null,
        ]);

        if ($this->photo) {
            $path = $this->photo->store('photos', 'public');
            $user->update(['photo_path' => $path]);
        }

        return $app->fresh();
    }

    private function storeDocuments(Application $app): void
    {
        $uploads = [
            DocumentType::Cv => $this->cvFile,
            DocumentType::Rccm => $this->rccmFile,
            DocumentType::IdCard => $this->idCardFile,
        ];

        foreach ($uploads as $type => $file) {
            if (! $file) {
                continue;
            }
            $path = $file->store("applications/{$app->id}/documents", 'public');
            ApplicationDocument::updateOrCreate(
                ['application_id' => $app->id, 'type' => $type],
                [
                    'original_name' => $file->getClientOriginalName(),
                    'storage_path' => $path,
                    'mime_type' => $file->getMimeType(),
                    'size_bytes' => $file->getSize(),
                ]
            );
        }
    }

    private function generateReferenceCode(): string
    {
        do {
            $code = 'HIE-2026-'.strtoupper(substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 6));
        } while (Application::where('reference_code', $code)->exists());

        return $code;
    }

    public function withdraw(): void
    {
        $app = Application::where('id', $this->applicationId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        (new ApplicationStatusService)->transition($app, ApplicationStatus::Withdrawn);

        session()->flash('status_flash', 'Votre candidature a été retirée.');
        $this->redirect(route('candidate.dashboard'), navigate: true);
    }

    public function render()
    {
        return view('livewire.application.application-wizard', [
            'workshops' => $this->workshops,
            'categories' => ApplicationCategory::cases(),
            'windowOpen' => $this->applicationWindowOpen,
        ]);
    }
}
