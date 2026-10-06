<?php

namespace Services\Reservations\V1\Services;

use App\Models\Book;
use App\Models\Reservation;
use App\Repositories\Interfaces\BookRepositoryInterface;
use App\Repositories\Interfaces\ReservationRepositoryInterface;
use App\Requests\V1\StoreReservationRequest;
use App\Requests\V1\UpdateReservationRequest;
use App\Services\Books\V1\Exceptions\OutOfStockException;
use App\Services\Reservations\V1\Exceptions\AlreadyHasSameStatusException;
use App\Services\Reservations\V1\Exceptions\AlreadyReservedException;
use App\Services\Reservations\V1\Services\ReservationService;
use App\Services\Utils\Constants;
use Generator;
use PHPUnit\Framework\TestCase;

class ReservationServiceTest extends TestCase
{
    private mixed $reservationRepository;
    private mixed $bookRepository;
    private mixed $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->reservationRepository = $this->createMock(ReservationRepositoryInterface::class);
        $this->bookRepository = $this->createMock(BookRepositoryInterface::class);
        $this->service = new ReservationService($this->reservationRepository, $this->bookRepository);
    }

    /**
     * @dataProvider returnBookDataProvider
     * @throws AlreadyHasSameStatusException
     */
    public function testReturnBookReservation(string $status, ?string $exception)
    {
        if ($exception) {
            $this->expectException($exception);
        } else {
            $this->reservationRepository->expects($this->once())->method('extendReturnReservation');
        }
        $reservation = new Reservation();
        $reservation->status = $status;

        $this->service->returnBookReservation($reservation);
        $this->assertTrue(true);
    }

    public function testGetReservation()
    {

        $this->reservationRepository->expects($this->once())->method('getReservation');
        $this->service->getReservation($this->createMock(Reservation::class));
        $this->assertTrue(true);
    }

    public function testDeleteReservation()
    {
        $this->reservationRepository->expects($this->once())->method('deleteReservation');
        $this->service->deleteReservation($this->createMock(Reservation::class));
        $this->assertTrue(true);
    }

    public function testUpdateReservation()
    {
        $this->reservationRepository->expects($this->once())->method('updateReservation');
        $this->service->updateReservation(
            $this->createMock(UpdateReservationRequest::class),
            $this->createMock(Reservation::class)
        );
        $this->assertTrue(true);
    }


    /**
     * @dataProvider createReservationDataProvider
     * @throws OutOfStockException
     * @throws AlreadyReservedException
     */
    public function testCreateReservation(
        int $userId,
        int $bookId,
        string $status,
        int $quantity,
        array $reservations,
        ?string $exception
    ) {
        if ($exception) {
            $this->expectException($exception);
        }
        $storeReservationRequest = $this->generateFakeStoreReservationRequest($userId, $bookId, $status);
        $fakeBook = $this->generateFakeBook($quantity, $reservations);
        $this->bookRepository->expects($this->once())->method('getBookById')->willReturn($fakeBook);
        $this->service->createReservation($storeReservationRequest);
        $this->assertTrue(true);
    }

    /**
     * @dataProvider extendReservationDataProvider
     * @throws AlreadyHasSameStatusException
     */
    public function testExtendBookReservation(string $status, ?string $exception)
    {
        if ($exception) {
            $this->expectException($exception);
        }
        $reservation = new Reservation();
        $reservation->status = $status;
        $this->service->extendBookReservation($reservation);
        $this->assertTrue(true);
    }

    public function extendReservationDataProvider(): Generator
    {
        yield 'status is ' . Constants::STATUS_RETURNED => [
            'status' => Constants::STATUS_RETURNED,
            'exception' => AlreadyHasSameStatusException::class,
        ];
        yield 'status is ' . Constants::STATUS_EXTENDED => [
            'status' => Constants::STATUS_EXTENDED,
            'exception' => AlreadyHasSameStatusException::class,
        ];
        yield 'status is ' . Constants::STATUS_TAKEN => [
            'status' => Constants::STATUS_TAKEN,
            'exception' => null,
        ];
        yield 'status is M' => [
            'status' => 'M',
            'exception' => AlreadyHasSameStatusException::class,
        ];
    }


    public function createReservationDataProvider(): Generator
    {
        yield 'out of stock but generating return' => [
            'userId' => 5,
            'bookId' => 5,
            'status' => Constants::STATUS_RETURNED,
            'quantity' => 1,
            'reservation' => [['user_id' => 4], ['user_id' => 3]],
            'exception' => null
        ];
        yield 'out of stock and generating taken' => [
            'userId' => 5,
            'bookId' => 5,
            'status' => Constants::STATUS_TAKEN,
            'quantity' => 1,
            'reservation' => [['user_id' => 4], ['user_id' => 3]],
            'exception' => OutOfStockException::class
        ];
        yield 'out of stock and generating extend' => [
            'userId' => 5,
            'bookId' => 5,
            'status' => Constants::STATUS_EXTENDED,
            'quantity' => 1,
            'reservation' => [['user_id' => 4], ['user_id' => 3]],
            'exception' => OutOfStockException::class
        ];
        yield 'in stock but already have one and taking again' => [
            'userId' => 5,
            'bookId' => 5,
            'status' => Constants::STATUS_TAKEN,
            'quantity' => 3,
            'reservation' => [['user_id' => 4], ['user_id' => 5]],
            'exception' => AlreadyReservedException::class
        ];
        yield 'in stock but already have one and extending new one' => [
            'userId' => 5,
            'bookId' => 5,
            'status' => Constants::STATUS_EXTENDED,
            'quantity' => 3,
            'reservation' => [['user_id' => 4], ['user_id' => 5]],
            'exception' => AlreadyReservedException::class
        ];
        yield 'in stock but already have one and returning new one' => [
            'userId' => 5,
            'bookId' => 5,
            'status' => Constants::STATUS_RETURNED,
            'quantity' => 3,
            'reservation' => [['user_id' => 4], ['user_id' => 5]],
            'exception' => null
        ];
        yield 'in stock and taking new' => [
            'userId' => 5,
            'bookId' => 5,
            'status' => Constants::STATUS_TAKEN,
            'quantity' => 3,
            'reservation' => [['user_id' => 4], ['user_id' => 6]],
            'exception' => null
        ];
        yield 'in stock and extending new' => [
            'userId' => 5,
            'bookId' => 5,
            'status' => Constants::STATUS_EXTENDED,
            'quantity' => 3,
            'reservation' => [['user_id' => 4], ['user_id' => 6]],
            'exception' => null
        ];
        yield 'in stock and returning new' => [
            'userId' => 5,
            'bookId' => 5,
            'status' => Constants::STATUS_RETURNED,
            'quantity' => 3,
            'reservation' => [['user_id' => 4], ['user_id' => 6]],
            'exception' => null
        ];
    }


    public function returnBookDataProvider(): Generator
    {
        yield 'status is ' . Constants::STATUS_EXTENDED => [
            'status' => Constants::STATUS_EXTENDED,
            'exception' => null,
        ];

        yield 'status is ' . Constants::STATUS_RETURNED => [
            'status' => Constants::STATUS_RETURNED,
            'exception' => AlreadyHasSameStatusException::class,
        ];

        yield 'status is ' . Constants::STATUS_TAKEN => [
            'status' => Constants::STATUS_TAKEN,
            'exception' => null,
        ];
    }


    private function generateFakeStoreReservationRequest(int $userId, int $bookId, string $status)
    {
        $storeReservationRequest = $this->createMock(StoreReservationRequest::class);
        $storeReservationRequest->userId = $userId;
        $storeReservationRequest->bookId = $bookId;
        $storeReservationRequest->status = $status;
        return $storeReservationRequest;
    }

    private function generateFakeBook(int $quantity, array $reservations): Book
    {
        $fakeBook = new Book();
        $fakeBook->quantity = $quantity;
        $fakeBook->reservations = $reservations;
        return $fakeBook;
    }
}
