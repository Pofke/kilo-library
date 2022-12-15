<?php

namespace App\Services\Reservations\V1\Services;

use App\Filters\V1\ReservationsFilter;
use App\Models\Reservation;
use App\Repositories\Interfaces\BookRepositoryInterface;
use App\Repositories\Interfaces\ReservationRepositoryInterface;
use App\Requests\V1\StoreReservationRequest;
use App\Requests\V1\UpdateReservationRequest;
use App\Resources\V1\BookResource;
use App\Resources\V1\ReservationCollection;
use App\Resources\V1\ReservationResource;
use App\Services\Books\V1\Exceptions\OutOfStockException;
use App\Services\Reservations\V1\DTO\UpdateStatusDTO;
use App\Services\Reservations\V1\Exceptions\AlreadyHasSameStatusException;
use App\Services\Reservations\V1\Exceptions\AlreadyReservedException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationService
{
    public function __construct(
        private ReservationRepositoryInterface $reservationRepository,
        private BookRepositoryInterface $bookRepository
    ) {
    }


    private function getFilterItems(Request $request): array
    {
        $filter = new ReservationsFilter();
        $filterItems = $filter->transform($request);

        $user = Auth::user();
        if ($user->tokenCan('getSelf')) {
            $filterItems[] = (new AddUserIdFilterService())->execute(Auth::id());
        }
        return $filterItems;
    }

    private function getStockQuantity(BookResource $book): int
    {
        return $book->quantity - count($book->reservations);
    }

    private function isBookInStock(BookResource $book): bool
    {
        return $this->getStockQuantity($book) > 0;
    }

    private function isBookStatusReturned(string $status): bool
    {
        return $status === 'R';
    }

    public function getReservations(Request $request): ReservationCollection
    {
        $filterItems = $this->getFilterItems($request);
        $reservations = $this->reservationRepository->getReservations($filterItems);
        return new ReservationCollection($reservations->paginate()->appends($request->query()));
    }


    /**
     * @throws AlreadyReservedException
     * @throws OutOfStockException
     */
    public function createReservation(StoreReservationRequest $request): ReservationResource
    {
        $book = $this->bookRepository->getBookById($request->bookId);
        $bookIsReturned = $this->isBookStatusReturned($request->status);

        if (!($this->isBookInStock($book) || $bookIsReturned)) {
            throw new OutOfStockException();
        }

        $bookIsReserved = (new IsBookReservedService())->execute(
            new ReservationCollection($book->reservations),
            $request->userId
        );
        if ($bookIsReserved && !$bookIsReturned) {
            throw new AlreadyReservedException();
        }

        return new ReservationResource($this->reservationRepository->createReservation($request));
    }

    public function extendBookReservation(Reservation $reservation): void
    {

        $isStatusTaken = (new IsStatusTakenService())->execute($reservation->status);
        if ($isStatusTaken) {
            throw new AlreadyHasSameStatusException($reservation->status);
        }
        $updateDTO = new UpdateStatusDTO('E');
        $this->reservationRepository->extendReturnReservation($reservation, $updateDTO);
    }

    public function returnBookReservation(Reservation $reservation): void
    {
        $bookIsReturned = (new IsStatusReturnedService())->execute($reservation->status);
        if ($bookIsReturned) {
            throw new AlreadyHasSameStatusException($reservation->status);
        }
        $updateDTO = new UpdateStatusDTO('R');
        $this->reservationRepository->extendReturnReservation($reservation, $updateDTO);
    }

    public function getReservation(Reservation $reservation): ReservationResource
    {
        return $this->reservationRepository->getReservation($reservation);
    }

    public function updateReservation(UpdateReservationRequest $request, Reservation $reservation): void
    {
        $this->reservationRepository->updateReservation($request, $reservation);
    }

    public function deleteReservation(Reservation $reservation): void
    {
        $this->reservationRepository->deleteReservation($reservation);
    }
}
