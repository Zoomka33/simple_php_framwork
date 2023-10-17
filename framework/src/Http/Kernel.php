<?php

declare(strict_types=1);

namespace Kalinin\Framework\Http;

use Kalinin\Framework\Routing\Exception\HttpException;
use Kalinin\Framework\Routing\RouterInterface;
use League\Container\Container;

class Kernel
{
    private string $appEnv;

    public function __construct(
        private readonly RouterInterface $router,
        private readonly Container $container,
    ) {
        $this->appEnv = $this->container->get('APP_ENV');
    }

    public function handle(Request $request): Response
    {

        try {
            [$responseHandler, $vars] = $this->router->dispatcher($request, $this->container);

            $response = call_user_func_array($responseHandler, $vars);
        } catch (\Exception $e) {
            $response = $this->createExceptionResponse($e);
        }

        return $response;
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
