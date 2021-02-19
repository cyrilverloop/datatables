<?php

declare(strict_types=1);

namespace CyrilVerloop\Datatables;

use CyrilVerloop\Datatables\Column;
use CyrilVerloop\Datatables\Columns;
use PHPUnit\Framework\TestCase;

/**
 * Tests the list of columns for Datatables.
 * @package \Bundles\CrudBundle\Tests\Datatables
 *
 * @coversDefaultClass \CyrilVerloop\Datatables\Columns
 * @covers ::__construct
 */
class ColumnsTest extends TestCase
{
    // Properties :

    /**
     * @var \CyrilVerloop\Datatables\Columns the list of columns.
     */
    protected Columns $columns;


    // Methods :

    /**
     * Initialises tests.
     * @return void
     */
    public function setUp(): void
    {
        $this->columns = new Columns([]);
    }


    /**
     * Returns missing datas.
     * @return mixed[] missing datas.
     */
    public function getMissingColumnDatas(): array
    {
        return [
            'the array is empty' => [
                [[]]
            ],
            'the "data" key does not exist' => [
                [['searchable' => '', 'orderable' => '']]
            ],
            'the "searchable" key does not exist' => [
                [['data' => '', 'orderable' => '']]
            ],
            'the "orderable" key does not exist' => [
                [['data' => '', 'searchable' => '']]
            ]
        ];
    }

    /**
     * Tests that an exception is thrown
     * if datas are missing.
     * @param mixed[] $missingColumnDatas missing search datas.
     * @return void
     *
     * @covers ::addFromArray
     * @dataProvider getMissingColumnDatas
     */
    public function testCanThrownAnOutOfBoundsExceptionWhenConstructingIf(array $missingColumnDatas): void
    {
        $this->expectException(\OutOfBoundsException::class);
        $this->expectExceptionMessage('columns.key.notExist');

        new Columns($missingColumnDatas);
    }


    /**
     * Returns invalid datas.
     * @return mixed[] invalid datas.
     */
    public function getInvalidColumnDatas(): array
    {
        return [
            'the "data" key is not a string' => [
                [['data' => 0, 'searchable' => '', 'orderable' => '']]
            ],
            'the "searchable" key is not a string' => [
                [['data' => '', 'searchable' => 0, 'orderable' => '']]
            ],
            'the "orderable" key is not a string' => [
                [['data' => '', 'searchable' => '', 'orderable' => 0]]
            ]
        ];
    }

    /**
     * Tests that an exception is thrown
     * if datas are invalid.
     * @param mixed[] $invalidColumnDatas invalid datas.
     * @return void
     *
     * @covers ::addFromArray
     * @dataProvider getInvalidColumnDatas
     */
    public function testCanThrownAnInvalidArgumentExceptionWhenConstructingIf(array $invalidColumnDatas): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('columns.column.InvalidArgument');

        new Columns($invalidColumnDatas);
    }


    /**
     * Returns unexpected datas.
     * @return mixed[] unexpected datas.
     */
    public function getUnexpectedColumnDatas(): array
    {
        return [
            'if "searchable" is an empty string' => [
                [['data' => '', 'name' => '', 'searchable' => '', 'orderable' => 'false', 'search' => []]]
            ],
            'if "orderable" is an empty string' => [
                [['data' => '', 'name' => '', 'searchable' => 'false', 'orderable' => '', 'search' => []]]
            ]
        ];
    }

    /**
     * Tests that an exception is thrown
     * if datas are unexpected.
     * @param mixed[] $unexpectedColumnDatas unexpected datas.
     * @return void
     *
     * @covers ::addFromArray
     * @dataProvider getUnexpectedColumnDatas
     */
    public function testCanThrowAnUnexpectedValueExceptionWhenConstructing(array $unexpectedColumnDatas): void
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('columns.dir.unexpectedValue');

        new Columns($unexpectedColumnDatas);
    }


    /**
     * Tests that a column can be added from an array
     * when "searchable" and "oderable" are "false".
     * @return void
     *
     * @covers ::addFromArray
     * @uses \CyrilVerloop\Datatables\Column
     * @uses \CyrilVerloop\Datatables\Columns
     * @uses \CyrilVerloop\Datatables\Search
     */
    public function testCanABeConstructedWhenSearchableAndOrderableAreFalse(): void
    {
        $columnDatas = [[
            'data' => 'data',
            'name' => 'name',
            'searchable' => 'false',
            'orderable' => 'false',
            'search' => [
                'value' => '',
                'regex' => 'false'
            ],
        ]];

        $this->columns = new Columns($columnDatas);

        /** @var \CyrilVerloop\Datatables\Column */
        $column = $this->columns->current();

        self::assertSame('data', $column->getData(), 'The datas must be the same.');
        self::assertFalse($column->isSearchable(), 'The method must return true.');
        self::assertFalse($column->isOrderable(), 'The method must return true.');
    }

    /**
     * Tests that a column can be added from an array
     * when "searchable" and "oderable" are "true".
     * @return void
     *
     * @covers ::addFromArray
     * @uses \CyrilVerloop\Datatables\Column
     * @uses \CyrilVerloop\Datatables\Columns
     * @uses \CyrilVerloop\Datatables\Search
     * @uses \CyrilVerloop\Iterator\IntPosition
     */
    public function testCanBeConstructedWhenSearchableAndOrderableAreTrue(): void
    {
        $columnDatas = [[
            'data' => 'data',
            'searchable' => 'true',
            'orderable' => 'true'
        ]];

        $this->columns = new Columns($columnDatas);

        /** @var \CyrilVerloop\Datatables\Column */
        $column = $this->columns->current();

        self::assertSame('data', $column->getData(), 'The datas must be the same.');
        self::assertTrue($column->isSearchable(), 'The column must not be searchable.');
        self::assertTrue($column->isOrderable(), 'The column must not be orderable.');
    }

    /**
     * Tests that an object can be constructed with datas.
     * @return void
     *
     * @uses \CyrilVerloop\Datatables\Column
     * @uses \CyrilVerloop\Datatables\Columns
     * @uses \CyrilVerloop\Datatables\Search
     */
    public function testCanBeConstructedWithDatas(): void
    {
        $columnsValues = [
            [
                'data' => 'data',
                'name' => 'name',
                'searchable' => 'true',
                'orderable' => 'true',
                'search' => [
                    'value' => '',
                    'regex' => 'false'
                ]
            ]
        ];

        $this->columns = new Columns($columnsValues);
        $column = $this->columns->current();

        self::assertSame('data', $column->getData(), 'the datas must be the same.');
    }


    /**
     * Tests that an InvalidArgumentException is thrown
     * if the position does not exist.
     * @return void
     *
     * @covers ::getColumn
     * @depends testCanABeConstructedWhenSearchableAndOrderableAreFalse
     * @depends testCanBeConstructedWhenSearchableAndOrderableAreTrue
     * @depends testCanBeConstructedWithDatas
     */
    public function testCanThrowOutOfBoundsExceptionIfPositionDoesNotExist(): void
    {
        $this->expectException(\OutOfBoundsException::class);
        $this->expectExceptionMessage('columns.position.notExist');

        $this->columns->getColumn(0);
    }


    /**
     * Tests that a column can be added and found in the list.
     * @return void
     *
     * @covers ::add
     * @covers ::getColumn
     * @uses \CyrilVerloop\Datatables\Column
     * @uses \CyrilVerloop\Datatables\Columns
     * @uses \CyrilVerloop\Datatables\Search
     * @depends testCanABeConstructedWhenSearchableAndOrderableAreFalse
     * @depends testCanBeConstructedWhenSearchableAndOrderableAreTrue
     * @depends testCanBeConstructedWithDatas
     */
    public function testCanAddAndGetColumn(): void
    {
        $column = new Column();
        $this->columns->add($column);

        self::assertSame($column, $this->columns->getColumn(0), 'The method must return the same column');
    }


    /**
     * Tests that the iterator can be rewinded.
     * @return void
     *
     * @covers ::rewind
     * @uses \CyrilVerloop\Datatables\Column
     * @uses \CyrilVerloop\Datatables\Columns
     * @uses \CyrilVerloop\Datatables\Search
     * @depends testCanAddAndGetColumn
     */
    public function testCanRewind(): void
    {
        $column = new Column();
        $this->columns->add($column);
        $this->columns->add($column);

        $firstCounter = 0;

        foreach ($this->columns as $column) {
            $firstCounter++;
        }

        self::assertSame(2, $firstCounter, 'There must be 2 object in the iterator.');

        $secondCounter = 0;

        foreach ($this->columns as $column) {
            $secondCounter++;
        }

        self::assertSame(2, $secondCounter, 'There must be also 2 object in the iterator.');
    }
}
