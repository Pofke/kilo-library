<?php

declare(strict_types=1);

namespace App\Services\Policies;

use App\Models\Book;
use App\Models\User;
use App\Services\Commands\Books\IsBookInStock;
use App\Services\Commands\Reservations\IsBookAlreadyReserved;
use App\Services\Exceptions\AlreadyHaveSameBookException;
use App\Services\Exceptions\BookOutOfStockException;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class BookPolicy
{
    use HandlesAuthorization;

    public function chosenBookInStock(User $user, Book $book)
    {
        $bookIsInStock = (new IsBookInStock())->execute($book);
        if ($bookIsInStock) {
            return Response::allow();
        }
        return Response::denyWithStatus(
            422,
            (new BookOutOfStockException())->execute()
        );
    }

    public function chosenBookIsNotSame(User $user, Book $book): Response
    {
        $bookIsAlreadyReserved = (new IsBookAlreadyReserved())->execute($user->id, $book);
        if ($bookIsAlreadyReserved) {
            return Response::denyWithStatus(
                422,
                (new AlreadyHaveSameBookException())->execute()
            );
        }
        return Response::allow();
    }
}
