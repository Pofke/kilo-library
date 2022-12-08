<?php

declare(strict_types=1);

namespace App\Services\Commands\Reservations;

class IsStatusReturned
{
    public function execute(string $status): bool
    {
        return $status == 'R';
    }
}
