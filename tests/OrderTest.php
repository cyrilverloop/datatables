<?php

declare(strict_types=1);

namespace CyrilVerloop\Datatables;

use CyrilVerloop\Datatables\Order;
use PHPUnit\Framework\TestCase;

/**
 * Tests an order for Datatables.
 *
 * @coversDefaultClass \CyrilVerloop\Datatables\Order
 * @covers ::__construct
 */
class OrderTest extends TestCase
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
     * Tests that an object can not be constructed
     * if the direction is invalid.
     */
    public function testCanThrowADomainExceptionWhenDirIsInvalid(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('order.dir.notExist');

        new Order(0, 'test');
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
        $order = new Order(0, 'asc');

        self::assertSame('asc', $order->getDir(), 'The direction must be "asc".');
    }

    /**
     * Tests that the ASC direction can be returned.
     *
     * @covers ::getDir
     */
    public function testCanGetDescDirection(): void
    {
        $order = new Order(0, 'desc');

        self::assertSame('desc', $order->getDir(), 'The direction must be "desc".');
    }
}
