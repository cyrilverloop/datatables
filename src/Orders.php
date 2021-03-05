<?php

declare(strict_types=1);

namespace CyrilVerloop\Datatables;

use CyrilVerloop\Iterator\IntPosition;

/**
 * A class to iterate over the orders.
 * @package \CyrilVerloop\Datatables
 */
class Orders extends IntPosition
{
    // Magic methods :

    /**
     * The constructor.
     * @param mixed[] $ordersDatas the datas of each order.
     */
    public function __construct(array $ordersDatas)
    {
        parent::__construct();
        $this->position = 0;

        /**
         * @var mixed[] $orderDatas
         */
        foreach ($ordersDatas as $orderDatas) {
            $this->addFromArray($orderDatas);
        }
    }


    // Methods :

    // \Iterator :

    public function rewind(): void
    {
        $this->position = 0;
    }


    /**
     * Adds an order from an array.
     * @param mixed[] $orderDatas the datas.
     * @throws \OutOfBoundsException if a key is missing.
     * @throws \InvalidArgumentException if "column" is not an integer.
     * @return void
     */
    private function addFromArray(array $orderDatas): void
    {
        if (
            array_key_exists('column', $orderDatas) === false ||
            array_key_exists('dir', $orderDatas) === false
        ) {
            throw new \OutOfBoundsException('orders.key.notExist');
        }

        $this->list[] = new Order((int)$orderDatas['column'], (string)$orderDatas['dir']);
    }

    /**
     * Adds an order.
     * @param \CyrilVerloop\Datatables\Order $order an order.
     * @return void
     */
    public function add(Order $order): void
    {
        $this->list[] = $order;
    }
}
