<?php

declare(strict_types=1);

namespace CyrilVerloop\Datatables;

/**
 * A class representing an order for DataTables.
 */
class Order
{
    // Properties :

    /**
     * @var int the column number (order priority).
     */
    protected int $column;

    /**
     * @var \CyrilVerloop\Datatables\Direction the direction (asc/desc).
     */
    protected Direction $dir;


    // Magic methods :

    /**
     * The constructor.
     * @param int $column the priority.
     * @param \CyrilVerloop\Datatables\Direction $dir the direction (asc/desc).
     * @throws \DomainException If the column number (order priority) is negative.
     * @throws \DomainException if the direction is neither "asc" nor "desc".
     */
    public function __construct(int $column, Direction $dir = Direction::Ascending)
    {
        // If the column number (order priority) is negative :
        if ($column < 0) {
            throw new \DomainException('order.column.negativeValue');
        }

        $this->column = $column;
        $this->dir = $dir;
    }

    // Accessors :

    /**
     * Returns the column number (order priority).
     * @return int the column number (order priority).
     */
    public function getColumn(): int
    {
        return $this->column;
    }

    /**
     * Returns the direction (asc/desc).
     * @return \CyrilVerloop\Datatables\Direction the direction (asc/desc).
     */
    public function getDir(): Direction
    {
        return $this->dir;
    }
}
