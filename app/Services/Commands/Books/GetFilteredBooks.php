<?php

declare(strict_types=1);

namespace App\Services\Commands\Books;

use App\Models\Book;
use App\Services\Filters\V1\BooksFilter;
use Illuminate\Http\Request;

class GetFilteredBooks
{
    public function execute(Request $request): Book
    {
        $filter = new BooksFilter();
        $filterItems = $filter->transform($request);
        return Book::where($filterItems);
    }
}
