<?php

namespace App\Services\Books\V1\Exceptions;

class OutOfStockException extends \Exception
{
    private const ERROR_MESSAGE = 'Book is out of stock.';

    public function __construct()
    {
        parent::__construct(self::ERROR_MESSAGE);
    }
}
