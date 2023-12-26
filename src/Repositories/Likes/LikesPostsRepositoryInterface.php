<?php

namespace Ember\Repositories\Likes;


use Ember\Blog\LikePost;
use Ember\Person\UUID;

interface LikesPostsRepositoryInterface
{
    public function save(LikePost $like): void;
    public function get(UUID $uuid): LikePost;
    public function getByPostUuid(UUID $post_uuid): LikePost;
}
