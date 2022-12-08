<?php

declare(strict_types=1);

namespace App\Services\Exceptions;

class BookOutOfStockException
{
    private const ERROR_MESSAGE = 'Book is out of stock.';
    public function execute(): string
    {
        return self::ERROR_MESSAGE;
    }
}
