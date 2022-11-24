<?php

namespace App\Services\Commands\Books;

use App\Models\Book;
use App\Services\Filters\V1\BooksFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class GetFilteredBooks
{
    public function execute(Request $request): Builder
    {
        $filter = new BooksFilter();
        $filterItems = $filter->transform($request);
        return Book::where($filterItems);
    }
}
