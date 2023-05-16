<?php

declare(strict_types=1);

namespace Sfadless\HttpFilter\Adapter;

use Doctrine\ORM\Query\Expr\Comparison;
use Doctrine\ORM\QueryBuilder;
use Exception;
use Sfadless\HttpFilter\FilterField;
use Sfadless\HttpFilter\FilterFieldOperator;
use Sfadless\HttpFilter\HttpFilterInterface;

/**
 * @author Pavel Golikov <pgolikov327@gmail.com>
 */
final class DoctrineQueryBuilderAdapter extends AbstractAdapter
{
    public function __construct(private readonly QueryBuilder $qb, private readonly array $fieldsMapping = []){}

    public function applyFilter(HttpFilterInterface $httpFilter): void
    {
        foreach ($httpFilter->getFilters() as $filterField) {
            $queryFieldName = $this->resolveQueryFieldName($filterField->name);

            $this->qb->andWhere(
                $this->getExprForOperator($filterField->operator, $queryFieldName, $filterField->value)
            );
        }
    }

    private function resolveQueryFieldName(string $fieldName): string
    {
        return $this->fieldsMapping[$fieldName] ?? $this->qb->getRootAliases()[0] . '.' . $fieldName;
    }

    private function getExprForOperator(FilterFieldOperator $operator, string $fieldName, mixed $fieldValue): Comparison
    {
        $expr = $this->qb->expr();

        switch ($operator) {
            case FilterFieldOperator::EQUAL: return $expr->eq($fieldName, $fieldValue);
            case FilterFieldOperator::LIKE: return $expr->like($fieldName, $expr->literal("%$fieldValue%"));
        }

        throw new Exception("Not implementer logic for $operator->value");
    }
}