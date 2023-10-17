<?php

declare(strict_types=1);

namespace Kalinin\Framework\Console;

interface CommandInterface
{
    public function execute(array $parameters = []): int;
}
