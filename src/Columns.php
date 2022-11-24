<?php

declare(strict_types=1);

namespace CyrilVerloop\Datatables;

use CyrilVerloop\Iterator\IntPosition;

/**
 * A class to iterate over the columns.
 */
class Columns extends IntPosition
{
    // Magic methods :

    /**
     * The constructor.
     * @param array $columnsDatas the datas of each column.
     */
    public function __construct(array $columnsDatas)
    {
        parent::__construct();

        /**
         * @var mixed[] $columnDatas
         */
        foreach ($columnsDatas as $columnDatas) {
            $this->addFromArray($columnDatas);
        }
    }


    // Methods :

    /**
     * Adds a column from an array.
     * @param array $columnDatas the datas.
     * @throws \OutOfBoundsException if the "data" key is missing.
     * @throws \InvalidArgumentException if the "data" key is not a string.
     */
    private function addFromArray(array $columnDatas): void
    {
        if (array_key_exists('data', $columnDatas) === false) {
            throw new \OutOfBoundsException('data.notExist');
        }

        if (is_string($columnDatas['data']) === false) {
            throw new \InvalidArgumentException('data.notAString');
        }

        $this->checkSearchable($columnDatas);
        $this->checkOrderable($columnDatas);

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

        $this->list[] = new Column($columnDatas['data'], $searchable, $orderable);
    }

    /**
     * Check if data is valid.
     * @param array $columnDatas the datas.
     * @throws \OutOfBoundsException if the "searchable" key is missing.
     * @throws \InvalidArgumentException if the "searchable" key is not a string.
     * @throws \UnexpectedValueException if "searchable" key is neither "true" nor "false".
     */
    private function checkSearchable(array $columnDatas): void
    {
        if (array_key_exists('searchable', $columnDatas) === false) {
            throw new \OutOfBoundsException('searchable.notExist');
        }

        if (is_string($columnDatas['searchable']) === false) {
            throw new \InvalidArgumentException('searchable.notAString');
        }

        if (
            $columnDatas['searchable'] !== 'true' &&
            $columnDatas['searchable'] !== 'false'
        ) {
            throw new \UnexpectedValueException('searchable.unexpectedValue');
        }
    }

    /**
     * Check if data is valid.
     * @param array $columnDatas the datas.
     * @throws \OutOfBoundsException if the "orderable" key is missing.
     * @throws \InvalidArgumentException if the "orderable" key is not a string.
     * @throws \UnexpectedValueException if "orderable"  key is neither "true" nor "false".
     */
    private function checkOrderable(array $columnDatas): void
    {
        if (array_key_exists('orderable', $columnDatas) === false) {
            throw new \OutOfBoundsException('orderable.notExist');
        }

        if (is_string($columnDatas['orderable']) === false) {
            throw new \InvalidArgumentException('orderable.notAString');
        }

        if (
            $columnDatas['orderable'] !== 'true' &&
            $columnDatas['orderable'] !== 'false'
        ) {
            throw new \UnexpectedValueException('orderable.unexpectedValue');
        }
    }

    /**
     * Adds a column.
     * @param \CyrilVerloop\Datatables\Column $column the column.
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
