<?php

declare(strict_types=1);

namespace CyrilVerloop\Datatables;

use CyrilVerloop\Datatables\Column;
use PHPUnit\Framework\TestCase;

/**
 * Tests a column for Datatables.
 * @package \Bundles\CrudBundle\Tests\Datatables
 *
 * @coversDefaultClass \CyrilVerloop\Datatables\Column
 * @covers ::__construct
 * @uses \CyrilVerloop\Datatables\Search
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
     * @return void
     */
    public function setUp(): void
    {
        $this->column = new Column();
    }


    /**
     * Tests that data can be accessed.
     * @return void
     *
     * @covers ::getData
     */
    public function testCanGetData(): void
    {
        self::assertSame('', $this->column->getData(), 'Data must be an empty string.');
    }


    /**
     * Tests if the column can be searched.
     * @return void
     *
     * @covers ::isSearchable
     */
    public function testCanGetSearchable(): void
    {
        self::assertFalse($this->column->isSearchable(), 'The column must not be searchable.');
    }


    /**
     * Tests if the column can be ordered.
     * @return void
     *
     * @covers ::isOrderable
     */
    public function testCanGetOrderable(): void
    {
        self::assertFalse($this->column->isOrderable(), 'The column must not be orderable.');
    }
}
