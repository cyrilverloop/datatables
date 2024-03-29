<?php

declare(strict_types=1);

namespace CyrilVerloop\Datatables\Tests;

use CyrilVerloop\Datatables\Direction;
use CyrilVerloop\Datatables\Order;
use PHPUnit\Framework\Attributes as PA;
use PHPUnit\Framework\TestCase;

/**
 * Tests an order for Datatables.
 */
#[
    PA\CoversClass(Order::class),
    PA\Group('order')
]
final class OrderTest extends TestCase
{
    // Methods :

    /**
     * Tests that an object can throw a \DomainException
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
     */
    public function testCanGiveItsColumn(): void
    {
        $order = new Order(0);

        self::assertSame(0, $order->getColumn());
    }


    /**
     * Tests that the ASC direction can be returned.
     */
    public function testCanGiveAnAscDirection(): void
    {
        $order = new Order(0, Direction::Ascending);

        self::assertSame(Direction::Ascending, $order->getDir(), 'The direction must be "asc".');
    }

    /**
     * Tests that the ASC direction can be returned.
     */
    public function testCanGiveAnDescDirection(): void
    {
        $order = new Order(0, Direction::Descending);

        self::assertSame(Direction::Descending, $order->getDir(), 'The direction must be "desc".');
    }
}
