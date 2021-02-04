<?php

declare(strict_types=1);

namespace CyrilVerloop\Datatables;

use CyrilVerloop\Datatables\Order;
use CyrilVerloop\Datatables\Orders;
use PHPUnit\Framework\TestCase;

/**
 * Test the list of orders for Datatables.
 * @package \Bundles\CrudBundle\Tests\Datatables
 *
 * @coversDefaultClass \CyrilVerloop\Datatables\Orders
 */
class OrdersTest extends TestCase
{
    // Properties :

    /**
     * @var \CyrilVerloop\Datatables\Orders the list of orders.
     */
    protected Orders $orders;


    // Methods :

    /**
     * Initialises tests.
     * @return void
     */
    public function setUp(): void
    {
        $this->orders = new Orders([]);
    }


    /**
     * Returns missing datas.
     * @return mixed[] missing datas.
     */
    public function getMissingOrderDatas(): array
    {
        return [
            'when the array is empty' => [[[]]],
            'when the "column" key does not exist' => [[['dir' => '']]],
            'when the "dir" key does not exist' => [[['column' => 1]]]
        ];
    }

    /**
     * Test that an exception is thrown
     * if datas are missing.
     * @param mixed[] $missingOrderDatas missing search datas.
     * @return void
     *
     * @test
     * @covers ::__construct
     * @covers ::addFromArray
     * @dataProvider getMissingOrderDatas
     */
    public function testCanThrownAnOutOfBoundsExceptionWhenConstructingIfDatasAreMissing(array $missingOrderDatas): void
    {
        $this->expectException(\OutOfBoundsException::class);
        $this->expectExceptionMessage('orders.key.notExist');

        new Orders($missingOrderDatas);
    }

    /**
     * Test that an object can be constructed without data.
     * @return void
     *
     * @test
     * @covers ::__construct
     */
    public function testCanBeConstructedWithEmptyDatas(): void
    {
        self::assertFalse($this->orders->valid(), 'The orders must be an empty array.');
    }

    /**
     * Returns datas to add an order.
     * @return mixed[] datas to add an order.
     */
    public function getOrderDatas(): array
    {
        return [
            'when dir is Order::DIR_ASC' => [[['column' => '1', 'dir' => Order::DIR_ASC]]],
            'when dir is Order::DIR_DESC' => [[['column' => '1', 'dir' => Order::DIR_DESC]]]
        ];
    }

    /**
     * Test that an object can be constructed with datas.
     * @param mixed[] $orderDatas order datas.
     * @return void
     *
     * @test
     * @covers ::__construct
     * @covers ::addFromArray
     * @uses \CyrilVerloop\Datatables\Order
     * @uses \CyrilVerloop\Datatables\Orders
     * @dataProvider getOrderDatas
     */
    public function testCanBeConstructedWithDatas(array $orderDatas): void
    {
        $this->orders = new Orders($orderDatas);

        self::assertTrue($this->orders->valid(), 'The orders must not be an empty array.');
    }


    /**
     * Test that an order can be added.
     * @return void
     *
     * @test
     * @covers ::add
     * @uses \CyrilVerloop\Datatables\Order
     * @uses \CyrilVerloop\Datatables\Orders
     * @depends testCanBeConstructedWithEmptyDatas
     * @depends testCanBeConstructedWithDatas
     */
    public function testCanAdd(): void
    {
        self::assertFalse($this->orders->valid(), 'The orders must be an empty array.');

        $order = new Order(0);
        $this->orders->add($order);

        self::assertTrue($this->orders->valid(), 'The orders must not be an empty array.');
    }


    /**
     * Test that the iterator can be rewinded.
     * @return void
     *
     * @test
     * @covers ::rewind
     * @uses \CyrilVerloop\Datatables\Order
     * @uses \CyrilVerloop\Datatables\Orders
     * @depends testCanAdd
     */
    public function testCanRewind(): void
    {
        $order = new Order(1, 'asc');
        $this->orders->add($order);
        $this->orders->add($order);

        $firstCounter = 0;

        foreach ($this->orders as $order) {
            $firstCounter++;
        }

        self::assertSame(2, $firstCounter, 'There must be 2 object in the iterator.');

        $secondCounter = 0;

        foreach ($this->orders as $order) {
            $secondCounter++;
        }

        self::assertSame(2, $secondCounter, 'There must be also 2 object in the iterator.');
    }
}
