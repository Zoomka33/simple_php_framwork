<?php

declare(strict_types=1);

namespace Kalinin\Framework\Routing;

use Kalinin\Framework\Http\Request;
use League\Container\Container;

interface RouterInterface
{
    public function dispatcher(Request $request, Container $container);

    public function registerRoutes(array $routes): void;
}
