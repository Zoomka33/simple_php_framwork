<?php

declare(strict_types=1);

namespace Kalinin\Framework\Http\Middleware;

use Kalinin\Framework\Http\Middleware\RequestHandlerInterface;
use Kalinin\Framework\Http\Request;
use Kalinin\Framework\Http\Response;
use Psr\Container\ContainerInterface;

class RequestHandler implements RequestHandlerInterface
{

    private array $middleware = [
        StartSession::class,
        Auth::class,
        RouterDispatch::class // обязательный middleware и обязательно в конце
    ];

    public function __construct(
        private ContainerInterface $container
    ) {
    }

    public function handle(Request $request): Response
    {
        if (empty($this->middleware)) {
            return new Response('Server error', 500);
        }

        $middlewareClass = array_shift($this->middleware);

        /**
         * @var $middleware MiddlewareInterface
         */
        $middleware = $this->container->get($middlewareClass);

        $response = $middleware->process($request, $this);

        return $response;
    }
}