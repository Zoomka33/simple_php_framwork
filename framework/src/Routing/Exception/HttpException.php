<?php

declare(strict_types=1);

namespace Kalinin\Framework\Routing\Exception;

use Throwable;

class HttpException extends \Exception
{
    public function __construct(string $message = '', int $code = 500, Throwable $previous = null)
    {
        parent::__construct(
            $message,
            $code,
            $previous
        );
    }
}
