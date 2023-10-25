<?php

declare(strict_types=1);

namespace Kalinin\Framework\Http\Middleware;

use Kalinin\Framework\Http\Middleware\MiddlewareInterface;
use Kalinin\Framework\Http\Request;
use Kalinin\Framework\Http\Response;
use Kalinin\Framework\Routing\RouterInterface;
use Psr\Container\ContainerInterface;

class RouterDispatch implements MiddlewareInterface
{
    public function __construct(
        private RouterInterface $router,
        private ContainerInterface $container
    ) {
    }

    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        [$responseHandler, $vars] = $this->router->dispatcher($request, $this->container);

        $response = call_user_func_array($responseHandler, $vars);
        return $response;
    }
}