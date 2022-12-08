<?php

declare(strict_types=1);

namespace App\Services\Commands\Reservations;

class AddExtraFilterForReader
{
    public function execute(int $userId): array
    {
        return ["user_id", '=', $userId];
    }
}
