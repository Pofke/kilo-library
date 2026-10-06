<?php

declare(strict_types=1);

namespace App\Filters\V1;

use App\Filters\ApiFilter;
use App\Services\Utils\Constants;

class BooksFilter extends ApiFilter
{
    protected array $safeParams = [
        'name' => [Constants::FILTER_EQUAL],
        'author' => [Constants::FILTER_EQUAL],
        'year' => [
            Constants::FILTER_EQUAL,
            Constants::FILTER_GREATER_EQUAL,
            Constants::FILTER_LESS,
            Constants::FILTER_LESS_EQUAL,
            Constants::FILTER_GREATER
        ],
        'genre' => [Constants::FILTER_EQUAL],
        'pages' => [
            Constants::FILTER_EQUAL,
            Constants::FILTER_GREATER_EQUAL,
            Constants::FILTER_LESS,
            Constants::FILTER_LESS_EQUAL,
            Constants::FILTER_GREATER
        ],
        'language' => [Constants::FILTER_EQUAL],
        'quantity' => [
            Constants::FILTER_EQUAL,
            Constants::FILTER_GREATER_EQUAL,
            Constants::FILTER_LESS,
            Constants::FILTER_LESS_EQUAL,
            Constants::FILTER_GREATER
        ],
    ];
}
