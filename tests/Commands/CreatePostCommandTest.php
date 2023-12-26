<?php

namespace Ember\Blog\UnitTests\Commands;

use Ember\Blog\Exceptions\PostNotFoundException;
use Ember\Blog\Post;
use Ember\Commands\CreatePostCommand;
use Ember\Person\UUID;
use Ember\Person\User;
use Ember\Repositories\Posts\PostsRepositoryInterface;
use Ember\Person\Name;
use PHPUnit\Framework\TestCase;
use Faker;

class CreatePostCommandTest extends TestCase
{
    public function testItSavesPostToRepository(): void
    {

        $postRepository = $this->makePostsRepository();
        $command = new CreatePostCommand($postRepository);

        $username = new Name("user", "last");
        $user = new User(UUID::random(), 'user', $username);

        $faker =  Faker\Factory::create('ru_RU');
        $command->handle($user, $faker);

        $this->assertTrue($postRepository->wasCalled());
    }

    private function makePostsRepository(): PostsRepositoryInterface
    {
        return  new class implements PostsRepositoryInterface
        {

            private bool $called = true;
            public function save(Post $post): void
            {
                $this->called = true;
            }
            public function get(UUID $uuid): Post
            {
                throw new PostNotFoundException("Not found");
            }
            public function wasCalled(): bool
            {
                return $this->called;
            }
        };
    }
}
