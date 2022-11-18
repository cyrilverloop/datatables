<?php

declare(strict_types=1);

namespace CyrilVerloop\Datatables;

use CyrilVerloop\Datatables\Direction;
use CyrilVerloop\Datatables\Order;
use PHPUnit\Framework\TestCase;

/**
 * Tests an order for Datatables.
 *
 * @coversDefaultClass \CyrilVerloop\Datatables\Order
 * @covers ::__construct
 * @group order
 */
final class OrderTest extends TestCase
{
    // Methods :

    /**
     * Tests that an object can not be constructed
     * if the column number is negative.
     */
    public function testCanThrowADomainExceptionWhenColumnIsNegative(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('order.column.negativeValue');

        new Order(-1);
    }

    /**
     * Tests that the column can be returned.
     *
     * @covers ::getColumn
     */
    public function testCanGetColumn(): void
    {
        $order = new Order(0);

        self::assertSame(0, $order->getColumn(), 'The column must be 0 (zero).');
    }

    /**
     * Tests that the ASC direction can be returned.
     *
     * @covers ::getDir
     */
    public function testCanGetAscDirection(): void
    {
        $order = new Order(0, Direction::Ascending);

        self::assertSame(Direction::Ascending, $order->getDir(), 'The direction must be "asc".');
    }

    /**
     * Tests that the ASC direction can be returned.
     *
     * @covers ::getDir
     */
    public function testCanGetDescDirection(): void
    {
        $order = new Order(0, Direction::Descending);

        self::assertSame(Direction::Descending, $order->getDir(), 'The direction must be "desc".');
    }
}
