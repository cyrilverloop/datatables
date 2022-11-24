<?php

declare(strict_types=1);

namespace CyrilVerloop\Datatables\Tests;

use CyrilVerloop\Datatables\Order;
use CyrilVerloop\Datatables\Orders;
use PHPUnit\Framework\TestCase;

/**
 * Tests the list of orders for Datatables.
 *
 * @coversDefaultClass \CyrilVerloop\Datatables\Orders
 * @covers ::__construct
 * @covers ::addFromArray
 * @group orders
 */
final class OrdersTest extends TestCase
{
    // Methods :

    /**
     * Returns orders values.
     * @return array orders values.
     */
    private function getOrdersValues(): array
    {
        return [[
            'dir' => 'asc',
            'column' => '0'
        ]];
    }

    /**
     * Tests that an \OutOfBoundsException is thrown
     * when dir is missing.
     */
    public function testCanThrownAnOutOfBoundsExceptionWhenDirIsMissing(): void
    {
        $this->expectException(\OutOfBoundsException::class);
        $this->expectExceptionMessage('dir.notExist');

        $ordersValues = $this->getOrdersValues();
        unset($ordersValues[0]['dir']);

        new Orders($ordersValues);
    }

    /**
     * Tests that an \OutOfBoundsException is thrown
     * when column is missing.
     */
    public function testCanThrownAnOutOfBoundsExceptionWhenColumnIsMissing(): void
    {
        $this->expectException(\OutOfBoundsException::class);
        $this->expectExceptionMessage('column.notExist');

        $ordersValues = $this->getOrdersValues();
        unset($ordersValues[0]['column']);

        new Orders($ordersValues);
    }

    /**
     * Tests that an exception is thrown
     * if the order direction is neither 'asc' nor 'desc'.
     */
    public function testCanThrownAnUnexpectedValueExceptionWhenDirectionIsNeitherAscNorDesc(): void
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('dir.unexpectedValue');

        $ordersValues = $this->getOrdersValues();
        $ordersValues[0]['dir'] = 5;

        new Orders($ordersValues);
    }


    /**
     * Returns invalid digits.
     * @return mixed[] invalid digits.
     */
    public function getNonDigitsValue(): array
    {
        return [
            'a float string' => ['0.5'],
            'an alpha string' => ['test'],
            'an int' => [0],
            'a float' => [0.5],
            'an array' => [[]],
            'an object' => [new \stdClass()]
        ];
    }

    /**
     * Tests that an exception is thrown
     * when column is not string containing only digits.
     * @param mixed $notADigitValue not a digit value.
     *
     * @dataProvider getNonDigitsValue
     */
    public function testCanThrowAnInvalidArgumentExceptionWhenDataIsNotAString(mixed $notADigitValue): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('column.notOnlyDigitsInString');

        $ordersValues = $this->getOrdersValues();
        $ordersValues[0]['column'] = $notADigitValue;

        new Orders($ordersValues);
    }


    /**
     * Tests that an order can be added from an array
     * when "dir" is "asc".
     *
     * @uses \CyrilVerloop\Datatables\Order::__construct
     */
    public function testCanHaveOrdersWhenDirectionIsAsc(): void
    {
        $orders = new Orders($this->getOrdersValues());

        self::assertTrue($orders->valid(), 'The orders must not be an empty array.');
    }

    /**
     * Tests that an order can be added from an array
     * when "dir" is "desc".
     *
     * @uses \CyrilVerloop\Datatables\Order::__construct
     */
    public function testCanHaveOrdersWhenDirectionIsDesc(): void
    {
        $ordersValues = $this->getOrdersValues();
        $ordersValues[0]['dir'] = 'desc';
        $orders = new Orders($ordersValues);

        self::assertTrue($orders->valid(), 'The orders must not be an empty array.');
    }


    /**
     * Tests that an order can be added.
     *
     * @covers ::add
     * @uses \CyrilVerloop\Datatables\Order::__construct
     * @depends testCanHaveOrdersWhenDirectionIsAsc
     * @depends testCanHaveOrdersWhenDirectionIsDesc
     */
    public function testCanAdd(): void
    {
        $orders = new Orders([]);

        self::assertFalse($orders->valid(), 'The orders must be an empty array.');

        $order = new Order(0);
        $orders->add($order);

        self::assertTrue($orders->valid(), 'The orders must not be an empty array.');
    }
}
