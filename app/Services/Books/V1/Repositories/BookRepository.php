<?php

declare(strict_types=1);

namespace App\Services\Books\V1\Repositories;

use App\Models\Book;
use App\Models\Reservation;
use App\Repositories\Interfaces\BookRepositoryInterface;
use App\Requests\V1\BulkStoreBooksRequest;
use App\Requests\V1\StoreBookRequest;
use App\Requests\V1\UpdateBookRequest;
use App\Resources\V1\BookResource;
use App\Services\Books\V1\DTO\TakeBookDTO;
use App\Services\Books\V1\Services\GetNotReturnedBooksService;
use Illuminate\Database\Eloquent\Builder;

class BookRepository implements BookRepositoryInterface
{
    public function getBooks(array $filterItems): Builder
    {
        $books = Book::where($filterItems);
        $books->with(['reservations' => function ($query) {
            (new GetNotReturnedBooksService())->execute($query);
        }]);
        return $books;
    }

    public function getBook(Book $book): Book
    {
        $book->load(['reservations' => function ($query) {
            (new GetNotReturnedBooksService())->execute($query);
        }]);
        return $book;
    }

    public function takeBook(TakeBookDTO $bookDTO): Reservation
    {
        return Reservation::create(
            $bookDTO->toArray()
        );
    }

    public function createBook(StoreBookRequest $request): BookResource
    {
        return new BookResource(Book::create($request->all()));
    }

    public function updateBook(UpdateBookRequest $request, Book $book): void
    {
        $book->update($request->all());
    }

    public function bulkAddBook(BulkStoreBooksRequest $request): void
    {
        Book::insert($request->toArray());
    }

    public function deleteBook(Book $book): void
    {
        $book->delete();
    }

    public function getBookById(int $bookId): Book
    {
        $book = Book::findOrFail($bookId)->load(['reservations' => function ($query) {
            (new GetNotReturnedBooksService())->execute($query);
        }]);

        return $book;
    }
}
