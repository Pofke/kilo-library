<?php

declare(strict_types=1);

namespace App\Services\DTO;

class TakeBookDTO
{
    public function __construct(private int $userId, private int $bookId)
    {
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getBookId(): int
    {
        return $this->bookId;
    }
}
