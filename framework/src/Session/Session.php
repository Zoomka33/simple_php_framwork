<?php

declare(strict_types=1);

namespace Kalinin\Framework\Session;

class Session implements SessionInterface
{

    private const FLASH_KEY = 'flash';


    public function start(): void
    {
        session_start();
    }

    public function get(string $key): mixed
    {
        return $_SESSION[$key] ?? [];
    }

    public function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    public function getFlash(string $type): array
    {
        $flash = $this->get(self::FLASH_KEY) ?? [];

        if (isset($flash[$type])) {
            $messages = $flash[$type];
            unset($flash[$type]);
            $this->set(self::FLASH_KEY, $flash);
            return $messages;
        }
        return [];
    }

    public function setFlash(string $type, string $message): void
    {
        $flash = $this->get(self::FLASH_KEY) ?? [];
        $flash[$type][] = $message;
        $this->set(self::FLASH_KEY, $flash);
    }

    public function hasFlash(string $type): bool
    {
        return isset($_SESSION[self::FLASH_KEY][$type]);
    }

    public function clearFlash(): void
    {
        unset($_SESSION[self::FLASH_KEY]);
    }
}