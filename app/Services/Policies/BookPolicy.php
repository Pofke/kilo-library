<?php

namespace App\Services\Policies;

use App\Models\Book;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class BookPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        //
    }

    public function view(User $user, Book $book)
    {
        //
    }

    public function create(User $user)
    {
        //
    }

    public function update(User $user, Book $book)
    {
        //
    }


    public function takeBookNotTakenAlready(User $user, Book $book): Response
    {

        foreach ($book->reservations as $reservation) {
            if ($reservation->user_id === $user->id) {
                return Response::denyWithStatus(
                    422,
                    'Already have same book.'
                );
            }
        }
        return Response::allow();
    }

    public function takeBookInStock(User $user, int $stock): Response
    {
        return $stock > 0 ?
            Response::allow() :
            Response::denyWithStatus(
                422,
                'Book is not in stock.'
            );
    }

    public function delete(User $user, Book $book)
    {
        //
    }

    public function restore(User $user, Book $book)
    {
        //
    }

    public function forceDelete(User $user, Book $book)
    {
        //
    }
}
