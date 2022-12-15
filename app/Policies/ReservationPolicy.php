<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Reservation;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReservationPolicy
{
    use HandlesAuthorization;

    public function viewChangeSelf(User $user, Reservation $reservation): bool
    {
        $admin = $user->tokenCan('get');
        $allow = $user->tokenCan('getSelf') && $user->id == $reservation->user_id;
        return $admin || $allow;
    }

    public function delete(User $user, Reservation $reservation): bool
    {
        return $user->tokenCan('update');
    }
}
