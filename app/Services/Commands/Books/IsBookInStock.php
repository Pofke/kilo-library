<?php

declare(strict_types=1);

namespace App\Services\Commands\Books;

use App\Models\Book;

class IsBookInStock
{
    public function execute(Book $book): bool
    {
        return ((new GetStockQuantity())->execute($book) > 0);
    }
}
