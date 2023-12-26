<?php

namespace Ember\Repositories\Likes;


use Ember\Blog\Exceptions\LikeMoreOneException;
use Ember\Blog\Exceptions\LikeNotFoundException;
use Ember\Blog\LikePost;
use Ember\Person\UUID;
use PDO;
use Psr\Log\LoggerInterface;

class SqliteLikesPostsRepository implements LikesPostsRepositoryInterface
{
    public function __construct(
        private PDO $connection,
        private LoggerInterface $logger
    ) {
    }
    public function save(LikePost $like): void
    {

        if($this->moreOneLike($like)){
            throw new LikeMoreOneException(
                "Уже есть лайк к статье");
        }

        $statement = $this->connection->prepare(
            'INSERT INTO likes_posts (uuid, user_uuid, post_uuid)
            VALUES (:uuid, :user_uuid, :post_uuid)'
        );

        $like_uuid = $like->getUuid();

        $statement->execute([
            ':uuid' => (string)$like_uuid,
            ':user_uuid' => (string)$like->getUserUuid(),
            ':post_uuid' => (string)$like->getPostUuid(),
        ]);

        $this->logger->info("Post Like saved: $like_uuid");
    }
    public function get(UUID $uuid): LikePost
    {

        $statement = $this->connection->prepare(
            'SELECT * FROM likes_posts WHERE uuid = :uuid'
        );
        $statement->execute([
            ':uuid' => (string)$uuid,
        ]);
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if (false === $result) {
            $this->logger->warning("Cannot find like: $uuid");
            throw new LikeNotFoundException(
                "Cannot get like: $uuid"
            );
        }


        return new LikePost(
            new UUID($result['uuid']),
            new UUID($result['post_uuid']),
            new UUID($result['user_uuid'])

        );
    }

    public function getByPostUuid(UUID $post_uuid): LikePost
    {
        $statement = $this->connection->prepare(
        'SELECT * FROM likes_posts WHERE post_uuid = :post_uuid'
    );
        $statement->execute([
            ':post_uuid' => (string)$post_uuid,
        ]);
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if (false === $result) {
            $this->logger->warning("Cannot find like: $post_uuid");
            throw new LikeNotFoundException(
                "Cannot get like: $post_uuid"
            );
        }


        return new LikePost(
            new UUID($result['uuid']),
            new UUID($result['post_uuid']),
            new UUID($result['user_uuid'])

        );

    }

    private function moreOneLike(LikePost $like): bool
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM likes_posts WHERE post_uuid = :post_uuid AND user_uuid = :user_uuid'
        );
        $statement->execute([
            ':post_uuid' => (string)$like->getPostUuid(),
            ':user_uuid' => (string)$like->getUserUuid(),
        ]);
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if (false === $result) {
            return false;
        }
        return true;
    }
}
