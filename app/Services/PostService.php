<?php

declare(strict_types=1);

namespace App\Services;

use App\Entities\Post;
use Doctrine\DBAL\Connection;
use Kalinin\Framework\Routing\Exception\NotFoundException;

class PostService
{
    private const DATE_TIME_FORMAT = 'Y-m-d H:i:s';
    public function __construct(
        private Connection $connection
    ) {
    }

    public function save(Post $post): Post
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        $queryBuilder
            ->insert('posts')
            ->values([
                'title' => ':title',
                'text' => ':content',
                'created_at' => ':created_at'
            ])
            ->setParameters([
                'title' => $post->getTitle(),
                'content' => $post->getContent(),
                'created_at' => $post->getCreatedAt()->format(static::DATE_TIME_FORMAT)
            ])
            ->executeQuery();

        $id = $this->connection->lastInsertId();

        $post->setId((int)$id);

        return $post;
    }

    public function find(int $id): ?Post
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        $result = $queryBuilder
            ->select('*')
            ->from('posts')
            ->where('id = :id')
            ->setParameter('id', $id)
            ->executeQuery();
        $result = $result->fetchAssociative();

        if (!$result) {
            return null;
        }
        return Post::create(
            $result['title'],
            $result['text'],
            $result['id'],
            new \DateTimeImmutable($result['created_at']),
        );
    }

    public function findOrFail($id): Post
    {
        $post = $this->find($id);

        if (is_null($post)) {
            throw new NotFoundException("Post $id not found", 404);
        }

        return $post;
    }

}