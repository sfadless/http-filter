<?php

declare(strict_types=1);

namespace Sfadless\HttpFilter\Test;

use PHPUnit\Framework\TestCase;
use Sfadless\HttpFilter\FilterFieldOperator;
use Sfadless\HttpFilter\HttpFilterInterface;
use Sfadless\HttpFilter\QueryHttpFilter;

/**
 * @author Pavel Golikov <pgolikov327@gmail.com>
 */
final class QueryHttpFilterTest extends TestCase
{
    public function testEqual(): void
    {
        $pageValue = 3;
        $perPageValue = 20;
        $filterName = "foo";
        $filterValue = "bar";

        $query = http_build_query([
            HttpFilterInterface::PAGE => $pageValue,
            HttpFilterInterface::PER_PAGE => $perPageValue,
            HttpFilterInterface::FILTERS => [
                [
                    HttpFilterInterface::NAME => $filterName,
                    HttpFilterInterface::VALUE => $filterValue,
                    HttpFilterInterface::OPERATOR => FilterFieldOperator::EQUAL->value
                ]
            ]
        ]);

        $queryFilter = new QueryHttpFilter($query);
        $this->assertEquals($queryFilter->getPage(), $pageValue);
        $this->assertEquals($queryFilter->getPerPage(), $perPageValue);
    }

    public function testDefaultValues(): void
    {
        $queryFilter = new QueryHttpFilter("");

        $this->assertEquals($queryFilter->getPage(), 1);
        $this->assertEquals($queryFilter->getPerPage(), 10);
    }
}