<?php

declare(strict_types=1);

namespace App\Services\Commands\Reservations;

use App\Models\Book;

class IsBookAlreadyReserved
{
    public function execute(int $userId, Book $book): bool
    {
        foreach ($book->reservations as $reservation) {
            if ($reservation->user_id === $userId) {
                return true;
            }
        }
        return false;
    }
}
