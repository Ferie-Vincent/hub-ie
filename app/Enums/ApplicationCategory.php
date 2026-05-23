<?php

namespace App\Enums;

enum ApplicationCategory: string
{
    case AgroTransformateur = 'agro_transformateur';
    case PmeExportatrice = 'pme_exportatrice';
    case Transitaire = 'transitaire';
    case BanqueFinance = 'banque_finance';
    case DouaneAdministration = 'douane_administration';
    case AgentAppui = 'agent_appui';
    case AgentMcia = 'agent_mcia';
    case Transporteur = 'transporteur';
    case Universitaire = 'universitaire';
    case Journaliste = 'journaliste';
    case SocieteCivile = 'societe_civile';
    case Autre = 'autre';

    public function label(): string
    {
        return match($this) {
            self::AgroTransformateur   => 'Agro-transformateur',
            self::PmeExportatrice      => 'PME exportatrice / importatrice',
            self::Transitaire          => 'Transitaire',
            self::BanqueFinance        => 'Banque / assurance / finance',
            self::DouaneAdministration => 'Administration douanière',
            self::AgentAppui           => 'Agent d\'agence d\'appui (ACIEx, CNE, GUCE-CI…)',
            self::AgentMcia            => 'Agent MCIA',
            self::Transporteur         => 'Transporteur',
            self::Universitaire        => 'Universitaire / chercheur',
            self::Journaliste          => 'Journaliste',
            self::SocieteCivile        => 'Société civile',
            self::Autre                => 'Autre',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::AgroTransformateur, self::PmeExportatrice => 'success',
            self::BanqueFinance                             => 'indigo',
            self::Journaliste                               => 'orange',
            self::Universitaire                             => 'purple',
            default                                         => 'gray',
        };
    }

    public function requiresRccm(): bool
    {
        return in_array($this, [self::AgroTransformateur, self::PmeExportatrice]);
    }
}
