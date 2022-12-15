<?php

declare(strict_types=1);

namespace App\Services\Reservations\V1\Services;

class IsStatusReturnedService
{
    public function execute(string $status): bool
    {
        return $status == 'R';
    }
}
