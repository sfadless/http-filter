<?php

declare(strict_types=1);

namespace Sfadless\HttpFilter\Adapter;

use Sfadless\HttpFilter\HttpFilterInterface;

/**
 * @author Pavel Golikov <pgolikov327@gmail.com>
 */
abstract class AbstractAdapter
{
    abstract public function applyFilter(HttpFilterInterface $httpFilter): void;
}