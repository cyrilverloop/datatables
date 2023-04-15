<?php

declare(strict_types=1);

namespace CyrilVerloop\Datatables\Tests;

use CyrilVerloop\Datatables\Column;
use CyrilVerloop\Datatables\Columns;
use CyrilVerloop\Datatables\Order;
use CyrilVerloop\Datatables\Orders;
use CyrilVerloop\Datatables\Request;
use CyrilVerloop\Datatables\Search;
use PHPUnit\Framework\Attributes as PA;
use PHPUnit\Framework\TestCase;

/**
 * Tests the request for Datatables.
 */
#[
    PA\CoversClass(Request::class),
    PA\UsesClass(Column::class),
    PA\UsesClass(Columns::class),
    PA\UsesClass(Order::class),
    PA\UsesClass(Orders::class),
    PA\UsesClass(Search::class),
    PA\Group('request')
]
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
    private static function getSearch(): array
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
    public static function getParametersForRangeExceptions(): array
    {
        return [
            'when start is negative' => [[], [], -1, 1, self::getSearch()],
            'when length is zero' => [[], [], 0, 0, self::getSearch()],
            'when length is less than minus one' => [[], [], 0, -2, self::getSearch()],
        ];
    }

    /**
     * Tests that an \RangeException is thrown.
     * @param mixed[] $columns the columns.
     * @param mixed[] $order the order (column/direction).
     * @param int $start the starting point.
     * @param int $length the length.
     * @param mixed[] $search the elements for the search.
     */
    #[
        PA\DataProvider('getParametersForRangeExceptions'),
        PA\TestDox('Can throw a range exception $_dataName')
    ]
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
     */
    public function testCanGiveEmptyCriteria(): void
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
     */
    public function testCanGiveCriteria(): void
    {
        $request = new Request(
            $this->getColumns(),
            $this->getOrders(),
            0,
            -1,
            self::getSearch()
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
     */
    public function testCanHaveEmptyOrderByWhenThereIsNoOrders(): void
    {
        $request = new Request(
            $this->getColumns(),
            [],
            0,
            -1,
            self::getSearch()
        );

        self::assertSame([], $request->getOrderBy(), 'The value must be an empty array.');
    }


    /**
     * Tests that an exception is thrown
     * when it is impossible to order on a column.
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
            self::getSearch()
        );

        $this->expectException(\OutOfBoundsException::class);
        $this->expectExceptionMessage('request.orderBy.columnNotOrderable');

        $request->getOrderBy();
    }


    /**
     * Tests that the order is returned.
     */
    public function testCanGiveOrderBy(): void
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
            self::getSearch()
        );

        $orderBy = $request->getOrderBy();

        self::assertArrayHasKey('data', $orderBy, 'The array must have a "data" key.');
        self::assertSame('asc', $orderBy['data'], 'The direction must be "asc".');
        self::assertArrayHasKey('data4', $orderBy, 'The array must have a "data4" key.');
        self::assertSame('desc', $orderBy['data4'], 'The direction must be "desc".');
    }
}
