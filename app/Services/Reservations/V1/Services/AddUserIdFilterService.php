<?php

declare(strict_types=1);

namespace App\Services\Reservations\V1\Services;

class AddUserIdFilterService
{
    public function execute(int $userId): array
    {
        return ["user_id", '=', $userId];
    }
}
