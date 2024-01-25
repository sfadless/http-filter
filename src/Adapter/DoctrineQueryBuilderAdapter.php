<?php

declare(strict_types=1);

namespace Sfadless\HttpFilter\Adapter;

use Doctrine\ORM\QueryBuilder;
use Sfadless\HttpFilter\Adapter\Doctrine\Exception\DuplicateOperatorHandlerException;
use Sfadless\HttpFilter\Adapter\Doctrine\Exception\OperatorHandlerNotExistsException;
use Sfadless\HttpFilter\Adapter\Doctrine\OperatorHandler\EqualHandler;
use Sfadless\HttpFilter\Adapter\Doctrine\OperatorHandler\LikeHandler;
use Sfadless\HttpFilter\Adapter\Doctrine\OperatorHandler\OperatorHandler;
use Sfadless\HttpFilter\FilterFieldOperator;
use Sfadless\HttpFilter\HttpFilterInterface;

/**
 * @author Pavel Golikov <pgolikov327@gmail.com>
 */
final class DoctrineQueryBuilderAdapter extends AbstractAdapter
{
    /**
     * @var OperatorHandler[]
     */
    private array $handlers;

    public function __construct(
        private readonly QueryBuilder $qb,

        /**
         * Массив вида 'field' => 'field_in_db', если название поля отличается от того, что в фильтре
         */
        private readonly array $fieldsMapping = []
    ) {
        $this->initDefaultOperatorHandlers();
    }

    public function addHandler(OperatorHandler $handler): void
    {
        $operatorCode = $handler->getOperator()->value;

        if (isset($this->handlers[$operatorCode])) {
            throw new DuplicateOperatorHandlerException();
        }

        $this->handlers[$operatorCode] = $handler;
    }

    public function applyFilter(HttpFilterInterface $httpFilter): void
    {
        foreach ($httpFilter->getFilters() as $filterField) {
            $operatorHandler = $this->resolveHandlerForOperator($filterField->operator);
            $operatorHandler->handle($this->qb, $this->resolveQueryFieldName($filterField->name), $filterField->value);
        }
    }

    private function initDefaultOperatorHandlers(): void
    {
        $this->handlers = [
            FilterFieldOperator::EQUAL->value => new EqualHandler(),
            FilterFieldOperator::LIKE->value => new LikeHandler(),
        ];
    }

    private function resolveQueryFieldName(string $fieldName): string
    {
        return $this->fieldsMapping[$fieldName] ?? $this->qb->getRootAliases()[0] . '.' . $fieldName;
    }

    private function resolveHandlerForOperator(FilterFieldOperator $operator): OperatorHandler
    {
        if (! isset($this->handlers[$operator->value])) {
            throw new OperatorHandlerNotExistsException('Operator handler for operator ' . $operator->value . ' not exists.');
        }

        return $this->handlers[$operator->value];
    }
}