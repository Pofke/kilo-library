<?php

namespace Services\Reservations\V1\Services;

use App\Services\Reservations\V1\Services\IsStatusReturnedService;
use App\Services\Utils\Constants;
use Generator;
use PHPUnit\Framework\TestCase;

class IsStatusReturnedServiceTest extends TestCase
{
    /**
     * @dataProvider statusDataProvider
     */
    public function testReturnCorrectReturnedStatus(string $status, bool $correct)
    {
        $actual = (new IsStatusReturnedService())->execute($status);
        $this->assertSame($correct, $actual);
    }

    public function statusDataProvider(): Generator
    {
        yield 'status is R' => [
            "status" => Constants::STATUS_RETURNED,
            "bool" => true,
        ];
        yield 'status is T' => [
            "status" => Constants::STATUS_TAKEN,
            "bool" => false,
        ];
        yield 'status is E' => [
            "status" => Constants::STATUS_EXTENDED,
            "bool" => false,
        ];
        yield 'status is Q' => [
            "status" => "Q",
            "bool" => false,
        ];
    }
}
