<?php

namespace App\Http\Controllers;

use App\Mail\PreInscriptionInvitation;
use App\Models\PreInscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PreInscriptionController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'atelier' => ['required', 'in:zlecaf-cedeao,financement-garanties,commerce-electronique,conformite-qualite'],
            'nom' => ['required', 'string', 'max:100'],
            'prenom' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:pre_inscriptions,email', 'unique:users,email'],
            'telephone' => ['nullable', 'string', 'max:30'],
            'entreprise' => ['required', 'string', 'max:150'],
            'secteur' => ['required', 'string', 'max:100'],
            'poste' => ['nullable', 'string', 'max:100'],
            'motivation_projet' => ['nullable', 'string', 'max:2000'],
            'motivation_objectifs' => ['nullable', 'string', 'max:2000'],
        ], [
            'atelier.required' => 'Veuillez choisir un atelier.',
            'atelier.in' => 'Atelier invalide.',
            'nom.required' => 'Le nom est obligatoire.',
            'prenom.required' => 'Le prénom est obligatoire.',
            'email.required' => "L'adresse e-mail est obligatoire.",
            'email.unique' => 'Cette adresse e-mail est déjà enregistrée.',
            'entreprise.required' => "Le nom de l'entreprise est obligatoire.",
            'secteur.required' => "Le secteur d'activité est obligatoire.",
        ]);

        $preInscription = PreInscription::create(array_merge($validated, [
            'invitation_token' => Str::random(64),
            'invitation_sent_at' => now(),
        ]));

        $activationUrl = route('pre-inscription.activate.show', $preInscription->invitation_token);

        Mail::to($preInscription->email)->send(new PreInscriptionInvitation($preInscription, $activationUrl));

        return response()->json(['success' => true]);
    }
}
