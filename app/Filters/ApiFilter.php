<?php

declare(strict_types=1);

namespace App\Filters;

use App\Services\Utils\Constants;

class ApiFilter
{
    protected array $safeParams = [];
    protected array $columnMap = [];
    protected array $operatorMap = [
        Constants::FILTER_EQUAL => '=',
        Constants::FILTER_LESS => '<',
        Constants::FILTER_LESS_EQUAL => '<=',
        Constants::FILTER_GREATER => '>',
        Constants::FILTER_GREATER_EQUAL => '>=',
        Constants::FILTER_NOT_EQUAL => '!='
    ];

    public function transform(array $request): array
    {
        $eloQuery = [];
        foreach ($this->safeParams as $param => $operators) {
            if (!array_key_exists($param, $request)) {
                continue;
            }
            $query = $request[$param];
            $column = $this->columnMap[$param] ?? $param;
            foreach ($operators as $operator) {
                if (array_key_exists($operator, $query)) {
                    $eloQuery[] = [$column, $this->operatorMap[$operator], $query[$operator]];
                }
            }
        }
        return $eloQuery;
    }
}
