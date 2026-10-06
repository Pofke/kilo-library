<?php

declare(strict_types=1);

namespace App\Services\Reservations\V1\Repositories;

use App\Models\Reservation;
use App\Repositories\Interfaces\ReservationRepositoryInterface;
use App\Requests\V1\StoreReservationRequest;
use App\Requests\V1\UpdateReservationRequest;
use App\Resources\V1\ReservationResource;
use App\Services\Reservations\V1\DTO\UpdateStatusDTO;
use Illuminate\Database\Eloquent\Builder;

class ReservationRepository implements ReservationRepositoryInterface
{
    public function getReservations(array $filterItems): Builder
    {
        return Reservation::where($filterItems);
    }

    public function createReservation(StoreReservationRequest $request): Reservation
    {
        return Reservation::create($request->all());
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

    public function extendReturnReservation(Reservation $reservation, UpdateStatusDTO $updateDTO): void
    {
        $reservation->update($updateDTO->toArray());
    }
}
