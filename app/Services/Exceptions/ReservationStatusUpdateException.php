<?php

declare(strict_types=1);

namespace App\Services\Exceptions;

use App\Services\Commands\Reservations\IsStatusReturned;

class ReservationStatusUpdateException
{
    private const ERROR_MESSAGE = 'Book already %s';
    private const EXTENDED_MESSAGE = 'extended';
    private const RETURNED_MESSAGE = 'returned';
    public function execute(string $status): string
    {
        return sprintf(
            self::ERROR_MESSAGE,
            (new IsStatusReturned())->execute($status) ?
                self::RETURNED_MESSAGE :
                self::EXTENDED_MESSAGE
        );
    }
}
