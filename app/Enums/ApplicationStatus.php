<?php

namespace App\Enums;

enum ApplicationStatus: string
{
    case Draft = 'draft';
    case Received = 'received';
    case Incomplete = 'incomplete';
    case Eligible = 'eligible';
    case UnderReview = 'under_review';
    case Shortlisted = 'shortlisted';
    case Accepted = 'accepted';
    case Waitlisted = 'waitlisted';
    case Rejected = 'rejected';
    case Withdrawn = 'withdrawn';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Brouillon',
            self::Received => 'Candidature reçue',
            self::Incomplete => 'Dossier incomplet',
            self::Eligible => 'Dossier recevable',
            self::UnderReview => 'En évaluation',
            self::Shortlisted => 'Présélectionné(e)',
            self::Accepted => 'Retenu(e) — Auditeur confirmé',
            self::Waitlisted => 'Sur liste d\'attente',
            self::Rejected => 'Non retenu(e)',
            self::Withdrawn => 'Candidature retirée',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Draft => 'gray',
            self::Received => 'blue',
            self::Incomplete => 'warning',
            self::Eligible => 'cyan',
            self::UnderReview => 'indigo',
            self::Shortlisted => 'purple',
            self::Accepted => 'success',
            self::Waitlisted => 'orange',
            self::Rejected => 'danger',
            self::Withdrawn => 'gray',
        };
    }

    public function isActive(): bool
    {
        return ! in_array($this, [self::Draft, self::Withdrawn, self::Rejected]);
    }

    public function canWithdraw(): bool
    {
        return in_array($this, [
            self::Received,
            self::Incomplete,
            self::Eligible,
            self::UnderReview,
            self::Shortlisted,
            self::Accepted,
            self::Waitlisted,
        ]);
    }
}
