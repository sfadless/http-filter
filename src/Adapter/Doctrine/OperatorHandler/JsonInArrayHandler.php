<?php

declare(strict_types=1);

namespace Sfadless\HttpFilter\Adapter\Doctrine\OperatorHandler;

use Doctrine\ORM\QueryBuilder;
use Sfadless\HttpFilter\FilterFieldOperator;

/**
 * @author Pavel Golikov <pgolikov327@gmail.com>
 */
final class JsonInArrayHandler implements OperatorHandler
{
    public function handle(QueryBuilder $qb, string $field, string $value): void
    {
        $qb
            ->andWhere("JSON_CONTAINS($field, '\"$value\"') = 1")
        ;
    }

    public function getOperator(): FilterFieldOperator
    {
        return FilterFieldOperator::JSON_IN_ARRAY;
    }
}