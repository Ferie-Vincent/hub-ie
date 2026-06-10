<?php

namespace App\Livewire\Participant;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.candidate', ['title' => 'Mon profil'])]
class Profile extends Component
{
    use WithFileUploads;

    // ── Informations personnelles ──────────────────────────────
    public string $firstName = '';

    public string $lastName = '';

    public string $email = '';

    public string $phone = '';

    public string $city = '';

    public string $nationality = '';

    public string $gender = '';

    public string $birthDate = '';

    // ── Photo ─────────────────────────────────────────────────
    public $photo = null; // uploaded file (temporary)

    // ── Mot de passe ──────────────────────────────────────────
    public string $currentPassword = '';

    public string $newPassword = '';

    public string $confirmPassword = '';

    public function mount(): void
    {
        $user = auth()->user();

        $this->firstName = $user->first_name ?? '';
        $this->lastName = $user->last_name ?? '';
        $this->email = $user->email ?? '';
        $this->phone = $user->phone ?? '';
        $this->city = $user->city ?? '';
        $this->nationality = $user->nationality ?? '';
        $this->gender = $user->gender?->value ?? '';
        $this->birthDate = $user->birth_date?->format('Y-m-d') ?? '';
    }

    public function saveProfile(): void
    {
        $user = auth()->user();

        $validated = $this->validate([
            'firstName' => ['required', 'string', 'max:100'],
            'lastName' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:30'],
            'city' => ['nullable', 'string', 'max:100'],
            'nationality' => ['nullable', 'string', 'max:100'],
            'gender' => ['nullable', 'in:M,F,X'],
            'birthDate' => ['nullable', 'date', 'before:today'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'firstName.required' => 'Le prénom est obligatoire.',
            'lastName.required' => 'Le nom est obligatoire.',
            'email.required' => "L'adresse email est obligatoire.",
            'email.unique' => 'Cette adresse email est déjà utilisée.',
            'birthDate.before' => 'La date de naissance doit être antérieure à aujourd\'hui.',
            'photo.image' => 'Le fichier doit être une image.',
            'photo.max' => 'La photo ne doit pas dépasser 2 Mo.',
        ]);

        $data = [
            'first_name' => $validated['firstName'],
            'last_name' => $validated['lastName'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?: null,
            'city' => $validated['city'] ?: null,
            'nationality' => $validated['nationality'] ?: null,
            'gender' => $validated['gender'] ?: null,
            'birth_date' => $validated['birthDate'] ?: null,
        ];

        // Email changed → re-verify
        if ($user->email !== $data['email']) {
            $data['email_verified_at'] = null;
        }

        // Photo upload
        if ($this->photo) {
            // Delete old photo
            if ($user->photo_path && Storage::disk('public')->exists($user->photo_path)) {
                Storage::disk('public')->delete($user->photo_path);
            }

            $data['photo_path'] = $this->photo->store('avatars', 'public');
            $this->photo = null;
        }

        $user->update($data);

        session()->flash('profile_saved', 'Profil mis à jour avec succès.');

        $this->redirect(route('participant.profile'), navigate: true);
    }

    public function changePassword(): void
    {
        $this->validate([
            'currentPassword' => ['required'],
            'newPassword' => ['required', Password::min(8)->mixedCase()->numbers(), 'different:currentPassword'],
            'confirmPassword' => ['required', 'same:newPassword'],
        ], [
            'currentPassword.required' => 'Le mot de passe actuel est obligatoire.',
            'newPassword.required' => 'Le nouveau mot de passe est obligatoire.',
            'newPassword.different' => 'Le nouveau mot de passe doit être différent du mot de passe actuel.',
            'confirmPassword.same' => 'Les mots de passe ne correspondent pas.',
        ]);

        if (! Hash::check($this->currentPassword, auth()->user()->password)) {
            $this->addError('currentPassword', 'Le mot de passe actuel est incorrect.');

            return;
        }

        auth()->user()->update(['password' => Hash::make($this->newPassword)]);

        $this->reset('currentPassword', 'newPassword', 'confirmPassword');

        session()->flash('password_saved', 'Mot de passe mis à jour avec succès.');
    }

    public function removePhoto(): void
    {
        $user = auth()->user();

        if ($user->photo_path && Storage::disk('public')->exists($user->photo_path)) {
            Storage::disk('public')->delete($user->photo_path);
        }

        $user->update(['photo_path' => null]);

        session()->flash('profile_saved', 'Photo supprimée.');
    }

    public function render(): View
    {
        return view('livewire.participant.profile', [
            'user' => auth()->user(),
        ]);
    }
}
