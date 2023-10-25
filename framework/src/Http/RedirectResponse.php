<?php

declare(strict_types=1);

namespace Kalinin\Framework\Http;

use Kalinin\Framework\Http\Response;

class RedirectResponse extends Response
{

    public function __construct(string $url)
    {
        parent::__construct('', 302, ['location' => $url]);
    }

    public function send()
    {
        header("Location: {$this->getHeader('location')}", true, $this->getStatusCode());
    }
}