<?php

declare(strict_types=1);

namespace Kalinin\Framework\Http\Middleware;

use Kalinin\Framework\Http\Request;
use Kalinin\Framework\Http\Response;

class Auth implements MiddlewareInterface
{

    private bool $auth = true;
    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        if (!$this->auth) {
            return new Response('Auth failed', 401);
        }

        return $handler->handle($request);
    }
}