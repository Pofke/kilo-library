<?php

namespace App\Services\DTO;

use DateTime;

class UpdateStatusDTO
{
    public function __construct(private string $status)
    {
    }

    public function getDate(): DateTime
    {
        return (new DateTime('now'));
    }

    public function getStatus(): string
    {
        return $this->status;
    }
}
