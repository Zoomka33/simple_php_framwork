<?php

declare(strict_types=1);

namespace Kalinin\Framework\Dbal;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

class ConnectionFactory
{
    public function __construct(
        private readonly string $databseUrl
    ) {
    }

    public function create(): Connection
    {
        return DriverManager::getConnection([
            'url' => $this->databseUrl,
        ]);
    }
}
