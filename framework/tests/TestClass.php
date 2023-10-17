<?php

declare(strict_types=1);

namespace Kalinin\Framework\Tests;

class TestClass
{
    public function __construct(
        private readonly TelegramClass $telegramClass,
        private readonly VKClass $VKClass,
    ) {
    }

    public function getTelegramClass(): TelegramClass
    {
        return $this->telegramClass;
    }

    public function getVKClass(): VKClass
    {
        return $this->VKClass;
    }
}
