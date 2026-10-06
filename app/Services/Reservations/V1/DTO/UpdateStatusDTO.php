<?php

declare(strict_types=1);

namespace App\Services\Reservations\V1\DTO;

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

    public function toArray(): array
    {
        return [
            'status' => $this->getStatus(),
            'extended_date' => $this->getDate()
        ];
    }
}
