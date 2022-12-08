<?php

declare(strict_types=1);

namespace App\Services\Policies;

use App\Models\Reservation;
use App\Models\User;
use App\Services\Commands\Books\GetBookById;
use App\Services\Commands\Books\IsBookInStock;
use App\Services\Commands\Reservations\IsBookAlreadyReserved;
use App\Services\Commands\Reservations\IsStatusReturned;
use App\Services\Commands\Reservations\IsStatusTaken;
use App\Services\Exceptions\AlreadyHaveSameBookException;
use App\Services\Exceptions\BookOutOfStockException;
use App\Services\Exceptions\ReservationStatusUpdateException;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;

class ReservationPolicy
{
    use HandlesAuthorization;


    public function viewAny(User $user): bool
    {
        return !($user->tokenCan('getSelf'));
    }

    public function viewChangeSelf(User $user, Reservation $reservation): bool
    {
        $admin = $user->tokenCan('get');
        $allow = $user->tokenCan('getSelf') && $user->id == $reservation->user_id;
        return $admin || $allow;
    }

    public function updateExtendReservation(User $user, Reservation $reservation): Response
    {
        $isStatusTaken = (new IsStatusTaken())->execute($reservation->status);
        return $isStatusTaken ?
            Response::allow() :
            Response::denyWithStatus(
                422,
                (new ReservationStatusUpdateException())->execute($reservation->status)
            );
    }

    public function storeReservationInStock(User $user, Request $request): Response
    {
        $book = (new GetBookById())->execute($request->bookId);
        $bookStatusIsReturn = (new IsStatusReturned())->execute($request->status);
        $bookIsInStock = (new IsBookInStock())->execute($book);
        if ($bookStatusIsReturn || $bookIsInStock) {
            return Response::allow();
        }
        return
            Response::denyWithStatus(
                422,
                (new BookOutOfStockException())->execute()
            );
    }

    public function storeReservationIsNotSame(User $user, Request $request): Response
    {
        $book = (new GetBookById())->execute($request->bookId);
        $bookIsAlreadyReserved = (new IsBookAlreadyReserved())->execute($request->userId, $book);
        $bookStatusIsReturn = (new IsStatusReturned())->execute($request->status);
        if ($bookIsAlreadyReserved || $bookStatusIsReturn) {
            return Response::denyWithStatus(
                422,
                (new AlreadyHaveSameBookException())->execute()
            );
        }
        return Response::allow();
    }

    public function updateReturnReservation(User $user, Reservation $reservation): Response
    {
        $bookIsReturned = (new IsStatusReturned())->execute($reservation->status);
        return !$bookIsReturned ?
            Response::allow() :
            Response::denyWithStatus(
                422,
                (new ReservationStatusUpdateException())->execute($reservation->status)
            );
    }
    public function delete(User $user, Reservation $reservation)
    {
        return $user->tokenCan('update');
    }
}
