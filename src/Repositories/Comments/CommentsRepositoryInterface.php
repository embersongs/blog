<?php

namespace Ember\Repositories\Comments;

use Ember\Blog\Comment;
use Ember\Blog\Post;
use Ember\Person\User;
use Ember\Person\UUID;

interface CommentsRepositoryInterface
{
    public function save(Comment $comment): void;
    public function get(UUID $uuid): Comment;
}
