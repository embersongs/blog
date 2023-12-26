<?php

namespace PostsRepository;

use Ember\Blog\Exceptions\PostNotFoundException;
use Ember\Person\UUID;
use Ember\Repositories\Posts\SqlitePostsRepository;
use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;

class SqlitePostsRepositoryTest extends TestCase
{
    public function testItThrowsAnExceptionWhenPostNotFound(): void
    {

        $connectionStub = $this->createStub((PDO::class));

        $statementStub = $this->createStub(PDOStatement::class);
        $statementStub->method('fetch')->willReturn(false);

        $connectionStub->method('prepare')->willReturn($statementStub);

        $repository = new SqlitePostsRepository($connectionStub);

        $this->expectException(PostNotFoundException::class);
        $this->expectExceptionMessage('Cannot find post');

        $repository->get(UUID::random());
    }

    public function testItGetPost(): void
    {
        $connectionStub = $this->createStub((PDO::class));

        $statementStub = $this->createStub(PDOStatement::class);
        $statementStub->method('fetch')->willReturn(false);

        $connectionStub->method('prepare')->willReturn($statementStub);

        $repository = new SqlitePostsRepository($connectionStub);

        $repository->get(new UUID("a55efaaf-d8cc-4510-b8b0-9c1afd3de872"));
    }
}
