<?php

namespace Services\Books\V1\Services;

use App\Models\Book;
use App\Services\Books\V1\Services\IsBookInStockService;
use Generator;
use PHPUnit\Framework\TestCase;

class IsBookInStockServiceTest extends TestCase
{
    /**
     * @dataProvider bookInStockDataProvider
     */
    public function testIsBookInStockCalculatesCorrect(int $quantity, array $reservations, bool $expected)
    {
        $book = new Book();
        $book->quantity = $quantity;
        $book->reservations = $reservations;
        $bookIsInStock = (new IsBookInStockService())->execute($book);
        $this->assertSame($expected, $bookIsInStock);
    }

    public function bookInStockDataProvider(): Generator
    {
        yield 'book is in stock' => [
            "quantity" => 5,
            "reservations" => [1, 2],
            "expected" => true
        ];
        yield 'book is in stock and no reservations' => [
            "quantity" => 5,
            "reservations" => [],
            "expected" => true
        ];
        yield 'book quantity is zero no reservations' => [
            "quantity" => 0,
            "reservations" => [],
            "expected" => false
        ];
        yield 'book quantity is zero there is reservations' => [
            "quantity" => 0,
            "reservations" => [1, 2],
            "expected" => false
        ];
        yield 'book is not in stock' => [
            "quantity" => 2,
            "reservations" => [1, 2],
            "expected" => false
        ];
        yield 'reserved more books than library has' => [
            "quantity" => 1,
            "reservations" => [1, 2],
            "expected" => false
        ];
    }
}
