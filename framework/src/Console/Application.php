<?php

declare(strict_types=1);

namespace Kalinin\Framework\Console;

use Psr\Container\ContainerInterface;

class Application
{
    public function __construct(
        private ContainerInterface $container
    )
    {
    }

    public function run(): int
    {
        $argv = $_SERVER['argv'];
        $commandName = $argv[1] ?? null;

        if (!$commandName) {
            throw new ConsoleException('Invalid console command 🥳');
        }

        /**
         * @var CommandInterface $command
         */
        $command = $this->container->get("console:$commandName");

        $args = $_SERVER['argv'];
        $options = $this->parseOptions($args);

        $status = $command->execute($options);

        return $status;
    }

    private function parseOptions(array $args): array
    {
        $options = [];

        $params = array_slice($args, 2);

        foreach ($params as $param)
        {
            if (str_starts_with($param, '--')) {
                $option = explode('=', substr($param, 2));
                $options[$option[0]] = $option[1] ?? true;
            }
        }
        return $options;
    }
}