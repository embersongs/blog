<?php

namespace CommentsRepository;

use Ember\Blog\Comment;
use Ember\Blog\Exceptions\CommentNotFoundException;
use Ember\Blog\Post;
use Ember\Person\Name;
use Ember\Person\UUID;
use Ember\Repositories\Comments\SqliteCommentsRepository;
use PDO;
use PDOStatement;

use PHPUnit\Framework\TestCase;
use Faker;
use Ember\Person\User;

class SqliteCommentsRepositoryTest extends TestCase
{
    public function testItThrowsAnExceptionWhenCommentNotFound(): void
    {

        $connectionStub = $this->createStub((PDO::class));

        $statementStub = $this->createStub(PDOStatement::class);
        $statementStub->method('fetch')->willReturn(false);

        $connectionStub->method('prepare')->willReturn($statementStub);

        $repository = new SqliteCommentsRepository($connectionStub);

        $this->expectException(CommentNotFoundException::class);
        $this->expectExceptionMessage('Cannot find post');

        $repository->get(UUID::random());
    }

    public function testItGetComment(): void
    {
        $connectionStub = $this->createStub((PDO::class));

        $statementStub = $this->createStub(PDOStatement::class);
        $statementStub->method('fetch')->willReturn(false);

        $connectionStub->method('prepare')->willReturn($statementStub);

        $repository = new SqliteCommentsRepository($connectionStub);

        $repository->get(new UUID("a55efaaf-d8cc-4510-b8b0-9c1afd3de872"));
    }

    public function testItSavesCommentToDatabase(): void
    {
        $connectionStub = $this->createStub(PDO::class);

        $statementMock = $this->createMock(PDOStatement::class);

        $uuid = UUID::random();

        $faker = Faker\Factory::create('ru_RU');
        $text = $faker->text(200);
        $header = $faker->text(20);
        $username = $faker->firstName();
        $lastname = $faker->lastName();

        $name = new Name($username, $lastname);

        $user = new User(UUID::random(), $username, $name);
        $post = new Post(UUID::random(), $user, $header, $text);

        $statementMock
            ->expects($this->once())
            ->method('execute')
            ->with([
                ':uuid' => $uuid,
                ':post_uuid' => $post->uuid(),
                ':author_uuid' => $user->uuid(),
                ':text' => $text,
            ]);

        $connectionStub->method('prepare')->willReturn($statementMock);

        $repository = new SqliteCommentsRepository($connectionStub);

        $repository->save(
            new Comment($uuid, $user, $post, $text)
        );
    }
}
