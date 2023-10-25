<?php

declare(strict_types=1);

namespace Kalinin\Framework\Routing;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Kalinin\Framework\Controllers\AbstractController;
use Kalinin\Framework\Http\Request;
use Kalinin\Framework\Routing\Exception\MethodNotAllowedException;
use Kalinin\Framework\Routing\Exception\RouteNotFoundException;
use League\Container\Container;

use function FastRoute\simpleDispatcher;

class Router implements RouterInterface
{
    private array $routes;

    public function dispatcher(Request $request, Container $container): array
    {
        [$handler, $vars] = $this->extractRouteInfo($request);

        if (is_array($handler)) {
            [$controllerId, $method] = $handler;
            $controller = $container->get($controllerId);

            if (is_subclass_of($controller, AbstractController::class)) {
                $controller->setRequest($request);
            }

            $handler = [$controller, $method];
        }

        //Раскомментировать если нужно передавать Request через аргументы метода
//        $vars['request'] = $request;

        return [$handler, $vars];
    }

    public function registerRoutes(array $routes): void
    {
        $this->routes = $routes;
    }

    private function extractRouteInfo(Request $request): array
    {
        $dispatcher = simpleDispatcher(function (RouteCollector $collector) {
            foreach ($this->routes as $route) {
                $collector->addRoute(...$route);
            }
        });

        $routeInfo = $dispatcher->dispatch(
            $request->getMethod(),
            $request->getPath(),
        );

        switch ($routeInfo[0]) {
            case Dispatcher::FOUND:
                return [$routeInfo[1], $routeInfo[2]];
            case Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = implode(', ', $routeInfo[1]);
                throw new MethodNotAllowedException("Allowed HTTP methods: $allowedMethods 🤬");
            default:
                throw new RouteNotFoundException('Route not found 😢');
        }

    }
}
