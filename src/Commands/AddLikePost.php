<?php

namespace Ember\Commands;

use Ember\Blog\LikePost;
use Ember\Person\UUID;
use Ember\Repositories\Likes\LikesPostsRepositoryInterface;


readonly class AddLikePost
{
    public function __construct(
        private LikesPostsRepositoryInterface $likesRepository
    ) {
    }

    public function handle($post_uuid, $user_uuid): void
    {


        $this->likesRepository->save(new LikePost(
            UUID::random(),
            new UUID($post_uuid),
            new UUID($user_uuid)
        ));
    }
}
