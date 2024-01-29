<?php

declare(strict_types=1);

namespace Sfadless\HttpFilter\Test;

use PHPUnit\Framework\TestCase;
use Sfadless\HttpFilter\FilterField;
use Sfadless\HttpFilter\FilterFieldOperator;
use Sfadless\HttpFilter\QueryHttpFilter;
use Sfadless\HttpFilter\QueryStringBuilder;

/**
 * @author Pavel Golikov <pgolikov327@gmail.com>
 */
final class QueryStringBuilderTest extends TestCase
{
    public function testBuild(): void
    {
        $builder = new QueryStringBuilder(15, 2, [
            new FilterField(name: 'foo', value: 'bar', operator: FilterFieldOperator::EQUAL),
            new FilterField(name: 'baz', value: '5', operator: FilterFieldOperator::GREATER_OR_EQUAL)
        ]);

        $queryString = $builder->build();

        $filter = new QueryHttpFilter($queryString);

        $this->assertEquals($filter->getFilterValue('foo'), 'bar');
        $this->assertEquals($filter->getFilterValue('baz'), '5');
    }
}