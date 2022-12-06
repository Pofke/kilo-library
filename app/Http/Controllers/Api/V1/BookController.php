<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Services\Commands\Books\GetNotReturnedBooks;
use App\Services\Commands\Books\GetStockQuantity;
use App\Services\Repositories\BookRepository;
use App\Services\Requests\BulkStoreBooksRequest;
use App\Services\Requests\StoreBookRequest;
use App\Services\Requests\UpdateBookRequest;
use App\Services\Resources\V1\BookCollection;
use App\Services\Resources\V1\BookResource;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function __construct(private BookRepository $bookRepository)
    {
    }

    public function index(Request $request): BookCollection
    {
        return $this->bookRepository->getBooks($request);
    }

    public function store(StoreBookRequest $request): BookResource
    {
        return $this->bookRepository->createBook($request);
    }

    public function takeBook(Book $book)
    {
        $book->load(['reservations' => function ($query) {
            (new GetNotReturnedBooks())->execute($query);
        }]);
        $this->authorize('takeBookInStock', [Book::class, (new GetStockQuantity())->execute($book)]);
        $this->authorize('takeBookNotTakenAlready', [Book::class, $book]);
        $this->bookRepository->takeBook($book);
    }

    public function show(Book $book): BookResource
    {
        return $this->bookRepository->getBook($book);
    }

    public function update(UpdateBookRequest $request, Book $book): void
    {
        $this->bookRepository->updateBook($request, $book);
    }

    public function destroy(Book $book): void
    {

        $this->bookRepository->deleteBook($book);
    }

    public function bulkStore(BulkStoreBooksRequest $request)
    {
         Book::insert($request->toArray());
    }
}
