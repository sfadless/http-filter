<?php

declare(strict_types=1);

namespace Sfadless\HttpFilter\Adapter\Doctrine\OperatorHandler;

use Doctrine\ORM\QueryBuilder;
use Sfadless\HttpFilter\FilterFieldOperator;

/**
 * @author Pavel Golikov <pgolikov327@gmail.com>
 */
final class LikeHandler implements OperatorHandler
{
    public function handle(QueryBuilder $qb, string $field, string $value): void
    {
        $expr = $qb->expr();

        $qb->andWhere(
            $expr->like($field, $expr->literal("%$value%"))
        );
    }

    public function getOperator(): FilterFieldOperator
    {
        return FilterFieldOperator::LIKE;
    }
}