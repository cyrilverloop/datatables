<?php

declare(strict_types=1);

namespace CyrilVerloop\Datatables\Tests;

use CyrilVerloop\Datatables\Column;
use PHPUnit\Framework\Attributes as PA;
use PHPUnit\Framework\TestCase;

/**
 * Tests a column for Datatables.
 */
#[
    PA\CoversClass(Column::class),
    PA\Group('column')
]
final class ColumnTest extends TestCase
{
    // Methods :

    public static function getParametersForGetData(): array
    {
        return [
            'empty' => [''],
            '"data"' => ['data']
        ];
    }

    /**
     * Tests that the data can be accessed.
     */
    #[
        PA\DataProvider('getParametersForGetData'),
        PA\TestDox('Can give its data when it is $_dataName')
    ]
    public function testCanGiveItsData(string $data): void
    {
        $column = new Column($data);

        self::assertSame($data, $column->getData());
    }


    /**
     * Tests if the column can be searched.
     */
    public function testCanGiveASearchableState(): void
    {
        $column = new Column(searchable: true);

        self::assertTrue($column->isSearchable());
    }

    /**
     * Tests if the column can not be searched.
     */
    public function testCanGiveANonSearchableState(): void
    {
        $column = new Column();

        self::assertFalse($column->isSearchable());
    }


    /**
     * Tests if the column can be ordered.
     */
    public function testCanGiveAnOrderableState(): void
    {
        $column = new Column(orderable: true);

        self::assertTrue($column->isOrderable());
    }

    /**
     * Tests if the column can not be ordered.
     */
    public function testCanGiveANonOrderableState(): void
    {
        $column = new Column();

        self::assertFalse($column->isOrderable());
    }
}
