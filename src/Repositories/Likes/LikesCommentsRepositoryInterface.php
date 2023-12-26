<?php

namespace Ember\Repositories\Likes;


use Ember\Blog\LikeComment;
use Ember\Person\UUID;

interface LikesCommentsRepositoryInterface
{
    public function save(LikeComment $like): void;
    public function get(UUID $uuid): LikeComment;
    public function getByCommentUuid(UUID $comment_uuid): LikeComment;
}
