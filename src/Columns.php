<?php

declare(strict_types=1);

namespace CyrilVerloop\Datatables;

use CyrilVerloop\Iterator\IntPosition;

/**
 * A class to iterate over the columns.
 * @package \CyrilVerloop\Datatables
 */
class Columns extends IntPosition
{
    // Magic methods :

    /**
     * The constructor.
     * @param mixed[] $columnsDatas the datas of each column.
     */
    public function __construct(array $columnsDatas)
    {
        parent::__construct();
        $this->position = 0;

        /**
         * @var mixed[] $columnDatas
         */
        foreach ($columnsDatas as $columnDatas) {
            $this->addFromArray($columnDatas);
        }
    }


    // Methods :

    // \Iterator :

    public function rewind(): void
    {
        $this->position = 0;
    }


    /**
     * Adds a column from an array.
     * @param mixed[] $columnDatas the datas.
     * @throws \OutOfBoundsException if a key is missing.
     * @throws \InvalidArgumentException if the values are not of the proper types.
     * @throws \UnexpectedValueException if "searchable" or "orderable" are not "true" or "false".
     * @return void
     */
    private function addFromArray(array $columnDatas): void
    {
        if (
            array_key_exists('data', $columnDatas) === false ||
            array_key_exists('searchable', $columnDatas) === false ||
            array_key_exists('orderable', $columnDatas) === false
        ) {
            throw new \OutOfBoundsException('columns.key.notExist');
        }

        if (
            is_string($columnDatas['data']) === false ||
            is_string($columnDatas['searchable']) === false ||
            is_string($columnDatas['orderable']) === false
        ) {
            throw new \InvalidArgumentException('columns.column.InvalidArgument');
        }

        if (
            $columnDatas['searchable'] !== 'true' &&
            $columnDatas['searchable'] !== 'false' ||
            $columnDatas['orderable'] !== 'true' &&
            $columnDatas['orderable'] !== 'false'
        ) {
            throw new \UnexpectedValueException('columns.dir.unexpectedValue');
        }

        $searchable = false;
        $orderable = false;

        // If we can search on the column :
        if ($columnDatas['searchable'] === 'true') {
            $searchable = true;
        }

        // If we can order on the column :
        if ($columnDatas['orderable'] === 'true') {
            $orderable = true;
        }

        $this->add(new Column($columnDatas['data'], $searchable, $orderable));
    }

    /**
     * Adds a column.
     * @param \CyrilVerloop\Datatables\Column $column the column.
     * @return void
     */
    public function add(Column $column): void
    {
        $this->list[] = $column;
    }

    /**
     * Returns the column at the position $position.
     * @param int $position the position.
     * @throws \OutOfBoundsException if the position does not exist.
     * @return \CyrilVerloop\Datatables\Column the column at the position $position.
     */
    public function getColumn(int $position): Column
    {
        if (array_key_exists($position, $this->list) === false) {
            throw new \OutOfBoundsException('columns.position.notExist');
        }

        /**
         * @var \CyrilVerloop\Datatables\Column
         */
        return $this->list[$position];
    }
}
