<?php

declare(strict_types=1);

namespace Sfadless\HttpFilter;

/**
 * @author Pavel Golikov <pgolikov327@gmail.com>
 */
final class FilterField
{
    public function __construct(
        public readonly string $name,
        public readonly string $value,
        public readonly FilterFieldOperator $operator = FilterFieldOperator::EQUAL
    ) {}
}