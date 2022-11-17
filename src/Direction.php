<?php

declare(strict_types=1);

namespace CyrilVerloop\Datatables;

/**
 * Directions for sorting.
 */
enum Direction: string
{
    /**
     * Ascending.
     */
    case Ascending = 'asc';

    /**
     * Descending.
     */
    case Descending = 'desc';
}
