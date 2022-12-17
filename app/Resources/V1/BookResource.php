<?php

declare(strict_types=1);

namespace App\Resources\V1;

use App\Models\Book;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var Book $this */
        return [
            'id' => $this->id,
            'name' => $this->name,
            'author' => $this->author,
            'year' => $this->year,
            'genre' => $this->genre,
            'pages' => $this->pages,
            'language' => $this->language,
            'currentStock' => $this->quantity - count($this->reservations),
            'quantity' => $this->quantity,
        ];
    }
}
