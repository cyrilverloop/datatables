<?php

declare(strict_types=1);

namespace CyrilVerloop\Datatables;

use CyrilVerloop\Datatables\Request;
use PHPUnit\Framework\TestCase;

/**
 * Tests the request for Datatables.
 *
 * @coversDefaultClass \CyrilVerloop\Datatables\Request
 * @covers ::__construct
 * @uses \CyrilVerloop\Datatables\Column
 * @uses \CyrilVerloop\Datatables\Columns
 * @uses \CyrilVerloop\Datatables\Order
 * @uses \CyrilVerloop\Datatables\Orders
 * @uses \CyrilVerloop\Datatables\Search
 * @group request
 */
final class RequestTest extends TestCase
{
    // Properties :

    /**
     * @var \CyrilVerloop\Datatables\Request the request.
     */
    protected Request $request;


    // Methods :

    /**
     * Returns different columns.
     * @return array different columns.
     */
    private function getColumns(): array
    {
        return [
            [
                'data' => 'data',
                'searchable' => 'true',
                'orderable' => 'true'
            ],
            [
                'data' => 'data2',
                'searchable' => 'false',
                'orderable' => 'false'
            ],
            [
                'data' => 'data3',
                'searchable' => 'true',
                'orderable' => 'false'
            ],
            [
                'data' => 'data4',
                'searchable' => 'false',
                'orderable' => 'true'
            ]
        ];
    }

    /**
     * Returns different orders.
     * @return array different orders.
     */
    private function getOrders(): array
    {
        return [
            [
                'column' => '0',
                'dir' => 'asc'
            ],
            [
                'column' => '1',
                'dir' => 'desc'
            ]
        ];
    }

    /**
     * Returns search parameters.
     * @return array search parameters.
     */
    private function getSearch(): array
    {
        return [
            'value' => 'value',
            'regex' => 'false'
        ];
    }


    /**
     * Returns the elements to test the exceptions.
     * @return mixed[] the elements to test the exceptions.
     */
    public function getParametersForRangeExceptions(): array
    {
        return [
            'when start is negative' => [[], [], -1, 1, $this->getSearch()],
            'when length is zero' => [[], [], 0, 0, $this->getSearch()],
            'when length is less than minus one' => [[], [], 0, -2, $this->getSearch()],
        ];
    }

    /**
     * Tests that an \RangeException is thrown.
     * @param mixed[] $columns the columns.
     * @param mixed[] $order the order (column/direction).
     * @param int $start the starting point.
     * @param int $length the length.
     * @param mixed[] $search the elements for the search.
     *
     * @dataProvider getParametersForRangeExceptions
     */
    public function testCanThrowARangeException(
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
     */
    public function testCanGetEmptyCriteria(): void
    {
        $search = [
            'value' => '',
            'regex' => 'false'
        ];

        $request = new Request(
            $this->getColumns(),
            $this->getOrders(),
            0,
            -1,
            $search
        );

        $criteria = $request->getCriterias();

        self::assertSame([], $criteria, 'Criteria must be empty.');
    }

    /**
     * Tests that criterias are returned.
     *
     * @covers ::getCriterias
     */
    public function testCanGetCriteria(): void
    {
        $request = new Request(
            $this->getColumns(),
            $this->getOrders(),
            0,
            -1,
            $this->getSearch()
        );
        $criterias = $request->getCriterias();

        self::assertArrayHasKey('data', $criterias, 'The array must have a "data" key.');
        self::assertArrayNotHasKey('data2', $criterias, 'The array must not have a "data2" key.');
        self::assertSame('value', $criterias['data'], 'The value to search must be test.');
        self::assertArrayHasKey('data3', $criterias, 'The array must have a "data3" key.');
    }


    /**
     * Tests that an empty array is returned
     * if there is no order.
     *
     * @covers ::getOrderBy
     */
    public function testCanHaveEmptyOrderByWhenThereIsNoOrders(): void
    {
        $request = new Request(
            $this->getColumns(),
            [],
            0,
            -1,
            $this->getSearch()
        );

        self::assertSame([], $request->getOrderBy(), 'The value must be an empty array.');
    }


    /**
     * Tests that an exception is thrown
     * when it is impossible to order on a column.
     *
     * @covers ::getOrderBy
     */
    public function testCanThrowAnOutOfBoundsExceptionIfOrderByANonOrderablaColumn(): void
    {
        $columns = [[
            'data' => 'data',
            'searchable' => 'true',
            'orderable' => 'false'
        ]];
        $orders = [['column' => '0', 'dir' => 'desc']];

        $request = new Request(
            $columns,
            $orders,
            0,
            -1,
            $this->getSearch()
        );

        $this->expectException(\OutOfBoundsException::class);
        $this->expectExceptionMessage('request.orderBy.columnNotOrderable');

        $request->getOrderBy();
    }


    /**
     * Tests that the order is returned.
     *
     * @covers ::getOrderBy
     */
    public function testCanGetOrderBy(): void
    {
        $orders = [
            [
                'column' => '0',
                'dir' => 'asc'
            ],
            [
                'column' => '3',
                'dir' => 'desc'
            ]
        ];

        $request = new Request(
            $this->getColumns(),
            $orders,
            0,
            -1,
            $this->getSearch()
        );

        $orderBy = $request->getOrderBy();

        self::assertArrayHasKey('data', $orderBy, 'The array must have a "data" key.');
        self::assertSame('asc', $orderBy['data'], 'The direction must be "asc".');
        self::assertArrayHasKey('data4', $orderBy, 'The array must have a "data4" key.');
        self::assertSame('desc', $orderBy['data4'], 'The direction must be "desc".');
    }
}
