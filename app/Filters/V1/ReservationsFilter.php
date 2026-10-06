<?php

declare(strict_types=1);

namespace App\Filters\V1;

use App\Filters\ApiFilter;
use App\Services\Utils\Constants;

class ReservationsFilter extends ApiFilter
{
    protected array $safeParams = [
        'bookId' => [Constants::FILTER_EQUAL],
        'userId' => [Constants::FILTER_EQUAL],
        'status' => [
            Constants::FILTER_EQUAL,
            Constants::FILTER_NOT_EQUAL
        ],
        'extendedDate' => [
            Constants::FILTER_EQUAL,
            Constants::FILTER_GREATER_EQUAL,
            Constants::FILTER_LESS,
            Constants::FILTER_LESS_EQUAL,
            Constants::FILTER_GREATER
        ],
        'returnedDate' => [
            Constants::FILTER_EQUAL,
            Constants::FILTER_GREATER_EQUAL,
            Constants::FILTER_LESS,
            Constants::FILTER_LESS_EQUAL,
            Constants::FILTER_GREATER
        ],
    ];

    protected array $columnMap = [
        'bookId' => 'book_id',
        'userId' => 'user_id',
        'extendedDate' => 'extended_date',
        'returnedDate' => 'returned_date'
    ];
}
