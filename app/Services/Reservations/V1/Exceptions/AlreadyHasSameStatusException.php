<?php

namespace App\Services\Reservations\V1\Exceptions;

use App\Services\Reservations\V1\Services\IsStatusReturnedService;

class AlreadyHasSameStatusException extends \Exception
{
    private const ERROR_MESSAGE = 'Book already %s';
    private const EXTENDED_MESSAGE = 'extended';
    private const RETURNED_MESSAGE = 'returned';

    public function __construct(string $status)
    {
        parent::__construct(sprintf(
            self::ERROR_MESSAGE,
            (new IsStatusReturnedService())->execute($status) ?
                self::RETURNED_MESSAGE :
                self::EXTENDED_MESSAGE
        ));
    }
}
