<?php

declare(strict_types=1);

namespace App\Filters\V1;

use App\Filters\ApiFilter;

class ReservationsFilter extends ApiFilter
{
    protected array $safeParams = [
        'bookId' => ['eq'],
        'userId' => ['eq'],
        'status' => ['eq', 'ne'],
        'extendedDate' => ['eq', 'gt', 'lt', 'lte', 'gte'],
        'returnedDate' => ['eq', 'gt', 'lt', 'lte', 'gte'],
    ];

    protected array $columnMap = [
        'bookId' => 'book_id',
        'userId' => 'user_id',
        'extendedDate' => 'extended_date',
        'returnedDate' => 'returned_date'
    ];
}
