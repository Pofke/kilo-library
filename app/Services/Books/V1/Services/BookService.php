<?php

namespace App\Services\Books\V1\Services;

use App\Filters\V1\BooksFilter;
use App\Models\Book;
use App\Repositories\Interfaces\BookRepositoryInterface;
use App\Requests\V1\BulkStoreBooksRequest;
use App\Requests\V1\StoreBookRequest;
use App\Requests\V1\UpdateBookRequest;
use App\Resources\V1\BookCollection;
use App\Resources\V1\BookResource;
use App\Resources\V1\ReservationCollection;
use App\Resources\V1\ReservationResource;
use App\Services\Books\V1\DTO\TakeBookDTO;
use App\Services\Books\V1\Exceptions\OutOfStockException;
use App\Services\Reservations\V1\Exceptions\AlreadyReservedException;
use App\Services\Reservations\V1\Services\IsBookReservedService;
use Illuminate\Support\Facades\Auth;

class BookService
{
    public function __construct(private BookRepositoryInterface $bookRepository)
    {
    }

    public function getBooks(array $request): BookCollection
    {
        $filterItems = (new BooksFilter())->transform($request);
        $books = $this->bookRepository->getBooks($filterItems);
        return new BookCollection($books->paginate()->appends($request));
    }

    public function createBook(StoreBookRequest $request): BookResource
    {
        return $this->bookRepository->createBook($request);
    }

    public function getBook(Book $book): BookResource
    {
        return new BookResource($this->bookRepository->getBook($book));
    }

    public function updateBook(UpdateBookRequest $request, Book $book): void
    {
        $this->bookRepository->updateBook($request, $book);
    }

    public function deleteBook(Book $book): void
    {
        $this->bookRepository->deleteBook($book);
    }

    public function bulkAddBook(BulkStoreBooksRequest $request): void
    {
        $this->bookRepository->bulkAddBook($request);
    }

    /**
     * @throws AlreadyReservedException
     * @throws OutOfStockException
     */
    public function takeBook(Book $book): ReservationResource
    {
        $book = $this->bookRepository->getBook($book);

        $bookIsReserved = (new IsBookReservedService())->execute(
            new ReservationCollection($book->reservations),
            Auth::id()
        );
        if ($bookIsReserved) {
            throw new AlreadyReservedException();
        }

        $bookIsInStock = (new IsBookInStockService())->execute($book);
        if (!$bookIsInStock) {
            throw new OutOfStockException();
        }

        $takeBookDTO = new TakeBookDTO($book->id);

        return new ReservationResource($this->bookRepository->takeBook($takeBookDTO));
    }
}
