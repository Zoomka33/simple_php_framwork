<?php

declare(strict_types=1);

namespace Kalinin\Framework\Container;

use Kalinin\Framework\Container\Exceptions\ContainerException;
use Psr\Container\ContainerInterface;
use ReflectionParameter;

class Container implements ContainerInterface
{
    private array $services = [];

    public function add(string $id, string|object $concrete = null)
    {
        if (is_null($concrete)) {
            if (! class_exists($id)) {
                throw new ContainerException("Service $id not found  😢");
            }
            $concrete = $id;
        }
        $this->services[$id] = $concrete;

    }

    public function get(string $id)
    {
        if (! $this->has($id)) {
            if (! class_exists($id)) {
                throw new ContainerException("Service $id could not be resolved 😭");
            }
            $this->add($id);
        }

        $instance = $this->resolve($this->services[$id]);

        return $instance;
    }

    private function resolve($class)
    {

        $reflectionClass = new \ReflectionClass($class);

        $constructor = $reflectionClass->getConstructor();

        if (is_null($constructor)) {
            return $reflectionClass->newInstance();
        }

        $constructorParams = $constructor->getParameters();

        $classDependencies = $this->resolveClassDependencies($constructorParams);

        $instance = $reflectionClass->newInstanceArgs($classDependencies);

        return $instance;
    }

    private function resolveClassDependencies(array $constructorParams): array
    {
        $classDependencies = [];

        /**
         * @var ReflectionParameter $param
         */
        foreach ($constructorParams as $param) {

            $serviceType = $param->getType();

            $service = $this->get($serviceType->getName());

            $classDependencies[] = $service;

        }

        return $classDependencies;
    }

    public function has(string $id): bool
    {
        return isset($this->services[$id]);
    }
}
