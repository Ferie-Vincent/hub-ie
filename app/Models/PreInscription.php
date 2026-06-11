<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'invitation_token',
        'invitation_sent_at',
        'user_id',
    ];

    protected function casts(): array
    {
        return ['invitation_sent_at' => 'datetime'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
