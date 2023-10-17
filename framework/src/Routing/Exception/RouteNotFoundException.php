<?php

declare(strict_types=1);

namespace Kalinin\Framework\Routing\Exception;

use Throwable;

class RouteNotFoundException extends HttpException
{
    public function __construct(string $message = '', int $code = 404, Throwable $previous = null)
    {
        parent::__construct(
            $message,
            $code,
            $previous
        );
    }
}
