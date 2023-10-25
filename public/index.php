<?php

define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH.'/vendor/autoload.php';

use Kalinin\Framework\Http\Kernel;
use Kalinin\Framework\Http\Request;
use League\Container\Container;

$r = Request::createFromGlobals();

/**
 * @var Container $container
 */
$container = require_once BASE_PATH.'/config/services.php';

$kernel = $container->get(Kernel::class);

$response = $kernel->handle($r);
$response->send();

$kernel->terminate($r, $response);
