<?php

namespace App\Services\Commands\Books;

use App\Models\Book;

class GetStockQuantity
{
    public function execute(Book $book): int
    {
         return $book->quantity - count($book->reservations);
    }
}
