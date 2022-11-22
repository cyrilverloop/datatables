<?php

declare(strict_types=1);

namespace CyrilVerloop\Datatables\Tests;

use CyrilVerloop\Datatables\Column;
use PHPUnit\Framework\TestCase;

/**
 * Tests a column for Datatables.
 *
 * @coversDefaultClass \CyrilVerloop\Datatables\Column
 * @covers ::__construct
 * @uses \CyrilVerloop\Datatables\Search
 * @group column
 */
final class ColumnTest extends TestCase
{
    // Methods :

    private function getParametersForGetData(): array
    {
        return [
            "data is empty" => [''],
            "data is 'data'" => ['data']
        ];
    }

    /**
     * Tests that the data can be accessed.
     *
     * @covers ::getData
     * @dataProvider getParametersForGetData
     */
    public function testCanGiveItsData(string $data): void
    {
        $column = new Column($data);

        self::assertSame($data, $column->getData());
    }


    /**
     * Tests if the column can be searched.
     *
     * @covers ::isSearchable
     */
    public function testCanGiveASearchableState(): void
    {
        $column = new Column(searchable: true);

        self::assertTrue($column->isSearchable());
    }

    /**
     * Tests if the column can not be searched.
     *
     * @covers ::isSearchable
     */
    public function testCanGiveANonSearchableState(): void
    {
        $column = new Column();

        self::assertFalse($column->isSearchable());
    }


    /**
     * Tests if the column can be ordered.
     *
     * @covers ::isOrderable
     */
    public function testCanGiveAnOrderableState(): void
    {
        $column = new Column(orderable: true);

        self::assertTrue($column->isOrderable());
    }

    /**
     * Tests if the column can not be ordered.
     *
     * @covers ::isOrderable
     */
    public function testCanGiveANonOrderableState(): void
    {
        $column = new Column();

        self::assertFalse($column->isOrderable());
    }
}
