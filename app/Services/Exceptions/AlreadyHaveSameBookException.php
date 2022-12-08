<?php

declare(strict_types=1);

namespace App\Services\Exceptions;

class AlreadyHaveSameBookException
{
    private const ERROR_MESSAGE = 'User already have same book.';
    public function execute(): string
    {
        return self::ERROR_MESSAGE;
    }
}
