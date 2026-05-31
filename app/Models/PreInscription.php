<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PreInscription extends Model
{
    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'telephone',
        'entreprise',
        'secteur',
        'poste',
        'atelier',
        'motivation_projet',
        'motivation_objectifs',
    ];
}
