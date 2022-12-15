<?php

namespace App\Services\Books\V1\Services;

use App\Filters\V1\ReservationsFilter;
use App\Models\Book;
use App\Repositories\Interfaces\BookRepositoryInterface;
use App\Requests\V1\BulkStoreBooksRequest;
use App\Requests\V1\StoreBookRequest;
use App\Requests\V1\UpdateBookRequest;
use App\Resources\V1\BookCollection;
use App\Resources\V1\BookResource;
use App\Resources\V1\ReservationCollection;
use App\Services\Books\V1\DTO\TakeBookDTO;
use App\Services\Books\V1\Exceptions\OutOfStockException;
use App\Services\Reservations\V1\Exceptions\AlreadyReservedException;
use App\Services\Reservations\V1\Services\IsBookReservedService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookService
{
    public function __construct(private BookRepositoryInterface $bookRepository)
    {
    }

    private function getFilterItems(Request $request): array
    {
        $filter = new ReservationsFilter();
        return $filter->transform($request);
    }

    public function getBooks(Request $request): BookCollection
    {
        $filterItems = $this->getFilterItems($request);
        $books = $this->bookRepository->getBooks($filterItems);
        return new BookCollection($books->paginate()->appends($request->query()));
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
    public function takeBook(Book $book): BookResource
    {
        $book->load(['reservations' => function ($query) {
            (new GetNotReturnedBooksService())->execute($query);
        }]);

        $bookIsInStock = (new IsBookInStockService())->execute($book);
        if (!$bookIsInStock) {
            throw new OutOfStockException();
        }

        $bookIsReserved = (new IsBookReservedService())->execute(
            new ReservationCollection($book->reservations),
            Auth::id()
        );
        if ($bookIsReserved) {
            throw new AlreadyReservedException();
        }

        $takeBookDTO = new TakeBookDTO(Auth::id(), $book->id);

        return new BookResource($this->bookRepository->takeBook($takeBookDTO));
    }
}
