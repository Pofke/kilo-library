<?php

declare(strict_types=1);

namespace App\Services\Books\V1\DTO;

use Illuminate\Support\Facades\Auth;

class TakeBookDTO
{
    public function __construct(private int $bookId)
    {
    }

    public function getUserId(): int
    {
        return Auth::id();
    }

    public function getBookId(): int
    {
        return $this->bookId;
    }

    public function toArray(): array
    {
        return [
            'user_id' => $this->getUserId(),
            'book_id' => $this->getBookId()
        ];
    }
}
