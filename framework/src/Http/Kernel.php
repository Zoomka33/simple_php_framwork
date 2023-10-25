<?php

declare(strict_types=1);

namespace Kalinin\Framework\Http;

use Kalinin\Framework\Http\Middleware\RequestHandlerInterface;
use Kalinin\Framework\Routing\Exception\HttpException;
use Kalinin\Framework\Routing\RouterInterface;
use League\Container\Container;

class Kernel
{
    private string $appEnv;

    public function __construct(
        private readonly RouterInterface $router,
        private readonly Container $container,
        private RequestHandlerInterface $requestHandler
    ) {
        $this->appEnv = $this->container->get('APP_ENV');
    }

    public function handle(Request $request): Response
    {

        try {
            $response = $this->requestHandler->handle($request);
        } catch (\Exception $e) {
            $response = $this->createExceptionResponse($e);
        }

        return $response;
    }

    public function terminate(Request $request, Response $response): void
    {
        //очистка мусора

        //чистим сессию от флэш сообщений
        $request->getSession()->clearFlash();
    }

    private function createExceptionResponse(\Exception $e): Response
    {
        if (in_array($this->appEnv, ['local', 'dev'])) {
            $whoops = new \Whoops\Run;
            $whoops->allowQuit(false);
            $whoops->writeToOutput(true);
            $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
            $whoops->handleException($e);
        }

        if ($e instanceof HttpException) {
            return new Response($e->getMessage(), $e->getCode());
        }

        return new Response('Server error', 500);
    }
}
