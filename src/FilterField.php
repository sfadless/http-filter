<?php

declare(strict_types=1);

namespace Sfadless\HttpFilter;

use JsonSerializable;

/**
 * @author Pavel Golikov <pgolikov327@gmail.com>
 */
final class FilterField implements JsonSerializable
{
    public function __construct(
        public readonly string $name,
        public readonly string $value,
        public readonly FilterFieldOperator $operator = FilterFieldOperator::EQUAL
    ) {}

    public function jsonSerialize(): array
    {
        return [
            HttpFilterInterface::NAME => $this->name,
            HttpFilterInterface::VALUE => $this->value,
            HttpFilterInterface::OPERATOR => $this->operator->value
        ];
    }
}