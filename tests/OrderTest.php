<?php

declare(strict_types=1);

namespace CyrilVerloop\Datatables;

use CyrilVerloop\Datatables\Order;
use PHPUnit\Framework\TestCase;

/**
 * Tests an order for Datatables.
 * @package \Bundles\CrudBundle\Tests\Datatables
 *
 * @coversDefaultClass \CyrilVerloop\Datatables\Order
 * @covers ::__construct
 */
class OrderTest extends TestCase
{
    // Properties :

    /**
     * @var \CyrilVerloop\Datatables\Order the order.
     */
    protected Order $order;


    // Methods :

    /**
     * Initialises tests.
     * @return void
     */
    public function setUp(): void
    {
        $this->order = new Order(0);
    }


    /**
     * Tests that an object can not be constructed
     * if the column number is negative.
     * @return void
     */
    public function testCanNotBeConstructedWhenColumnIsNegative(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('order.column.negativeValue');

        new Order(-1);
    }

    /**
     * Tests that an object can not be constructed
     * if the direction is invalid.
     * @return void
     */
    public function testCanNotBeConstructedWhenDirIsInvalid(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('order.dir.notExist');

        new Order(0, 'test');
    }

    /**
     * Returns valid column numbers and directions.
     * @return mixed[] valid column numbers and directions.
     */
    public function getValidColumnAndDir(): array
    {
        return [
            'when the direction is asc' => [0, 'asc'],
            'when the direction is desc' => [0, 'desc']
        ];
    }

    /**
     * Tests that an object can be constructed.
     * @param int $column the column number.
     * @param string $dir the direction (asc/desc).
     * @return void
     *
     * @covers ::getColumn
     * @covers ::getDir
     * @dataProvider getValidColumnAndDir
     */
    public function testCanBeConstructed(int $column, string $dir): void
    {
        $this->order = new Order($column, $dir);

        self::assertSame($column, $this->order->getColumn(), 'The column must be 0 (zero).');
        self::assertSame($dir, $this->order->getDir(), 'The direction must be asc/desc.');
    }
}
