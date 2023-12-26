<?php

namespace Ember\Commands;

use Ember\Blog\Comment;
use Ember\Blog\Post;
use Ember\Person\User;
use Ember\Person\UUID;
use Ember\Repositories\Comments\CommentsRepositoryInterface;

class CreateCommentCommand
{
    public function __construct(
        private CommentsRepositoryInterface $commentsRepository
    ) {
    }

    public function handle(User $user, Post $post, $faker): void
    {
        $this->commentsRepository->save(new Comment(
            UUID::random(),
            $user,
            $post,
            $faker->text(200)
        ));
    }
}
