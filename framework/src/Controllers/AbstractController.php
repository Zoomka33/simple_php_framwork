<?php

declare(strict_types=1);

namespace Kalinin\Framework\Controllers;

use Kalinin\Framework\Http\Response;
use Psr\Container\ContainerInterface;
use Twig\Environment;

class AbstractController
{
    protected ?ContainerInterface $container = null;

    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function render(string $content, array $parameters = [], Response $response = null)
    {
        /**
         * @var Environment $twig
         */
        $twig = $this->container->get('twig');

        $content = $twig->render($content, $parameters);

        $response ??= new Response();

        $response->setContent($content);

        return $response;
    }
}
