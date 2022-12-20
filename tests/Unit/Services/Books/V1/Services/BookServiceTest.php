<?php

namespace Services\Books\V1\Services;

use App\Models\Book;
use App\Models\User;
use App\Repositories\Interfaces\BookRepositoryInterface;
use App\Requests\V1\BulkStoreBooksRequest;
use App\Requests\V1\StoreBookRequest;
use App\Requests\V1\UpdateBookRequest;
use App\Resources\V1\BookCollection;
use App\Services\Books\V1\Exceptions\OutOfStockException;
use App\Services\Books\V1\Services\BookService;
use App\Services\Books\V1\Services\IsBookInStockService;
use App\Services\Reservations\V1\Exceptions\AlreadyReservedException;
use App\Services\Reservations\V1\Services\IsBookReservedService;
use Generator;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class BookServiceTest extends TestCase
{
    private mixed $repository;
    private mixed $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->createMock(BookRepositoryInterface::class);
        $this->service = new BookService($this->repository);
    }

    public function testGetBook()
    {
        $this->repository->expects($this->once())->method('getBook');
        $this->service->getBook($this->createMock(Book::class));
        $this->assertTrue(true);
    }

    public function testBulkAddBook()
    {
        $this->repository->expects($this->once())->method('bulkAddBook');
        $this->service->bulkAddBook($this->createMock(BulkStoreBooksRequest::class));
        $this->assertTrue(true);
    }

    public function testCreateBook()
    {
        $this->repository->expects($this->once())->method('createBook');
        $this->service->createBook($this->createMock(StoreBookRequest::class));
        $this->assertTrue(true);
    }

    public function testDeleteBook()
    {
        $this->repository->expects($this->once())->method('deleteBook');
        $this->service->deleteBook($this->createMock(Book::class));
        $this->assertTrue(true);
    }


    /**
     * @dataProvider takeBookDataProvider
     * @throws OutOfStockException
     * @throws AlreadyReservedException
     */
    public function testTakeBook(int $quantity, array $reservations, ?string $exception)
    {
        if ($exception) {
            $this->expectException($exception);
        }
        $fakeBook = $this->generateBook($quantity, $reservations);
        $this->repository->expects($this->once())->method('getBook')->willReturn($fakeBook);
        Auth::shouldReceive('id')->once()->andReturn(1);
        $this->service->takeBook($fakeBook);
        $this->assertTrue(true);
    }

    public function testUpdateBook()
    {
        $this->repository->expects($this->once())->method('updateBook');
        $this->service->updateBook($this->createMock(UpdateBookRequest::class), $this->createMock(Book::class));
        $this->assertTrue(true);
    }

    private function generateBook(int $quantity, array $reservations): Book
    {
        $book = new Book();
        $book->quantity = $quantity;
        $book->id = 1;
        $book->reservations = $reservations;
        return $book;
    }

    public function takeBookDataProvider(): Generator
    {
        yield 'taking without exceptions' => [
            'quantity' => 20,
            'reservations' => [['user_id' => 2], ['user_id' => 3]],
            'exception' => null
        ];
        yield 'book is not in stock' => [
            'quantity' => 2,
            'reservations' => [['user_id' => 2], ['user_id' => 3]],
            'exception' => OutOfStockException::class
        ];
        yield 'taking same book second time' => [
            'quantity' => 4,
            'reservations' => [['user_id' => 1], ['user_id' => 3]],
            'exception' => AlreadyReservedException::class
        ];
    }
}
