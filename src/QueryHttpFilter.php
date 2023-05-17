<?php

declare(strict_types=1);

namespace Sfadless\HttpFilter;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Pavel Golikov <pgolikov327@gmail.com>
 */
final class QueryHttpFilter implements HttpFilterInterface
{
    private readonly int $page;
    private readonly int $perPage;

    /**
     * @var FilterField[]
     */
    private array $filters = [];

    public function __construct(string $queryString, array $options = [])
    {
        $options = $this->configureOptions($options);
        parse_str($queryString, $parsed);
        $parsed = $this->configureFilters($parsed, $options);

        $this->page = (int) $parsed[HttpFilterInterface::PAGE];
        $this->perPage = (int) $parsed[HttpFilterInterface::PER_PAGE];

        foreach ($parsed[HttpFilterInterface::FILTERS] as $filter) {
            $validatedFilter = $this->validateFilter($filter);

            $this->filters[] = new FilterField(
                $validatedFilter[HttpFilterInterface::NAME],
                $validatedFilter[HttpFilterInterface::VALUE],
                FilterFieldOperator::from($validatedFilter[HttpFilterInterface::OPERATOR])
            );
        }
    }

    public function getPerPage(): int
    {
        return $this->perPage;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getFilters(): array
    {
        return $this->filters;
    }

    private function configureOptions(array $options): array
    {
        $resolver = new OptionsResolver();

        $resolver
            ->setDefault(HttpFilterOptions::DEFAULT_PER_PAGE, 10)
            ->setAllowedTypes(HttpFilterOptions::DEFAULT_PER_PAGE, "int")

            ->setDefault(HttpFilterOptions::DEFAULT_PAGE, 1)
            ->setAllowedTypes(HttpFilterOptions::DEFAULT_PAGE, "int")
        ;

        return $resolver->resolve($options);
    }

    private function configureFilters(array $parsed, array $options): array
    {
        $resolver = new OptionsResolver();

        $resolver
            ->setDefaults([
                HttpFilterInterface::PAGE => $options[HttpFilterOptions::DEFAULT_PAGE],
                HttpFilterInterface::PER_PAGE => $options[HttpFilterOptions::DEFAULT_PER_PAGE],
                HttpFilterInterface::FILTERS => []
            ])
            ->setAllowedTypes(HttpFilterInterface::PAGE, ['int', 'string'])
            ->setAllowedTypes(HttpFilterInterface::PER_PAGE, ['int', 'string'])
            ->setAllowedTypes(HttpFilterInterface::FILTERS, 'array[]')
        ;

        return $resolver->resolve($parsed);
    }

    private function validateFilter(array $filter): array
    {
        $resolver = new OptionsResolver();

        $resolver
            ->setDefined([HttpFilterInterface::NAME, HttpFilterInterface::VALUE])
            ->setDefault(HttpFilterInterface::OPERATOR, FilterFieldOperator::EQUAL->value)
        ;

        return $resolver->resolve($filter);
    }

    public function buildQuery(array $params): string
    {
        $params = [
            HttpFilterInterface::PAGE => $params[HttpFilterInterface::PAGE] ?? $this->page,
            HttpFilterInterface::PER_PAGE => $this->perPage,
        ];

        if (count($this->filters) > 0) {
            $params[HttpFilterInterface::FILTERS] = array_map(
                fn(FilterField $filterField) => $filterField->jsonSerialize(),
                $this->filters
            );
        }

        return http_build_query($params);
    }

    public function getFilterValue(string $filterName): ?string
    {
        foreach ($this->filters as $filter) {
            if ($filter->name === $filterName) {
                return $filter->value;
            }
        }

        return null;
    }
}