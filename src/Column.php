<?php

declare(strict_types=1);

namespace CyrilVerloop\Datatables;

/**
 * A class representing a column for DataTables.
 */
class Column
{
    // Properties :

    /**
     * @var string the field name.
     */
    protected string $data;

    /**
     * @var bool if it is possible to search on the column.
     */
    protected bool $searchable;

    /**
     * @var bool if it is possible to order on the column.
     */
    protected bool $orderable;


    // Magic methods :

    /**
     * The constructor.
     * @param string $data the field name.
     * @param bool $searchable if it is possible to search on the column.
     * @param bool $orderable if it is possible to order on the column.
     */
    public function __construct(
        string $data = '',
        bool $searchable = false,
        bool $orderable = false
    ) {
        $this->data = $data;
        $this->searchable = $searchable;
        $this->orderable = $orderable;
    }


    // Accessors :

    /**
     * Returns the field name.
     * @return string the field name.
     */
    public function getData(): string
    {
        return $this->data;
    }

    /**
     * Tells wether the column is searchable or not.
     * @return bool wether the column is searchable or not.
     */
    public function isSearchable(): bool
    {
        return $this->searchable;
    }

    /**
     * Tells wether the column is orderable or not.
     * @return bool wether the column is orderable or not.
     */
    public function isOrderable(): bool
    {
        return $this->orderable;
    }
}
