<?php

declare(strict_types=1);

namespace CyrilVerloop\Datatables;

/**
 * A class representing an order for DataTables.
 */
class Order
{
    // Constantes :

    /**
     * Ascending order.
     */
    public const DIR_ASC = 'asc';

    /**
     * Discending order.
     */
    public const DIR_DESC = 'desc';


    // Properties :

    /**
     * @var int the column number (order priority).
     */
    protected int $column;

    /**
     * @var string the direction (asc/desc).
     */
    protected string $dir;


    // Magic methods :

    /**
     * The constructor.
     * @param int $column the priority.
     * @param string $dir the direction (asc/desc).
     * @throws \DomainException If the column number (order priority) is negative.
     * @throws \DomainException if the direction is neither "asc" nor "desc".
     */
    public function __construct(int $column, string $dir = self::DIR_ASC)
    {
        // If the column number (order priority) is negative :
        if ($column < 0) {
            throw new \DomainException('order.column.negativeValue');
        }


        // Throws an exception if the direction is neither "asc" nor "desc" :
        if (
            $dir !== self::DIR_ASC &&
            $dir !== self::DIR_DESC
        ) {
            throw new \DomainException('order.dir.notExist');
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
     * @return string the direction (asc/desc).
     */
    public function getDir(): string
    {
        return $this->dir;
    }
}
