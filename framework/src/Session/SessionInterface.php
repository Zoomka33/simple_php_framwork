<?php

declare(strict_types=1);

namespace Kalinin\Framework\Session;

interface SessionInterface
{
    public function start(): void;
    public function get(string $key);
    public function set(string $key, mixed $value);
    public function remove(string $key);
    public function has(string $key);
    public function getFlash(string $type): array;

    public function setFlash(string $type, string $message): void;

    public function hasFlash(string $type): bool;

    public function clearFlash(): void;

}