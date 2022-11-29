<?php

namespace App\Services\Repositories;

use App\Models\Book;
use App\Models\Reservation;
use App\Services\Commands\Books\GetFilteredBooks;
use App\Services\Commands\Books\GetNotReturnedBooks;
use App\Services\Requests\StoreBookRequest;
use App\Services\Requests\UpdateBookRequest;
use App\Services\Resources\V1\BookCollection;
use App\Services\Resources\V1\BookResource;
use App\Services\Resources\V1\ReservationResource;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookRepository
{
    public function getBooks(Request $request): BookCollection
    {
        $books = (new getFilteredBooks())->execute($request);
        $books->withCount(['reservations' => function ($query) {
            (new GetNotReturnedBooks())->execute($query);
        }]);
        return new BookCollection($books->paginate()->appends($request->query()));
    }

    public function getBook(Book $book): BookResource
    {
        $book->loadCount(['reservations' => function ($query) {
            (new GetNotReturnedBooks())->execute($query);
        }]);
        return new BookResource($book);
    }

    public function takeBook(Book $book): ReservationResource
    {
        $request = [
            'user_id' => Auth::id(),
            'book_id' => $book->id
        ];
        return new ReservationResource(Reservation::create($request));
    }

    public function createBook(StoreBookRequest $request): BookResource
    {
        return new BookResource(Book::create($request->all()));
    }

    public function updateBook(UpdateBookRequest $request, Book $book): void
    {
        $book->update($request->all());
    }

    public function deleteBook(Book $book): void
    {
        $book->delete();
    }
}
