<?php

declare(strict_types=1);

namespace CyrilVerloop\Datatables;

/**
 * The request from DataTables.
 */
class Request
{
    // Properties :

    /**
     * @var \CyrilVerloop\Datatables\Columns the columns.
     */
    protected Columns $columns;

    /**
     * @var \CyrilVerloop\Datatables\Orders the orders.
     */
    protected Orders $orders;

    /**
     * @var int the starting point.
     */
    protected int $start;

    /**
     * @var int the length.
     */
    protected int $length;

    /**
     * @var \CyrilVerloop\Datatables\Search the search elements.
     */
    protected Search $search;


    // Magic methods :

    /**
     * The constructor.
     * @param mixed[] $columns the columns.
     * @param mixed[] $order the orders.
     * @param int $start the starting point.
     * @param int $length the length.
     * @param mixed[] $search the search elements (keys : <int>value, <string>regex(true|false)).
     * @throws \RangeException if the draw number is less than 1.
     * @throws \RangeException if the starting point is negative.
     * @throws \RangeException if the length is 0 (zero) or less than -1 (-1 means all).
     */
    public function __construct(
        array $columns,
        array $order,
        int $start,
        int $length,
        array $search
    ) {
        $this->columns = new Columns($columns);
        $this->orders = new Orders($order);
        $this->search = new Search($search);

        if (
            $start < 0 ||
            $length === 0 ||
            $length < -1
        ) {
            throw new \RangeException('datatables.request.valueTooSmall');
        }

        $this->start = $start;
        $this->length = $length;
    }


    // Methods :

    /**
     * Returns the search criterias.
     * @return mixed[] the search criterias.
     */
    public function getCriterias(): array
    {
        // If there is no criteria :
        if ($this->search->getValue() === '') {
            return [];
        }

        $criteria = [];

        /**
         * @var \CyrilVerloop\Datatables\Column $column
         */
        foreach ($this->columns as $column) {
            if ($column->isSearchable() === true) {
                $criteria[$column->getData()] = $this->search->getValue();
            }
        }

        return $criteria;
    }

    /**
     * Returns the order.
     * @throws \OutOfBoundsException if the column does nos exist.
     * @throws \OutOfBoundsException if the column can not be order by.
     * @return mixed[] the orders for Doctrine.
     */
    public function getOrderBy(): array
    {
        $orderBy = [];

        /**
         * @var \CyrilVerloop\Datatables\Order $order
         */
        foreach ($this->orders as $order) {
            $column = $this->columns->getColumn($order->getColumn());

            if ($column->isOrderable() === false) {
                throw new \OutOfBoundsException('request.orderBy.columnNotOrderable');
            }

            // field name => asc/desc :
            $orderBy[$column->getData()] = $order->getDir();
        }

        return $orderBy;
    }
}
