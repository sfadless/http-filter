<?php

declare(strict_types=1);

namespace Sfadless\HttpFilter;

/**
 * @author Pavel Golikov <pgolikov327@gmail.com>
 */
enum FilterFieldOperator: string
{
    case EQUAL = "=";
    case GREATER = ">";
    case GREATER_OR_EQUAL = '>=';
    case LOWER = "<";
    case LOWER_OR_EQUAL = "<=";
    case IN = "in";
    case LIKE = "like";
}