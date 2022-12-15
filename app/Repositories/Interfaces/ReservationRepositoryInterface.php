<?php

namespace App\Repositories\Interfaces;

use App\Models\Reservation;
use App\Requests\V1\StoreReservationRequest;
use App\Requests\V1\UpdateReservationRequest;
use App\Resources\V1\ReservationResource;
use App\Services\Reservations\V1\DTO\UpdateStatusDTO;
use Illuminate\Database\Eloquent\Builder;

interface ReservationRepositoryInterface
{
    public function getReservations(array $filterItems): Builder;
    public function getReservation(Reservation $reservation): ReservationResource;
    public function createReservation(StoreReservationRequest $request): Reservation;
    public function extendReturnReservation(Reservation $reservation, UpdateStatusDTO $updateDTO): void;
    public function updateReservation(UpdateReservationRequest $request, Reservation $reservation): void;
    public function deleteReservation(Reservation $reservation): void;
}
