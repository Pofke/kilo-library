<?php

declare(strict_types=1);

namespace App\Services\Commands\Books;

use App\Models\Book;

class GetBookById
{
    public function execute(int $bookId): Book
    {
        return Book::findOrFail($bookId)->load(['reservations' => function ($query) {
            (new GetNotReturnedBooks())->execute($query);
        }]);
    }
}
