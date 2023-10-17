<?php

declare(strict_types=1);

namespace App\Controllers;

use Kalinin\Framework\Controllers\AbstractController;
use Kalinin\Framework\Http\Response;

class PostController extends AbstractController
{
    public function show(int $id): Response
    {
        $content = "Post = $id";

        return new Response($content);
    }

    public function showCreate(): Response
    {
        return $this->render('posts/post_create.html.twig');
    }
}
