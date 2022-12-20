<?php

namespace App\Services\Reservations\V1\Services;

use Illuminate\Support\Facades\Auth;

class AddUserIdFilterForReaderService
{
    public function execute(): ?array
    {
        $user = Auth::user();
        if ($user->tokenCan('getSelf')) {
            return ["user_id", '=', Auth::id()];
        }
        return null;
    }
}
