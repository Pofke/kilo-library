<?php

declare(strict_types=1);

namespace App\Services\Books\V1\Services;

use App\Services\Utils\Constants;

class GetNotReturnedBooksService
{
    public function execute($query): void
    {
        $query->where("reservations.status", "!=", Constants::STATUS_RETURNED);
    }
}
