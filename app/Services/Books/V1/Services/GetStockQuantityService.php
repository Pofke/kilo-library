<?php

declare(strict_types=1);

namespace App\Services\Books\V1\Services;

use App\Models\Book;

class GetStockQuantityService
{
    public function execute(Book $book): int
    {
        return $book->quantity - count($book->reservations);
    }
}
