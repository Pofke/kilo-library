<?php

namespace Services\Reservations\V1\Services;

use App\Filters\V1\ReservationsFilter;
use App\Services\Utils\Constants;
use Generator;
use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;

class GetReservationFilterArrayServiceTest extends TestCase
{
    /**
     * @dataProvider filtersDataProvider
     */
    public function testBookFilterArrayCorrect(
        string $filterName,
        string $filterNameQuery,
        string $filterOperator,
        string $filterOperatorQuery,
        mixed $filterValue
    ) {
        $request = new Request();

        $request->replace([$filterNameQuery => [$filterOperatorQuery => $filterValue]]);
        $filterArray = new ReservationsFilter();
        $array = $filterArray->transform($request->query());
        $this->assertIsArray($array);
        $this->assertSame($array, [[$filterName, $filterOperator, $filterValue]]);
    }

    public function filtersDataProvider(): Generator
    {
        yield 'bookId == 1' => [
            "name" => "book_id",
            "nameQuery" => "bookId",
            "operator" => '=',
            "operatorQuery" => Constants::FILTER_EQUAL,
            "value" => 1
        ];
        yield 'userId == 2' => [
            "name" => "user_id",
            "nameQuery" => "userId",
            "operator" => '=',
            "operatorQuery" => Constants::FILTER_EQUAL,
            "value" => 2
        ];
        yield 'status == E' => [
            "name" => "status",
            "nameQuery" => "status",
            "operator" => '=',
            "operatorQuery" => Constants::FILTER_EQUAL,
            "value" => "E"
        ];

        yield 'status != R' => [
            "name" => "status",
            "nameQuery" => "status",
            "operator" => '!=',
            "operatorQuery" => Constants::FILTER_NOT_EQUAL,
            "value" => "R"
        ];

        yield 'extendedDate == 2022-01-12' => [
            "name" => "extended_date",
            "nameQuery" => "extendedDate",
            "operator" => '=',
            "operatorQuery" => Constants::FILTER_EQUAL,
            "value" => "2022-01-12"
        ];

        yield 'extendedDate > 2022-02-15' => [
            "name" => "extended_date",
            "nameQuery" => "extendedDate",
            "operator" => '>',
            "operatorQuery" => Constants::FILTER_GREATER,
            "value" => "2022-02-15"
        ];

        yield 'extendedDate < 2022-05-24' => [
            "name" => "extended_date",
            "nameQuery" => "extendedDate",
            "operator" => '<',
            "operatorQuery" => Constants::FILTER_LESS,
            "value" => "2022-05-24"
        ];

        yield 'extendedDate >= 2022-12-14' => [
            "name" => "extended_date",
            "nameQuery" => "extendedDate",
            "operator" => '>=',
            "operatorQuery" => Constants::FILTER_GREATER_EQUAL,
            "value" => "2022-12-14"
        ];

        yield 'extendedDate <= 2022-11-20' => [
            "name" => "extended_date",
            "nameQuery" => "extendedDate",
            "operator" => '<=',
            "operatorQuery" => Constants::FILTER_LESS_EQUAL,
            "value" => "2022-11-20"
        ];


        yield 'returnedDate == 2022-01-14' => [
            "name" => "returned_date",
            "nameQuery" => "returnedDate",
            "operator" => '=',
            "operatorQuery" => Constants::FILTER_EQUAL,
            "value" => "2022-01-14"
        ];

        yield 'returnedDate > 2022-03-15' => [
            "name" => "returned_date",
            "nameQuery" => "returnedDate",
            "operator" => '>',
            "operatorQuery" => Constants::FILTER_GREATER,
            "value" => "2022-03-15"
        ];

        yield 'returnedDate < 2022-07-24' => [
            "name" => "returned_date",
            "nameQuery" => "returnedDate",
            "operator" => '<',
            "operatorQuery" => Constants::FILTER_LESS,
            "value" => "2022-07-24"
        ];

        yield 'returnedDate >= 2022-10-14' => [
            "name" => "returned_date",
            "nameQuery" => "returnedDate",
            "operator" => '>=',
            "operatorQuery" => Constants::FILTER_GREATER_EQUAL,
            "value" => "2022-10-14"
        ];

        yield 'returnedDate <= 2022-12-20' => [
            "name" => "returned_date",
            "nameQuery" => "returnedDate",
            "operator" => '<=',
            "operatorQuery" => Constants::FILTER_LESS_EQUAL,
            "value" => "2022-12-20"
        ];
    }
}
