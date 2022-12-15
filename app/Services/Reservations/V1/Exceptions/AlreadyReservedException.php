<?php

namespace App\Services\Reservations\V1\Exceptions;

class AlreadyReservedException extends \Exception
{
    private const ERROR_MESSAGE = 'User already have same book.';

    public function __construct()
    {
        parent::__construct(self::ERROR_MESSAGE);
    }
}
