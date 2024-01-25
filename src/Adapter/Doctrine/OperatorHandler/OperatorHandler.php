<?php

declare(strict_types=1);

namespace Sfadless\HttpFilter\Adapter\Doctrine\OperatorHandler;

use Doctrine\ORM\QueryBuilder;
use Sfadless\HttpFilter\FilterFieldOperator;

/**
 * @author Pavel Golikov <pgolikov327@gmail.com>
 */
interface OperatorHandler
{
    public function handle(QueryBuilder $qb, string $field, string $value): void;

    public function getOperator(): FilterFieldOperator;
}