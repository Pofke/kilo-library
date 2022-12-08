<?php

declare(strict_types=1);

namespace App\Services\Commands\Reservations;

use App\Models\Reservation;
use App\Services\Filters\V1\ReservationsFilter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GetFilteredReservations
{
    public function execute(Request $request): Reservation
    {
        $filter = new ReservationsFilter();
        $filterItems = $filter->transform($request);
        if ((Auth::user())->cannot('viewAny', Reservation::class)) {
            $filterItems[] = (new AddExtraFilterForReader())->execute(Auth::id());
        }
        return Reservation::where($filterItems);
    }
}
