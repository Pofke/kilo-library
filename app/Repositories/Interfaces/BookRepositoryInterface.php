<?php

namespace App\Repositories\Interfaces;

use App\Models\Book;
use App\Models\Reservation;
use App\Requests\V1\BulkStoreBooksRequest;
use App\Requests\V1\StoreBookRequest;
use App\Requests\V1\UpdateBookRequest;
use App\Resources\V1\BookResource;
use App\Services\Books\V1\DTO\TakeBookDTO;

interface BookRepositoryInterface
{
    public function getBookById(int $bookId): BookResource;
    public function getBooks(array $filterItems);
    public function createBook(StoreBookRequest $request): BookResource;
    public function getBook(Book $book): Book;
    public function updateBook(UpdateBookRequest $request, Book $book): void;
    public function deleteBook(Book $book): void;
    public function bulkAddBook(BulkStoreBooksRequest $request): void;
    public function takeBook(TakeBookDTO $bookDTO): Reservation;
}
