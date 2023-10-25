<?php

declare(strict_types=1);

namespace Kalinin\Framework\Http;

class Response
{
    public function __construct(
        private mixed $content = '',
        private int $statusCode = 200,
        private array $header = []
    ) {
        http_response_code($this->statusCode);
    }

    public function setContent(mixed $content): void
    {
        $this->content = $content;
    }

    public function send()
    {
        echo $this->content;
    }

    public function getHeader(string $key)
    {
        return $this->header[$key];
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
