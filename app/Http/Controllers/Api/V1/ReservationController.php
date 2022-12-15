<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Requests\V1\StoreReservationRequest;
use App\Requests\V1\UpdateReservationRequest;
use App\Resources\V1\ReservationCollection;
use App\Resources\V1\ReservationResource;
use App\Services\Books\V1\Exceptions\OutOfStockException;
use App\Services\Reservations\V1\Exceptions\AlreadyHasSameStatusException;
use App\Services\Reservations\V1\Exceptions\AlreadyReservedException;
use App\Services\Reservations\V1\Services\ReservationService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function __construct(
        private ReservationService $reservationService
    ) {
    }

    public function index(Request $request): ReservationCollection
    {
        return $this->reservationService->getReservations($request);
    }

    /**
     * @throws AlreadyReservedException
     * @throws OutOfStockException
     */
    public function store(StoreReservationRequest $request): ReservationResource
    {
        return $this->reservationService->createReservation($request);
    }

    /**
     * @throws AuthorizationException
     * @throws AlreadyHasSameStatusException
     */
    public function extendBook(Reservation $reservation): void
    {
        $this->authorize('viewChangeSelf', [Reservation::class, $reservation]);
        $this->reservationService->extendBookReservation($reservation);
    }

    /**
     * @throws AuthorizationException
     * @throws AlreadyHasSameStatusException
     */
    public function returnBook(Reservation $reservation): void
    {
        $this->authorize('viewChangeSelf', [Reservation::class, $reservation]);
        $this->reservationService->returnBookReservation($reservation);
    }

    /**
     * @throws AuthorizationException
     */
    public function show(Reservation $reservation): ReservationResource
    {
        $this->authorize('viewChangeSelf', [Reservation::class, $reservation]);
        return $this->reservationService->getReservation($reservation);
    }

    public function update(UpdateReservationRequest $request, Reservation $reservation): void
    {
        $this->reservationService->updateReservation($request, $reservation);
    }

    public function destroy(Reservation $reservation): void
    {
        $this->reservationService->deleteReservation($reservation);
    }
}
