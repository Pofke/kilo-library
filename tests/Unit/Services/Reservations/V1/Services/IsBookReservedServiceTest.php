<?php

namespace Services\Reservations\V1\Services;

use App\Resources\V1\ReservationCollection;
use App\Services\Reservations\V1\Services\IsBookReservedService;
use Generator;
use PHPUnit\Framework\TestCase;

class IsBookReservedServiceTest extends TestCase
{
    /**
     * @dataProvider bookReservedDataProvider
     */
    public function testIsBookIsAlreadyReservedCorrect(array $reservations, bool $returnValue)
    {
        $service = new IsBookReservedService();
        $actual = $service->execute(new ReservationCollection($reservations), 1);
        $this->assertSame($returnValue, $actual);
    }

    public function bookReservedDataProvider(): Generator
    {
        yield 'book not reserved' => [
            "reservations" => [['user_id' => 3], ['user_id' => 4]],
            "returnValue" => false,
        ];
        yield 'status is reserved' => [
            "reservations" => [['user_id' => 1]],
            "returnValue" => true,
        ];
        yield 'status is reserved 2' => [
            "reservations" => [['user_id' => 3], ['user_id' => 4], ['user_id' => 1]],
            "returnValue" => true,
        ];
        yield 'status is not reserved with empty reservations' => [
            "reservations" => [],
            "returnValue" => false,
        ];
    }
}
