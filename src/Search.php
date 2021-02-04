<?php

declare(strict_types=1);

namespace CyrilVerloop\Datatables;

/**
 * A classe representing the search element for a DataTables column.
 * @package \CyrilVerloop\Datatables
 */
class Search
{

    // Properties :

    /**
     * @var string the value to search.
     */
    protected string $value;

    /**
     * @var bool whether the value is a regex.
     */
    protected bool $regex;


    // Magic methods :

    /**
     * The constructor.
     * @param mixed[] $searchDatas the datas for the search.
     */
    public function __construct(array $searchDatas)
    {
        if (
            array_key_exists('value', $searchDatas) === false ||
            array_key_exists('regex', $searchDatas) === false
        ) {
            throw new \OutOfBoundsException('search.key.notExist');
        }

        if (
            is_string($searchDatas['value']) === false ||
            is_string($searchDatas['regex']) === false
        ) {
            throw new \InvalidArgumentException('search.key.notAString');
        }

        if (
            $searchDatas['regex'] !== 'true' &&
            $searchDatas['regex'] !== 'false'
        ) {
            throw new \RangeException('search.regex.notValid');
        }

        $this->value = $searchDatas['value'];

        // If it is a regex :
        if ($searchDatas['regex'] === 'true') {
            $this->regex = true;
        } else {
            $this->regex = false;
        }
    }

    // Accessors :

    /**
     * Returns the value to search.
     * @return string whether the value is a regex.
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Returns whether the value is a regex.
     * @return bool whether the value is a regex.
     */
    public function getRegex(): bool
    {
        return $this->regex;
    }
}
