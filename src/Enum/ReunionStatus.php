<?php

namespace App\Enum;

enum ReunionStatus: string
{
    case EN_ATTENTE = 'en_attente';
    case CONFIRMEE = 'confirmée';
    case ANNULEE = 'annulée';
}
