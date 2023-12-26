<?php

namespace Ember\Repositories\Comments;

use Ember\Blog\Comment;
use Ember\Blog\Exceptions\CommentNotFoundException;
use Ember\Blog\Post;
use Ember\Person\User;
use Ember\Person\UUID;
use Ember\Repositories\Posts\SqlitePostsRepository;
use Ember\Repositories\Users\SqliteUsersRepository;
use PDO;
use Psr\Log\LoggerInterface;

class SqliteCommentsRepository implements CommentsRepositoryInterface
{
    public function __construct(
        private PDO $connection,
        private LoggerInterface $logger
    ) {
    }
    public function save(Comment $comment): void
    {
        $statement = $this->connection->prepare(
            'INSERT INTO comments (uuid, post_uuid, author_uuid, text)
            VALUES (:uuid, :post_uuid, :author_uuid, :text)'
        );

        $comment_uuid = $comment->uuid();

        $statement->execute([
            ':uuid' => (string)$comment_uuid,
            ':post_uuid' => (string)$comment->getPost()->uuid(),
            ':author_uuid' => (string)$comment->getAuthor()->uuid(),
            ':text' => $comment->getText(),
        ]);

        $this->logger->info("Comment saved: $comment_uuid");
    }
    public function get(UUID $uuid): Comment
    {

        $statement = $this->connection->prepare(
            'SELECT * FROM comments WHERE uuid = :uuid'
        );
        $statement->execute([
            ':uuid' => (string)$uuid,
        ]);
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if (false === $result) {
            $this->logger->warning("Cannot find comment: $uuid");
            throw new CommentNotFoundException(
                "Cannot get post: $uuid"
            );
        }

        $userRepository = new SqliteUsersRepository($this->connection);
        $user = $userRepository->get(new UUID($result['author_uuid']));

        $postRepository = new SqlitePostsRepository($this->connection);
        $post = $postRepository->get(new UUID($result['post_uuid']));


        return new Comment(
            new UUID($result['uuid']),
            $user,
            $post,
            $result['text']
        );
    }
}
