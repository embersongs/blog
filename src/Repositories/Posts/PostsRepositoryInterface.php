<?php

namespace Ember\Repositories\Posts;

use Ember\Blog\Post;
use Ember\Person\User;
use Ember\Person\UUID;

interface PostsRepositoryInterface
{
    public function save(Post $post): void;

    public function get(UUID $uuid): Post;

    public function delete(UUID $uuid): void;
}
