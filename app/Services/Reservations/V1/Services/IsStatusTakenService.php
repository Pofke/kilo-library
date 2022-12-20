<?php

namespace App\Services\Reservations\V1\Services;

use App\Services\Utils\Constants;

class IsStatusTakenService
{
    public function execute(string $status): bool
    {
        return $status == Constants::STATUS_TAKEN;
    }
}
