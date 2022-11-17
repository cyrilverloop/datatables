<?php

declare(strict_types=1);

namespace CyrilVerloop\Datatables;

use CyrilVerloop\Datatables\Order;
use CyrilVerloop\Datatables\Request;
use PHPUnit\Framework\TestCase;

/**
 * Tests the request for Datatables.
 *
 * @coversDefaultClass \CyrilVerloop\Datatables\Request
 * @covers ::__construct
 * @group request
 */
class RequestTest extends TestCase
{
    // Properties :

    /**
     * @var mixed[] the columns.
     */
    protected array $columns;

    /**
     * @var mixed[] the orders (column/direction).
     */
    protected array $orders;

    /**
     * @var int the starting point.
     */
    protected int $start;

    /**
     * @var int the length.
     */
    protected int $length;

    /**
     * @var mixed[] the search elements.
     */
    protected array $search;

    /**
     * @var \CyrilVerloop\Datatables\Request the request.
     */
    protected Request $request;


    // Methods :

    /**
     * Initialises tests.
     */
    public function setUp(): void
    {
        $this->columns = [
            [
                'data' => 'data',
                'searchable' => 'true',
                'orderable' => 'true'
            ],
            [
                'data' => 'data2',
                'searchable' => 'false',
                'orderable' => 'false'
            ]
        ];

        $this->orders = [
            [
                'column' => '0',
                'dir' => Order::DIR_DESC
            ]
        ];

        $this->start = 0;
        $this->length = 1;

        $this->search = [
            'value' => 'value',
            'regex' => 'false'
        ];

        $this->request = new Request(
            $this->columns,
            $this->orders,
            $this->start,
            $this->length,
            $this->search
        );
    }


    /**
     * Returns the elements to test the exceptions.
     * @return mixed[] the elements to test the exceptions.
     */
    public function getParametersForConstructorRangeExceptions(): array
    {
        $search = [
            'value' => '',
            'regex' => 'false'
        ];

        return [
            'when start is negative' => [[], [], -1, 1, $search],
            'when length is zero' => [[], [], 0, 0, $search],
            'when length is less than minus one' => [[], [], 0, -2, $search],
        ];
    }

    /**
     * Tests that an \RangeException is thrown when constructed.
     * @param mixed[] $columns the columns.
     * @param mixed[] $order the order (column/direction).
     * @param int $start the starting point.
     * @param int $length the length.
     * @param mixed[] $search the elements for the search.
     *
     * @uses \CyrilVerloop\Datatables\Column
     * @uses \CyrilVerloop\Datatables\Columns
     * @uses \CyrilVerloop\Datatables\Order
     * @uses \CyrilVerloop\Datatables\Orders
     * @uses \CyrilVerloop\Datatables\Search
     * @dataProvider getParametersForConstructorRangeExceptions
     */
    public function testConstructorCanThrowARangeException(
        array $columns,
        array $order,
        int $start,
        int $length,
        array $search
    ): void {
        $this->expectException(\RangeException::class);
        $this->expectExceptionMessage('datatables.request.valueTooSmall');

        new Request($columns, $order, $start, $length, $search);
    }


    /**
     * Tests that no criteria is returned.
     *
     * @covers ::getCriterias
     * @uses \CyrilVerloop\Datatables\Column
     * @uses \CyrilVerloop\Datatables\Columns
     * @uses \CyrilVerloop\Datatables\Order
     * @uses \CyrilVerloop\Datatables\Orders
     * @uses \CyrilVerloop\Datatables\Search
     */
    public function testCanGetEmptyCriteria(): void
    {
        $this->search['value'] = '';

        $this->request = new Request(
            $this->columns,
            $this->orders,
            $this->start,
            $this->length,
            $this->search
        );

        $criteria = $this->request->getCriterias();

        self::assertSame([], $criteria, 'Criteria must be empty.');
    }

    /**
     * Tests that criterias are returned.
     *
     * @covers ::getCriterias
     * @uses \CyrilVerloop\Datatables\Column
     * @uses \CyrilVerloop\Datatables\Columns
     * @uses \CyrilVerloop\Datatables\Order
     * @uses \CyrilVerloop\Datatables\Orders
     * @uses \CyrilVerloop\Datatables\Search
     */
    public function testCanGetCriteria(): void
    {
        $criterias = $this->request->getCriterias();

        self::assertArrayHasKey('data', $criterias, 'The array must have a "data" key.');
        self::assertArrayNotHasKey('data2', $criterias, 'The array must not have a "data2" key.');
        self::assertSame('value', $criterias['data'], 'The value to search must be test.');
    }


    /**
     * Tests that an empty array is returned
     * if there is no order.
     *
     * @covers ::getOrderBy
     * @uses \CyrilVerloop\Datatables\Column
     * @uses \CyrilVerloop\Datatables\Columns
     * @uses \CyrilVerloop\Datatables\Order
     * @uses \CyrilVerloop\Datatables\Orders
     * @uses \CyrilVerloop\Datatables\Search
     */
    public function testCanHaveEmptyOrderByWhenThereIsNoOrders(): void
    {
        $this->request = new Request(
            $this->columns,
            [],
            $this->start,
            $this->length,
            $this->search
        );

        self::assertSame([], $this->request->getOrderBy(), 'The value must be an empty array.');
    }


    /**
     * Tests that an exception is thrown
     * when it is impossible to order on a column.
     *
     * @covers ::getOrderBy
     * @uses \CyrilVerloop\Datatables\Column
     * @uses \CyrilVerloop\Datatables\Columns
     * @uses \CyrilVerloop\Datatables\Order
     * @uses \CyrilVerloop\Datatables\Orders
     * @uses \CyrilVerloop\Datatables\Request
     * @uses \CyrilVerloop\Datatables\Search
     */
    public function testCanThrowAnOutOfBoundsExceptionIfOrderByANonOrderablaColumn(): void
    {
        $this->orders = [['column' => '0', 'dir' => Order::DIR_DESC]];
        $this->columns = [[
            'data' => 'data',
            'searchable' => 'true',
            'orderable' => 'false'
        ]];

        $this->request = new Request(
            $this->columns,
            $this->orders,
            $this->start,
            $this->length,
            $this->search
        );

        $this->expectException(\OutOfBoundsException::class);
        $this->expectExceptionMessage('request.orderBy.columnNotOrderable');

        $this->request->getOrderBy();
    }


    /**
     * Tests that the order is returned.
     *
     * @covers ::getOrderBy
     * @uses \CyrilVerloop\Datatables\Column
     * @uses \CyrilVerloop\Datatables\Columns
     * @uses \CyrilVerloop\Datatables\Order
     * @uses \CyrilVerloop\Datatables\Orders
     * @uses \CyrilVerloop\Datatables\Search
     */
    public function testCanGetOrderBy(): void
    {
        $this->orders = [['column' => '0', 'dir' => Order::DIR_DESC]];
        $this->columns = [[
            'data' => 'data',
            'searchable' => 'true',
            'orderable' => 'true'
        ]];

        $this->request = new Request(
            $this->columns,
            $this->orders,
            $this->start,
            $this->length,
            $this->search
        );

        $orderBy = $this->request->getOrderBy();

        self::assertArrayHasKey('data', $orderBy, 'The array must have a "data" key.');
        self::assertSame(Order::DIR_DESC, $orderBy['data'], 'The value must be ' . Order::DIR_DESC . '.');
    }
}
