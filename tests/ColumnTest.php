<?php

declare(strict_types=1);

namespace CyrilVerloop\Datatables;

use CyrilVerloop\Datatables\Column;
use CyrilVerloop\Datatables\Search;
use PHPUnit\Framework\TestCase;

/**
 * Test a column for Datatables.
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
     * Test that data can be accessed.
     * @return void
     *
     * @test
     * @covers ::getData
     */
    public function testCanGetData(): void
    {
        self::assertSame('', $this->column->getData(), 'Data must be an empty string.');
    }


    /**
     * Test if the column can be searched.
     * @return void
     *
     * @test
     * @covers ::isSearchable
     */
    public function testCanGetSearchable(): void
    {
        self::assertFalse($this->column->isSearchable(), 'The column must not be searchable.');
    }


    /**
     * Test if the column can be ordered.
     * @return void
     *
     * @test
     * @covers ::isOrderable
     */
    public function testCanGetOrderable(): void
    {
        self::assertFalse($this->column->isOrderable(), 'The column must not be orderable.');
    }
}
