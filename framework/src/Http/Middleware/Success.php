<?php

declare(strict_types=1);

namespace Kalinin\Framework\Http\Middleware;

use App\Services\PostService;
use Kalinin\Framework\Http\Request;
use Kalinin\Framework\Http\Response;

class Success implements MiddlewareInterface
{
    public function __construct(
        private PostService $service
    ) {
    }

    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        return new Response('Hello');
    }
}