<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Requests\V1\BulkStoreBooksRequest;
use App\Requests\V1\StoreBookRequest;
use App\Requests\V1\UpdateBookRequest;
use App\Resources\V1\BookCollection;
use App\Resources\V1\BookResource;
use App\Services\Books\V1\Exceptions\OutOfStockException;
use App\Services\Books\V1\Services\BookService;
use App\Services\Reservations\V1\Exceptions\AlreadyReservedException;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function __construct(private BookService $bookService)
    {
    }

    public function index(Request $request): BookCollection
    {
        return $this->bookService->getBooks($request);
    }

    public function store(StoreBookRequest $request): BookResource
    {
        return $this->bookService->createBook($request);
    }

    /**
     * @throws AlreadyReservedException
     * @throws OutOfStockException
     */
    public function takeBook(Book $book): void
    {
        $this->bookService->takeBook($book);
    }

    public function show(Book $book): BookResource
    {
        return $this->bookService->getBook($book);
    }

    public function update(UpdateBookRequest $request, Book $book): void
    {
        $this->bookService->updateBook($request, $book);
    }

    public function destroy(Book $book): void
    {
        $this->bookService->deleteBook($book);
    }

    public function bulkStore(BulkStoreBooksRequest $request): void
    {
        $this->bookService->bulkAddBook($request);
    }
}
