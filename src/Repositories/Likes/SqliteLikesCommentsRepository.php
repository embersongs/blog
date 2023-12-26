<?php

namespace Ember\Repositories\Likes;


use Ember\Blog\Exceptions\LikeMoreOneException;
use Ember\Blog\Exceptions\LikeNotFoundException;
use Ember\Blog\LikeComment;
use Ember\Person\UUID;
use PDO;
use Psr\Log\LoggerInterface;

class SqliteLikesCommentsRepository implements LikesCommentsRepositoryInterface
{
    public function __construct(
        private PDO $connection,
        private LoggerInterface $logger
    ) {
    }
    public function save(LikeComment $like): void
    {

        if($this->moreOneLike($like)){
            throw new LikeMoreOneException(
                "Уже есть лайк к комментарию");
        }

        $like_uuid = $like->getUuid();

        $statement = $this->connection->prepare(
            'INSERT INTO likes_comments (uuid, user_uuid, comment_uuid)
            VALUES (:uuid, :user_uuid, :comment_uuid)'
        );

        $statement->execute([
            ':uuid' => (string)$like->getUuid(),
            ':user_uuid' => (string)$like->getUserUuid(),
            ':comment_uuid' => (string)$like->getCommentUuid(),
        ]);

        $this->logger->info("Comment Like saved: $like_uuid");

    }
    public function get(UUID $uuid): LikeComment
    {

        $statement = $this->connection->prepare(
            'SELECT * FROM likes_comments WHERE uuid = :uuid'
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


        return new LikeComment(
            new UUID($result['uuid']),
            new UUID($result['comment_uuid']),
            new UUID($result['user_uuid'])

        );
    }


    private function moreOneLike(LikeComment $like): bool
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM likes_comments WHERE comment_uuid = :comment_uuid AND user_uuid = :user_uuid'
        );
        $statement->execute([
            ':comment_uuid' => (string)$like->getCommentUuid(),
            ':user_uuid' => (string)$like->getUserUuid(),
        ]);
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if (false === $result) {
            return false;
        }
        return true;
    }

    public function getByCommentUuid(UUID $comment_uuid): LikeComment
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM likes_comments WHERE comment_uuid = :comment_uuid'
        );
        $statement->execute([
            ':comment_uuid' => (string)$comment_uuid,
        ]);
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if (false === $result) {
            $this->logger->warning("Cannot find like: $comment_uuid");
            throw new LikeNotFoundException(
                "Cannot get like: $comment_uuid"
            );
        }


        return new LikeComment(
            new UUID($result['uuid']),
            new UUID($result['comment_uuid']),
            new UUID($result['user_uuid'])

        );
    }
}
