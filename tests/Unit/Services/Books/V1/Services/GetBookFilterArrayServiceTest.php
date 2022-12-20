<?php

namespace Services\Books\V1\Services;

use App\Filters\V1\BooksFilter;
use App\Services\Utils\Constants;
use Generator;
use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;

class GetBookFilterArrayServiceTest extends TestCase
{
    /**
     * @dataProvider filtersDataProvider
     */
    public function testBookFilterArrayCorrect(
        string $filterName,
        string $filterOperator,
        string $filterOperatorQuery,
        mixed $filterValue
    ) {
        $request = new Request();

        $request->replace([$filterName => [$filterOperatorQuery => $filterValue]]);
        $filterArray = new BooksFilter();
        $array = $filterArray->transform($request->query());
        $this->assertIsArray($array);
        $this->assertSame($array, [[$filterName, $filterOperator, $filterValue]]);
    }

    public function filtersDataProvider(): Generator
    {
        yield 'name == Garfield' => [
            "name" => "name",
            "operator" => '=',
            "operatorQuery" => Constants::FILTER_EQUAL,
            "value" => "Garfield"
        ];
        yield 'author == Ody' => [
            "name" => "author",
            "operator" => '=',
            "operatorQuery" => Constants::FILTER_EQUAL,
            "value" => "Ody"
        ];
        yield 'year = 1969' => [
            "name" => "year",
            "operator" => '=',
            "operatorQuery" => Constants::FILTER_EQUAL,
            "value" => 1969
        ];
        yield 'year > 1920' => [
            "name" => "year",
            "operator" => '>',
            "operatorQuery" => Constants::FILTER_GREATER,
            "value" => 1920
        ];
        yield 'year < 2000' => [
            "name" => "year",
            "operator" => '<',
            "operatorQuery" => Constants::FILTER_LESS,
            "value" => 2000
        ];
        yield 'year <= 1932' => [
            "name" => "year",
            "operator" => '<=',
            "operatorQuery" => Constants::FILTER_LESS_EQUAL,
            "value" => 1932
        ];
        yield 'year >= 2012' => [
            "name" => "year",
            "operator" => '>=',
            "operatorQuery" => Constants::FILTER_GREATER_EQUAL,
            "value" => 2012
        ];

        yield 'genre == romantic' => [
            "name" => "genre",
            "operator" => '=',
            "operatorQuery" => Constants::FILTER_EQUAL,
            "value" => "romantic"
        ];

        yield 'pages = 317' => [
            "name" => "pages",
            "operator" => '=',
            "operatorQuery" => Constants::FILTER_EQUAL,
            "value" => 317
        ];
        yield 'pages > 592' => [
            "name" => "pages",
            "operator" => '>',
            "operatorQuery" => Constants::FILTER_GREATER,
            "value" => 592
        ];
        yield 'pages < 324' => [
            "name" => "pages",
            "operator" => '<',
            "operatorQuery" => Constants::FILTER_LESS,
            "value" => 324
        ];
        yield 'pages <= 168' => [
            "name" => "pages",
            "operator" => '<=',
            "operatorQuery" => Constants::FILTER_LESS_EQUAL,
            "value" => 168
        ];
        yield 'pages >= 200' => [
            "name" => "pages",
            "operator" => '>=',
            "operatorQuery" => Constants::FILTER_GREATER_EQUAL,
            "value" => 200
        ];

        yield 'language == lt' => [
            "name" => "language",
            "operator" => '=',
            "operatorQuery" => Constants::FILTER_EQUAL,
            "value" => 'lt'
        ];

        yield 'quantity = 23' => [
            "name" => "quantity",
            "operator" => '=',
            "operatorQuery" => Constants::FILTER_EQUAL,
            "value" => 23
        ];
        yield 'quantity > 2' => [
            "name" => "quantity",
            "operator" => '>',
            "operatorQuery" => Constants::FILTER_GREATER,
            "value" => 2
        ];
        yield 'quantity < 16' => [
            "name" => "quantity",
            "operator" => '<',
            "operatorQuery" => Constants::FILTER_LESS,
            "value" => 16
        ];
        yield 'quantity <= 24' => [
            "name" => "quantity",
            "operator" => '<=',
            "operatorQuery" => Constants::FILTER_LESS_EQUAL,
            "value" => 24
        ];
        yield 'quantity >= 7' => [
            "name" => "quantity",
            "operator" => '>=',
            "operatorQuery" => Constants::FILTER_GREATER_EQUAL,
            "value" => 7
        ];
    }
}
