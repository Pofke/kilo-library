<?php

namespace App\Services\Filters\V1;

use App\Services\Filters\ApiFilter;

class BooksFilter extends ApiFilter
{
    protected array $safeParams = [
        'name' => ['eq'],
        'author' => ['eq'],
        'year' => ['eq', 'gt', 'lt', 'lte', 'gte'],
        'genre' => ['eq'],
        'pages' => ['eq', 'gt', 'lt', 'lte', 'gte'],
        'language' => ['eq'],
        'quantity' => ['eq', 'gt', 'lt', 'lte', 'gte'],
    ];

    protected array $columnMap = [
        'currentStock' => 'current_stock',
    ];
}
