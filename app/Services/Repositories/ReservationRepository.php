<?php

declare(strict_types=1);

namespace App\Services\Repositories;

use App\Models\Reservation;
use App\Services\Commands\Reservations\GetFilteredReservations;
use App\Services\DTO\UpdateStatusDTO;
use App\Services\Requests\V1\StoreReservationRequest;
use App\Services\Requests\V1\UpdateReservationRequest;
use App\Services\Resources\V1\ReservationCollection;
use App\Services\Resources\V1\ReservationResource;
use Illuminate\Http\Request;

class ReservationRepository
{
    public function getReservations(Request $request): ReservationCollection
    {
        $reservations = (new GetFilteredReservations())->execute($request);
        return new ReservationCollection($reservations->paginate()->appends($request->query()));
    }

    public function createReservation(StoreReservationRequest $request): ReservationResource
    {
        return new ReservationResource(Reservation::create($request->all()));
    }

    public function extendBookReservation(Reservation $reservation): void
    {
        $updateDTO = new UpdateStatusDTO('E');
        $reservation->update([
            'status' => $updateDTO->getStatus(),
            'extended_date' => $updateDTO->getDate()->modify('+1 month')
        ]);
    }

    public function returnReservedBook(Reservation $reservation): void
    {
        $updateDTO = new UpdateStatusDTO('R');
        $reservation->update([
            'status' => $updateDTO->getStatus(),
            'returned_date' => $updateDTO->getDate()
        ]);
    }

    public function getReservation(Reservation $reservation): ReservationResource
    {
        return new ReservationResource($reservation);
    }

    public function updateReservation(UpdateReservationRequest $request, Reservation $reservation): void
    {
        $reservation->update($request->all());
    }

    public function deleteReservation(Reservation $reservation): void
    {
        $reservation->delete();
    }
}
