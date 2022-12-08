<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Services\Repositories\ReservationRepository;
use App\Services\Requests\V1\StoreReservationRequest;
use App\Services\Requests\V1\UpdateReservationRequest;
use App\Services\Resources\V1\ReservationCollection;
use App\Services\Resources\V1\ReservationResource;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function __construct(private ReservationRepository $reservationRepository)
    {
    }

    public function index(Request $request): ReservationCollection
    {
        return $this->reservationRepository->getReservations($request);
    }

    public function store(StoreReservationRequest $request): ReservationResource
    {
        $this->authorize('storeReservationInStock', [Reservation::class, $request]);
        $this->authorize('storeReservationIsNotSame', [Reservation::class, $request]);
        return $this->reservationRepository->createReservation($request);
    }

    public function extendBook(Reservation $reservation): void
    {
        $this->authorize('viewChangeSelf', [Reservation::class, $reservation]);
        $this->authorize('updateExtendReservation', [Reservation::class, $reservation]);
        $this->reservationRepository->extendBookReservation($reservation);
    }

    public function returnBook(Reservation $reservation): void
    {
        $this->authorize('viewChangeSelf', [Reservation::class, $reservation]);
        $this->authorize('updateReturnReservation', [Reservation::class, $reservation]);
        $this->reservationRepository->returnReservedBook($reservation);
    }

    public function show(Reservation $reservation): ReservationResource
    {
        $this->authorize('viewChangeSelf', [Reservation::class, $reservation]);
        return $this->reservationRepository->getReservation($reservation);
    }

    public function update(UpdateReservationRequest $request, Reservation $reservation): void
    {
        $this->reservationRepository->updateReservation($request, $reservation);
    }

    public function destroy(Reservation $reservation): void
    {
        $this->reservationRepository->deleteReservation($reservation);
    }
}
