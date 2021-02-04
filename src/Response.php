<?php

declare(strict_types=1);

namespace CyrilVerloop\Datatables;

/**
 * The response to DataTables.
 * @package \CyrilVerloop\Datatables
 */
class Response implements \JsonSerializable
{
    // Properties :

    /**
     * @var int the number of time the array is drawn.
     */
    protected int $draw;

    /**
     * @var mixed[] the datas.
     */
    protected array $data;

    /**
     * @var int the number of records.
     */
    protected int $recordsTotal;

    /**
     * @var int the number of filtered records.
     */
    protected int $recordsFiltered;


    // Magic methods :

    /**
     * The constructor.
     * @param int $draw the number of time the array is drawn.
     * @param mixed[] $data the datas.
     * @param int $recordsTotal the number of records.
     * @param int $recordsFiltered the number of filtered records.
     * @throws \RangeException if draw is less than 1.
     * @throws \RangeException if the number of records is negative.
     * @throws \RangeException if the number of filtered records is negative.
     * @throws \LogicException if the number of records is less than the number of filtered records.
     * @throws \LogicException if the datas count is greater than the number of records.
     * @throws \LogicException if the datas count is greater than the number of filtered records.
     */
    public function __construct(int $draw, array $data, int $recordsTotal, int $recordsFiltered)
    {
        if (
            $draw < 1 ||
            $recordsTotal < 0 ||
            $recordsFiltered < 0
        ) {
            throw new \RangeException('datatables.response.valueTooSmall');
        }

        $datasCount = count($data);

        if (
            $recordsTotal < $recordsFiltered ||
            $datasCount > $recordsTotal ||
            $datasCount > $recordsFiltered
        ) {
            throw new \LogicException('datatables.response.recordsCountError');
        }

        $this->draw = $draw;
        $this->data = $data;
        $this->recordsTotal = $recordsTotal;
        $this->recordsFiltered = $recordsFiltered;
    }


    // Methods :

    /**
     * Returns the serialized object.
     * @return mixed[] the serialized object.
     */
    public function jsonSerialize(): array
    {
        return [
            'draw' => $this->draw,
            'data' => $this->data,
            'recordsTotal' => $this->recordsTotal,
            'recordsFiltered' => $this->recordsFiltered
        ];
    }
}
