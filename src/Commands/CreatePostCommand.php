<?php

namespace Ember\Commands;

use Ember\Blog\Post;
use Ember\Person\User;
use Ember\Person\UUID;
use Ember\Repositories\Posts\PostsRepositoryInterface;

class CreatePostCommand
{
    public function __construct(
        private PostsRepositoryInterface $postsRepository
    ) {
    }

    public function handle(User $user, $faker): void
    {


        $this->postsRepository->save(new Post(
            UUID::random(),
            $user,
            $faker->text(20),
            $faker->text(200)
        ));
    }
}
