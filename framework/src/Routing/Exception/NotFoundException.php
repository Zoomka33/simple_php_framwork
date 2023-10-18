<?php

declare(strict_types=1);

namespace Kalinin\Framework\Routing\Exception;

use Throwable;

class NotFoundException extends HttpException
{
    public function __construct(string $message = '', int $code = 405, Throwable $previous = null)
    {
        parent::__construct(
            $message,
            $code,
            $previous
        );
    }
}
