<?php

declare(strict_types=1);

namespace Sfadless\HttpFilter;

/**
 * @author Pavel Golikov <pgolikov327@gmail.com>
 */
final class QueryStringBuilder
{
    public function __construct(
        private readonly int $perPage = 10,
        private readonly int $page = 1,
        /**
         * @var FilterField[]
         */
        private array $filters = []
    ) {}

    public function addFilter(FilterField $filterField): QueryStringBuilder
    {
        $this->filters[] = $filterField;

        return $this;
    }

    public function build(): string
    {
        $data = [
            HttpFilterInterface::PAGE => $this->page,
            HttpFilterInterface::PER_PAGE => $this->perPage,
            HttpFilterInterface::FILTERS => array_map(
                fn(FilterField $filterField) => $filterField->jsonSerialize(),
                $this->filters
            )
        ];

        return http_build_query($data);
    }
}