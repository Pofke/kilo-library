<?php

declare(strict_types=1);

namespace App\Services\Books\V1\Services;

use App\Models\Book;

class IsBookInStockService
{
    public function execute(Book $book): bool
    {
        return $book->quantity > count($book->reservations);
    }
}
