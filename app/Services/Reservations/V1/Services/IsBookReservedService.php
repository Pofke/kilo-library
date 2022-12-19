<?php

namespace App\Services\Reservations\V1\Services;

use App\Resources\V1\ReservationCollection;

class IsBookReservedService
{
    public function execute(ReservationCollection $reservations, int $userId): bool
    {

        foreach ($reservations as $reservation) {
            if ($reservation["user_id"] === $userId) {
                return true;
            }
        }
        return false;
    }
}
