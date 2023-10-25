<?php

use Doctrine\DBAL\Connection;
use Kalinin\Framework\Console\Application;
use Kalinin\Framework\Console\Commands\MigrateCommand;
use Kalinin\Framework\Controllers\AbstractController;
use Kalinin\Framework\Dbal\ConnectionFactory;
use Kalinin\Framework\Http\Kernel;
use Kalinin\Framework\Http\Middleware\RequestHandler;
use Kalinin\Framework\Http\Middleware\RequestHandlerInterface;
use Kalinin\Framework\Http\Middleware\RouterDispatch;
use Kalinin\Framework\Routing\Router;
use Kalinin\Framework\Routing\RouterInterface;
use Kalinin\Framework\Session\Session;
use Kalinin\Framework\Session\SessionInterface;
use Kalinin\Framework\Template\TwigFactory;
use League\Container\Argument\Literal\ArrayArgument;
use League\Container\Argument\Literal\StringArgument;
use League\Container\Container;
use League\Container\ReflectionContainer;
use Symfony\Component\Dotenv\Dotenv;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Kalinin\Framework\Console\Kernel as ConsoleKernel;

$dotenv = new Dotenv();

// Application parameters 🚀
$dotenv->load(BASE_PATH.'/.env');
$routes = require_once BASE_PATH.'/routes/web.php';
$viewsPath = BASE_PATH.'/views';
$databseUrl = 'pdo-mysql://lemp:lemp@database:3306/lemp?charset=utf8mb4';

$appEnv = $_ENV['APP_ENV'];

// Application services

$container = new Container();

$container->delegate(new ReflectionContainer(true));

$container->add('APP_ENV', new StringArgument($appEnv));

$container->add('console-command-namespace', new StringArgument('Kalinin\\Framework\\Console\\Commands\\'));

$container->add(RouterInterface::class, Router::class);

$container->extend(RouterInterface::class)
    ->addMethodCall('registerRoutes', [
        new ArrayArgument($routes),
    ]);

$container->add(RequestHandlerInterface::class, RequestHandler::class)
    ->addArgument($container);

$container->add(Kernel::class)
    ->addArgument(RouterInterface::class)
    ->addArgument($container)
    ->addArgument(RequestHandlerInterface::class);

$container->addShared(SessionInterface::class, Session::class);

$container->add('twig-factory', TwigFactory::class)
    ->addArguments([
        new StringArgument($viewsPath),
        SessionInterface::class
    ]);

$container->addShared('twig', function () use ($container) {
   return $container->get('twig-factory')->create();
});

$container->inflector(AbstractController::class)
    ->invokeMethod('setContainer', [$container]);

$container->add(ConnectionFactory::class)
    ->addArgument(new StringArgument($databseUrl));

$container->addShared(Connection::class, function () use ($container): Connection {
    return $container->get(ConnectionFactory::class)->create();
});

$container->add(Application::class)
    ->addArgument($container);

$container->add(ConsoleKernel::class)
    ->addArgument($container)
    ->addArgument(Application::class);

$container->add('console:migrate', MigrateCommand::class)
    ->addArgument(Connection::class)
    ->addArgument(new StringArgument(BASE_PATH . '/database/migrations'));


//middleware
$container->add(RouterDispatch::class)
    ->addArgument(RouterInterface::class)
    ->addArgument($container);

return $container;
