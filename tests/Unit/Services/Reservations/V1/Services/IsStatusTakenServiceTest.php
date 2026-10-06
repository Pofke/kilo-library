<?php

namespace Services\Reservations\V1\Services;

use App\Services\Reservations\V1\Services\IsStatusTakenService;
use App\Services\Utils\Constants;
use Generator;
use PHPUnit\Framework\TestCase;

class IsStatusTakenServiceTest extends TestCase
{
    /**
     * @dataProvider statusDataProvider
     */
    public function testReturnCorrectReturnedStatus(string $status, bool $correct)
    {
        $actual = (new IsStatusTakenService())->execute($status);
        $this->assertSame($correct, $actual);
    }

    public function statusDataProvider(): Generator
    {
        yield 'status is ' . Constants::STATUS_RETURNED => [
            "status" => Constants::STATUS_RETURNED,
            "bool" => false,
        ];
        yield 'status is ' . Constants::STATUS_TAKEN => [
            "status" => Constants::STATUS_TAKEN,
            "bool" => true,
        ];
        yield 'status is ' . Constants::STATUS_EXTENDED => [
            "status" => Constants::STATUS_EXTENDED,
            "bool" => false,
        ];
        yield 'status is Q' => [
            "status" => "Q",
            "bool" => false,
        ];
    }
}
