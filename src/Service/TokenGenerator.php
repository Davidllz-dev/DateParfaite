<?php

namespace App\Service;

use Symfony\Component\Uid\Uuid;

class TokenGenerator
{
    public function generate(): string
    {
        return Uuid::v4()->toRfc4122(); // Génère un UUID v4, formaté correctement
    }
}
