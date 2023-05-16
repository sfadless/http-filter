<?php

declare(strict_types=1);

namespace Sfadless\HttpFilter;

/**
 * @author Pavel Golikov <pgolikov327@gmail.com>
 */
interface HttpFilterInterface
{
    public const PER_PAGE = 'perPage';
    public const PAGE = 'page';
    public const NAME = 'name';
    public const VALUE = 'value';
    public const OPERATOR = 'operator';
    public const FILTERS = 'filters';

    public function getPerPage(): int;

    public function getPage(): int;

    /**
     * @return FilterField[]
     */
    public function getFilters(): array;
}