<?php

declare(strict_types=1);

namespace App\Filters\V1;

use App\Filters\ApiFilter;

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
}
