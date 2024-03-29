<?php

declare(strict_types=1);

namespace CyrilVerloop\Datatables\Tests;

use CyrilVerloop\Datatables\Column;
use CyrilVerloop\Datatables\Columns;
use PHPUnit\Framework\Attributes as PA;
use PHPUnit\Framework\TestCase;

/**
 * Tests the list of columns for Datatables.
 */
#[
    PA\CoversClass(Columns::class),
    PA\UsesClass(Column::class),
    PA\Group('columns')
]
final class ColumnsTest extends TestCase
{
    // Methods :

    /**
     * Returns columns values.
     * @return array columns values.
     */
    private function getColumnsValues(): array
    {
        return [[
            'data' => 'test-data',
            'name' => 'test-name',
            'searchable' => 'true',
            'orderable' => 'true',
            'search' => [
                'value' => '',
                'regex' => 'false'
            ]
        ]];
    }

    /**
     * Tests that an \OutOfBoundsException is thrown
     * when data is missing.
     */
    public function testCanThrowAnOutOfBoundsExceptionWhenDataIsMissing(): void
    {
        $this->expectException(\OutOfBoundsException::class);
        $this->expectExceptionMessage('data.notExist');

        $columnsValues = $this->getColumnsValues();
        unset($columnsValues[0]['data']);

        new Columns($columnsValues);
    }

    /**
     * Tests that an \OutOfBoundsException is thrown
     * when searchable is missing.g.
     */
    public function testCanThrowAnOutOfBoundsExceptionWhenSearchableIsMissing(): void
    {
        $this->expectException(\OutOfBoundsException::class);
        $this->expectExceptionMessage('searchable.notExist');

        $columnsValues = $this->getColumnsValues();
        unset($columnsValues[0]['searchable']);

        new Columns($columnsValues);
    }

    /**
     * Tests that an \OutOfBoundsException is thrown
     * when orderable is missing.
     */
    public function testCanThrowAnOutOfBoundsExceptionWhenOrderableIsMissing(): void
    {
        $this->expectException(\OutOfBoundsException::class);
        $this->expectExceptionMessage('orderable.notExist');

        $columnsValues = $this->getColumnsValues();
        unset($columnsValues[0]['orderable']);

        new Columns($columnsValues);
    }


    /**
     * Returns invalid datas.
     * @return mixed[] invalid datas.
     */
    public static function getNotAStringValue(): array
    {
        return [
            'an int' => [0],
            'a float' => [0.5],
            'an array' => [[]],
            'an object' => [new \stdClass()]
        ];
    }

    /**
     * Tests that an exception is thrown
     * when data is not string.
     * @param mixed $notAStringValue not a string value.
     */
    #[
        PA\DataProvider('getNotAStringValue')
    ]
    public function testCanThrowAnInvalidArgumentExceptionWhenDataIsNotAString(mixed $notAStringValue): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('data.notAString');

        $columnsValues = $this->getColumnsValues();
        $columnsValues[0]['data'] = $notAStringValue;

        new Columns($columnsValues);
    }

    /**
     * Tests that an exception is thrown
     * when searchable is not string.
     * @param mixed $notAStringValue not a string value.
     */
    #[
        PA\DataProvider('getNotAStringValue')
    ]
    public function testCanThrowAnInvalidArgumentExceptionWhenSearchableIsNotAString(mixed $notAStringValue): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('searchable.notAString');

        $columnsValues = $this->getColumnsValues();
        $columnsValues[0]['searchable'] = $notAStringValue;

        new Columns($columnsValues);
    }

    /**
     * Tests that an exception is thrown
     * when orderable is not string.
     * @param mixed $notAStringValue not a string value.
     */
    #[
        PA\DataProvider('getNotAStringValue')
    ]
    public function testCanThrowAnInvalidArgumentExceptionWhenOrderableIsNotAString(mixed $notAStringValue): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('orderable.notAString');

        $columnsValues = $this->getColumnsValues();
        $columnsValues[0]['orderable'] = $notAStringValue;

        new Columns($columnsValues);
    }


    /**
     * Tests that an exception is thrown
     * when searchable is neither "true" nor "false".
     */
    public function testCanThrowAnUnexpectedValueExceptionWhenSearchableIsNeitherTrueNorFalse(): void
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('searchable.unexpectedValue');

        $columnsValues = $this->getColumnsValues();
        $columnsValues[0]['searchable'] = '';

        new Columns($columnsValues);
    }

    /**
     * Tests that an exception is thrown
     * when orderable is neither "true" nor "false".
     */
    public function testCanThrowAnUnexpectedValueExceptionWhenOrderableIsNeitherTrueNorFalse(): void
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage('orderable.unexpectedValue');

        $columnsValues = $this->getColumnsValues();
        $columnsValues[0]['orderable'] = '';

        new Columns($columnsValues);
    }


    /**
     * Tests that a column can be added from an array
     * when "searchable" and "oderable" are "false".
     */
    public function testCanHaveAFalseValueForSearchableAndOrderable(): void
    {
        $columnsValues = $this->getColumnsValues();
        $columnsValues[0]['searchable'] = 'false';
        $columnsValues[0]['orderable'] = 'false';
        $columns = new Columns($columnsValues);

        /** @var \CyrilVerloop\Datatables\Column */
        $column = $columns->current();

        self::assertSame('test-data', $column->getData(), 'The datas must be the same.');
        self::assertFalse($column->isSearchable(), 'The method must return true.');
        self::assertFalse($column->isOrderable(), 'The method must return true.');
    }

    /**
     * Tests that a column can be added from an array
     * when "searchable" and "oderable" are "true".
     */
    public function testCanHaveATrueValueForSearchableAndOrderable(): void
    {
        $columns = new Columns($this->getColumnsValues());

        /** @var \CyrilVerloop\Datatables\Column */
        $column = $columns->current();

        self::assertSame('test-data', $column->getData());
        self::assertTrue($column->isSearchable(), 'The column must not be searchable.');
        self::assertTrue($column->isOrderable(), 'The column must not be orderable.');
    }


    /**
     * Tests that an InvalidArgumentException is thrown
     * if the position does not exist.
     */
    #[
        PA\Depends('testCanHaveAFalseValueForSearchableAndOrderable'),
        PA\Depends('testCanHaveATrueValueForSearchableAndOrderable')
    ]
    public function testCanThrowAnOutOfBoundsExceptionWhenPositionDoesNotExist(): void
    {
        $this->expectException(\OutOfBoundsException::class);
        $this->expectExceptionMessage('columns.position.notExist');

        $columns = new Columns([]);
        $columns->getColumn(0);
    }


    /**
     * Tests that a column can be added and found in the list.
     */
    #[
        PA\Depends('testCanHaveAFalseValueForSearchableAndOrderable'),
        PA\Depends('testCanHaveATrueValueForSearchableAndOrderable')
    ]
    public function testCanAddAndGiveAColumn(): void
    {
        $columns = new Columns([]);
        $column = new Column('test-data');
        $columns->add($column);

        self::assertSame($column, $columns->getColumn(0));
    }
}
