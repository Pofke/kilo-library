<?php

declare(strict_types=1);

namespace App\Services\Books\V1\Services;

class GetNotReturnedBooksService
{
    public function execute($query): void
    {
        $query->where("reservations.status", "!=", "R");
    }
}
