<?php

namespace App\Services\Commands\Reservations;

class IsStatusTaken
{
    public function execute(string $status): bool
    {
        return $status == 'T';
    }
}
