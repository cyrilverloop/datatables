<?php

declare(strict_types=1);

namespace CyrilVerloop\Datatables;

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
class ColumnTest extends TestCase
{
    // Properties :

    /**
     * @var \CyrilVerloop\Datatables\Column the column.
     */
    protected Column $column;


    // Methods :

    /**
     * Initialises tests.
     */
    public function setUp(): void
    {
        $this->column = new Column();
    }


    /**
     * Tests that data can be accessed.
     *
     * @covers ::getData
     */
    public function testCanGetData(): void
    {
        self::assertSame('', $this->column->getData(), 'Data must be an empty string.');
    }


    /**
     * Tests if the column can be searched.
     *
     * @covers ::isSearchable
     */
    public function testCanGetSearchable(): void
    {
        self::assertFalse($this->column->isSearchable(), 'The column must not be searchable.');
    }


    /**
     * Tests if the column can be ordered.
     *
     * @covers ::isOrderable
     */
    public function testCanGetOrderable(): void
    {
        self::assertFalse($this->column->isOrderable(), 'The column must not be orderable.');
    }
}
