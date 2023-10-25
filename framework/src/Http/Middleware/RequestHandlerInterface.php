<?php

declare(strict_types=1);

namespace Kalinin\Framework\Http\Middleware;

use Kalinin\Framework\Http\Request;
use Kalinin\Framework\Http\Response;

interface RequestHandlerInterface
{
    public function handle(Request $request): Response;

}