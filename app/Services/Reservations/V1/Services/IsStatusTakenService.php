<?php

namespace App\Services\Reservations\V1\Services;

class IsStatusTakenService
{
    public function execute(string $status): bool
    {
        return $status == 'T';
    }
}
