<?php

declare(strict_types=1);

namespace Kalinin\Framework\Tests;

class KalininClass
{
    public function __construct(
        private readonly TestClass $testClass
    ) {
    }

    public function getTestClass(): TestClass
    {
        return $this->testClass;
    }
}
