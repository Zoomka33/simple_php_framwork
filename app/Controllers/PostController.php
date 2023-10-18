<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Entities\Post;
use App\Services\PostService;
use Kalinin\Framework\Controllers\AbstractController;
use Kalinin\Framework\Http\Request;
use Kalinin\Framework\Http\Response;

class PostController extends AbstractController
{
    public function __construct(
        private PostService $service
    )
    {
    }

    public function show(Request $request,int $id): Response
    {
        $post = $this->service->findOrFail($id);

        return $this->render('posts/post_show.html.twig', [
            'post' => $post
        ]);
    }

    public function showCreate(): Response
    {
        return $this->render('posts/post_create.html.twig');
    }

    public function create(Request $request): Response
    {
        $post = Post::create(
            title: $this->request->getPostData()['title'],
            content: $this->request->getPostData()['content']
        );

        $post = $this->service->save($post);
        dd($post);
    }
}
