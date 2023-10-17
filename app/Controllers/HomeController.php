<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\ExampleService;
use Doctrine\DBAL\Connection;
use Kalinin\Framework\Controllers\AbstractController;
use Kalinin\Framework\Http\Response;

class HomeController extends AbstractController
{
    public function __construct(
        private ExampleService $service
    ) {
    }

    public function index(): Response
    {
        dd($this->container->get(Connection::class)->connect());

        return $this->render('home.html.twig', ['name' => $this->service->getName()]);
    }
}
