<?php

declare(strict_types=1);

namespace Kalinin\Framework\Http\Middleware;

use Kalinin\Framework\Http\Request;
use Kalinin\Framework\Http\Response;
use Kalinin\Framework\Session\Session;

class StartSession implements MiddlewareInterface
{

    public function __construct(
        private Session $session
    ) {
    }

    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        $this->session->start();

        $request->setSession($this->session);

        return $handler->handle($request);
    }
}