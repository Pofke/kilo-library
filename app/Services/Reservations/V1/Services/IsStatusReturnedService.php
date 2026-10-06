<?php

declare(strict_types=1);

namespace App\Services\Reservations\V1\Services;

use App\Services\Utils\Constants;

class IsStatusReturnedService
{
    public function execute(string $status): bool
    {
        return $status == Constants::STATUS_RETURNED;
    }
}
